<?php
require_once 'Sucursal.php';
require_once 'Sucursal.model.php';
require_once 'Cargo.php';
require_once 'Cargo.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['id_sucursal'])) {
    header("Location: login.php");
    exit();
}

$sucursal = new Sucursal();
$sucursalmodel = new SucursalModel();
$cargo = new Cargo();
$cargomodel = new CargoModel();

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $id_sucursal = filter_input(INPUT_POST, 'id_sucursal', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $hora = trim($_POST['hora'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $codigo_postal = trim($_POST['codigo_postal'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = filter_input(INPUT_POST, 'Correo', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['Clave'] ?? '';

    switch ($operacion) {
        case 'actualizar':
            $sucursal->setid_sucursal($id_sucursal);
            $sucursal->setnombre($nombre);
            $sucursal->setdireccion($direccion);
            $sucursal->sethora($hora);
            $sucursal->setlocalidad($localidad);
            $sucursal->setcodigo_postal($codigo_postal);
            $sucursal->settelefono($telefono);
            $sucursal->setcorreo($correo);
            if (!empty($clave)) {
                $sucursal->setclave(password_hash($clave, PASSWORD_DEFAULT));
            }
            $sucursalmodel->Actualizar($sucursal);
            header('Location: SucursalGUI.php');
            exit();

        case 'registrar':
            $sucursal->setid_sucursal($id_sucursal);
            $sucursal->setnombre($nombre);
            $sucursal->setdireccion($direccion);
            $sucursal->sethora($hora);
            $sucursal->setlocalidad($localidad);
            $sucursal->setcodigo_postal($codigo_postal);
            $sucursal->settelefono($telefono);
            $sucursal->setcorreo($correo)
            $sucursal->setclave(password_hash($clave, PASSWORD_DEFAULT));
            //  
            $sucursalmodel->Registrar($sucursal);
            header('Location: SucursalGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'id_sucursal', FILTER_VALIDATE_INT);
            $sucursalmodel->Eliminar($idEliminar);
            header('Location: SucursalGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'id_sucursal', FILTER_VALIDATE_INT);
            $sucursal = $sucursalmodel->Obtener($idEditar);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Sucursales</title>
    <link rel="stylesheet" href="Css/SucursalGUI.css">
</head>
<body>
    <h1>Administración de Sucursales</h1>

    <!-- FORMULARIO ADMINISTRACION USUARIOS -->
     <form action="SucursalGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $sucursal->getid_sucursal() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="id_sucursal" value="<?= $sucursal->getid_sucursal(); ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($sucursal->getnombre() ?? ''); ?>"><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" required value="<?= htmlspecialchars($sucursal->getdireccion() ?? ''); ?>"><br>

        <label>Hora:</label>
        <input type="date" name="hora" required value="<?= htmlspecialchars($sucursal->gethora() ?? ''); ?>"><br>

        <label>Localidad:</label>
        <input type="text" name="localidad" required value="<?= htmlspecialchars($sucursal->getlocalidad() ?? ''); ?>"><br>

        <label>Codigo Postal:</label>
        <input type="text" name="codigo_postal" required value="<?= htmlspecialchars($sucursal->getcodigo_postal() ?? ''); ?>"><br>

        <label>Telefono:</label>
        <input type="text" name="telefono" required value="<?= htmlspecialchars($sucursal->gettelefono() ?? ''); ?>"><br>

        <label>Correo:</label>
        <input type="text" name="correo" required value="<?= htmlspecialchars($sucursal->getcorreo() ?? ''); ?>"><br>


        <label>Clave:</label>
        <input type="password" name="Clave" <?= $sucursal->getid_sucursal() < 1 ? 'required' : ''; ?>><br>
        <br>

        
        <button type="submit">
            <?= $sucursal->getid_sucursal() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de sucursales -->
    <h2>Listado de Sucursales</h2>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Hora</th>
            <th>Localidad</th>
            <th>Codigo Postal</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($sucursalmodel->Listar() as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r->getnombre()); ?></td>
                <td><?= htmlspecialchars($r->getdireccion()); ?></td>
                <td><?= htmlspecialchars($r->gethora()); ?></td>
                <td><?= htmlspecialchars($r->getlocalidad()); ?></td>
                <td><?= htmlspecialchars($r->getcodigo_postal()); ?></td>
                <td><?= htmlspecialchars($r->gettelefono()); ?></td>
                <td><?= htmlspecialchars($r->getcorreo()); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="id_sucursal" value="<?= $r->getid_sucursal(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar esta sucursal?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="id_sucursal" value="<?= $r->getid_sucursal(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

