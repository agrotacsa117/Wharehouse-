# AGENTS.md - Warehouse Management System

## Project Overview
- **Type**: Laravel 8 PHP Application
- **PHP**: ^7.4.1
- **Architecture**: Enterprise_Layer, Application_Layer, Infrastructure layers

---

## Build / Lint / Test Commands

### Installation
```bash
composer install && npm install && cp .env.example .env && php artisan key:generate
```

### Development
```bash
php artisan serve              # Start server
npm run watch                  # Watch assets (hot reload)
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
php artisan tinker                 # Interactive REPL
```

---

## Code Style

### EditorConfig (.editorconfig)
- **Indentation**: 4 spaces
- **Line endings**: LF
- **Charset**: UTF-8
- **Final newline**: Yes
- **Trailing whitespace**: Remove

### PHP CS Fixer
- Preset: Laravel (`@auto`)
- Config: `.styleci.yml`
- No tabs, strict ordering of imports

### Naming Conventions
| Element | Convention | Example |
|---------|------------|---------|
| Classes | PascalCase | `WarehouseInventory` |
| Interfaces | PascalCase + `Interface` | `WarehouseInventoryServiceInterface` |
| Methods | camelCase | `getAllWarehouseInventories()` |
| Variables | camelCase | `$warehouseInventory` |
| Constants | UPPER_SNAKE_CASE | `MAX_RETRY_COUNT` |
| Tables | snake_case, plural | `warehouse_inventories` |

### Import Statements
- One `use` per line, sorted alphabetically
- Groups: PHP core → Laravel → Custom
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

---

## Architecture Layers

### Enterprise_Layer (`app/Enterprise_Layer/`)
- Pure PHP domain entities (no framework deps)
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
- Request validation classes
- Exception handling
- Framework-specific implementations

### Models (`app/Models/`)
- Eloquent ORM models
- Follow Laravel conventions

### Mappers (`app/Mappers/`)
- DTO <-> Entity mapping
- Request DTOs: `app/Mappers/DTO/Requests/`

---

## Key Patterns

### Result Pattern
```php
public function create(WarehouseInventoryRequestDTO $dto): ResultPattern
{
    if ($condition) {
        return ResultPattern::failure("Error message");
    }
    return ResultPattern::success($result);
}

// Usage
$result = $service->create($dto);
if ($result->isFailure()) {
    return $result->getError();
}
$value = $result->getValue();
```

### Builder Pattern (Entities)
```php
$warehouse = Warehouse::builder()
    ->setWarehouseName('Main Warehouse')
    ->setWarehouseKey('WH-001')
    ->build();
```

### Repository/Service Pattern
- Interfaces in `app/Contracts/`
- Implementations in `app/Application_Layer/`

---

## Error Handling
1. **Domain exceptions**: Throw from `Enterprise_Layer/Exception/`
2. **Infrastructure exceptions**: Handle in `Infrastructure/Exception/`
3. **Application layer**: Catch exceptions, convert to `ResultPattern::failure()`
4. **Controllers**: Return appropriate HTTP responses

```php
try {
    $result = $this->repository->save($entity);
} catch (\Throwable $th) {
    return ResultPattern::failure($th->getMessage());
}
```

---

## Testing Guidelines
- Unit tests: `tests/Unit/` → extend `PHPUnit\Framework\TestCase`
- Feature tests: `tests/Feature/` → extend `Tests\TestCase`
- Test file suffix: `Test.php`
- Test method prefix: `test` or `@test` annotation
- Use factories: `database/factories/`

---

## File Structure
```
app/
├── Application_Layer/
│   ├── Services_Implementation/
│   ├── Repository_Implementation/
│   └── ResultPattern.php
├── Contracts/                    # Interfaces
├── Enterprise_Layer/            # Domain entities
│   └── Exception/
├── Http/Controllers/
├── Infrastructure/
│   └── Exception/
├── Mappers/DTO/Requests/
└── Models/
```

---

## Precautions
- Never commit secrets; use `.env.example`
- `vendor/` and `node_modules/` are gitignored
- `php artisan migrate:fresh` only in development
- Keep entities pure (no framework dependencies)
