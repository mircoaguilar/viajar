<?php

    require_once ('conexion.php');
    require_once ('paginacion.php');


    class Usuario extends Pagination {
        private $id_usuarios;
        private $usuarios_nombre_usuario;
        private $usuarios_email;
        private $usuarios_password;
        private $rela_personas;
        private $rela_perfiles;

        public function __construct($id_usuarios='', $usuarios_nombre_usuario='', $usuarios_email='', $usuarios_password='', $rela_personas='', $rela_perfiles=''){
            $this->id_usuarios = $id_usuarios;
            $this->usuarios_nombre_usuario = $usuarios_nombre_usuario;
            $this->usuarios_email = $usuarios_email;
            $this->usuarios_password = $usuarios_password;
            $this->rela_personas = $rela_personas;
            $this->rela_perfiles = $rela_perfiles;
        }

        public function guardar(){
            $conexion = new Conexion();
            $password = password_hash($this->usuarios_password, PASSWORD_DEFAULT);
            $query = "Insert into usuarios (usuarios_nombre_usuario, usuarios_email, usuarios_password, rela_personas, rela_perfiles) 
            values ('$this->usuarios_nombre_usuario', '$this->usuarios_email', '$password','$this->rela_personas', '$this->rela_perfiles')";
            echo $query;
            return $conexion->insertar($query);
        }

        public function actualizar(){
            $conexion = new Conexion();
            $password = password_hash($this->usuarios_password, PASSWORD_DEFAULT);
            $query = "Update usuarios set usuarios_nombre_usuario = '$this->usuarios_nombre_usuario', usuarios_email = '$this->usuarios_email', usuarios_password = '$password', rela_personas = '$this->rela_personas', rela_perfiles = '$this->rela_perfiles' where id_usuarios = $this->id_usuarios";
            return $conexion->actualizar($query);
        }

        public function eliminar(){
            $conexion = new Conexion();
            $query = "Update usuarios set activo = 0 where id_usuarios = $this->id_usuarios";
            return $conexion->actualizar($query);
        }

        public function validar_usuario(){
            $conexion = new Conexion();
            $query = "Select * from usuarios where usuarios_nombre_usuario = '$this->usuarios_nombre_usuario' and activo=1";
            return $conexion->consultar($query);
        }   

        public function validar_usuario_existente(){
            $conexion = new Conexion();
            $query = "SELECT * FROM usuarios WHERE usuarios_nombre_usuario = '$this->usuarios_nombre_usuario'";
            return $conexion->consultar($query);
        }

        public function validar_email(){
            $conexion = new Conexion();
            $query = "SELECT * FROM usuarios WHERE usuarios_email = '$this->usuarios_email'";
            return $conexion->consultar($query);
        }


        public function traer_usuarios(){
            $conexion = new Conexion();
            $offset = $this->current_page * $this->page_size;
            $query = "SELECT 
                            usuarios.id_usuarios, 
                            usuarios.usuarios_nombre_usuario,
                            usuarios.usuarios_email,
                            usuarios.rela_personas,
                            usuarios.rela_perfiles,
                            perfiles.perfiles_nombre,
                            personas.personas_nombre,
                            personas.personas_apellido
                    FROM usuarios
                    JOIN perfiles ON usuarios.rela_perfiles = perfiles.id_perfiles
                    JOIN personas ON usuarios.rela_personas = personas.id_personas
                    WHERE usuarios.activo = 1
                    LIMIT $offset, $this->page_size";
            return $conexion->consultar($query);
        }


        public function traer_usuarios_cantidad(){
            $conexion = new Conexion();
            $query = "SELECT COUNT(*) as total FROM usuarios WHERE activo = 1";
            return $conexion->consultar($query); 
        }

        public function traer_usuarios_por_id($id_usuarios) {
            $conexion = new Conexion();
            $query = "SELECT * FROM usuarios WHERE id_usuarios = $id_usuarios";
            return $conexion->consultar($query);
        }

        public function cambiar_password(){
            $conexion = new Conexion();
            $password = password_hash($this->usuarios_password, PASSWORD_DEFAULT);
            $query = "Update usuarios set usuarios_password = '$password' where id_usuarios = $this->id_usuarios";
            return $conexion->actualizar($query);
        }

        public function generar_token_reset($id_usuario) {
    $conexion = new Conexion();
    $token = bin2hex(random_bytes(32)); // Genera un token aleatorio
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expira en 1 hora
    $created_at = date('Y-m-d H:i:s'); // Fecha y hora actual

    // Primero, borra tokens antiguos para este usuario si existen
    $delete_query = "DELETE FROM password_resets WHERE id_usuario = '$id_usuario'";
    if (!$conexion->actualizar($delete_query)) {
        throw new Exception("No se pudo eliminar el token anterior.");
    }

    // Inserta el nuevo token
    $query = "INSERT INTO password_resets (id_usuario, token, expires_at, created_at) VALUES ('$id_usuario', '$token', '$expires', '$created_at')";
    if (!$conexion->insertar($query)) {
        throw new Exception("No se pudo insertar el nuevo token.");
    }

    return $token;
    }


    // Método para validar el token recibido del formulario
    public function validar_token_reset($id_usuario, $token) {
    $conexion = new Conexion();
    $current_time = date('Y-m-d H:i:s');
    $query = "SELECT * FROM password_resets WHERE id_usuario = '$id_usuario' AND token = '$token' AND expires_at > '$current_time'";
    $result = $conexion->consultar($query);

    if (empty($result)) {
        throw new Exception("Token inválido o expirado.");
    }

    return true; // Token válido
    }

    // Método para invalidar/eliminar el token después de usarlo
    public function invalidar_token_reset($id_usuario, $token) {
    $conexion = new Conexion();
    $query = "DELETE FROM password_resets WHERE id_usuario = '$id_usuario' AND token = '$token'";
    return $conexion->actualizar($query);
    }



        /**
         * Get the value of id_usuarios
         */ 
        public function getId_usuarios()
        {
                return $this->id_usuarios;
        }

        /**
         * Set the value of id_usuarios
         *
         * @return  self
         */ 
        public function setId_usuarios($id_usuarios)
        {
                $this->id_usuarios = $id_usuarios;

                return $this;
        }

        /**
         * Get the value of usuarios_nombre_usuario
         */ 
        public function getUsuarios_nombre_usuario()
        {
                return $this->usuarios_nombre_usuario;
        }

        /**
         * Set the value of usuarios_nombre_usuario
         *
         * @return  self
         */ 
        public function setUsuarios_nombre_usuario($usuarios_nombre_usuario)
        {
                $this->usuarios_nombre_usuario = $usuarios_nombre_usuario;

                return $this;
        }

        /**
         * Get the value of usuarios_email
         */ 
        public function getUsuarios_email()
        {
                return $this->usuarios_email;
        }

        /**
         * Set the value of usuarios_email
         *
         * @return  self
         */ 
        public function setUsuarios_email($usuarios_email)
        {
                $this->usuarios_email = $usuarios_email;

                return $this;
        }

        /**
         * Get the value of usuarios_password
         */ 
        public function getUsuarios_password()
        {
                return $this->usuarios_password;
        }

        /**
         * Set the value of usuarios_password
         *
         * @return  self
         */ 
        public function setUsuarios_password($usuarios_password)
        {
                $this->usuarios_password = $usuarios_password;

                return $this;
        }

            /**
             * Get the value of rela_perfiles
             */ 
            public function getRela_perfiles()
            {
                        return $this->rela_perfiles;
            }

            /**
             * Set the value of rela_perfiles
             *
             * @return  self
             */ 
            public function setRela_perfiles($rela_perfiles)
            {
                        $this->rela_perfiles = $rela_perfiles;

                        return $this;
            }

        /**
         * Get the value of rela_personas
         */ 
        public function getRela_personas()
        {
                return $this->rela_personas;
        }

        /**
         * Set the value of rela_personas
         *
         * @return  self
         */ 
        public function setRela_personas($rela_personas)
        {
                $this->rela_personas = $rela_personas;

                return $this;
        }
    }