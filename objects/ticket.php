<?php
class Ticket {
    private $conn;
    private $table_name = "tickets";
    private $log_table_name = "ticket_logs";

    public $id;
    public $area;
    public $descripcion;
    public $estado;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET area=:area, descripcion=:descripcion, estado=:estado";
        $stmt = $this->conn->prepare($query);

        $this->area = htmlspecialchars(strip_tags($this->area));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":area", $this->area);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->logEstado();
            return true;
        }
        return false;
    }

    function read() {
        $query = "SELECT id, area, descripcion, estado, fecha_creacion FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            $this->logEstado();
            return true;
        }
        return false;
    }

    function logEstado() {
        $query = "INSERT INTO " . $this->log_table_name . " SET ticket_id=:ticket_id, estado=:estado";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":ticket_id", $this->id);
        $stmt->bindParam(":estado", $this->estado);

        $stmt->execute();
    }

    function readLogs() {
        $query = "SELECT estado, fecha FROM " . $this->log_table_name . " WHERE ticket_id = :ticket_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ticket_id', $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>
