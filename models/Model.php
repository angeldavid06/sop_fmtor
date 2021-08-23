<?php
   
   session_start();

   require_once "bd/conexion.php";
  
   class Model{
      public $db;

      public function __construct(){
         $this->db= conexion::conectar();
      }

      public function mostrar($tabla){
         $sql="SELECT * FROM $tabla ORDER BY id DESC";
         $mostrar = $this->db->query($sql);
         return $mostrar;
      }

      public function insert($tabla,$columnas,$valores){
         $sql="INSERT INTO $tabla($columnas) VALUES ($valores)";
         $insert=$this->db->query($sql);
         return $insert;
      }

      public function actualizar($tabla,$valores,$condicion) {
         $sql="UPDATE $tabla set $valores WHERE $condicion";
         $actu=$this->db->query($sql);
         return $actu;

      }

      public function eliminar($tabla,$condicion) {
         $sql="DELETE FROM $tabla WHERE $condicion";
         $eliminar=$this->db->query($sql);
         return $eliminar;
      }

      public function buscar($tabla,$campo,$condicion) {
         $sql="SELECT $campo FROM $tabla WHERE $condicion";
         $buscar=$this->db->query($sql);
         return $buscar;
      }

      public function filtrar(){

      }

      public function ingresarU ($nombre,$password) {
         $sql = "SELECT count(t_usuarios.id) AS total, t_usuarios.nombre, t_usuarios.password, t_rol.nombre AS rol FROM t_usuarios, t_rol WHERE t_usuarios.nombre = '$nombre' AND t_usuarios.id_rol = t_rol.id";
         $query = mysqli_query($this->db,$sql);
         $assoc = mysqli_fetch_assoc($query);
         if (password_verify($password,$assoc['password'])) {
            return $assoc;
         } else {
            return 0;
         }
      }
    
  }
