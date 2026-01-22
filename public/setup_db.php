<?php
// setup_db.php
// This script applies the migration_clients.sql to the database.
// It bypasses CLI connection issues by running inside the web server.

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Applying Database Migration...</h1>";

// Try to include database config
if (file_exists('../config/database.php')) {
    require_once '../config/database.php';
} else {
    die("Error: config/database.php not found.");
}

// Check connection
if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}
echo "<p>Connected to database.</p>";

$sqlFile = __DIR__ . '/../migration_clients.sql';
if (!file_exists($sqlFile)) {
    die("Error: migration_clients.sql not found at $sqlFile");
}

$sql = file_get_contents($sqlFile);
$commands = explode(';', $sql);

echo "<ul>";
foreach ($commands as $command) {
    $command = trim($command);
    if (empty($command))
        continue;

    echo "<li>Executing: " . htmlspecialchars(substr($command, 0, 50)) . "... ";
    try {
        if ($conexion->query($command) === TRUE) {
            echo "<span style='color:green'>OK</span>";
        } else {
            // Ignore some errors
            if ($conexion->errno == 1060 || $conexion->errno == 1050 || $conexion->errno == 1061) {
                echo "<span style='color:orange'>Skipped (Exists)</span>";
            } else {
                echo "<span style='color:red'>Error: " . $conexion->error . "</span>";
            }
        }
    } catch (Exception $e) {
        echo "<span style='color:red'>Exception: " . $e->getMessage() . "</span>";
    }
    echo "</li>";
}
echo "</ul>";

echo "<h2>Migration Finished.</h2>";
?>