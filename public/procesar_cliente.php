<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$accion = $_POST['accion'] ?? '';
$id = $_POST['id'] ?? null;
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$dni = trim($_POST['dni'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

if (empty($nombres) || empty($apellidos) || empty($dni)) {
    header("Location: clientes.php?msg=error_campos");
    exit();
}

if ($accion === 'crear') {
    $stmt = $conexion->prepare("INSERT INTO clientes (nombres, apellidos, dni, telefono, email, direccion) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombres, $apellidos, $dni, $telefono, $email, $direccion);

    if ($stmt->execute()) {
        header("Location: clientes.php?msg=creado");
    } else {
        header("Location: clientes.php?msg=error_db&err=" . urlencode($conexion->error));
    }
    $stmt->close();

} elseif ($accion === 'editar' && $id) {
    $stmt = $conexion->prepare("UPDATE clientes SET nombres=?, apellidos=?, dni=?, telefono=?, email=?, direccion=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nombres, $apellidos, $dni, $telefono, $email, $direccion, $id);

    if ($stmt->execute()) {
        header("Location: clientes.php?msg=actualizado");
    } else {
        header("Location: clientes.php?msg=error_db");
    }
    $stmt->close();
} else {
    header("Location: clientes.php");
}

$conexion->close();
?>