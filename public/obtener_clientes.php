<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// Validar sesión si es necesario
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$query = $_GET['query'] ?? '';

$sql = "SELECT id, nombres, apellidos, dni FROM clientes 
        WHERE activo = 1 
        ORDER BY apellidos, nombres";

// Si quisieras filtrar por nombre:
// $sql = "SELECT ... WHERE (nombres LIKE ? OR apellidos LIKE ? OR dni LIKE ?) ... "

$result = $conexion->query($sql);

$clientes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = [
            'id' => $row['id'],
            'completo' => $row['apellidos'] . ', ' . $row['nombres'] . ' (DNI: ' . $row['dni'] . ')'
        ];
    }
}

echo json_encode($clientes);
?>