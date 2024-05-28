<?php
require_once 'Alumno.php';
$data = json_decode(file_get_contents("php://input"), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["error" => true, "message" => "Invalid JSON"]);
    exit;
}
$num_rows = 10;
$pagina = 1;
if(!empty($data['num_rows'])){
    $num_rows = $data['num_rows'];
}
if(!empty($data['n_pagina'])){
    $pagina = $data['n_pagina'];
}
$res = Alumno::getAlumnos($num_rows, $pagina);
switch($data['action']){
    case 'get':
        if (isset($data['filter_field']) && isset($data['filter_value'])) {
            $res = Alumno::getAlumnos($num_rows, null, null, $data['filter_field'], $data['filter_value']);
        } else if (isset($data['special_field'])) {
            $res = Alumno::getAlumnos(100000, null, "count(*)");
        }
        
        break;

    case 'insert':
        $dni = $data['values']['dni'];
        $apellido1 = $data['values']['apellido1'];
        $apellido2 = $data['values']['apellido2'];
        $nombre = $data['values']['nombre'];
        $direccion = $data['values']['direccion'];
        $localidad = $data['values']['localidad'];
        $provincia = $data['values']['provincia'];
        $fecha_nacimiento = $data['values']['fecha_nacimiento'];
        $alumno = new Alumno(null, null, $dni, $apellido1, $apellido2, $nombre, $direccion, $localidad, $provincia, $fecha_nacimiento);
        $res = $alumno->insert()?"OK":null;
        break;

    case 'update':
        $id = $data['fields_to_update']['id_alumno'];
        $dni = $data['fields_to_update']['dni'];
        $apellido1 = $data['fields_to_update']['apellido1'];
        $apellido2 = $data['fields_to_update']['apellido2'];
        $nombre = $data['fields_to_update']['nombre'];
        $direccion = $data['fields_to_update']['direccion'];
        $localidad = $data['fields_to_update']['localidad'];
        $provincia = $data['fields_to_update']['provincia'];
        $fecha_nacimiento = $data['fields_to_update']['fecha_nacimiento'];
        $alumno = new Alumno(null, $id, $dni, $apellido1, $apellido2, $nombre, $direccion, $localidad, $provincia, $fecha_nacimiento);
        $alumno->update();
        $res = "OK";
        break;

    case 'delete':
        $field = $data['field'];
        $datosAlumno = Alumno::getAlumnos(1, null,null,"dni", $field);
        $alumno = new Alumno($datosAlumno[0]);
        $res = $alumno->delete()?"OK":null;
        break;
}

$error = is_null($res);

$json = [
    "error" => $error,
    "data" => $res
];

echo json_encode($json);
?>
