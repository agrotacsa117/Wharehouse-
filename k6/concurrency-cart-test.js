import http from 'k6/http';
import { Counter } from 'k6/metrics';
import { loginAndGetSession } from './lib/auth.js';

// ---------------------------------------------------------------------------
// Variante multi-producto del ataque de concurrencia: cada VU manda UN solo
// POST /output/process-cart con VARIOS items (distintos warehouseInventoryId
// y quantity), en vez de un item por request. Sirve para probar contencion
// concurrente sobre multiples filas a la vez -- NO es una prueba de deadlock:
// CartOutputService::processBatch() procesa los items del carrito en un
// foreach secuencial, cada uno en su propia DB::transaction(), asi que ninguna
// transaccion retiene locks sobre mas de una fila a la vez.
//
// Variables de entorno (todas las produce scripts/concurrency/seed-cart.php):
//   BASE_URL, EMAIL, PASSWORD, SCENARIO, VUS,
//   SEED_FILE (ruta absoluta al last-seed-cart-{SCENARIO}.json, de donde se
//   lee products: [{inventoryId, productId, initialQty, amount}, ...] -- no
//   se pasa ese array por -e porque las comillas embebidas del JSON se
//   corrompen al cruzar la linea de comandos hacia un exe nativo en
//   PowerShell/Windows),
//   DEST_WAREHOUSE_ID, NEW_RACK, NEW_LEVEL, NEW_RACK_R, NEW_LEVEL_R,
//   DEST_WAREHOUSE_NAME, FOLIO_TRANSFER, INVOICE_SAP
// ---------------------------------------------------------------------------

const BASE_URL = __ENV.BASE_URL || 'http://127.0.0.1:8000';
const EMAIL = __ENV.EMAIL;
const PASSWORD = __ENV.PASSWORD;
const SCENARIO = __ENV.SCENARIO;
const SEED_FILE = __ENV.SEED_FILE;
const PRODUCTS = SEED_FILE ? (JSON.parse(open(SEED_FILE)).products || []) : [];
const VUS = Number(__ENV.VUS || 20);

const successCounter = new Counter('cart_item_success');
const failureCounter = new Counter('cart_item_failure');
const httpErrorCounter = new Counter('cart_http_error');

export const options = {
    scenarios: {
        concurrent_attack: {
            executor: 'per-vu-iterations',
            vus: VUS,
            iterations: 1,
            maxDuration: '30s',
        },
    },
};

export function setup() {
    if (!EMAIL || !PASSWORD || !SCENARIO || !SEED_FILE || !PRODUCTS.length) {
        throw new Error('Faltan variables de entorno: EMAIL, PASSWORD, SCENARIO, SEED_FILE son requeridas (y el seed debe traer products).');
    }

    return loginAndGetSession(BASE_URL, EMAIL, PASSWORD);
}

function buildItems() {
    return PRODUCTS.map((product) => {
        const item = { warehouseInventoryId: product.inventoryId, quantity: product.amount };

        if (SCENARIO === 'RELOCATION') {
            item.new_rack = Number(__ENV.NEW_RACK);
            item.new_level = Number(__ENV.NEW_LEVEL);
        }
        if (SCENARIO === 'LOCATION_UPDATE') {
            item.new_rack_r = Number(__ENV.NEW_RACK_R);
            item.new_level_r = Number(__ENV.NEW_LEVEL_R);
        }

        return item;
    });
}

function buildShared() {
    const shared = {
        movement_type: SCENARIO,
        reason: `k6 concurrency cart test - ${SCENARIO}`,
        operation_date: new Date().toISOString().slice(0, 10),
    };

    if (SCENARIO === 'SALE') {
        shared.invoice_sap = Number(__ENV.INVOICE_SAP || 1000);
    }
    if (SCENARIO === 'RELOCATION') {
        shared.destination_warehouse_id = Number(__ENV.DEST_WAREHOUSE_ID);
    }
    if (SCENARIO === 'TRANSFER') {
        shared.destination_warehouse = __ENV.DEST_WAREHOUSE_NAME;
        shared.folio_transfer = Number(__ENV.FOLIO_TRANSFER);
    }

    return shared;
}

export default function (data) {
    const payload = Object.assign({ items: buildItems() }, buildShared());

    const res = http.post(`${BASE_URL}/output/process-cart`, JSON.stringify(payload), {
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': data.csrfToken,
            Cookie: data.cookieHeader,
        },
    });

    if (res.status !== 200) {
        httpErrorCounter.add(1);
        console.error(`[${__VU}] HTTP ${res.status}: ${res.body}`);
        return;
    }

    let body;
    try {
        body = JSON.parse(res.body);
    } catch (e) {
        httpErrorCounter.add(1);
        console.error(`[${__VU}] respuesta no-JSON: ${res.body}`);
        return;
    }

    const results = body.results || [];
    for (const result of results) {
        if (result && result.ok) {
            successCounter.add(1);
        } else {
            failureCounter.add(1);
        }
        console.log(`[VU ${__VU}] inventoryId=${result.warehouseInventoryId} ok=${result.ok} msg=${result.message}`);
    }
}
