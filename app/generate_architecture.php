<?php
/**
 * Generador automático de arquitectura + ERD en Mermaid
 * Uso: php scripts/generate_architecture.php
 */

$basePath = realpath(__DIR__ . "/..");

// Paths principales
$modelsPath = $basePath . "/app/Models";
$controllersPath = $basePath . "/app/Http/Controllers";
$outputFile = $basePath . "/architecture.md";

// Función para obtener clases PHP dentro de una carpeta
function getPhpClasses($path) {
    $files = glob($path . "/*.php");
    $classes = [];

    foreach ($files as $file) {
        $content = file_get_contents($file);
        if (preg_match('/class\s+([A-Za-z0-9_]+)/', $content, $m)) {
            $classes[] = $m[1];
        }
    }

    return $classes;
}

// Obtener modelos y controladores
$models = getPhpClasses($modelsPath);
$controllers = getPhpClasses($controllersPath);

// Detectar relaciones en modelos (1:N, N:1, N:M)
function detectRelations($fileContent) {
    $relations = [];

    if (preg_match_all('/return \$this->(belongsTo|hasMany|belongsToMany|hasOne)\(([^;]+)\)/', $fileContent, $m)) {
        foreach ($m[1] as $i => $type) {
            $relations[] = [$type, trim($m[2][$i])];
        }
    }

    return $relations;
}

$relationsMap = [];

foreach (glob($modelsPath . "/*.php") as $file) {
    $content = file_get_contents($file);
    if (preg_match('/class\s+([A-Za-z0-9_]+)/', $content, $m)) {
        $modelName = $m[1];
        $relationsMap[$modelName] = detectRelations($content);
    }
}

// Generar archivo Mermaid
$mermaid = "```mermaid\nflowchart TD\n\n";
$mermaid .= "  %% === CAPA PRESENTACIÓN ===\n";
$mermaid .= "  User[\"Usuario (Browser)\"] --> WebServer[\"Servidor Web\"]\n";
$mermaid .= "  WebServer --> Entry[\"public/index.php\"]\n";
$mermaid .= "  Entry --> Laravel[\"Laravel Framework\"]\n\n";

$mermaid .= "  %% === CONTROLADORES ===\n";
foreach ($controllers as $c) {
    $mermaid .= "  Laravel --> C_{$c}[\"Controlador: $c\"]\n";
}

$mermaid .= "\n  %% === MODELOS ===\n";
foreach ($models as $m) {
    $mermaid .= "  Laravel --> M_{$m}[\"Modelo: $m\"]\n";
}

$mermaid .= "\n  %% === RELACIONES ENTRE MODELOS ===\n";
foreach ($relationsMap as $model => $relations) {
    foreach ($relations as [$type, $target]) {
        $clean = str_replace('::class', '', $target);
        $mermaid .= "  M_$model -->|$type| M_$clean\n";
    }
}

$mermaid .= "\n  %% === FIN ===\n```";

// Guardar archivo
file_put_contents($outputFile, $mermaid);

echo "Archivo generado: architecture.md\n";
