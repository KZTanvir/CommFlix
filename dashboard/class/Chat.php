<?php

class Chat {

    private $chatTable = 'cms_chat';
    private $conn;

    public $m_id;
    public $sender_id;
    public $receiver_id;

    public function __construct($db){
        $this->conn = $db;
    }

    public function sendMessage($message) {
        $sqlQuery = "INSERT INTO {$this->chatTable} (sender_id, receiver_id, message, date) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sqlQuery);
        $date = date('Y-m-d H:i:s');
        $stmt->bind_param('iiss', $this->sender_id, $this->receiver_id, $message, $date);

        return $stmt->execute();
    }

    public function getChatHistory() {
        $sqlQuery = "SELECT sender_id, message, date FROM {$this->chatTable} ORDER BY m_id DESC LIMIT 20";
        $stmt = $this->conn->prepare($sqlQuery);

        if (!$stmt->execute()) {
            return false; 
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cleanChat() {
        $sqlQuery = "DELETE FROM {$this->chatTable} ORDER BY m_id ASC LIMIT 5";
        $stmt = $this->conn->prepare($sqlQuery);

        return $stmt->execute();
    }
}

?>