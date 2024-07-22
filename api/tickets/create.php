<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../objects/ticket.php';

$database = new Database();
$db = $database->getConnection();

$ticket = new Ticket($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->area) && !empty($data->descripcion)){
    $ticket->area = $data->area;
    $ticket->descripcion = $data->descripcion;
    $ticket->estado = "generado";

    if($ticket->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Ticket creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo crear el ticket."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Datos incompletos."));
}
?>
