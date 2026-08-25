# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 8 (PHP ^7.4.1) warehouse management system. The codebase is mid-migration from a legacy
procedural/Spanish-named controller style to a layered, domain-driven design (`Enterprise_Layer` →
`Application_Layer` → `Infrastructure` → `Contracts`). New warehouse/inventory/movements features
follow the DDD layers; older modules (sales, categories, products) still use the legacy pattern.
This repo also has a detailed `AGENTS.md` — read it for full conventions; this file summarizes what
matters most for productive work plus things AGENTS.md doesn't cover. For any UI/view work, also read
`PRODUCT.md` — it defines the product's visual voice ("Sobria. Operacional. Precisa."), explicit
anti-references (no gradients, no glassmorphism, no large border-radius, no hover-only tooltips for
critical info), and TACSA domain UI conventions (product-code chips, rack-location formatting, ISO
dates, two-step confirmation for destructive actions, always-visible branch/sucursal filter).

## Setup & Common Commands

```bash
composer install && npm install && cp .env.example .env && php artisan key:generate
```

```bash
php artisan serve              # Dev server on localhost:8000
npm run watch                  # Hot reload assets (Laravel Mix)
npm run dev / npm run prod     # Dev/prod asset build
php artisan tinker             # REPL
php artisan route:list         # List routes
```

### Testing
```bash
php artisan test                                   # All tests
./vendor/bin/phpunit tests/Unit/ExampleTest.php     # Single file
php artisan test --filter test_basic_test           # Single method
./vendor/bin/phpunit --testsuite Unit               # Suite only
./vendor/bin/phpunit --coverage-html coverage/      # Coverage (PHPUnit 9 + Mockery)
```
Note: `tests/Unit` and `tests/Feature` currently only contain the framework's default `ExampleTest.php`
stubs — there is no real test suite to run as a regression check yet. If you add logic, consider adding
tests for it, but don't expect `php artisan test` to catch regressions in existing code.

### Database & Cache
```bash
php artisan migrate
php artisan migrate:fresh --seed   # Destructive — dev only, never run against real data
php artisan db:seed
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize
```

### Code Quality
```bash
./vendor/bin/php-cs-fixer fix --dry-run --diff   # Check only (uses @auto preset, per-directory configs)
./vendor/bin/php-cs-fixer fix                     # Auto-fix
```
StyleCI also runs (`.styleci.yml`: Laravel preset, `unused_use` disabled).

## Architecture

### Layers (`app/`)
- **`Enterprise_Layer/`** — Pure PHP domain entities, zero `Illuminate\*` imports. Business logic lives
  in constructors/entity methods. Builder pattern (`Warehouse::builder()->setX()->build()`). Domain
  exceptions in `Enterprise_Layer/Exception/` extend `RuntimeException`.
- **`Application_Layer/`** — `Services_Implementation/` and `Repository_Implementation/` hold the
  concrete logic behind the `Contracts/` interfaces. `Strategies/` holds Strategy-pattern classes for
  movement reversal/transfer logic (`InReversalStrategy`, `OutReversalStrategy`,
  `IntraWarehouseTransferStrategy`). `ManagesInventoryStock` provides static
  `validateStockAvailability()` / `reduceStock()` helpers used across services.
- **`Contracts/`** — Interfaces (`XxxServiceInterface`, `XxxRepositoryInterface`, or `XxxMapperI`).
  Controllers and services depend on these via constructor injection, not concrete classes.
- **`Infrastructure/`** — `FormRequest` validation classes, infrastructure exceptions
  (`Infrastructure/Exception/`), and `Infrastructure/Factories/` (`MovementFactory`,
  `ReversalStrategyFactory`, `WarehouseOutputStrategyFactory`) that select a Strategy at runtime.
- **`Mappers/`** — Pure mapping classes between Eloquent Models, Entities, and DTOs, one class per
  direction (e.g. `WarehouseModelToWarehouseEntityMapper` vs `WarehouseToWarehouseModelMapper`). DTOs
  live in `Mappers/DTO/` (typed properties, `JsonSerializable`); request-specific DTOs in
  `Mappers/DTO/Requests/`.
- **`Models/`** — Eloquent models. New models use `XxxModel` naming; legacy Spanish-named models
  (`Bodega`, `Producto`, `Proveedor`, `Usuario`, `Ventas`, `Categoria`, `Rol`) back the older modules
  and are not being renamed. There's a *third*, unrelated set of English-named models without the
  `Model` suffix (`Product`, `Category`, `Role`, `User`) — e.g. `ProductController` uses `Product`
  while `ProductRepositoryImplementation` uses `ProductModel`, and neither is `Producto`. Check which
  one a file already imports before assuming; don't assume `XxxModel` is always "the new one."
- **`Http/Controllers/`** — Mixed: DDD-style controllers (`WarehouseRegistrationController`,
  `MovementsController`, `WareouseInventoryController`, `LocationRegistrationController`,
  `RackController`, `WarehouseTypeController`, `InventoryManagementController`, `OutputController`,
  `ProductController`, `ReportController`, `WarehouseManagmentController`) call into `Contracts/`
  services and return `ResultPattern`-driven redirects; legacy controllers (`Categorias`, `Productos`,
  `Proveedores`, `Usuarios`, `Ventas`, `Reportes_productos`, `DetalleVentas`, `TemporaryProducts`) talk
  more directly to Eloquent models. When touching a legacy controller, match its existing
  (non-layered) style rather than forcing DDD.

### Dependency injection bindings
There is no auto-discovery: every `Contracts/` interface (service, repository, or mapper) is bound to
its concrete implementation with an explicit `$this->app->bind(Interface::class, Implementation::class)`
call in `app/Providers/AppServiceProvider::register()`. When adding a new interface + implementation
pair, you must add its bind here too, or Laravel will throw a "target is not instantiable" error at
resolution time — grep `AppServiceProvider.php` for an existing bind of the same kind (service,
repository, mapper) to match the pattern.

### Output Strategy pattern (stock removal)
A second Strategy hierarchy handles *why* stock is leaving a warehouse. `Contracts/WarehouseOutputStrategy`
declares `processOutput(RemoveWarehouseInventoryStockDTO): ResultPattern` and `getType(): string`.
`Application_Layer/Services_Implementation/BaseOutputService` is the abstract base (validates stock,
reduces it, records the movement); concrete strategies extend it: `SimpleOutputService` (`OUT`),
`SaleOutputService` (`SALE`), `InternalRelocationService` (`RELOCATION`) — plus
`Strategies/IntraWarehouseTransferStrategy` (`LOCATION_UPDATE`), which implements the same interface
directly. `Infrastructure/Factories/WarehouseOutputStrategyFactory::make(string $type)` maps the type
string to the right strategy instance; `TransferOutputService` and `WarehouseInventoryQueryService` /
`WarehouseInventoryService` support this flow. When adding a new reason for stock to leave a warehouse,
add a case here rather than branching on type elsewhere.

### Cart / batch output flow
Note: `CartOutputService`/`CartOutputServiceInterface`/`WarehouseOutputDtoMapper` are currently
untracked/uncommitted (per `git status`) — verify with `git status`/`git log` before assuming this
flow is on the integrated baseline, since that can change as the branch progresses.

`OutputController::processOutput` handles one movement at a time (`RELOCATION`, `SALE`, `OUT`,
`TRANSFER`, `LOCATION_UPDATE`), each building its DTO via `WarehouseOutputDtoMapperI`
(`app/Mappers/WarehouseOutputDtoMapper.php`) — one `toXxxDto()` method per movement type, plus a
generic `toDto(string $movementType, ...)` that switches over them. `OutputController::processCart`
(`POST /output/process-cart`, route `output.inventory.process.cart`) is a second entry point for
submitting many inventory lines in one request: it delegates to
`CartOutputServiceInterface`/`CartOutputService::processBatch(movementType, shared, items, userId)`,
which merges the shared/common fields into each item, resolves the same DTOs through
`WarehouseOutputDtoMapperI`, and calls `WarehouseInventoryServiceInterface::processInventoryOutput()`
(or `transferInventory()` for `TRANSFER`) per item. This is best-effort, not atomic — there is no
enclosing transaction, so a failure on one line does not roll back or block the others. Each item's
outcome is caught individually and returned as `['warehouseInventoryId', 'ok', 'message']`; the
controller returns the full list as JSON (`{"results": [...]}`) for the frontend to render per-row.
When adding a new movement type, add its `toXxxDto()` case to `WarehouseOutputDtoMapper::toDto()` so
both the single-item and cart flows pick it up automatically.

### Result Pattern (core error-handling convention)
Fallible operations return `ResultPattern` instead of throwing outward:
```php
public function registerWarehouse(WarehouseDTO $dto): ResultPattern
{
    if ($condition) return ResultPattern::failure('¡Error!');
    return ResultPattern::success('¡Almacén registrado con éxito!');
}
// caller
$result = $this->service->registerWarehouse($dto);
if ($result->isFailure()) return redirect()->back()->with('error', $result->getError());
```
Error propagation flow: domain exceptions (`Enterprise_Layer/Exception/*`) and infrastructure
exceptions (`Infrastructure/Exception/*`) are thrown internally, caught in repositories, and converted
to `ResultPattern::failure()`; services re-wrap failures with user-facing Spanish messages; controllers
check `isFailure()` and redirect with the error. **All user-facing messages (both success and failure)
must be in Spanish**; internal/log messages may stay in English.

### Routes (`routes/web.php`)
Grouped by `Route::prefix()` and `Route::middleware('auth')` / `->middleware('auth', 'role:admin')`,
named routes via `->name()`, controllers referenced with `[ControllerClass::class, 'method']` array
syntax. `routes/api.php` is minimal and uses `auth:api`.

### Reversal / counter-movement pattern
Movements are never edited in place. Reversing a movement creates a new one with folio `REV-{original
folio}` (see `WarehouseInventoryServiceImplementation::registerReversal`-style flow) linked back via
`reversalOf`; `Strategies/InReversalStrategy` and `Strategies/OutReversalStrategy` (selected through
`Infrastructure/Factories/ReversalStrategyFactory`) contain the actual stock-adjustment logic per
direction. When working on movement corrections/audits, follow this immutable-ledger convention rather
than mutating the original `WarehouseMovements` record.

### Frontend stack
Server-rendered Blade views (`resources/views/module/...`) + Bootstrap 5 (utility classes preferred over
custom CSS) + vanilla JS, compiled through Laravel Mix (`webpack.mix.js` — `resources/js/app.js`,
`resources/css/app.css`). No frontend framework (React/Vue) and no Tailwind — don't introduce either.
Bootstrap 5 itself is loaded via CDN `<link>`/`<script>` tags per Blade view, not bundled through Mix —
version pins are inconsistent across views (e.g. `5.3.0` vs `5.3.3` seen); check the view you're
touching rather than assuming a single pinned version.

### Report exports
Note: `app/Exports/` is currently untracked/uncommitted (per `git status`) — verify with
`git status`/`git log` before assuming this is on the integrated baseline.

`ReportController` exposes POST endpoints (`reports.products.lots.export.excel` /
`.export.pdf`) that accept a JSON-encoded `lots` payload from the client, reshape it with private
helpers (`prepareLotExportRows`, `formatLotLocation`, etc.), then hand off to `maatwebsite/excel`
(`Excel::download(new ProductLotsExport($rows), $filename)`, class in `app/Exports/`, implements
`FromArray`/`WithHeadings`) or `barryvdh/laravel-dompdf` (`PDF::loadView('module.reports.exports.xxx_pdf',
...)->download($filename)`, Blade templates in `resources/views/module/reports/exports/`). A second
pair, `reports.products.physicalcount.export.excel` / `.export.pdf`
(`exportPhysicalCountExcel`/`Pdf` + `preparePhysicalCountRows` + `PhysicalCountExport`), follows the
identical shape for a different dataset. Follow this pattern — controller reshapes data, `Exports/`
class or `exports/*.blade.php` template renders it — for new report export formats rather than building
CSV/PDF output inline in the controller. Both packages are Composer dependencies (`maatwebsite/excel`,
`barryvdh/laravel-dompdf`) — no other Excel/PDF library is used elsewhere in the codebase.

## Code Style (see AGENTS.md for full detail)
- `declare(strict_types=1);` at the top of files — aspirational, add it when editing a file that lacks it.
- Explicit return types and typed/nullable properties (`private ?DateTime $expirationDate;`).
- One `use` per line, grouped alphabetically: PHP core → Laravel → App.
- `#[\Override]` attribute on repository methods implementing an interface method.
- Constructor property assignment written out (no constructor promotion).
- Test files: `*Test.php`, test methods `test_` + snake_case.

## Precautions
- Never commit secrets; `.env` is gitignored — use `.env.example` as the template.
- `php artisan migrate:fresh` is destructive — development only.
- `Enterprise_Layer` must stay framework-free (no `Illuminate` imports).
- The repo root has many stray `*_diff.txt`, `*.md` session/changelog dumps (e.g. `cambios.txt`,
  `debating.md`, dated `YYYY-MM-DD-HHMMSS-*.txt` files) left over from prior work — they are not
  documentation to follow and can be ignored. The count is in the dozens and growing (several are
  empty 0-byte files) — don't assume it's a small fixed set.
- `README.md` is the unmodified Laravel scaffold (marketing copy, sponsor list) — it has no
  project-specific setup or architecture info; don't treat it as a source of truth.
