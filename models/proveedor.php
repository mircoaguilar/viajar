<?php
require_once('conexion.php');
require_once('tipo_proveedor.php');
require_once('auditoria.php');

class Proveedor {
    private $id_proveedores;
    private $razon_social;
    private $cuit;
    private $proveedor_direccion;
    private $proveedor_email;
    private $rela_tipo_proveedor;
    private $rela_usuario;  
    private $activo;
    private $created_at;
    private $estado;

    public function __construct($id_proveedores = '', $razon_social = '', $cuit = '', $proveedor_direccion = '', $email = '', $rela_tipo = '', $rela_usuario = '', $estado = 'pendiente') {
        $this->id_proveedores = $id_proveedores;
        $this->razon_social = $razon_social;
        $this->cuit = $cuit;
        $this->proveedor_direccion = $proveedor_direccion;
        $this->proveedor_email = $email;
        $this->rela_tipo_proveedor = $rela_tipo;
        $this->rela_usuario = $rela_usuario;
        $this->activo = 1;
        $this->created_at = date('Y-m-d H:i:s');
        $this->estado = $estado;
    }

    public function traer_proveedores() {
        $conexion = new Conexion();
        $query = "SELECT p.*, t.nombre AS tipo_proveedor_nombre, u.usuarios_nombre_usuario AS usuario_nombre
                  FROM proveedores p 
                  LEFT JOIN tipo_proveedores t ON p.rela_tipo_proveedor = t.id_tipo_proveedor 
                  LEFT JOIN usuarios u ON p.rela_usuario = u.id_usuarios
                  WHERE p.activo = 1 
                  ORDER BY p.razon_social ASC";
        return $conexion->consultar($query);
    }

    public function traer_proveedor($id_proveedores) {
        $conexion = new Conexion();
        $id_proveedores = (int)$id_proveedores;
        $query = "SELECT * FROM proveedores WHERE id_proveedores = $id_proveedores AND activo = 1";
        return $conexion->consultar($query);
    }

    public function obtenerPorId($id) {
        $conexion = new Conexion();
        $id = (int)$id;
        $query = "SELECT * FROM proveedores WHERE id_proveedores = $id AND activo = 1 LIMIT 1";
        $resultado = $conexion->consultar($query);
        return $resultado ? $resultado[0] : null;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $razon_escapada = $mysqli->real_escape_string($this->razon_social);
        $cuit_escapado = $mysqli->real_escape_string($this->cuit);
        $domicilio_escapado = $mysqli->real_escape_string($this->proveedor_direccion); 
        $email_escapado = $mysqli->real_escape_string($this->proveedor_email);
        $estado_escapado = $mysqli->real_escape_string($this->estado);
        $rela_tipo = (int)$this->rela_tipo_proveedor;
        $rela_usuario = (int)$this->rela_usuario;
        $query = "INSERT INTO proveedores 
                    (razon_social, cuit, proveedor_direccion, proveedor_email, rela_tipo_proveedor, rela_usuario, activo, created_at, estado) 
                                    
                VALUES 
                    ('$razon_escapada', '$cuit_escapado', '$domicilio_escapado', '$email_escapado', $rela_tipo, $rela_usuario, 1, NOW(), '$estado_escapado')";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de proveedor',
                "Se creó el proveedor: {$this->razon_social}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $razon_escapada = $mysqli->real_escape_string($this->razon_social);
        $cuit_escapado = $mysqli->real_escape_string($this->cuit);
        $domicilio_escapado = $mysqli->real_escape_string($this->proveedor_direccion);
        $email_escapado = $mysqli->real_escape_string($this->proveedor_email);
        $estado_escapado = $mysqli->real_escape_string($this->estado);
        $rela_tipo = (int)$this->rela_tipo_proveedor;
        $rela_usuario = (int)$this->rela_usuario;

        $query = "UPDATE proveedores SET 
                    razon_social='$razon_escapada', 
                    cuit='$cuit_escapado', 
                    proveedor_direccion='$domicilio_escapado', 
                    proveedor_email='$email_escapado', 
                    rela_tipo_proveedor=$rela_tipo,
                    rela_usuario=$rela_usuario,
                    estado='$estado_escapado'
                  WHERE id_proveedores=" . (int)$this->id_proveedores;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de proveedor',
                "Se actualizó el proveedor (ID: {$this->id_proveedores}) a nombre: {$this->razon_social}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE proveedores SET activo = 0 WHERE id_proveedores=" . (int)$this->id_proveedores;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de proveedor',
                "Se eliminó lógicamente el proveedor (ID: {$this->id_proveedores})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function obtenerPorUsuario($id_usuario) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;
        $query = "SELECT * FROM proveedores WHERE rela_usuario = $id_usuario AND activo = 1 LIMIT 1";
        $resultado = $conexion->consultar($query);
        return $resultado ? $resultado[0] : null;
    }

    public static function crearUsuarioProveedor($username, $email, $password, $perfil) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $username_escapado = $mysqli->real_escape_string($username);
        $email_escapado = $mysqli->real_escape_string($email);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $rela_personas_sql = 'NULL'; 
        $perfil_int = (int)$perfil;

        $query = "INSERT INTO usuarios 
                  (usuarios_nombre_usuario, usuarios_email, usuarios_password, rela_personas, rela_perfiles) 
                  VALUES ('$username_escapado', '$email_escapado', '$password_hash', $rela_personas_sql, $perfil_int)";
        
        $resultado = $conexion->insertar($query);

        if ($resultado && class_exists('Auditoria')) {
            $auditoria = new Auditoria(
                '', 
                $_SESSION['id_usuarios'] ?? null,  
                'Alta de usuario Proveedor', 
                "Se creó el usuario proveedor: {$username}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function obtenerEstadoProveedor($id_usuario) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;

        $query = "SELECT id_proveedores, estado, activo 
                FROM proveedores 
                WHERE rela_usuario = $id_usuario 
                LIMIT 1";

        $resultado = $conexion->consultar($query);
        return $resultado ? $resultado[0] : null;
    }

    public function listarPendientes() {
        $conexion = new Conexion();
        $query = "SELECT id_proveedores, razon_social, cuit, proveedor_email, fecha_registro, estado 
                FROM proveedores 
                WHERE estado = 'pendiente' AND activo = 1";
        return $conexion->consultar($query);
    }

    public function cambiarEstado($nuevoEstado) {
        $conexion = new Conexion();
        $id = (int)$this->id_proveedores;
        $estadoEscapado = $conexion->getConexion()->real_escape_string($nuevoEstado);

        $query = "UPDATE proveedores SET estado = '$estadoEscapado' WHERE id_proveedores = $id";
        return $conexion->actualizar($query); 
    }


    public function getId_proveedores() { return $this->id_proveedores; }
    public function setId_proveedores($id) { $this->id_proveedores = $id; return $this; }

    public function getRazon_social() { return $this->razon_social; }
    public function setRazon_social($razon) { $this->razon_social = $razon; return $this; }

    public function getCuit() { return $this->cuit; }
    public function setCuit($cuit) { $this->cuit = $cuit; return $this; }

    public function getProveedor_direccion() { return $this->proveedor_direccion; }
    public function setProveedor_direccion($domicilio) { $this->proveedor_direccion = $domicilio; return $this; }

    public function getProveedor_email() { return $this->proveedor_email; }
    public function setProveedor_email($email) { $this->proveedor_email = $email; return $this; }

    public function getRela_tipo_proveedor() { return $this->rela_tipo_proveedor; }
    public function setRela_tipo_proveedor($tipo) { $this->rela_tipo_proveedor = $tipo; return $this; }

    public function getRela_usuario() { return $this->rela_usuario; }
    public function setRela_usuario($usuario) { $this->rela_usuario = $usuario; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
    
    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
}
?>