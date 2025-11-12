<?php
require_once('conexion.php');
require_once('tipo_proveedor.php');
require_once('auditoria.php');

class Proveedor {
    private $id_proveedores;
    private $razon_social;
    private $cuit;
    private $proveedor_domicilio;
    private $proveedor_email;
    private $rela_tipo_proveedor;
    private $rela_usuario;  
    private $activo;
    private $created_at;

    public function __construct($id_proveedores = '', $razon_social = '', $cuit = '', $domicilio = '', $email = '', $rela_tipo = '', $rela_usuario = '') {
        $this->id_proveedores = $id_proveedores;
        $this->razon_social = $razon_social;
        $this->cuit = $cuit;
        $this->proveedor_domicilio = $domicilio;
        $this->proveedor_email = $email;
        $this->rela_tipo_proveedor = $rela_tipo;
        $this->rela_usuario = $rela_usuario;
        $this->activo = 1;
        $this->created_at = date('Y-m-d H:i:s');
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

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $razon_escapada = $mysqli->real_escape_string($this->razon_social);
        $cuit_escapado = $mysqli->real_escape_string($this->cuit);
        $domicilio_escapado = $mysqli->real_escape_string($this->proveedor_domicilio);
        $email_escapado = $mysqli->real_escape_string($this->proveedor_email);
        $rela_tipo = (int)$this->rela_tipo_proveedor;
        $rela_usuario = (int)$this->rela_usuario;

        $query = "INSERT INTO proveedores 
                    (razon_social, cuit, proveedor_domicilio, proveedor_email, rela_tipo_proveedor, rela_usuario, activo, created_at)
                  VALUES 
                    ('$razon_escapada', '$cuit_escapado', '$domicilio_escapado', '$email_escapado', $rela_tipo, $rela_usuario, 1, NOW())";
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
        $domicilio_escapado = $mysqli->real_escape_string($this->proveedor_domicilio);
        $email_escapado = $mysqli->real_escape_string($this->proveedor_email);
        $rela_tipo = (int)$this->rela_tipo_proveedor;
        $rela_usuario = (int)$this->rela_usuario;

        $query = "UPDATE proveedores SET 
                    razon_social='$razon_escapada', 
                    cuit='$cuit_escapado', 
                    proveedor_domicilio='$domicilio_escapado', 
                    proveedor_email='$email_escapado', 
                    rela_tipo_proveedor=$rela_tipo,
                    rela_usuario=$rela_usuario
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

    public function getId_proveedores() { return $this->id_proveedores; }
    public function setId_proveedores($id) { $this->id_proveedores = $id; return $this; }

    public function getRazon_social() { return $this->razon_social; }
    public function setRazon_social($razon) { $this->razon_social = $razon; return $this; }

    public function getCuit() { return $this->cuit; }
    public function setCuit($cuit) { $this->cuit = $cuit; return $this; }

    public function getProveedor_domicilio() { return $this->proveedor_domicilio; }
    public function setProveedor_domicilio($domicilio) { $this->proveedor_domicilio = $domicilio; return $this; }

    public function getProveedor_email() { return $this->proveedor_email; }
    public function setProveedor_email($email) { $this->proveedor_email = $email; return $this; }

    public function getRela_tipo_proveedor() { return $this->rela_tipo_proveedor; }
    public function setRela_tipo_proveedor($tipo) { $this->rela_tipo_proveedor = $tipo; return $this; }

    public function getRela_usuario() { return $this->rela_usuario; }
    public function setRela_usuario($usuario) { $this->rela_usuario = $usuario; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
?>
