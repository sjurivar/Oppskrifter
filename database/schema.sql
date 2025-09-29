-- Oppskriftshåndtering Database Schema
-- Lag denne databasen og tabellen i MySQL

CREATE DATABASE IF NOT EXISTS oppskriftsamling CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE oppskriftsamling;

CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category ENUM('forrett', 'hovedrett', 'dessert', 'snacks', 'drikke') NOT NULL,
    time INT NOT NULL COMMENT 'Tilberedningstid i minutter',
    servings INT NOT NULL COMMENT 'Antall porsjoner',
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_name (name),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Legg til noen eksempeloppskrifter
INSERT INTO recipes (name, category, time, servings, ingredients, instructions, image_url) VALUES
('Spaghetti Carbonara', 'hovedrett', 20, 4, '400g spaghetti\n200g bacon\n4 egg\n100g parmesan\nSalt og pepper', '1. Kok pasta etter pakkens anvisning\n2. Stek bacon til det er sprøtt\n3. Pisk egg og parmesan sammen\n4. Bland alt sammen og server umiddelbart', 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?w=400&h=300&fit=crop'),

('Chokoladekake', 'dessert', 60, 8, '200g smør\n200g sukker\n4 egg\n200g mel\n50g kakao\n1 ts bakepulver', '1. Pisk smør og sukker hvitt\n2. Tilsett egg en om gangen\n3. Bland inn tørre ingredienser\n4. Stek på 175°C i 30-35 min', 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=300&fit=crop'),

('Caesar Salat', 'forrett', 15, 4, '1 hode romansalat\n100g parmesan\n50g croutons\nCaesar dressing\nAnchovies (valgfritt)', '1. Riv salat i biter\n2. Bland med dressing\n3. Topp med parmesan og croutons\n4. Server umiddelbart', 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop');