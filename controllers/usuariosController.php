<?php
    require_once "models/Model_t_usuarios.php";
    require_once "routes/web.php";

    class usuariosController {
        public $model;
        public $web;

        public function __construct(){
            $this->model= new Usuario();
            $this->web = new Web();

        }

        public function mostrar () {
            $usuarios=$this->model->mostrar('usuarios');
            $this->web->View('usuarios',$usuarios);
        }

        public function bitacora () {
            $bitacora = $this->model->mostrar('bitacora');
            $this->web->View('bitacora', $bitacora);
        }

        public function menu () {
            $this->web->View('principal','');
        }

        public function ingresar () {
            if (isset($_POST['nombre_usuario']) && isset($_POST['password'])) {
                $this->model->setNombre($_POST['nombre_usuario']);
                $this->model->setPassword($_POST['password']);
                $result = $this->model->ingresar();
                if ($result) {
                    self::iniciar($result['nombre'],$result['rol']);
                    echo json_encode($result);
                } else {
                    echo 0;
                }
            }
        }

        public function cerrar () {
            session_unset();
            session_reset();
            session_destroy();
            header('Location: http://localhost/scp_fmtor/');
        }

        public function iniciar ($usuario,$rol) {
            $usuario = $this->model->setNombre($usuario);
            $rol = $this->model->setIdRol($rol);
            $this->model->sesion();
        }

        public function obtener () {
            $usuarios = $this->model->mostrar('usuarios');
            $Array = array();
            while ($row = $usuarios->fetch_array(MYSQLI_ASSOC)) {
                $Array[] = $row;
            }
            $json = json_encode($Array);
            echo $json;
        }

        public function obtener_bitacora () {
            $bitacora = $this->model->mostrar('bitacora');
            $Array = array();
            while ($row = $bitacora->fetch_array(MYSQLI_ASSOC)) {
                $Array[] = $row;
            }
            $json = json_encode($Array);
            echo $json;
        }

        public function obtener_usuario () {
            $usuarios = $this->model->buscar('t_usuarios','id',$_GET['id']);
            $Array = array();
            while ($row = $usuarios->fetch_array(MYSQLI_ASSOC)) {
                $Array[] = $row;
            }
            $json = json_encode($Array);
            echo $json;
        }

        public function insertar () { 
            if (isset($_POST['usuario'])) {
                $usuario = $_POST['usuario'];
                $email =$_POST['email'];
                $rol =$_POST['rol'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 10]);
                $values = "'$usuario','$password','$email','$rol'";
                $result = $this->model->insertar('t_usuarios','nombre,password,correo,id_rol',$values);
                
                $user = $_SESSION['usuario'];
                $bit = "'$user','t_usuarios','insertar','Se inserto un nuevo usuario',SYSDATE()";
                $bitacora=$this->model->insertar('bitacora','usuario,tabla,accion,descripcion,fecha',$bit);
                
                echo $result;
            } else {
                echo 'Error: No se envio nada';
            }
        }
        
        public function actualizar () {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $usuario = $_POST['usuarioU'];
                $correo = $_POST['emailU'];
                $rol = $_POST['rolU'];
                $condicion = "id = '$id'";
                $valores = "nombre = '$usuario', correo='$correo', id_rol = '$rol'";
                $result = $this->model->actualizar('t_usuarios',$valores,$condicion);
                
                $user = $_SESSION['usuario'];
                $bit="'$user','t_usuarios','actualizar','Se actualizo información de un usuario',SYSDATE()";
                $bitacora=$this->model->insertar('bitacora','usuario,tabla,accion,descripcion,fecha',$bit);
                
                echo $result;
            } else {
                echo 0;
            }
        }
        
        public function eliminar () {
            $id = $_GET['id'];
            $result = $this->model->eliminar('t_usuarios','id='.$id);
            
            $user = $_SESSION['usuario'];
            $bit="'$user','t_usuarios','eliminar','Se elimino un usuario',SYSDATE()";
            $bitacora=$this->model->insertar('bitacora','usuario,tabla,accion,descripcion,fecha',$bit);
            
            return $result;
        }
}