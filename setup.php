<?php
// /kwetu_con/setup.php

/**
 * Script rapide pour initialiser l'application
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Configuration KWETU CON</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 5px; }
        .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Configuration de KWETU CON</h1>";

// Créer les dossiers nécessaires
$directories = [
    'config',
    'storage/logs',
    'storage/uploads',
    'public/assets/images/uploads'
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0777, true)) {
            echo "<div class='success'>✓ Dossier créé: {$dir}</div>";
        } else {
            echo "<div class='error'>✗ Erreur création: {$dir}</div>";
        }
    } else {
        echo "<div class='success'>✓ Dossier existant: {$dir}</div>";
    }
}

// Créer le fichier de configuration database.php s'il n'existe pas
$configFile = __DIR__ . '/config/database.php';
if (!file_exists($configFile)) {
    $configContent = "<?php\n\nreturn [\n    'host' => 'localhost',\n    'dbname' => 'kwetu_con',\n    'user' => 'root',\n    'pass' => '',\n    'charset' => 'utf8mb4'\n];";
    if (file_put_contents($configFile, $configContent)) {
        echo "<div class='success'>✓ Fichier config/database.php créé</div>";
    }
}

echo "<div class='success' style='margin-top:20px;'>✅ Configuration terminée !</div>";
echo "<p><a href='init.php'>Exécuter init.php pour installer la base de données</a></p>";
echo "<p><a href='public/'>Accéder à l'application</a></p>";
echo "</div></body></html>";