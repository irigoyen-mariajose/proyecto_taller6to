<?php
include_once("Conexion.php");
include_once("Sucursal.php");
include_once("Cargo.php");
include_once("Cargo.model.php");


class SucursalModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todos los usuarios con su cargo
    public function Listar(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("SELECT * FROM sucursal ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $sucursal = new Sucursal();
                $sucursal->setid_sucursal($r->id_sucursal);
                $sucursal->setnombre($r->nombre);
                $sucursal->setdireccion($r->direccion);
                $sucursal->sethora($r->hora);
                $sucursal->setlocalidad($r->localidad);
                $sucursal->setcodigo_postal($r->codigo_postal);
                $sucursal->settelefono($r->telefono);
                $sucursal->setcorreo($r->Correo);
                $result[] = $sucursal;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar sucursal: " . $e->getMessage());
        }
    }

    // Obtener un usuario por su ID
    public function Obtener(int $id_sucursal): ?Sucursal {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM sucursal WHERE id_sucursal = ?");
            $stm->execute([$id_sucursal]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $sucursal = new Sucursal();
                $sucursal->setid_sucursal($r->id_sucursal);
                $sucursal->setnombre($r->nombre);
                $sucursal->setdireccion($r->direccion);
                $sucursal->sethora($r->hora);
                $sucursal->setlocalidad($r->localidad);
                $sucursal->setcodigo_postal($r->codigo_postal);
                $sucursal->settelefono($r->telefono);
                $sucursal->setcorreo($r->Correo);
                $result[] = $sucursal;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener sucursal: " . $e->getMessage());
        }
    }

    // Eliminar un usuario por ID
    public function Eliminar(int $id_sucursal): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM sucursal WHERE id_sucursal = ?");
            $stm->execute([$id_sucursal]);
        } catch (Exception $e) {
            die("Error al eliminar sucursal: " . $e->getMessage());
        }
    }

    // Actualizar un usuario (con o sin clave)
    public function Actualizar(Sucursal $data): void {
        try {
            $params = [
                $data->getnombre(),
                $data->getdireccion(),
                $data->gethora(),
                $data->getlocalidad(),
                $data->getcodigo_postal(),
                $data->gettelefono(),
                $data->getcorreo(),
            ];

            $sql = "UPDATE sucursal SET 
                    nombre = ?, direccion = ?, hora = ?, localidad = ?, codigo_postal = ?, telefono = ?, correo = ?";

            if (!empty($data->getclave())) {
                $sql .= ", Clave = ?";
                $params[] = $data->getclave();
            }

            $sql .= " WHERE id_sucursal = ?";
            $params[] = $data->getid_sucursal();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar sucursal: " . $e->getMessage());
        }
    }


    // Registrar un nuevo usuario
    public function Registrar(Sucursal $data): void {
        try {
            $sql = "
                INSERT INTO usuarios (nombre, direccion, hora, localidad, codigo_postal, telefono, correo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getnombre(),
                $data->getdireccion(),
                $data->gethora(),
                $data->getlocalidad(),
                $data->getcodigo_postal(),
                $data->gettelefono(),
                $data->getcorreo() // Debe estar hasheada con password_hash
            ]);
        } catch (Exception $e) {
            die("Error al registrar sucursal: " . $e->getMessage());
        }
    }

    // Verificar correo y clave (retorna true/false)
    public function Verificar(Sucursal $data): bool {
        try {
            $sql = "SELECT Clave FROM sucursal WHERE correo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$data->getcorreo()]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            return $r && password_verify($data->getclave(), $r->Clave);
        } catch (Exception $e) {
            die("Error al verificar sucursal: " . $e->getMessage());
        }
    }

    // Buscar por nombre, apellido o correo
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM sucursal 
                WHERE nombre LIKE :term";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $sucursal = new Sucursal();
                $sucursal->setid_sucursal($r->id_sucursal);
                $sucursal->setnombre($r->nombre);
                $sucursal->setdireccion($r->direccion);
                $sucursal->sethora($r->hora);
                $sucursal->setlocalidad($r->localidad);
                $sucursal->setcodigo_postal($r->codigo_postal);
                $sucursal->settelefono($r->telefono);
                $sucursal->setcorreo($r->Correo);
                $result[] = $sucursal;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al buscar sucursal: " . $e->getMessage());
        }
    }

    
    
}
?>
