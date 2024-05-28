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
       if (!is_null($arr)) {
           $this->dni = $arr['DNI'];
           $this->apellido1 = $arr['APELLIDO_1'];
           $this->apellido2 = $arr['APELLIDO_2'];
           $this->nombre = $arr['NOMBRE'];
           $this->direccion = $arr['DIRECCION'];
           $this->localidad = $arr['LOCALIDAD'];
           $this->provincia = $arr['PROVINCIA'];
           $this->fecha_nacimiento = $arr['FECHA_NACIMIENTO'];
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
            $stmt = $pdo->prepare("INSERT INTO alumno(dni, apellido1, apellido2, nombre, direccion, localidad, provincia, fecha_nacimiento)
                    VALUES (:dni, :apellido1, :apellido2, :nombre, :direccion, :localidad, :provincia, :fecha_nacimiento)");
            $stmt->bindParam(':dni', $this->dni);
            $stmt->bindParam(':apellido1', $this->apellido1);
            $stmt->bindParam(':apellido2', $this->apellido2);
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':direccion', $this->direccion);
            $stmt->bindParam(':localidad', $this->localidad);
            $stmt->bindParam(':provincia', $this->provincia);
            $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
            $res = $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
        return $res;
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
        WHERE dni = :dni";
        $arrParams = [
            ':dni' => $this->dni,
            ':apellido1' => $this->apellido1,
            ':apellido2' => $this->apellido2,
            ':nombre' => $this->nombre,
            ':direccion' => $this->direccion,
            ':localidad' => $this->localidad,
            ':provincia' => $this->provincia,
            ':fecha_nacimiento' => $this->fecha_nacimiento,
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
        $sql = "DELETE FROM alumno WHERE id=:id";
        try {
            $pdo = PDOConnection::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $this->id);
            $resultado = $stmt->execute();
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }
}
