<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['idCargo'] != 1) {
    header('Location: Login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="Css/Administrador.css">
</head>
<body>
    <div>
        <aside>
            <div>
                <img src="Imagenes/avatar.jpg" alt="Avatar del usuario" class="avatar">
                <p> Bienvenido: </p><span><?= htmlspecialchars($_SESSION['nombre']); ?></span>
                
            </div>
            <nav>
                <ul>
                    <li><a href="UsuarioGUI.php"> Administracion de Usuarios </a></li>
                    <li><a href="CargoGUI.php"> Administracion de Cargos </a></li>
                    <li><a href="ProductoGUI.php"> Administracion de Productos </a></li>
                    <li><a href="Cerrar_Sesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>

        <main>
            <h1>Panel de Administración</h1>
            <p>Este es el panel de control para administradores.</p>
        </main>
    </div>
</body>
</html>

