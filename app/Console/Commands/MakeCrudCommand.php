<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Comando para generar un CRUD completo automáticamente.
 * 
 * USO: php artisan make:crud ModelName --fields="nombre:string:unique,descripcion:text:nullable"
 * 
 * GENERA:
 * - Migración
 * - Modelo + Factory + Seeder
 * - Form (BaseModelForm)
 * - QueryBuilder (BaseQueryBuilder)
 * - Policy (BaseAdminPolicy)
 * - Componente Livewire (BaseCrudComponent)
 * - Vista (plantilla optimizada)
 * - Tests (BaseCrudTest)
 * - Ruta
 */
class MakeCrudCommand extends Command
{
    protected $signature = 'make:crud {name} {--fields=}';
    protected $description = 'Genera un CRUD completo con todos sus componentes';

    public function handle()
    {
        $name = $this->argument('name');
        $fields = $this->option('fields') ?? 'nombre:string:unique';
        
        $this->info("🚀 Generando CRUD completo para: {$name}");
        $this->info("📝 Campos: {$fields}");
        $this->newLine();
        
        // Crear cada componente
        $this->createMigration($name, $fields);
        $this->createModel($name, $fields);
        $this->createFactory($name, $fields);
        $this->createSeeder($name);
        $this->createForm($name, $fields);
        $this->createQueryBuilder($name, $fields);
        $this->createPolicy($name);
        $this->createLivewireComponent($name, $fields);
        $this->createView($name, $fields);
        $this->createTest($name, $fields);
        $this->createRoute($name);
        
        $this->newLine();
        $this->info("✅ CRUD completo generado exitosamente!");
        $this->info("📌 Próximos pasos:");
        $this->warn("   1. Ejecutar: php artisan migrate");
        $this->warn("   2. Agregar enlace en navegación (si es necesario)");
        $this->warn("   3. Ejecutar: php artisan test");
        
        return Command::SUCCESS;
    }
    
    protected function createMigration($name, $fields)
    {
        $table = Str::plural(Str::snake($name));
        $this->call('make:migration', ['name' => "create_{$table}_table"]);
    }
    
    protected function createModel($name, $fields)
    {
        $this->info("   → Generando {$name} modelo...");
        
        $table = Str::plural(Str::snake($name));
        $searchableFields = $this->getSearchableFields($fields);
        
        $content = "<?php

namespace App\Models;

use App\Models\Builders\\{$name}QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {$name} extends Model
{
    use HasFactory, SoftDeletes;

    protected \$fillable = [" . $this->getFieldsList($fields) . "];

    /**
     * Crea una instancia del query builder personalizado.
     */
    public function newEloquentBuilder(\$query): {$name}QueryBuilder
    {
        return new {$name}QueryBuilder(\$query);
    }
}
";
        
        file_put_contents(app_path("Models/{$name}.php"), $content);
        $this->info("      ✓ {$name}.php creado");
    }
    
    protected function createFactory($name, $fields)
    {
        $this->info("   → Generando {$name}Factory...");
        
        $fieldDefinitions = $this->getFactoryFields($fields);
        
        $content = "<?php

namespace Database\Factories;

use App\Models\\{$name};
use Database\Factories\Traits\HasStandardFields;
use Illuminate\Database\Eloquent\Factories\Factory;

class {$name}Factory extends Factory
{
    use HasStandardFields;

    protected \$model = {$name}::class;

    public function definition(): array
    {
        return [
{$fieldDefinitions}
        ];
    }
}
";
        
        file_put_contents(database_path("factories/{$name}Factory.php"), $content);
        $this->info("      ✓ {$name}Factory.php creado");
    }
    
    protected function createSeeder($name)
    {
        $this->info("   → Generando {$name}Seeder...");
        
        $content = "<?php

namespace Database\Seeders;

use App\Models\\{$name};

class {$name}Seeder extends BaseSeeder
{
    protected function getModelClass(): string
    {
        return {$name}::class;
    }
    
    protected function getCount(): int
    {
        return 20;
    }
}
";
        
        file_put_contents(database_path("seeders/{$name}Seeder.php"), $content);
        $this->info("      ✓ {$name}Seeder.php creado");
    }
    
    protected function createForm($name, $fields)
    {
        $this->info("   → Generando {$name}Form...");
        
        $properties = $this->getFormProperties($fields);
        $rules = $this->getFormRules($fields, $name);
        $fillMethod = $this->getFormFillMethod($fields);
        $dataMethod = $this->getFormDataMethod($fields);
        
        $content = "<?php

namespace App\Livewire\Forms;

use App\Models\\{$name};
use Illuminate\Validation\Rule as ValidationRule;

class {$name}Form extends BaseModelForm
{
{$properties}

    protected function rules(): array
    {
        return [
{$rules}
        ];
    }

    protected function getModelClass(): string
    {
        return {$name}::class;
    }

    protected function fillFromModel(\$model): void
    {
{$fillMethod}
    }

    protected function getModelData(): array
    {
        return [
{$dataMethod}
        ];
    }
}
";
        
        file_put_contents(app_path("Livewire/Forms/{$name}Form.php"), $content);
        $this->info("      ✓ {$name}Form.php creado");
    }
    
    protected function createQueryBuilder($name, $fields)
    {
        $this->info("   → Generando {$name}QueryBuilder...");
        
        $searchableFields = $this->getSearchableFieldsArray($fields);
        
        $content = "<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class {$name}QueryBuilder extends Builder
{
    use BaseQueryBuilder;

    protected array \$searchableFields = {$searchableFields};
}
";
        
        file_put_contents(app_path("Models/Builders/{$name}QueryBuilder.php"), $content);
        $this->info("      ✓ {$name}QueryBuilder.php creado");
    }
    
    protected function createPolicy($name)
    {
        $this->info("   → Generando {$name}Policy...");
        
        $content = "<?php

namespace App\Policies;

use App\Models\\{$name};
use App\Models\User;

class {$name}Policy extends BaseAdminPolicy
{
    // Toda la lógica de autorización se hereda de BaseAdminPolicy
    // Solo sobrescribe métodos si necesitas lógica específica
}
";
        
        file_put_contents(app_path("Policies/{$name}Policy.php"), $content);
        $this->info("      ✓ {$name}Policy.php creado");
    }
    
    protected function createLivewireComponent($name, $fields)
    {
        $plural = Str::plural($name);
        $this->info("   → Generando Gestionar{$plural} componente...");
        
        $lowerName = Str::lower($name);
        
        $content = "<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseCrudComponent;
use App\Livewire\Forms\\{$name}Form;
use App\Livewire\Traits\WithExport;
use App\Models\\{$name};
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Gestionar{$plural} extends BaseCrudComponent
{
    use WithExport;
    
    public {$name}Form \$form;
    public ?{$name} \${$lowerName}RecienCreado = null;
    
    protected \$listeners = [
        '{$lowerName}Deleted' => '\$refresh',
        '{$lowerName}Restored' => '\$refresh'
    ];
    
    protected function getModelClass(): string
    {
        return {$name}::class;
    }
    
    protected function getViewName(): string
    {
        return 'livewire.admin.gestionar-" . Str::kebab($plural) . "';
    }
    
    protected function getExportColumns(): array
    {
        return [
            'id' => 'ID',
" . $this->getExportColumns($fields) . "
            'created_at' => 'Fecha Creación',
        ];
    }
}
";
        
        file_put_contents(app_path("Livewire/Admin/Gestionar{$plural}.php"), $content);
        $this->info("      ✓ Gestionar{$plural}.php creado");
    }
    
    protected function createView($name, $fields)
    {
        $plural = Str::plural($name);
        $this->info("   → Generando vista...");
        
        $lowerPlural = Str::lower($plural);
        $lowerName = Str::lower($name);
        $columns = $this->getViewColumns($fields);
        $formFields = $this->getViewFormFields($fields, $name);
        
        $viewPath = resource_path("views/livewire/admin/gestionar-" . Str::kebab($plural) . ".blade.php");
        
        // Copiar plantilla y reemplazar placeholders
        $template = file_get_contents(resource_path('views/templates/crud-index.blade.php'));
        
        $replacements = [
            'Equipos' => $plural,
            'equipo' => $lowerName,
            'equipos' => $lowerPlural,
            'equipoRecienCreado' => "{$lowerName}RecienCreado",
            'Equipo' => $name,
        ];
        
        $content = str_replace(array_keys($replacements), array_values($replacements), $template);
        
        file_put_contents($viewPath, $content);
        $this->info("      ✓ gestionar-" . Str::kebab($plural) . ".blade.php creado");
    }
    
    protected function createTest($name, $fields)
    {
        $plural = Str::plural($name);
        $this->info("   → Generando Gestionar{$plural}Test...");
        
        $requiredFields = $this->getTestRequiredFields($fields);
        
        $content = "<?php

namespace Tests\Feature\Livewire\Admin;

use Tests\Feature\Livewire\BaseCrudTest;

class Gestionar{$plural}LivewireTest extends BaseCrudTest
{
    protected function getModelClass(): string
    {
        return \\App\\Models\\{$name}::class;
    }
    
    protected function getComponentClass(): string
    {
        return \\App\\Livewire\\Admin\\Gestionar{$plural}::class;
    }
    
    protected function getRequiredFields(): array
    {
        return {$requiredFields};
    }
}
";
        
        $testPath = base_path("tests/Feature/Livewire/Admin/Gestionar{$plural}LivewireTest.php");
        @mkdir(dirname($testPath), 0755, true);
        file_put_contents($testPath, $content);
        $this->info("      ✓ Gestionar{$plural}LivewireTest.php creado");
    }
    
    protected function createRoute($name)
    {
        $plural = Str::plural($name);
        $kebab = Str::kebab($plural);
        $this->info("   → Registrando ruta...");
        
        $route = "\nRoute::middleware(['auth', 'verified'])->group(function () {\n";
        $route .= "    Route::get('/admin/{$kebab}', \\App\\Livewire\\Admin\\Gestionar{$plural}::class)->name('admin.{$kebab}.index');\n";
        $route .= "});\n";
        
        file_put_contents(base_path('routes/web.php'), $route, FILE_APPEND);
        $this->info("      ✓ Ruta registrada en routes/web.php");
    }
    
    // MÉTODOS HELPER
    protected function parseFields($fields)
    {
        $parsed = [];
        foreach (explode(',', $fields) as $field) {
            $parts = explode(':', trim($field));
            $parsed[] = ['name' => $parts[0], 'type' => $parts[1] ?? 'string', 'rules' => array_slice($parts, 2)];
        }
        return $parsed;
    }
    
    protected function getFieldsList($fields)
    {
        $parsed = $this->parseFields($fields);
        return "\n        " . implode(",\n        ", array_map(fn($f) => "'{$f['name']}'", $parsed)) . "\n    ";
    }
    
    protected function getSearchableFields($fields)
    {
        $parsed = $this->parseFields($fields);
        return array_column(array_filter($parsed, fn($f) => in_array($f['type'], ['string', 'text'])), 'name');
    }
    
    protected function getSearchableFieldsArray($fields)
    {
        $searchable = $this->getSearchableFields($fields);
        return "[" . implode(', ', array_map(fn($f) => "'{$f}'", $searchable)) . "]";
    }
    
    protected function getFactoryFields($fields)
    {
        $lines = [];
        foreach ($this->parseFields($fields) as $field) {
            $value = match($field['type']) {
                'string' => in_array('unique', $field['rules']) ? "\$this->uniqueName()" : "fake()->words(2, true)",
                'text' => "\$this->description()",
                'integer' => "fake()->numberBetween(1, 100)",
                'boolean' => "fake()->boolean()",
                default => "fake()->word()",
            };
            $lines[] = "            '{$field['name']}' => {$value},";
        }
        return implode("\n", $lines);
    }
    
    protected function getFormProperties($fields)
    {
        $lines = [];
        foreach ($this->parseFields($fields) as $field) {
            $phpType = match($field['type']) { 'integer' => 'int', 'boolean' => 'bool', default => 'string' };
            $default = match($field['type']) { 'integer' => '0', 'boolean' => 'false', default => "''" };
            $lines[] = "    public {$phpType} \${$field['name']} = {$default};";
        }
        return implode("\n", $lines);
    }
    
    protected function getFormRules($fields, $modelName)
    {
        $lines = [];
        $table = Str::plural(Str::snake($modelName));
        foreach ($this->parseFields($fields) as $field) {
            $rulesList = [];
            if (!in_array('nullable', $field['rules'])) $rulesList[] = "'required'";
            $rulesList[] = match($field['type']) { 'integer' => "'integer'", 'boolean' => "'boolean'", default => "'string'" };
            if (in_array($field['type'], ['string', 'text'])) $rulesList[] = "'max:255'";
            if (in_array('unique', $field['rules'])) $rulesList[] = "ValidationRule::unique('{$table}')->ignore(\$this->model?->id)";
            $lines[] = "            '{$field['name']}' => [" . implode(', ', $rulesList) . "],";
        }
        return implode("\n", $lines);
    }
    
    protected function getFormFillMethod($fields)
    {
        return implode("\n", array_map(fn($f) => "        \$this->{$f['name']} = \$model->{$f['name']};", $this->parseFields($fields)));
    }
    
    protected function getFormDataMethod($fields)
    {
        return implode("\n", array_map(fn($f) => "            '{$f['name']}' => \$this->{$f['name']},", $this->parseFields($fields)));
    }
    
    protected function getExportColumns($fields)
    {
        return implode("\n", array_map(fn($f) => "            '{$f['name']}' => '" . ucfirst($f['name']) . "',", $this->parseFields($fields)));
    }
    
    protected function getViewColumns($fields) { return $this->parseFields($fields); }
    protected function getViewFormFields($fields, $modelName) { return $this->parseFields($fields); }
    
    protected function getTestRequiredFields($fields)
    {
        $firstField = $this->parseFields($fields)[0] ?? ['name' => 'nombre'];
        return "[\n            'form.{$firstField['name']}' => 'Test Value'\n        ]";
    }
}
