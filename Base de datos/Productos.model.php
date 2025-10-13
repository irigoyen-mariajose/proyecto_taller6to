<?php

include_once("Conexion.php");
include_once("Productos.php");
include_once("Categoria.php");
include_once("Categoria.model.php");


class ProductosModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todos los usuarios con su cargo
    public function Listar(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT u.idProductos, u.Nombre, u.Stock, u.Descripcion,u.Precio,u.Marca, c.Nomcategoria 
                FROM productos u 
                INNER JOIN categoria c ON u.idCategoria = c.idCategoria
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $productos = new Productos();
                $productos->setidProductos($r->idProductos);
                $productos->setNombre($r->Nombre);
                $productos->setStock($r->Stock);
                $productos->setDescripcion($r->Descripcion);
                $productos->setPrecio($r->Precio);
                $productos->setMarca($r->Marca);
                  $productos->setNomcategoria($r->Nomcategoria);
                $result[] = $productos;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar usuarios: " . $e->getMessage());
        }
    }

    // Obtener un usuario por su ID
    public function Obtener(int $idProductos): ?Productos {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM productos WHERE idProductos = ?");
            $stm->execute([$idProductos]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $productos = new Productos();
                $productos->setidProductos($r->idProductos);
                $productos->setNombre($r->Nombre);
                $productos->setStock($r->Stock);
                $productos->setDescripcion($r->Descripcion);
                $productos->setPrecio($r->Precio);
                $productos->setMarca($r->Marca);
                  $productos->setidCategoria($r->idCategoria);
                return $productos;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener usuario: " . $e->getMessage());
        }
    }

    // Eliminar un usuario por ID
    public function Eliminar(int $idProductos): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM productos WHERE idProductos = ?");
            $stm->execute([$idProductos]);
        } catch (Exception $e) {
            die("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    // Actualizar un usuario (con o sin clave)
    public function Actualizar(Productos $data): void {
        try {
            $params = [
                $data->getNombre(),
                $data->getStock(),
                $data->getDescripcion(),
                $data->getPrecio(),
                $data->getMarca(),
                $data->getidCategoria(),
            ];

            $sql = "UPDATE productos SET 
                    Nombre = ?, Stock = ?, Descripcion = ?, Precio = ?, Marca = ?, idCategoria = ?";

            $sql .= " WHERE idProductos = ?";
            $params[] = $data->getidProductos();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }


    // Registrar un nuevo usuario
    public function Registrar(Productos $data): void {
        try {
            $sql = "
                INSERT INTO productos (Nombre, Stock , Descripcion, Precio, Marca, idCategoria) 
                VALUES (?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getNombre(),
                $data->getStock(),
                $data->getDescripcion(),
                $data->getPrecio(),
                $data->getMarca(),
                $data->getidCategoria() // Debe estar hasheada con password_hash
            ]);
        } catch (Exception $e) {
            die("Error al registrar usuario: " . $e->getMessage());
        }
    }

    // Buscar por nombre, apellido o correo
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM productos
                WHERE Nomcategoria LIKE :term ";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                 $productos = new Productos();
                $productos->setidProductos($r->idProductos);
                $productos->setNombre($r->Nombre);
                $productos->setStock($r->Stock);
                $productos->setDescripcion($r->Descripcion);
                $productos->setPrecio($r->Precio);
                $productos->setMarca($r->Marca);
                  $productos->setidCategoria($r->idCategoria);
                $result[] = $productos;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al buscar usuario: " . $e->getMessage());
        }
    }

    
}
?>
