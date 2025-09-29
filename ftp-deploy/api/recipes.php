<?php
/**
 * Recipes API
 * Håndterer alle HTTP-forespørsler for oppskrifter
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Håndter OPTIONS-forespørsler for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';
require_once '../models/Recipe.php';

// Initialiser database-tilkobling
$database = new Database();
$db = $database->getConnection();

// Sjekk om tilkoblingen fungerer
if($db === null) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database-tilkobling feilet'
    ]);
    exit();
}

$recipe = new Recipe($db);
$method = $_SERVER['REQUEST_METHOD'];

// Hent request data
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch($method) {
        case 'GET':
            handleGet($recipe);
            break;
            
        case 'POST':
            handlePost($recipe, $input);
            break;
            
        case 'PUT':
            handlePut($recipe, $input);
            break;
            
        case 'DELETE':
            handleDelete($recipe);
            break;
            
        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Metode ikke tillatt'
            ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Serverfeil: ' . $e->getMessage()
    ]);
}

/**
 * Håndter GET-forespørsler
 */
function handleGet($recipe) {
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? 'all';
    $id = $_GET['id'] ?? null;
    
    if ($id) {
        // Hent spesifikk oppskrift
        if ($recipe->getById($id)) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => $recipe->id,
                    'name' => $recipe->name,
                    'category' => $recipe->category,
                    'time' => $recipe->time,
                    'servings' => $recipe->servings,
                    'ingredients' => $recipe->ingredients,
                    'instructions' => $recipe->instructions,
                    'image_url' => $recipe->image_url,
                    'created_at' => $recipe->created_at,
                    'updated_at' => $recipe->updated_at
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Oppskrift ikke funnet'
            ]);
        }
    } else {
        // Hent liste med oppskrifter
        if (!empty($search)) {
            $stmt = $recipe->searchByCategory($search, $category);
        } elseif ($category !== 'all') {
            $stmt = $recipe->getByCategory($category);
        } else {
            $stmt = $recipe->getAll();
        }
        
        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipes[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'category' => $row['category'],
                'time' => $row['time'],
                'servings' => $row['servings'],
                'ingredients' => $row['ingredients'],
                'instructions' => $row['instructions'],
                'image_url' => $row['image_url'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $recipes
        ]);
    }
}

/**
 * Håndter POST-forespørsler (opprett ny oppskrift)
 */
function handlePost($recipe, $input) {
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Ingen data mottatt'
        ]);
        return;
    }
    
    // Sett oppskriftsdata
    $recipe->name = $input['name'] ?? '';
    $recipe->category = $input['category'] ?? '';
    $recipe->time = $input['time'] ?? 0;
    $recipe->servings = $input['servings'] ?? 0;
    $recipe->ingredients = $input['ingredients'] ?? '';
    $recipe->instructions = $input['instructions'] ?? '';
    $recipe->image_url = $input['image_url'] ?? '';
    
    // Valider data
    $errors = $recipe->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Valideringsfeil',
            'errors' => $errors
        ]);
        return;
    }
    
    // Opprett oppskrift
    if ($recipe->create()) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Oppskrift opprettet',
            'data' => [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'category' => $recipe->category,
                'time' => $recipe->time,
                'servings' => $recipe->servings,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
                'image_url' => $recipe->image_url
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Kunne ikke opprette oppskrift'
        ]);
    }
}

/**
 * Håndter PUT-forespørsler (oppdater oppskrift)
 */
function handlePut($recipe, $input) {
    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Ingen data mottatt'
        ]);
        return;
    }
    
    $id = $input['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID er påkrevd for oppdatering'
        ]);
        return;
    }
    
    // Sjekk om oppskriften eksisterer
    if (!$recipe->getById($id)) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Oppskrift ikke funnet'
        ]);
        return;
    }
    
    // Oppdater data
    $recipe->name = $input['name'] ?? $recipe->name;
    $recipe->category = $input['category'] ?? $recipe->category;
    $recipe->time = $input['time'] ?? $recipe->time;
    $recipe->servings = $input['servings'] ?? $recipe->servings;
    $recipe->ingredients = $input['ingredients'] ?? $recipe->ingredients;
    $recipe->instructions = $input['instructions'] ?? $recipe->instructions;
    $recipe->image_url = $input['image_url'] ?? $recipe->image_url;
    
    // Valider data
    $errors = $recipe->validate();
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Valideringsfeil',
            'errors' => $errors
        ]);
        return;
    }
    
    // Oppdater oppskrift
    if ($recipe->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Oppskrift oppdatert',
            'data' => [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'category' => $recipe->category,
                'time' => $recipe->time,
                'servings' => $recipe->servings,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
                'image_url' => $recipe->image_url
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Kunne ikke oppdatere oppskrift'
        ]);
    }
}

/**
 * Håndter DELETE-forespørsler
 */
function handleDelete($recipe) {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID er påkrevd for sletting'
        ]);
        return;
    }
    
    // Sjekk om oppskriften eksisterer
    if (!$recipe->getById($id)) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Oppskrift ikke funnet'
        ]);
        return;
    }
    
    // Slett oppskrift
    if ($recipe->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Oppskrift slettet'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Kunne ikke slette oppskrift'
        ]);
    }
}
?>