<?php
require_once 'Productos.php';
require_once 'Productos.model.php';
require_once 'Categorias.php';
require_once 'Categoria.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['idProductos'])) {
    header("Location: login.php");
    exit();
}

$Productos = new Productos();
$Productosmodel = new ProductosModel();
$cargo = new Cargo();
$Categoriasmodel = new CategoriasModel();

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $idProductos = filter_input(INPUT_POST, 'idProductos', FILTER_VALIDATE_INT);
    $Nombre = trim($_POST['Nombre'] ?? '');
    $Stock = trim($_POST['Stock'] ?? '');
    $Descripcion = trim($_POST['Descripcion'] ?? '');
    $Precio = filter_input(INPUT_POST, 'Precio', FILTER_VALIDATE_FLOAT);
    $Marca = $_POST['Marca'] ?? '';
    $idCategorias = filter_input(INPUT_POST, 'Categoria', FILTER_VALIDATE_INT);

    switch ($operacion) {
        case 'actualizar':
            $Productos->setidProductos($idProductos);
            $Productos->setidProductos($idProductos);
              $Productos->setStock($Stock);
              $Productos->setDescripcion($Descripcion);
              $Productos->setPrecio($Precio);
              $Productos->setMarca($Marca);
              $Productos->setidCategorias($idCategorias);
            if (!empty($clave)) {
                  $Productos->setclave(password_hash($clave, PASSWORD_DEFAULT));
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
                        $Productos->setfotoPerfil($newFileName);
                    }
                }
            }
            $Productosmodel->Actualizar($Productos);
            header('Location: ProductosGUI.php');
            exit();

        case 'registrar':
           $Productos->setidProductos($idProductos);
            $Productos->setidProductos($idProductos);
              $Productos->setStock($Stock);
              $Productos->setDescripcion($Descripcion);
              $Productos->setPrecio($Precio);
              $Productos->setMarca($Marca);
              $Productos->setidCategorias($idCategorias);
            $Productos->setclave(password_hash($clave, PASSWORD_DEFAULT));
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
                        $Productos->setfotoPerfil($newFileName);
                    }
                }
            }
            $Productosmodel->Registrar($Productos);
            header('Location: ProductosGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'idProductos', FILTER_VALIDATE_INT);
            $Productosmodel->Eliminar($idEliminar);
            header('Location: ProductosGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'idProductos', FILTER_VALIDATE_INT);
            $Productos = $Productosmodel->Obtener($idEditar);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="Css/UsuarioGUI.css">
</head>
<body>
    <h1>Administración de Productos</h1>

    <!-- FORMULARIO ADMINISTRACION USUARIOS -->
     <form action="ProductosGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $Productos->getidProductos() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="idProductos" value="<?= $Productos->getidProductos(); ?>">

        <label>Nombre:</label>
        <input type="text" name="Nombre" required value="<?= htmlspecialchars($usuario->getnombre() ?? ''); ?>"><br>

        <label>Stock:</label>
        <input type="number" name="stock" required value="<?= htmlspecialchars($Productos->getStock() ?? ''); ?>"><br>

        <label>Descripcion:</label>
        <input type="text" name="Descripcion" required value="<?= htmlspecialchars($Productos->getDescripcion() ?? ''); ?>"><br>

        <label>Precio:</label>
        <input type="number" name="precio" required value="<?= htmlspecialchars($Productos->getPrecio() ?? ''); ?>"><br>

        <label>$Marca:</label>
        <input type="text" name="marca" required value="<?= htmlspecialchars($Productos->getMarca() ?? ''); ?>"><br>

        <!-- PREGUNTAR SI CLAVE VA  -->
        <label>Clave:</label>
        <input type="password" name="Clave" <?= $Productos->getidProductos() < 1 ? 'required' : ''; ?>><br>

        <label>Categorias:</label>
        <select name="Categoria" required>
            <option value="">Seleccione...</option>
            <?php
            $Categorias = ($_SESSION['idCategorias'] < 3) ? $Categoriasmodel->ListarTodos() : $Categoriamodel->ListarRestringidos();
            foreach ($Categorias as $r):
                $selected = $Productos->getidCategorias() == $r->getidCategorias() ? 'selected' : '';
                echo "<option value='{$r->getidCategorias()}' $selected>" . htmlspecialchars($r->getCategoria()) . "</option>";
            endforeach;
            ?>
        </select><br><br>

        <label>Foto de perfil:</label>
        <input type="file" name="foto" accept=".jpg,.jpeg,.png,.gif"><br>

        <button type="submit">
            <?= $Productos->getidProductos() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de usuarios -->
    <h2>Listado de Productos</h2>
    <table border="1">
        <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>stock</th>
            <th>Descripcion</th>
            <th>Precio</th>
            <th>Marca</th>
            <th>Categorias</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($Productosmodel->Listar() as $r): ?>
            <tr>
                <td>
                    <?php if ($r->getfotoPerfil()): ?>
                    <img src="uploads/perfiles/<?= htmlspecialchars($r->getfotoPerfil()); ?>" alt="Foto" width="60">
                    <?php else: ?>
                    <span>Sin foto</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($r->getnombre()); ?></td>
                <td><?= htmlspecialchars($r->getStock()); ?></td>
                <td><?= htmlspecialchars($r->getDescripcion()); ?></td>
                <td><?= htmlspecialchars($r->getPrecio()); ?></td>
                  <td><?= htmlspecialchars($r->getMarca()); ?></td>
                <td><?= htmlspecialchars($r->getCategorias()); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="idusuario" value="<?= $r->getidProductos(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este producto?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="idProductos" value="<?= $r->getidProductos(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>




