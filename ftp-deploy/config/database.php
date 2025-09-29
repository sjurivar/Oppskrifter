<?php
/**
 * Production Database Configuration
 * Kopier denne filen til config/database.php og oppdater med dine opplysninger
 */

class Database {
    // OPPDATER DISSE VERDIENE FOR HJELLUM.NET
    private $host = 'localhost';           // Din MySQL-server
    private $db_name = 'oppskriftsamling'; // Database-navn
    private $username = 'your_username';   // Ditt MySQL-brukernavn
    private $password = 'your_password';   // Ditt MySQL-passord
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
            error_log("Database connection error: " . $exception->getMessage());
            throw new Exception("Database connection failed");
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
            error_log("Database test failed: " . $e->getMessage());
            return false;
        }
    }
}
?>