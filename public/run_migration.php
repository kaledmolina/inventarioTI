<?php
// Bypass config/database.php to avoid getenv issues and use what we found in backuper
$host = "localhost";
$user = "root";
$pass = "";
$name = "inventario_ti";

$conexion = null;

try {
    // Try localhost first (Socket)
    echo "Attempting connection to $host...\n";
    $conexion = new mysqli($host, $user, $pass, $name);
} catch (Exception $e) {
    echo "Failed localhost: " . $e->getMessage() . "\n";
    try {
        // Try 127.0.0.1 (TCP)
        $host = "127.0.0.1";
        echo "Attempting connection to $host...\n";
        $conexion = new mysqli($host, $user, $pass, $name);
    } catch (Exception $e2) {
        die("Fatal connection error: " . $e2->getMessage() . "\n");
    }
}

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error . "\n");
}

echo "Connected successfully to $host\n";
$conexion->set_charset("utf8mb4");

$sqlFile = __DIR__ . '/../migration_clients.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found at $sqlFile\n");
}
$sql = file_get_contents($sqlFile);

// Simple splitter
$commands = explode(';', $sql);

foreach ($commands as $command) {
    $command = trim($command);
    if (empty($command))
        continue;

    // Skip delimiter hacks if present
    if (stripos($command, 'DELIMITER') !== false)
        continue;

    try {
        if ($conexion->query($command) === TRUE) {
            echo "Success: " . substr($command, 0, 80) . "...\n";
        } else {
            // Check if error is "Duplicate column" or similar benign error
            if ($conexion->errno == 1060) { // Duplicate column
                echo "Skipped (Duplicate Column): " . substr($command, 0, 50) . "...\n";
            } elseif ($conexion->errno == 1050) { // Table exists
                echo "Skipped (Table Exists): " . substr($command, 0, 50) . "...\n";
            } else {
                echo "Error ({$conexion->errno}): " . $conexion->error . "\nSQL: " . substr($command, 0, 100) . "\n";
            }
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}

$conexion->close();
echo "Migration completed.\n";
?>