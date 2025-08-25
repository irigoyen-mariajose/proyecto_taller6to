<?php
include_once("Conexion.php");
include_once("Usuario.php");
include_once("Cargo.php");
include_once("Cargo.model.php");


class UsuarioModel {
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
                SELECT u.idUsuario, u.Nombre, u.Apellido, u.Direccion, u.Correo, c.Cargo 
                FROM usuarios u 
                INNER JOIN cargos c ON u.idCargo = c.idCargo
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $usuario = new Usuario();
                $usuario->setidusuario($r->idUsuario);
                $usuario->setnombre($r->Nombre);
                $usuario->setapellido($r->Apellido);
                $usuario->setdireccion($r->Direccion);
                $usuario->setcorreo($r->Correo);
                $usuario->setcargo($r->Cargo);
                $result[] = $usuario;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar usuarios: " . $e->getMessage());
        }
    }

    // Obtener un usuario por su ID
    public function Obtener(int $idUsuario): ?Usuario {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM usuarios WHERE idUsuario = ?");
            $stm->execute([$idUsuario]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $usuario = new Usuario();
                $usuario->setidusuario($r->idUsuario);
                $usuario->setnombre($r->Nombre);
                $usuario->setapellido($r->Apellido);
                $usuario->setdireccion($r->Direccion);
                $usuario->setcorreo($r->Correo);
                $usuario->setclave($r->Clave);
                $usuario->setidcargo($r->idCargo);
                $usuario->setfotoPerfil($r->fotoPerfil ?? null);
                return $usuario;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener usuario: " . $e->getMessage());
        }
    }

    // Eliminar un usuario por ID
    public function Eliminar(int $idUsuario): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
            $stm->execute([$idUsuario]);
        } catch (Exception $e) {
            die("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    // Actualizar un usuario (con o sin clave)
    public function Actualizar(Usuario $data): void {
        try {
            $params = [
                $data->getnombre(),
                $data->getapellido(),
                $data->getdireccion(),
                $data->getcorreo(),
                $data->getidcargo(),
            ];

            $sql = "UPDATE usuarios SET 
                    Nombre = ?, Apellido = ?, Direccion = ?, Correo = ?, idCargo = ?";

            if (!empty($data->getclave())) {
                $sql .= ", Clave = ?";
                $params[] = $data->getclave();
            }

            if (!empty($data->getfotoPerfil())) {
                $sql .= ", fotoPerfil = ?";
                $params[] = $data->getfotoPerfil();
            }

            $sql .= " WHERE idUsuario = ?";
            $params[] = $data->getidusuario();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }


    // Registrar un nuevo usuario
    public function Registrar(Usuario $data): void {
        try {
            $sql = "
                INSERT INTO usuarios (Nombre, Apellido, Direccion, Correo, idCargo, Clave) 
                VALUES (?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getnombre(),
                $data->getapellido(),
                $data->getdireccion(),
                $data->getcorreo(),
                $data->getidcargo(),
                $data->getclave() // Debe estar hasheada con password_hash
            ]);
        } catch (Exception $e) {
            die("Error al registrar usuario: " . $e->getMessage());
        }
    }

    // Verificar correo y clave (retorna true/false)
    public function Verificar(Usuario $data): bool {
        try {
            $sql = "SELECT Clave FROM usuarios WHERE Correo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$data->getcorreo()]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            return $r && password_verify($data->getclave(), $r->Clave);
        } catch (Exception $e) {
            die("Error al verificar usuario: " . $e->getMessage());
        }
    }

    // Buscar por nombre, apellido o correo
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM usuarios 
                WHERE Nombre LIKE :term OR Apellido LIKE :term OR Correo LIKE :term";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $usuario = new Usuario();
                $usuario->setidusuario($r->idUsuario);
                $usuario->setnombre($r->Nombre);
                $usuario->setapellido($r->Apellido);
                $usuario->setcorreo($r->Correo);
                $usuario->setdireccion($r->Direccion);
                $usuario->setidcargo($r->idCargo);
                $result[] = $usuario;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al buscar usuario: " . $e->getMessage());
        }
    }

    // Login (retorna objeto Usuario o null)
    public function Login(Usuario $data): ?Usuario {
    try {
        $sql = "
            SELECT u.idUsuario, u.Nombre, u.Apellido, u.Direccion, u.Correo, u.Clave, 
                   u.idCargo, c.Cargo
            FROM usuarios u
            INNER JOIN cargos c ON u.idCargo = c.idCargo
            WHERE u.Correo = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$data->getcorreo()]);
        $r = $stm->fetch(PDO::FETCH_OBJ);

        if ($r && password_verify($data->getclave(), $r->Clave)) {
            $user = new Usuario();
            $user->setidusuario($r->idUsuario);
            $user->setnombre($r->Nombre);
            $user->setapellido($r->Apellido);
            $user->setdireccion($r->Direccion);
            $user->setcorreo($r->Correo);
            $user->setidcargo($r->idCargo);
            $user->setcargo($r->Cargo);
            // No seteamos la clave por seguridad: $user->setclave($r->Clave);
            return $user;
        }

        return null;
    } catch (Exception $e) {
        die("Error al hacer login: " . $e->getMessage());
    }
    }
}
?>
