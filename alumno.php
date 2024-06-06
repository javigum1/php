<?php
require_once '../PDOConnection.php';


class Alumno {
   /**
    * @var string|null $dni
    */
   private $dni;

   /**
    * @var string $apellido1
    */
   private $apellido1;

   /**
    * @var string $apellido2
    */
   private $apellido2;

   /**
    * @var string $nombre
    */
   private $nombre;

   /**
    * @var string $direccion
    */
   private $direccion;

   /**
    * @var string $localidad
    */
   private $localidad;

   /**
    * @var string $provincia
    */
   private $provincia;

   /**
    * @var string $fecha_nacimiento
    */
   private $fecha_nacimiento;

    public function __construct($arr = null, $dni = null, $apellido1 = null, $apellido2 = null, $nombre = null, $direccion = null, $localidad = null, $provincia = null, $fecha_nacimiento = null) {
        if (is_array($arr)) {
            $this->dni = isset($arr['DNI']) ? $arr['DNI'] : null;
            $this->apellido1 = isset($arr['APELLIDO_1']) ? $arr['APELLIDO_1'] : null;
            $this->apellido2 = isset($arr['APELLIDO_2']) ? $arr['APELLIDO_2'] : null;
            $this->nombre = isset($arr['NOMBRE']) ? $arr['NOMBRE'] : null;
            $this->direccion = isset($arr['DIRECCION']) ? $arr['DIRECCION'] : null;
            $this->localidad = isset($arr['LOCALIDAD']) ? $arr['LOCALIDAD'] : null;
            $this->provincia = isset($arr['PROVINCIA']) ? $arr['PROVINCIA'] : null;
            $this->fecha_nacimiento = isset($arr['FECHA_NACIMIENTO']) ? $arr['FECHA_NACIMIENTO'] : null;
        } else {
            $this->dni = $dni;
            $this->apellido1 = $apellido1;
            $this->apellido2 = $apellido2;
            $this->nombre = $nombre;
            $this->direccion = $direccion;
            $this->localidad = $localidad;
            $this->provincia = $provincia;
            $this->fecha_nacimiento = $fecha_nacimiento;
        }
        echo "Constructor Alumno: dni = " . $this->dni . ", apellido1 = " . $this->apellido1 . ", apellido2 = " . $this->apellido2 . ", nombre = " . $this->nombre . ", direccion = " . $this->direccion . ", localidad = " . $this->localidad . ", provincia = " . $this->provincia . ", fecha_nacimiento = " . $this->fecha_nacimiento . "<br>";
    } 

    /**
     * Devuelve todos los campos de todos los alumnos
     * @return array todos los campos de todos los registros de alumnos
     */
    public static function getAlumnos($num_rows, $pagina = null, $special_field = null, $filter = null, $filterValues = null) {
        $field = is_null($special_field) ? "*" : $special_field;
        $sql = "SELECT " . $field . " FROM alumno WHERE true";
        if (!is_null($filter)) {
            $sql .= " AND " . $filter . " = " . $filterValues;
        }
        try {
            $pdo = PDOConnection::getInstance();
            if (is_null($pagina)) {
                $pagina = "";
            } else {
                $pagina .= ", ";
            }
            $sql .= " LIMIT " . $pagina . " $num_rows";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            $arr = array();
        }
        return $arr;
    }

    public function insert() {
        try {
            $pdo = PDOConnection::getInstance();
            $stmt = $pdo->prepare("INSERT INTO alumno(DNI, APELLIDO_1, APELLIDO_2, NOMBRE, DIRECCION, LOCALIDAD, PROVINCIA, FECHA_NACIMIENTO)
                    VALUES (:dni, :apellido1, :apellido2, :nombre, :direccion, :localidad, :provincia, :fecha_nacimiento)");
            $stmt->bindParam(':dni', $this->dni);
            $stmt->bindParam(':apellido1', $this->apellido1);
            $stmt->bindParam(':apellido2', $this->apellido2);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':direccion', $this->direccion);
            $stmt->bindParam(':localidad', $this->localidad);
            $stmt->bindParam(':provincia', $this->provincia);
            $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
            return $stmt->execute();
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
    
    

    /**
     * Actualiza un alumno con los atributos del objeto
     */
    public function update() {
        $sql = "UPDATE alumno SET dni=:dni, 
        apellido1=:apellido1,
        apellido2=:apellido2,
        nombre=:nombre,
        direccion=:direccion,
        localidad=:localidad,
        provincia=:provincia,
        fecha_nacimiento=:fecha_nacimiento
        WHERE id = :id";
        $arrParams = [
            ':dni' => $this->dni,
            ':apellido1' => $this->apellido1,
            ':apellido2' => $this->apellido2,
            ':nombre' => $this->nombre,
            ':direccion' => $this->direccion,
            ':localidad' => $this->localidad,
            ':provincia' => $this->provincia,
            ':fecha_nacimiento' => $this->fecha_nacimiento,
            ':id' => $this->id
        ];
        try {
            $pdo = PDOConnection::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($arrParams);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete() {
        $sql = "DELETE FROM alumno WHERE DNI=:dni"; // Utilizamos DNI en lugar de id
        try {
            $pdo = PDOConnection::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dni', $this->dni);
            $resultado = $stmt->execute();
            if ($resultado) {
                echo "Alumno eliminado correctamente";
            } else {
                echo "Error al eliminar el alumno";
            }
            return $resultado;
        } catch (PDOException $e) {
            echo "Error en la operación de eliminación: " . $e->getMessage();
            return false;
        }
    }
    
    
}
