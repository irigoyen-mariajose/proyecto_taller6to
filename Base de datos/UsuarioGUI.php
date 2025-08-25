<?php
require_once 'Usuario.php';
require_once 'Usuario.model.php';
require_once 'Cargo.php';
require_once 'Cargo.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = new Usuario();
$usuariomodel = new UsuarioModel();
$cargo = new Cargo();
$cargomodel = new CargoModel();

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $idUsuario = filter_input(INPUT_POST, 'idUsuario', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['Nombre'] ?? '');
    $apellido = trim($_POST['Apellido'] ?? '');
    $direccion = trim($_POST['Direccion'] ?? '');
    $correo = filter_input(INPUT_POST, 'Correo', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['Clave'] ?? '';
    $idCargo = filter_input(INPUT_POST, 'Cargo', FILTER_VALIDATE_INT);

    switch ($operacion) {
        case 'actualizar':
            $usuario->setidusuario($idUsuario);
            $usuario->setnombre($nombre);
            $usuario->setapellido($apellido);
            $usuario->setdireccion($direccion);
            $usuario->setcorreo($correo);
            $usuario->setidcargo($idCargo);
            if (!empty($clave)) {
                $usuario->setclave(password_hash($clave, PASSWORD_DEFAULT));
            }
            // Manejo de la imagen subida
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $dest_path = 'uploads/perfiles/' . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $usuario->setfotoPerfil($newFileName);
                    }
                }
            }
            $usuariomodel->Actualizar($usuario);
            header('Location: UsuarioGUI.php');
            exit();

        case 'registrar':
            $usuario->setnombre($nombre);
            $usuario->setapellido($apellido);
            $usuario->setdireccion($direccion);
            $usuario->setcorreo($correo);
            $usuario->setidcargo($idCargo);
            $usuario->setclave(password_hash($clave, PASSWORD_DEFAULT));
            //  
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $dest_path = 'uploads/perfiles/' . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $usuario->setfotoPerfil($newFileName);
                    }
                }
            }
            $usuariomodel->Registrar($usuario);
            header('Location: UsuarioGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'idusuario', FILTER_VALIDATE_INT);
            $usuariomodel->Eliminar($idEliminar);
            header('Location: UsuarioGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'idusuario', FILTER_VALIDATE_INT);
            $usuario = $usuariomodel->Obtener($idEditar);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="Css/UsuarioGUI.css">
</head>
<body>
    <h1>Administración de Usuarios</h1>

    <!-- FORMULARIO ADMINISTRACION USUARIOS -->
     <form action="UsuarioGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $usuario->getidusuario() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="idUsuario" value="<?= $usuario->getidusuario(); ?>">

        <label>Nombre:</label>
        <input type="text" name="Nombre" required value="<?= htmlspecialchars($usuario->getnombre() ?? ''); ?>"><br>

        <label>Apellido:</label>
        <input type="text" name="Apellido" required value="<?= htmlspecialchars($usuario->getapellido() ?? ''); ?>"><br>

        <label>Dirección:</label>
        <input type="text" name="Direccion" required value="<?= htmlspecialchars($usuario->getdireccion() ?? ''); ?>"><br>

        <label>Correo:</label>
        <input type="email" name="Correo" required value="<?= htmlspecialchars($usuario->getcorreo() ?? ''); ?>"><br>

        <label>Clave:</label>
        <input type="password" name="Clave" <?= $usuario->getidusuario() < 1 ? 'required' : ''; ?>><br>

        <label>Cargo:</label>
        <select name="Cargo" required>
            <option value="">Seleccione...</option>
            <?php
            $cargos = ($_SESSION['idCargo'] < 3) ? $cargomodel->ListarTodos() : $cargomodel->ListarRestringidos();
            foreach ($cargos as $r):
                $selected = $usuario->getidcargo() == $r->getidCargo() ? 'selected' : '';
                echo "<option value='{$r->getidCargo()}' $selected>" . htmlspecialchars($r->getCargo()) . "</option>";
            endforeach;
            ?>
        </select><br><br>

        <label>Foto de perfil:</label>
        <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif"><br>

        <button type="submit">
            <?= $usuario->getidusuario() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de usuarios -->
    <h2>Listado de Usuarios</h2>
    <table border="1">
        <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Dirección</th>
            <th>Correo</th>
            <th>Cargo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuariomodel->Listar() as $r): ?>
            <tr>
                <td>
                    <?php if ($r->getfotoPerfil()): ?>
                    <img src="uploads/perfiles/<?= htmlspecialchars($r->getfotoPerfil()); ?>" alt="Foto" width="60">
                    <?php else: ?>
                    <span>Sin foto</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($r->getnombre()); ?></td>
                <td><?= htmlspecialchars($r->getapellido()); ?></td>
                <td><?= htmlspecialchars($r->getdireccion()); ?></td>
                <td><?= htmlspecialchars($r->getcorreo()); ?></td>
                <td><?= htmlspecialchars($r->getcargo()); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="idusuario" value="<?= $r->getidusuario(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este usuario?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="idusuario" value="<?= $r->getidusuario(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

