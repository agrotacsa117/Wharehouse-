import http from 'k6/http';
import { Counter } from 'k6/metrics';
import { loginAndGetSession } from './lib/auth.js';

// ---------------------------------------------------------------------------
// Ataque de concurrencia contra UNA fila de warehouse_inventory a traves de
// POST /output/process-cart, para comprobar si lockForUpdate() (usado por
// BaseOutputService::validateStockAvailability para OUT/SALE/RELOCATION/
// LOCATION_UPDATE, pero NO usado por WarehouseInventoryServiceImplementation
// ::transferInventory para TRANSFER) realmente evita la sobreventa bajo
// requests simultaneos contra el mismo warehouseInventoryId.
//
// Variables de entorno (todas las produce scripts/concurrency/seed.php):
//   BASE_URL, EMAIL, PASSWORD, SCENARIO, INVENTORY_ID, VUS, AMOUNT,
//   DEST_WAREHOUSE_ID, NEW_RACK, NEW_LEVEL, NEW_RACK_R, NEW_LEVEL_R,
//   DEST_WAREHOUSE_NAME, FOLIO_TRANSFER, INVOICE_SAP
// ---------------------------------------------------------------------------

const BASE_URL = __ENV.BASE_URL || 'http://127.0.0.1:8000';
const EMAIL = __ENV.EMAIL;
const PASSWORD = __ENV.PASSWORD;
const SCENARIO = __ENV.SCENARIO;
const INVENTORY_ID = Number(__ENV.INVENTORY_ID);
const VUS = Number(__ENV.VUS || 20);
const AMOUNT = Number(__ENV.AMOUNT || 10);

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
    if (!EMAIL || !PASSWORD || !SCENARIO || !INVENTORY_ID) {
        throw new Error('Faltan variables de entorno: EMAIL, PASSWORD, SCENARIO, INVENTORY_ID son requeridas.');
    }

    return loginAndGetSession(BASE_URL, EMAIL, PASSWORD);
}

function buildItem() {
    const item = { warehouseInventoryId: INVENTORY_ID, quantity: AMOUNT };

    if (SCENARIO === 'RELOCATION') {
        item.new_rack = Number(__ENV.NEW_RACK);
        item.new_level = Number(__ENV.NEW_LEVEL);
    }
    if (SCENARIO === 'LOCATION_UPDATE') {
        item.new_rack_r = Number(__ENV.NEW_RACK_R);
        item.new_level_r = Number(__ENV.NEW_LEVEL_R);
    }

    return item;
}

function buildShared() {
    const shared = {
        movement_type: SCENARIO,
        reason: `k6 concurrency test - ${SCENARIO}`,
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
    
    const payload = Object.assign({ items: [buildItem()] }, buildShared());

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

    const result = (body.results || [])[0];
    if (result && result.ok) {
        successCounter.add(1);
    } else {
        failureCounter.add(1);
    }

    console.log(`[VU ${__VU}] ok=${result ? result.ok : 'N/A'} msg=${result ? result.message : 'sin resultado'}`);
}
