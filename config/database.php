<?php
/**
 * Database Configuration
 * Konfigurasjon for MySQL-tilkobling
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'oppskriftsamling';
    private $username = 'root'; // Endre til din MySQL-brukernavn
    private $password = '';     // Endre til ditt MySQL-passord
    private $charset = 'utf8mb4';
    
    public $conn;
    
    /**
     * Få database-tilkobling
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
    
    /**
     * Test tilkobling
     */
    public function testConnection() {
        try {
            $this->getConnection();
            return $this->conn !== null;
        } catch(Exception $e) {
            return false;
        }
    }
}
?>