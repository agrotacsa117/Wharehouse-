# AGENTS.md - Warehouse Management System

## Project Overview
- **Type**: Laravel 8 PHP Application (PHP ^7.4.1)
- **Architecture**: Enterprise_Layer, Application_Layer, Infrastructure layers
- **Primary Author**: 808 Labs

## Build / Lint / Test Commands

### Installation
```bash
composer install && npm install && cp .env.example .env && php artisan key:generate
```

### Development
```bash
php artisan serve              # Start server on localhost:8000
npm run watch                  # Hot reload assets
npm run dev | npm run prod     # Build assets
```

### Testing
```bash
php artisan test               # Run all tests
./vendor/bin/phpunit

# Run single test file
./vendor/bin/phpunit tests/Unit/ExampleTest.php

# Run specific test method
./vendor/bin/phpunit --filter testBasicTest

# Run test suites
./vendor/bin/phpunit --testsuite Unit
./vendor/bin/phpunit --testsuite Feature

# Coverage report
./vendor/bin/phpunit --coverage-html coverage/
```

### Code Quality
```bash
./vendor/bin/php-cs-fixer fix --dry-run --diff   # Check only
./vendor/bin/php-cs-fixer fix                     # Auto-fix
```

### Database
```bash
php artisan migrate
php artisan migrate:fresh --seed   # Fresh DB (destructive)
php artisan db:seed
php artisan tinker                # Interactive REPL
```

## Code Style

### EditorConfig
- **Indentation**: 4 spaces | **YAML**: 2 spaces
- **Line endings**: LF | **Charset**: UTF-8
- **Final newline**: Yes | **Trailing whitespace**: Remove

### PHP CS Fixer
- **Version**: ^3.86 | **Preset**: Laravel (`@auto`)
- **Config**: `.styleci.yml` (disabled: `unused_use`)
- No comments unless explicitly requested

### Naming Conventions
| Element | Convention | Example |
|---------|------------|---------|
| Classes | PascalCase | `WarehouseInventory` |
| Interfaces | PascalCase + `Interface` | `WarehouseServiceInterface` |
| Methods/Vars | camelCase | `getAllWarehouses()`, `$warehouseId` |
| Constants | UPPER_SNAKE_CASE | `MAX_RETRY_COUNT` |
| Tables | snake_case, plural | `warehouse_inventories` |
| Test files | `XxxTest.php` | `WarehouseTest.php` |

### Import Statements
One `use` per line, sorted alphabetically. Groups: PHP core → Laravel → Custom
```php
use App\Application_Layer\ResultPattern;
use App\Contracts\ProductServiceInterface;
use App\Enterprise_Layer\WarehouseInventory;
use Illuminate\Http\Request;
```

### Type Declarations
- Always use `declare(strict_types=1);`
- Return type hints when possible
- PHPdoc for arrays and complex types

## Architecture Layers

### Enterprise_Layer (`app/Enterprise_Layer/`)
- Pure PHP domain entities (NO framework dependencies)
- Business logic and rules
- Custom exceptions in `Exception/` subfolder
- Entities: `Warehouse`, `Location`, `WarehouseInventory`

### Application_Layer
- Services: `app/Application_Layer/Services_Implementation/`
- Repositories: `app/Application_Layer/Repository_Implementation/`
- Use `ResultPattern` for operations that can fail

### Contracts (`app/Contracts/`)
- Interface definitions for all services/repositories
- Naming: `XxxServiceInterface`, `XxxRepositoryInterface`

### Infrastructure (`app/Infrastructure/`)
- Request validation classes, exception handling
- Framework-specific implementations

### Models (`app/Models/`)
- Eloquent ORM models (follow Laravel conventions)

### Mappers (`app/Mappers/DTO/Requests/`)
- DTO <-> Entity mapping

## Key Patterns

### Result Pattern
```php
public function create(DTO $dto): ResultPattern
{
    if ($condition) {
        return ResultPattern::failure("Error message");
    }
    return ResultPattern::success($result);
}
// Usage
$result = $service->create($dto);
if ($result->isFailure()) { return $result->getError(); }
$value = $result->getValue();
```

### Builder Pattern (Entities)
```php
$warehouse = Warehouse::builder()
    ->setWarehouseName('Main')
    ->setWarehouseKey('WH-001')
    ->build();
```

### Repository/Service Pattern
- Interfaces in `app/Contracts/`
- Implementations in `app/Application_Layer/`

## Error Handling
1. **Domain exceptions**: Throw from `Enterprise_Layer/Exception/`
2. **Infrastructure exceptions**: Handle in `Infrastructure/Exception/`
3. **Application layer**: Catch exceptions, convert to `ResultPattern::failure()`
4. **Controllers**: Return appropriate HTTP responses

## Testing Guidelines
- Unit tests: `tests/Unit/` → extend `PHPUnit\Framework\TestCase`
- Feature tests: `tests/Feature/` → extend `Tests\TestCase`
- Test file suffix: `Test.php`, method prefix: `test` or `@test`
- Use factories: `database/factories/`

## Precautions
- Never commit secrets; use `.env.example`
- `vendor/` and `node_modules/` are gitignored
- `php artisan migrate:fresh` only in development
- Keep entities pure (no framework dependencies)
