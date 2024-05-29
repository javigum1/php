<?php
require_once 'Alumno.php';

// Crear un array con los datos de ejemplo para un alumno
$alumnoData = array(
    'DNI' => '12345678A',
    'APELLIDO_1' => 'Gómez',
    'APELLIDO_2' => 'Pérez',
    'NOMBRE' => 'Juan',
    'DIRECCION' => 'Calle Principal, 123',
    'LOCALIDAD' => 'Ciudad Real',
    'PROVINCIA' => 'Ciudad Real',
    'FECHA_NACIMIENTO' => '1990-05-15'
);

// Crear una instancia de Alumno con los datos de ejemplo
$alumno = new Alumno($alumnoData);
var_dump($alumno); // Mostrar los datos del alumno

// Insertar el alumno en la base de datos
$resultado = $alumno->insert();

// Verificar si el insert fue exitoso
if ($resultado) {
    echo "El alumno se insertó correctamente en la base de datos.";
} else {
    echo "Hubo un problema al insertar el alumno en la base de datos.";
}
?>
