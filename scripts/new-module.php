<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

if ($argc < 2) {
    echo "Usage: php scripts/new-module.php [ModuleName]\n";
    echo "Example: php scripts/new-module.php Product\n";
    exit(1);
}

$name = ucfirst($argv[1]);
$baseDir = __DIR__ . '/..';

// 1. Controller
$controllerTemplate = <<<PHP
<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\\$name;

class {$name}Controller extends BaseController
{
    public function index()
    {
        \$model = new $name();
        \$items = \$model->all();
        return \$this->view('{$name}/index', ['items' => \$items]);
    }
}
PHP;

$controllerFile = "$baseDir/app/Controllers/{$name}Controller.php";
if (!file_exists($controllerFile)) {
    file_put_contents($controllerFile, $controllerTemplate);
    echo "Created Controller: $controllerFile\n";
} else {
    echo "Controller already exists: $controllerFile\n";
}

// 2. Model
$modelTemplate = <<<PHP
<?php

namespace App\Models;

use App\Core\BaseModel;

class $name extends BaseModel
{
    protected \$table = '{$name}s'; // Assumption: lowercase plural
}
PHP;

$modelFile = "$baseDir/app/Models/{$name}.php";
if (!file_exists($modelFile)) {
    file_put_contents($modelFile, $modelTemplate);
    echo "Created Model: $modelFile\n";
} else {
    echo "Model already exists: $modelFile\n";
}

// 3. View
$viewDir = "$baseDir/app/Views/" . strtolower($name);
if (!is_dir($viewDir)) {
    mkdir($viewDir, 0755, true);
}

$viewTemplate = <<<HTML
<h1>$name List</h1>
<ul>
    <?php foreach (\$items as \$item): ?>
        <li><?= htmlspecialchars(json_encode(\$item)) ?></li>
    <?php endforeach; ?>
</ul>
HTML;

$viewFile = "$viewDir/index.php";
if (!file_exists($viewFile)) {
    file_put_contents($viewFile, $viewTemplate);
    echo "Created View: $viewFile\n";
} else {
    echo "View already exists: $viewFile\n";
}

echo "\nDone. Don't forget to add the route in public/index.php:\n";
echo "\$router->get('/" . strtolower($name) . "s', [App\Controllers\\{$name}Controller::class, 'index']);\n";
