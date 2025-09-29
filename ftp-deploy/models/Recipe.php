<?php
/**
 * Recipe Model
 * Håndterer oppskriftsdata og database-operasjoner
 */

require_once '../config/database.php';

class Recipe {
    private $conn;
    private $table_name = "recipes";
    
    public $id;
    public $name;
    public $category;
    public $time;
    public $servings;
    public $ingredients;
    public $instructions;
    public $image_url;
    public $created_at;
    public $updated_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Hent alle oppskrifter
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Hent oppskrifter etter kategori
     */
    public function getByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Søk i oppskrifter
     */
    public function search($searchTerm) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE name LIKE ? OR ingredients LIKE ? OR instructions LIKE ? 
                  ORDER BY created_at DESC";
        
        $searchParam = "%" . $searchTerm . "%";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $searchParam);
        $stmt->bindParam(2, $searchParam);
        $stmt->bindParam(3, $searchParam);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Kombinert søk og kategori
     */
    public function searchByCategory($searchTerm, $category) {
        if ($category === 'all') {
            return $this->search($searchTerm);
        }
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE (name LIKE ? OR ingredients LIKE ? OR instructions LIKE ?) 
                  AND category = ? 
                  ORDER BY created_at DESC";
        
        $searchParam = "%" . $searchTerm . "%";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $searchParam);
        $stmt->bindParam(2, $searchParam);
        $stmt->bindParam(3, $searchParam);
        $stmt->bindParam(4, $category);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Hent en spesifikk oppskrift
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->category = $row['category'];
            $this->time = $row['time'];
            $this->servings = $row['servings'];
            $this->ingredients = $row['ingredients'];
            $this->instructions = $row['instructions'];
            $this->image_url = $row['image_url'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Opprett ny oppskrift
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, category, time, servings, ingredients, instructions, image_url) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->servings = htmlspecialchars(strip_tags($this->servings));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->instructions = htmlspecialchars(strip_tags($this->instructions));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        
        // Bind parameters
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->category);
        $stmt->bindParam(3, $this->time);
        $stmt->bindParam(4, $this->servings);
        $stmt->bindParam(5, $this->ingredients);
        $stmt->bindParam(6, $this->instructions);
        $stmt->bindParam(7, $this->image_url);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    /**
     * Oppdater oppskrift
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = ?, category = ?, time = ?, servings = ?, 
                      ingredients = ?, instructions = ?, image_url = ?
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->servings = htmlspecialchars(strip_tags($this->servings));
        $this->ingredients = htmlspecialchars(strip_tags($this->ingredients));
        $this->instructions = htmlspecialchars(strip_tags($this->instructions));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        
        // Bind parameters
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->category);
        $stmt->bindParam(3, $this->time);
        $stmt->bindParam(4, $this->servings);
        $stmt->bindParam(5, $this->ingredients);
        $stmt->bindParam(6, $this->instructions);
        $stmt->bindParam(7, $this->image_url);
        $stmt->bindParam(8, $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Slett oppskrift
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        return $stmt->execute();
    }
    
    /**
     * Valider oppskriftsdata
     */
    public function validate() {
        $errors = [];
        
        if (empty($this->name)) {
            $errors[] = "Oppskriftsnavn er påkrevd";
        }
        
        if (empty($this->category)) {
            $errors[] = "Kategori er påkrevd";
        }
        
        if (!in_array($this->category, ['forrett', 'hovedrett', 'dessert', 'snacks', 'drikke'])) {
            $errors[] = "Ugyldig kategori";
        }
        
        if (empty($this->time) || $this->time < 1) {
            $errors[] = "Tilberedningstid må være minst 1 minutt";
        }
        
        if (empty($this->servings) || $this->servings < 1) {
            $errors[] = "Antall porsjoner må være minst 1";
        }
        
        if (empty($this->ingredients)) {
            $errors[] = "Ingredienser er påkrevd";
        }
        
        if (empty($this->instructions)) {
            $errors[] = "Fremgangsmåte er påkrevd";
        }
        
        return $errors;
    }
}
?>