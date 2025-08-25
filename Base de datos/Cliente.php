<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['idCargo'] != 2) {
    header('Location: Login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Cliente</title>
    <link rel="stylesheet" href="Css/Cliente.css">
</head>
<body>
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']); ?> (Cliente)</h1>
    

    <ul>
        <li><a href="perfil.php">Ver perfil</a></li>
        <li><a href="Cerrar_Sesion.php">Cerrar sesión</a></li>
    </ul>
</body>
</html>
