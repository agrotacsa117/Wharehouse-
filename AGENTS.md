# AGENTS.md - Warehouse Management System

## Project Overview
- **Type**: Laravel 8 PHP Application (PHP ^7.4.1)
- **Architecture**: Domain-driven design with `Enterprise_Layer`, `Application_Layer`, `Infrastructure` layers
- **Testing**: PHPUnit 9, Mockery
- **Primary Author**: 808 Labs

## Build / Lint / Test Commands

```bash
composer install && npm install && cp .env.example .env && php artisan key:generate
```

### Development
```bash
php artisan serve              # Start server on localhost:8000
npm run watch                  # Hot reload assets (Laravel Mix)
npm run dev/prod               # Dev/prod build (Laravel Mix)
php artisan tinker             # Interactive REPL
php artisan route:list         # List all registered routes
```

### Testing
```bash
php artisan test                       # All tests
./vendor/bin/phpunit tests/Unit/ExampleTest.php   # Single file
php artisan test --filter test_basic_test          # Single method
./vendor/bin/phpunit --testsuite Unit              # Suite
./vendor/bin/phpunit --coverage-html coverage/     # Coverage
```

### Database & Cache
```bash
php artisan migrate
php artisan migrate:fresh --seed   # Destructive - dev only
php artisan db:seed
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan optimize
```

### Code Quality
```bash
# PHP CS Fixer (uses @auto preset — per-directory configs in routes/ and app/**/)
./vendor/bin/php-cs-fixer fix --dry-run --diff   # Check only
./vendor/bin/php-cs-fixer fix                     # Auto-fix
# StyleCI (.styleci.yml: Laravel preset, unused_use disabled)
```

## Code Style

### EditorConfig
- **Indentation**: 4 spaces (YAML: 2 spaces) | **Line endings**: LF | **Charset**: UTF-8
- **Final newline**: Yes | **Trailing whitespace**: Remove (except .md)

### PHP Conventions
- **Strict types**: `declare(strict_types=1);` at top of every file (aspirational — many lack it, add when editing)
- **Return types**: Always specify (`: void`, `: string`, `: ResultPattern`, `: array`)
- **Property types**: Typed (`private int $id;`), nullable with `?Type` (`?DateTime`, `?int`)
- **PHPDoc**: Minimal — only `@template T`, complex arrays, generics. No explanatory comments. Avoid legacy `@return void`.
- **Constructor**: Traditional assignment over constructor promotion
- **`#[\Override]` attribute**: Use on repository methods implementing interfaces (`#[\Override]`)
- **Logging**: `Log::info()` / `Log::error()` in repositories for debugging
- **DateTime**: Prefer `use DateTime;` import over global `\DateTime`

### Import Statements
- One `use` per line, grouped alphabetically: PHP core → Laravel → Custom App
```php
use DateTime;
use Illuminate\Http\Request;
use App\Application_Layer\ResultPattern;
use App\Contracts\WarehouseStorageServiceInterface;
use App\Enterprise_Layer\Warehouse;
use App\Mappers\DTO\WarehouseDTO;
```

### Naming Conventions
| Element | Convention | Example |
|---------|------------|---------|
| Classes | PascalCase | `WarehouseInventory` |
| Interfaces | PascalCase + `Interface` | `WarehouseServiceInterface` |
| (alt) | PascalCase + `I` suffix | `WarehouseMapperI` |
| Methods/Vars | camelCase | `getAllWarehouses()`, `$warehouseId` |
| Constants | UPPER_SNAKE_CASE | `MAX_RETRY_COUNT` |
| Eloquent Models | PascalCase + `Model` | `WarehouseModel` |
| Tables/Columns | snake_case | `warehouse_inventories`, `warehouses_name` |
| Exceptions | PascalCase + `Exception` | `InvalidAddressException` |
| Controllers | PascalCase (some legacy Spanish: `Categorias`, `Productos`, `Ventas`) | `WarehouseRegistrationController` |
| Test files | `*Test.php` | `WarehouseTest.php` |
| Test methods | `test_` + snake_case | `test_it_creates_warehouse()` |

### Spanish Error Messages
All user-facing messages are in Spanish: `ResultPattern::failure('¡Error: código postal invalido!')`, `ResultPattern::success('¡Almacén registrado con éxito!')`. Internal repository messages may be in English (e.g., 'Warehouse not found').

## Architecture Layers

### Enterprise_Layer (`app/Enterprise_Layer/`)
- Pure PHP domain entities — NO `use Illuminate\*`. Business logic in constructors.
- Custom exceptions: `Exception/` subfolder, extend `RuntimeException`
- Builder Pattern: `Warehouse::builder()->setX()->setY()->build()`
- Nullable properties for optionals: `private ?DateTime $expirationDate;`

### Application_Layer (`app/Application_Layer/`)
- **Services**: `Services_Implementation/` | **Repositories**: `Repository_Implementation/`
- **ResultPattern**: Return for fallible ops — `::success($val)` / `::failure("msg")`, check `$result->isFailure()`
- **Strategies**: Strategy pattern in `Strategies/` (`InReversalStrategy`, `OutReversalStrategy`, `IntraWarehouseTransferStrategy`)
- **Stock management**: `ManagesInventoryStock` class with static methods `validateStockAvailability()` and `reduceStock()`

### Contracts (`app/Contracts/`)
- Interfaces: `XxxServiceInterface`, `XxxRepositoryInterface`, `XxxMapperInterface` (or `XxxMapperI` suffix)

### Infrastructure (`app/Infrastructure/`)
- `FormRequest` validations with `rules()`, `messages()`, `authorize()`
- Exception classes extend `RuntimeException`
- Factory classes for strategies: `MovementFactory`, `ReversalStrategyFactory`, `WarehouseOutputStrategyFactory`

### Models (`app/Models/`)
- Eloquent models (`XxxModel`), properties: `$table`, `$fillable`, `$guarded`, `$casts`. Relationships: `belongsTo`, `hasMany`. Legacy Spanish models exist: `Bodega`, `Producto`, `Proveedor`, `Usuario`, `Ventas`, `Categoria`, `Rol`

### Mappers (`app/Mappers/`)
- DTOs in `DTO/` (typed props, `JsonSerializable` with `jsonSerialize(): array`). Request DTOs in `DTO/Requests/`.
- Entity↔Model mappers use Builder pattern

### Routes (`routes/`)
- **web.php**: Main routes, named with `->name()`, grouped by `Route::prefix()` and `Route::middleware('auth')` / `->middleware('auth', 'role:admin')`
- **api.php**: Minimal API routes, `auth:api` middleware
- Controller syntax: `[ControllerClass::class, 'method']` array syntax

## Key Patterns

### Result Pattern
```php
public function registerWarehouse(WarehouseDTO $dto): ResultPattern
{
    if ($condition) return ResultPattern::failure("¡Error!");
    return ResultPattern::success('¡Almacén registrado con éxito!');
}
$result = $this->service->registerWarehouse($dto);
if ($result->isFailure()) return redirect()->back()->with('error', $result->getError());
```

### Builder Pattern
```php
$warehouse = Warehouse::builder()->setWarehouseName('Main')->setWarehouseKey('WH-001')->build();
```

### Repository/Service Pattern
- Interface in `app/Contracts/`, impl in `app/Application_Layer/`. Constructor DI of interfaces.
- Repositories catch `\Throwable` or `QueryException`, wrap in infrastructure exceptions, return `ResultPattern::failure()`
- Services catch failure results and re-wrap with user-facing Spanish messages

### Error Handling
1. **Domain**: Throw `Enterprise_Layer/Exception/*` (extend `RuntimeException`)
2. **Infrastructure**: Throw `Infrastructure/Exception/*` (e.g., `CouldNotPersistLocationException`)
3. **Application**: Catch in repositories, return `ResultPattern::failure()`
4. **Controllers**: Check `$result->isFailure()`, redirect with error
5. **Global handler**: `app/Exceptions/Handler.php`

## Testing Guidelines
- **Unit tests**: `tests/Unit/` → `PHPUnit\Framework\TestCase`
- **Feature tests**: `tests/Feature/` → `Tests\TestCase` (uses `CreatesApplication`)
- **Naming**: `*Test.php` suffix, `test_` prefix (snake_case)
- **Assertions**: `$this->assertTrue()`, `$response->assertStatus(200)`

## Precautions
- Never commit secrets; use `.env.example`. `vendor/`, `node_modules/`, `.env` gitignored.
- `php artisan migrate:fresh` only in development
- Enterprise_Layer must be pure (zero `Illuminate` imports)
- All user-facing messages in Spanish
- Add `declare(strict_types=1)` to files that lack it
