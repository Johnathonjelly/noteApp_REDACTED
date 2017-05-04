<?php
class Connection {
    protected $db;
    public function Connection() {

    $conn = NULL;
        try{
            $conn = new PDO("mysql:host=localhost;dbname=REDACTED", "REDACTED", "REDACTED");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e){
                echo 'ERROR: ' . $e->getMessage();
                }
            $this->db = $conn;
    }

    public function getConnection() {
        return $this->db;
    }
}
?>
