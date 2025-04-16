// Stockage du panier dans localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

/**
 * Initialiser le site après le chargement complet
 */
document.addEventListener('DOMContentLoaded', () => {
    updateCartDisplay();
});


function addToCart(id, name, price) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existing = cart.find(item => item.id === id);
    
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ 
            id: parseInt(id), 
            name: name.toString(),
            price: parseFloat(price),
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCounter();
}

// Fonction pour mettre à jour le compteur du panier
function updateCartCounter() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);

    // Mettre à jour le texte du compteur
    const cartCounter = document.getElementById('cart-counter');
    if (cartCounter) {
        cartCounter.textContent = totalItems;
    }
}

// Appeler la fonction au chargement de la page pour initialiser le compteur
document.addEventListener('DOMContentLoaded', () => {
    updateCartCounter();
});


/**
 * Mettre à jour l'affichage du panier
 */
function updateCartDisplay() {
    const cartContainer = document.getElementById('cart-items');
    if (!cartContainer) return;

    cartContainer.innerHTML = '';

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="empty-cart">Votre panier est vide.</p>';
        updateOrderSummary();
        return;
    }

    cart.forEach((item, index) => {
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');
        cartItem.innerHTML = `
            <div class="item-details">
                <img src="assets/${item.id}.webp" alt="${item.name}" class="item-image">
                <div class="item-info">
                    <p class="item-name">${item.name}</p>
                    <p class="item-price">${item.price.toFixed(2)}€</p>
                </div>
            </div>
            <div class="item-actions">
                <button class="quantity-btn decrease" onclick="updateQuantity(${index}, 'decrease')">-</button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-btn increase" onclick="updateQuantity(${index}, 'increase')">+</button>
                <button class="remove-btn" onclick="removeFromCart(${index})">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
        `;
        cartContainer.appendChild(cartItem);
    });

    updateOrderSummary();
}

/**
 * Mettre à jour la quantité d'un produit
 */
function updateQuantity(index, action) {
    const item = cart[index];

    if (action === 'increase') {
        item.quantity += 1;
    } else if (action === 'decrease' && item.quantity > 1) {
        item.quantity -= 1;
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

/**
 * Supprimer un produit du panier
 */
function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

/**
 * Mettre à jour le récapitulatif de la commande
 */
function updateOrderSummary() {
    const subtotalElement = document.getElementById('subtotal');
    const feesElement = document.getElementById('fees');
    const totalElement = document.getElementById('total');

    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const shippingFees = 3;

    const total = subtotal + shippingFees;

    subtotalElement.textContent = `${subtotal.toFixed(2)}€`;
    feesElement.textContent = `3.00€`;
    totalElement.textContent = `${total.toFixed(2)}€`;
}

/**
 * Passer à la caisse
 */
function proceedToCheckout() {
    alert('Redirection vers la page de paiement...');
    // Ajoutez ici la logique de redirection ou de traitement du paiement
}


// Fonction pour réinitialiser et appliquer les filtres
function applyFilters() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const selectedCategory = document.querySelector('.category-buttons button.active')?.getAttribute('data-category') || 'all';

    const products = document.querySelectorAll('.product-card');

    products.forEach(product => {
        const productName = product.querySelector('h3').textContent.toLowerCase();
        const productCategory = product.getAttribute('data-category').toLowerCase();

        // Conditions pour afficher un produit
        const matchesSearch = productName.includes(searchInput);
        const matchesCategory = selectedCategory === 'all' || productCategory === selectedCategory;

        if (matchesSearch && matchesCategory) {
            product.style.display = 'block'; // Affiche le produit s'il correspond
        } else {
            product.style.display = 'none'; // Masque le produit s'il ne correspond pas
        }
    });
}

// Écouteur pour la barre de recherche
document.getElementById('search-input').addEventListener('input', applyFilters);

// Écouteurs pour les boutons de catégorie
document.querySelectorAll('.category-buttons button').forEach(button => {
    button.addEventListener('click', () => {
        // Retirer la classe "active" de tous les boutons
        document.querySelectorAll('.category-buttons button').forEach(btn => btn.classList.remove('active'));

        // Ajouter la classe "active" au bouton cliqué
        button.classList.add('active');

        // Appliquer les filtres
        applyFilters();
    });
});



document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Simuler une connexion (remplacer par une requête API en production)
    if (email && password) {
        alert('Connexion réussie !');
        window.location.href = 'profile.php'; // Redirection vers le profil
    } else {
        alert('Email ou mot de passe incorrect.');
    }
});


document.getElementById('forgot-password-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const phoneLastDigits = document.getElementById('phone-last-digits').value;

    // Vérification des données (remplacer par une requête API en production)
    if (email && phoneLastDigits.length === 4) {
        alert('Un lien de réinitialisation a été envoyé à votre email.');
    } else {
        alert('Veuillez entrer un email valide et les 4 derniers chiffres de votre téléphone.');
    }
});

function checkEmailAvailability() {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const emailValue = emailInput.value.trim();

    // Effacer les messages d'erreur précédents
    emailError.textContent = '';

    if (!emailValue) {
        return; // Ne rien faire si le champ est vide
    }

    // Envoyer une requête AJAX pour vérifier l'email
    fetch('check_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: emailValue })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            emailError.textContent = 'Cet email est déjà utilisé.';
        }
    })
    .catch(error => {
        console.error('Erreur lors de la vérification de l\'email :', error);
    });
}



// Fonction pour faire défiler la page vers le haut
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Défilement fluide
    });
}

// Afficher ou masquer le bouton "Retour en Haut" en fonction du défilement
document.addEventListener('DOMContentLoaded', () => {
    const backToTopButton = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('show'); // Afficher le bouton
        } else {
            backToTopButton.classList.remove('show'); // Masquer le bouton
        }
    });

    // Ajouter un écouteur d'événements pour le clic
    backToTopButton.addEventListener('click', scrollToTop);
});

document.getElementById('register-form').addEventListener('submit', function(event) {
    const emailError = document.getElementById('email-error');
    if (emailError.textContent) {
        event.preventDefault(); // Empêcher la soumission du formulaire
        alert('Veuillez corriger les erreurs avant de soumettre le formulaire.');
    }
});

document.getElementById('register-form').addEventListener('submit', function(event) {
    const addressInput = document.getElementById('address');
    const addressValue = addressInput.value.trim();

    if (!addressValue.match(/^[a-zA-Z0-9\s,'-]+$/)) {
        alert("Veuillez entrer une adresse valide.");
        event.preventDefault(); // Empêcher la soumission du formulaire
    }
});

function signInWithGoogle() {
    google.accounts.id.initialize({
        client_id: "216432935768-06a61vo1keli00emmsd4skri4m2k9cjr.apps.googleusercontent.com",
        callback: handleCredentialResponse
    });
    google.accounts.id.prompt();
}

function handleCredentialResponse(response) {
    console.log("ID Token:", response.credential);

    // Envoyez le token à votre serveur pour validation et authentification
    fetch('/auth/google', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token: response.credential })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "/dashboard"; // Redirection vers le tableau de bord
        } else {
            alert("Erreur lors de la connexion avec Google.");
        }
    })
    .catch(error => {
        console.error("Erreur lors de la requête au serveur :", error);
    });
}

// Vérifie si l'utilisateur est connecté au chargement de la page
function checkLoginStatus() {
    // Récupérez les informations de l'utilisateur depuis localStorage
    const user = JSON.parse(localStorage.getItem('user'));

    const loginButton = document.getElementById('login-button');
    const profileButton = document.getElementById('profile-button');
    const profileName = document.getElementById('profile-name');

    if (user && user.firstName) {
        // Si l'utilisateur est connecté
        loginButton.style.display = 'none'; // Masque le bouton "Connexion"
        profileButton.style.display = 'block'; // Affiche le lien "Mon Profil"
        profileName.textContent = user.firstName; // Remplace "Mon Profil" par le prénom de l'utilisateur
    } else {
        // Si l'utilisateur n'est pas connecté
        loginButton.style.display = 'block'; // Affiche le bouton "Connexion"
        profileButton.style.display = 'none'; // Masque le lien "Mon Profil"
    }
}

// Fonction pour connecter l'utilisateur
function login(firstName) {
    // Stockez les informations de l'utilisateur dans localStorage
    const user = { firstName };
    localStorage.setItem('user', JSON.stringify(user));

    // Mettez à jour la barre de navigation
    checkLoginStatus();
}

// Fonction pour déconnecter l'utilisateur
function logout() {
    // Supprimez les informations de l'utilisateur de localStorage
    localStorage.removeItem('user');

    // Redirigez vers la page d'accueil ou de connexion
    window.location.href = "http://localhost/azul.html/login.php";

    // Mettez à jour la barre de navigation
    checkLoginStatus();
}

// Appel initial pour vérifier l'état de connexion au chargement de la page
checkLoginStatus();


function handleCredentialResponse(response) {
    console.log("ID Token:", response.credential);

    // Envoyez le token au serveur pour validation
    fetch('/auth/google', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token: response.credential })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Récupérez le prénom de l'utilisateur depuis la réponse du serveur
            const firstName = data.user.firstName;

            // Connectez l'utilisateur avec son prénom
            login(firstName);

            // Redirigez vers la page de profil
            window.location.href = "http://localhost/azul.html/profile.php";
        } else {
            alert("Erreur lors de la connexion avec Google.");
        }
    })
    .catch(error => {
        console.error("Erreur lors de la requête au serveur :", error);
    });
}