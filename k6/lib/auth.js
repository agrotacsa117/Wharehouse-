import http from 'k6/http';
import { check } from 'k6';

// ---------------------------------------------------------------------------
// Login real via HTTP compartido por los distintos scripts de concurrencia
// k6 (single-item y carrito multi-producto). k6 no maneja cookies/sesion
// automaticamente entre requests como un navegador, asi que esto se arma a
// mano: GET / (token+cookie de invitado) -> POST /logear (autentica) ->
// GET /output autenticado (token CSRF valido de sesion logueada).
// ---------------------------------------------------------------------------

export function extractToken(html) {
    const match = html.match(/name="_token"\s+value="([^"]+)"/);
    if (!match) {
        throw new Error('No se pudo extraer el _token CSRF del HTML.');
    }
    return match[1];
}

export function cookieHeaderFrom(res) {
    const parts = [];
    for (const name in res.cookies) {
        const jar = res.cookies[name];
        if (jar && jar.length) {
            parts.push(`${name}=${jar[0].value}`);
        }
    }
    return parts.join('; ');
}

export function loginAndGetSession(baseUrl, email, password) {
    const loginPage = http.get(`${baseUrl}/`);
    check(loginPage, { 'login page 200': (r) => r.status === 200 });
    const loginToken = extractToken(loginPage.body);
    let cookieHeader = cookieHeaderFrom(loginPage);

    const loginRes = http.post(
        `${baseUrl}/logear`,
        { email, password, _token: loginToken },
        { headers: { Cookie: cookieHeader }, redirects: 5 }
    );
    check(loginRes, { 'login ok (redirect a home)': (r) => r.status === 200 || r.status === 302 });
    cookieHeader = cookieHeaderFrom(loginRes) || cookieHeader;

    const outputPage = http.get(`${baseUrl}/output`, { headers: { Cookie: cookieHeader } });
    check(outputPage, { 'output page 200 tras login': (r) => r.status === 200 });
    const csrfToken = extractToken(outputPage.body);
    cookieHeader = cookieHeaderFrom(outputPage) || cookieHeader;

    return { cookieHeader, csrfToken };
}
