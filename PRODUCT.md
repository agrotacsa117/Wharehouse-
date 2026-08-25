# PRODUCT.md — TACSA Inventarios

## Register

**Product.** Herramienta operativa interna. El diseño existe para que un
operador de almacén termine una tarea sin errores — no para dar una
impresión de marca. Cada decisión de tipografía, color y densidad debe
priorizar velocidad de lectura y certeza sobre exactitud del dato.

Nada en esta app es "landing". Ni siquiera la pantalla de login.

---

## Audiencia

**Usuarios primarios:** operadores de almacén en sucursales regionales de
TACSA (agroquímicos, bajo Becusa) en el sureste de México — Hopelchén,
Ciudad de Campeche, Cárdenas, Villahermosa y otras. Registran entradas,
salidas, relocaciones y contramovimientos varias veces al día.

**Usuarios secundarios:** gerentes de sucursal (supervisan por sucursal,
usan reportes de caducidad/existencias/movimientos) y administradores
(acceso global, ejecutan reversas y ajustes).

**Contexto real de uso:**

- Terminales viejas de sucursal, monitores de resolución baja, iluminación
  irregular (bodega o oficina de sucursal, no oficina corporativa).
- Sesiones cortas y densas: 20-40 transacciones seguidas en 10 minutos,
  luego nada por una hora.
- La certeza sobre el inventario pesa más que cualquier consideración
  estética. Un movimiento mal capturado se traduce en un contramovimiento,
  auditoría, y explicaciones a gerencia.
- Los usuarios **no son early adopters de software**. Patrones que asumen
  familiaridad con SaaS moderno (drawers con gestos, tooltips que aparecen
  al hover, atajos de teclado no anunciados) los confunden. Todo lo
  interactivo tiene que ser visible y con label.
- Todos leen y escriben en español mexicano. Nunca inglés en la UI.

---

## Propósito

Gestionar el inventario físico de agroquímicos entre bodegas y sucursales:

- Entradas y salidas de material con carrito multi-producto.
- Relocaciones dentro del mismo almacén (`LOCATION_UPDATE`) y entre
  almacenes.
- Contramovimientos (reversas) siguiendo principios inmutables tipo
  SAP/Odoo — se genera un documento nuevo `REV-{folio}`, nunca se edita
  el original.
- Rehabilitación de producto con actualización de fechas de caducidad
  y fabricación.
- Trazabilidad completa por sucursal con `BranchScope` global.
- Reportes tabulados (Caducidad, Existencias, Movimientos) con top de
  productos vencidos por almacén.

---

## Voz visual — tres palabras

**Sobria. Operacional. Precisa.**

- **Sobria** — cero adornos, cero decoración. Ningún elemento visual
  compite con los datos. Si un elemento no ayuda a completar una acción,
  no está.
- **Operacional** — la jerarquía visual sirve al operador que está
  ejecutando una tarea, no al primer visitante que intenta entender el
  producto. Densidad de información alta pero deliberada.
- **Precisa** — la misma acción se ve igual en todas las pantallas.
  Cero variación estilística por vista. Confirmaciones destructivas
  usan siempre el mismo patrón de dos pasos.

---

## Anti-references

Referencias visuales explícitamente prohibidas. Si Impeccable propone
algo que caiga en cualquiera de estas categorías, se descarta.

### Estética

- Gradientes de cualquier tipo, especialmente morado→azul y rosa→naranja
  (tell clásico de AI slop).
- Glassmorphism, blur backgrounds, cards flotando con sombras
  exageradas o multicapa.
- Border radius grandes (>8px) en elementos operacionales — botones,
  inputs, tablas. Los chips de código de producto pueden tener radius
  medio, pero nada más.
- Paletas leak de SaaS gringo: Linear, Vercel, Notion, Stripe, Framer.
- Íconos decorativos junto a headings sin función semántica.
- Fondos coloreados detrás de bloques de texto por decoración.

### Interacción

- Micro-animaciones en tablas de datos (fade-in de filas, hover con lift,
  transiciones en focus de celda).
- Confetti, celebraciones, bounce animations, streak counters.
- Skeleton loaders elaborados en operaciones sub-segundo.
- Tooltips que aparecen solo en hover para info crítica — si es crítico,
  se muestra directo.
- Drawers y sheets con gestos ocultos (swipe para cerrar sin botón).

### Copy

- "¡Excelente!", "¡Genial!", "¡Perfecto!" en confirmaciones de sistema.
- Copy motivacional o entusiasta ("¡Vamos!", "¡Sigue así!").
- Emojis en mensajes de sistema o notificaciones.
- Errores vagos como "Algo salió mal" o "Ups" — siempre error específico
  con el campo o acción que falló.
- Marketing-speak en la UI: "Boost", "Level up", "Powered by", "Impulsa
  tu productividad".

---

## Referencias positivas

A qué sí debe parecerse:

- **SAP GUI clásico y Odoo product view** — densidad, jerarquía, cero
  decoración.
- **Terminales de aerolínea (Sabre, Amadeus)** — información alta pero
  el operador siempre sabe dónde mirar primero.
- **Bloomberg Terminal** para el manejo de datos densos.
- **Punto de venta industriales** — botones grandes, labels claros,
  cero ambigüedad sobre qué hace cada acción.

El sentimiento correcto es: *"esta aplicación se ve como una herramienta
seria de trabajo"*, no *"esta aplicación se ve moderna"*.

---

## Reglas específicas del dominio TACSA

Convenciones de la marca y el dominio que Impeccable debe respetar en
cualquier vista generada:

- **Rojo institucional TACSA** se reserva para: header, acciones
  primarias, chips de branding, botones outline de acción positiva
  ("Seleccionar", "Confirmar"). No se usa como fondo decorativo ni como
  color de estado (para errores, alertas, etc. — usar los estándares
  Bootstrap).
- **Códigos de producto** (`mf019`, `mp073`, `ch004`) van siempre en chip
  gris claro con tipografía monoespaciada o similar. Nunca como texto
  corriente.
- **Ubicaciones de rack** (`R:1 / N:1 / M:1 / B:1 / T:1`) son texto
  técnico denso. No "prettificar" con íconos ni separadores decorativos.
- **Fechas** siempre en formato ISO `YYYY-MM-DD` en tablas. Formato largo
  ("15 de julio de 2026") solo en headers de reportes impresos.
- **Confirmaciones destructivas o irreversibles** (contramovimientos,
  salidas con stock negativo, ajustes manuales) usan modal de dos pasos.
  Nunca un botón directo, nunca `confirm()` nativo.
- **Estados vacíos** de tablas dicen qué hacer, no qué falta. En lugar
  de "No hay productos" → "Selecciona una bodega para ver sus productos".
- **Multi-sucursal por defecto**: cualquier vista de datos asume que
  puede filtrarse por sucursal. El selector de sucursal es visible y
  persistente, no oculto en un menú.

---

## Stack técnico (contexto para generación)

- Laravel + Blade templates
- Bootstrap 5 — preferir clases utilitarias antes que CSS custom
- JavaScript vanilla (sin framework frontend)
- MySQL
- Sin build step de CSS custom más allá del compilado de Bootstrap

Cualquier propuesta de Impeccable que requiera Tailwind, React, o
dependencias JS pesadas debe reescribirse en este stack antes de
implementarse.
