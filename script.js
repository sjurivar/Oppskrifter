// Oppskriftshåndtering JavaScript med PHP Backend

class RecipeManager {
    constructor() {
        this.recipes = [];
        this.currentFilter = 'all';
        this.editingRecipe = null;
        this.apiUrl = 'api/recipes.php';
        this.init();
    }

    async init() {
        this.bindEvents();
        await this.loadRecipes();
        this.renderRecipes();
    }

    bindEvents() {
        // Legg til oppskrift knapp
        document.getElementById('addRecipeBtn').addEventListener('click', () => {
            this.openModal();
        });

        // Modal hendelser
        document.getElementById('closeModal').addEventListener('click', () => {
            this.closeModal();
        });

        document.getElementById('closeViewModal').addEventListener('click', () => {
            this.closeViewModal();
        });

        document.getElementById('cancelBtn').addEventListener('click', () => {
            this.closeModal();
        });

        // Skjema hendelser
        document.getElementById('recipeForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveRecipe();
        });

        // Søkefunksjonalitet
        document.getElementById('searchInput').addEventListener('input', (e) => {
            this.filterRecipes(e.target.value);
        });

        // Filtreringsknapper
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.setActiveFilter(e.target.dataset.category);
                this.filterRecipes(document.getElementById('searchInput').value);
            });
        });

        // Lukk modal ved klikk utenfor
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('recipeModal');
            const viewModal = document.getElementById('viewModal');
            if (e.target === modal) {
                this.closeModal();
            }
            if (e.target === viewModal) {
                this.closeViewModal();
            }
        });
    }

    async loadRecipes() {
        try {
            const response = await fetch(this.apiUrl);
            const result = await response.json();
            
            if (result.success) {
                this.recipes = result.data;
            } else {
                this.showError('Kunne ikke laste oppskrifter: ' + result.message);
                this.recipes = [];
            }
        } catch (error) {
            console.error('Feil ved lasting av oppskrifter:', error);
            this.showError('Feil ved tilkobling til server');
            this.recipes = [];
        }
    }

    openModal(recipe = null) {
        this.editingRecipe = recipe;
        const modal = document.getElementById('recipeModal');
        const title = document.getElementById('modalTitle');
        const form = document.getElementById('recipeForm');

        if (recipe) {
            title.textContent = 'Rediger oppskrift';
            this.populateForm(recipe);
        } else {
            title.textContent = 'Legg til ny oppskrift';
            form.reset();
        }

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    closeModal() {
        document.getElementById('recipeModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        this.editingRecipe = null;
        document.getElementById('recipeForm').reset();
    }

    openViewModal(recipe) {
        const modal = document.getElementById('viewModal');
        const title = document.getElementById('viewModalTitle');
        const details = document.getElementById('recipeDetails');

        title.textContent = recipe.name;
        details.innerHTML = this.createRecipeDetailsHTML(recipe);

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    closeViewModal() {
        document.getElementById('viewModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    populateForm(recipe) {
        document.getElementById('recipeName').value = recipe.name;
        document.getElementById('recipeCategory').value = recipe.category;
        document.getElementById('recipeTime').value = recipe.time;
        document.getElementById('recipeServings').value = recipe.servings;
        document.getElementById('recipeIngredients').value = recipe.ingredients;
        document.getElementById('recipeInstructions').value = recipe.instructions;
        document.getElementById('recipeImage').value = recipe.image_url || '';
    }

    async saveRecipe() {
        const recipeData = {
            name: document.getElementById('recipeName').value,
            category: document.getElementById('recipeCategory').value,
            time: parseInt(document.getElementById('recipeTime').value),
            servings: parseInt(document.getElementById('recipeServings').value),
            ingredients: document.getElementById('recipeIngredients').value,
            instructions: document.getElementById('recipeInstructions').value,
            image_url: document.getElementById('recipeImage').value || 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop'
        };

        try {
            let response;
            if (this.editingRecipe) {
                // Oppdater eksisterende oppskrift
                recipeData.id = this.editingRecipe.id;
                response = await fetch(this.apiUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(recipeData)
                });
            } else {
                // Opprett ny oppskrift
                response = await fetch(this.apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(recipeData)
                });
            }

            const result = await response.json();
            
            if (result.success) {
                await this.loadRecipes();
                this.renderRecipes();
                this.closeModal();
                this.showSuccess(result.message);
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            console.error('Feil ved lagring av oppskrift:', error);
            this.showError('Feil ved lagring av oppskrift');
        }
    }

    deleteRecipe(id) {
        if (confirm('Er du sikker på at du vil slette denne oppskriften?')) {
            this.recipes = this.recipes.filter(recipe => recipe.id !== id);
            this.saveRecipes();
            this.renderRecipes();
        }
    }

    setActiveFilter(category) {
        this.currentFilter = category;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-category="${category}"]`).classList.add('active');
    }

    filterRecipes(searchTerm = '') {
        let filteredRecipes = this.recipes;

        // Filtrer etter kategori
        if (this.currentFilter !== 'all') {
            filteredRecipes = filteredRecipes.filter(recipe => 
                recipe.category === this.currentFilter
            );
        }

        // Filtrer etter søketerm
        if (searchTerm.trim()) {
            const term = searchTerm.toLowerCase();
            filteredRecipes = filteredRecipes.filter(recipe => 
                recipe.name.toLowerCase().includes(term) ||
                recipe.ingredients.toLowerCase().includes(term) ||
                recipe.instructions.toLowerCase().includes(term)
            );
        }

        this.renderRecipes(filteredRecipes);
    }

    renderRecipes(recipesToRender = null) {
        const recipes = recipesToRender || this.recipes;
        const grid = document.getElementById('recipesGrid');

        if (recipes.length === 0) {
            grid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <h3>Ingen oppskrifter funnet</h3>
                    <p>Legg til din første oppskrift eller prøv et annet søk!</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = recipes.map(recipe => this.createRecipeCardHTML(recipe)).join('');
        
        // Bind events til nye elementer
        this.bindRecipeEvents();
    }

    createRecipeCardHTML(recipe) {
        const categoryNames = {
            'forrett': 'Forrett',
            'hovedrett': 'Hovedrett',
            'dessert': 'Dessert',
            'snacks': 'Snacks',
            'drikke': 'Drikke'
        };

        return `
            <div class="recipe-card" data-id="${recipe.id}">
                <img src="${recipe.image}" alt="${recipe.name}" class="recipe-image" 
                     onerror="this.src='https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop'">
                <div class="recipe-content">
                    <h3 class="recipe-title">${recipe.name}</h3>
                    <div class="recipe-meta">
                        <span><i class="fas fa-clock"></i> ${recipe.time} min</span>
                        <span><i class="fas fa-users"></i> ${recipe.servings} porsjoner</span>
                    </div>
                    <div class="recipe-category">${categoryNames[recipe.category]}</div>
                    <p class="recipe-description">${this.getShortDescription(recipe.ingredients)}</p>
                    <div class="recipe-actions">
                        <button class="btn btn-edit" onclick="recipeManager.editRecipe(${recipe.id})">
                            <i class="fas fa-edit"></i> Rediger
                        </button>
                        <button class="btn btn-delete" onclick="recipeManager.deleteRecipe(${recipe.id})">
                            <i class="fas fa-trash"></i> Slett
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    createRecipeDetailsHTML(recipe) {
        const categoryNames = {
            'forrett': 'Forrett',
            'hovedrett': 'Hovedrett',
            'dessert': 'Dessert',
            'snacks': 'Snacks',
            'drikke': 'Drikke'
        };

        const ingredientsList = recipe.ingredients.split('\n')
            .filter(ingredient => ingredient.trim())
            .map(ingredient => `<li>${ingredient.trim()}</li>`)
            .join('');

        return `
            <img src="${recipe.image}" alt="${recipe.name}" class="recipe-detail-image"
                 onerror="this.src='https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop'">
            
            <div class="recipe-detail-meta">
                <div class="meta-item">
                    <i class="fas fa-tag"></i>
                    <div><strong>Kategori</strong><br>${categoryNames[recipe.category]}</div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <div><strong>Tilberedningstid</strong><br>${recipe.time} minutter</div>
                </div>
                <div class="meta-item">
                    <i class="fas fa-users"></i>
                    <div><strong>Porsjoner</strong><br>${recipe.servings} personer</div>
                </div>
            </div>

            <div class="recipe-section">
                <h3><i class="fas fa-list"></i> Ingredienser</h3>
                <ul class="ingredients-list">
                    ${ingredientsList}
                </ul>
            </div>

            <div class="recipe-section">
                <h3><i class="fas fa-clipboard-list"></i> Fremgangsmåte</h3>
                <div class="instructions-text">${this.formatInstructions(recipe.instructions)}</div>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <button class="btn btn-edit" onclick="recipeManager.editRecipe(${recipe.id}); recipeManager.closeViewModal();">
                    <i class="fas fa-edit"></i> Rediger oppskrift
                </button>
            </div>
        `;
    }

    getShortDescription(text, maxLength = 100) {
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    formatInstructions(instructions) {
        return instructions.split('\n')
            .filter(step => step.trim())
            .map(step => `<p>${step.trim()}</p>`)
            .join('');
    }

    editRecipe(id) {
        const recipe = this.recipes.find(r => r.id === id);
        if (recipe) {
            this.openModal(recipe);
        }
    }

    bindRecipeEvents() {
        // Bind klikk på oppskriftskort for å vise detaljer
        document.querySelectorAll('.recipe-card').forEach(card => {
            card.addEventListener('click', (e) => {
                // Ikke åpne modal hvis brukeren klikker på knapper
                if (e.target.closest('.recipe-actions')) {
                    return;
                }
                
                const recipeId = parseInt(card.dataset.id);
                const recipe = this.recipes.find(r => r.id === recipeId);
                if (recipe) {
                    this.openViewModal(recipe);
                }
            });
        });
    }

    loadRecipes() {
        try {
            const saved = localStorage.getItem('recipes');
            return saved ? JSON.parse(saved) : [];
        } catch (error) {
            console.error('Feil ved lasting av oppskrifter:', error);
            return [];
        }
    }

    saveRecipes() {
        try {
            localStorage.setItem('recipes', JSON.stringify(this.recipes));
        } catch (error) {
            console.error('Feil ved lagring av oppskrifter:', error);
        }
    }
}

// Initialiser applikasjonen
let recipeManager;
document.addEventListener('DOMContentLoaded', () => {
    recipeManager = new RecipeManager();
});

// Hjelpefunksjoner for global tilgang
window.recipeManager = recipeManager;