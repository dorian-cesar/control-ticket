<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../objects/ticket.php';

$database = new Database();
$db = $database->getConnection();

$ticket = new Ticket($db);

$stmt = $ticket->read();
$num = $stmt->rowCount();

if($num > 0){
    $tickets_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $ticket_item = array(
            "id" => $id,
            "area" => $area,
            "descripcion" => $descripcion,
            "estado" => $estado,
            "fecha_creacion" => $fecha_creacion
        );
        array_push($tickets_arr, $ticket_item);
    }
    echo json_encode($tickets_arr);
} else {
    echo json_encode(array());
}
?>
