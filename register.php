<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | AZUL Snack</title>
    <!-- Importer les styles CSS -->
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header role="banner">
        <div class="logo-container">
            <a href="azul.html" aria-label="Retour à l'accueil">
                <img src="assets/azul_logo.png" alt="Logo de AZUL snack" class="logo">
            </a>
        </div>
    </header>

    <!-- Section Inscription -->
    <section class="register-section" aria-labelledby="register-title">
        <div class="register-container">
            <h2 id="register-title">Inscrivez-vous</h2>
            <p>Remplissez le formulaire ci-dessous pour créer un compte.</p>

            <!-- Formulaire d'Inscription -->
            <form id="register-form" action="submit_register.php" method="POST" novalidate>
                <!-- Champ Email -->
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Entrez votre email" 
                        required 
                        onblur="checkEmailAvailability()" 
                        aria-describedby="email-error"
                    >
                    <div id="email-error" class="error-message" style="color: red; font-size: 0.9rem;"></div>
                </div>
                
                <!-- Champ Mot de passe -->
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Entrez un mot de passe" 
                        required 
                        aria-describedby="password-hint"
                    >
                    <small id="password-hint" class="hint">Le mot de passe doit contenir au moins 8 caractères.</small>
                </div>

                <!-- Champ Nom -->
                <div class="form-group">
                    <label for="last-name"><i class="fas fa-user"></i> Nom</label>
                    <input 
                        type="text" 
                        id="last-name" 
                        name="last-name" 
                        placeholder="Entrez votre nom" 
                        required
                    >
                </div>

                <!-- Champ Prénom -->
                <div class="form-group">
                    <label for="first-name"><i class="fas fa-user"></i> Prénom</label>
                    <input 
                        type="text" 
                        id="first-name" 
                        name="first-name" 
                        placeholder="Entrez votre prénom" 
                        required
                    >
                </div>
                
                <!-- Champ Numéro de téléphone -->
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Numéro de téléphone</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        placeholder="Entrez votre numéro de téléphone" 
                        pattern="[0-9]{10}" 
                        title="Veuillez entrer un numéro de téléphone valide (10 chiffres)."
                    >
                </div>

                <!-- Champ Adresse -->
                <div class="form-group">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Adresse</label>
                    <div class="address-inputs">
                        <input 
                            type="text" 
                            id="street-number" 
                            name="street-number" 
                            placeholder="Numéro" 
                            required
                        >
                        <input 
                            type="text" 
                            id="street-name" 
                            name="street-name" 
                            placeholder="Nom de la rue" 
                            required
                        >
                    </div>
                </div>
                
                <!-- Champ Code postal -->
                <div class="form-group">
                    <label for="postal-code"><i class="fas fa-mail-bulk"></i> Code postal</label>
                    <input 
                        type="text" 
                        id="postal-code" 
                        name="postal-code" 
                        placeholder="Exemple : 06000" 
                        required 
                        pattern="[0-9]{5}" 
                        title="Veuillez entrer un code postal valide (5 chiffres)."
                    >
                </div>
                
                <!-- Champ Ville -->
                <div class="form-group">
                    <label for="city"><i class="fas fa-city"></i> Ville</label>
                    <input 
                        type="text" 
                        id="city" 
                        name="city" 
                        placeholder="Exemple : Nice" 
                        required
                    >
                </div>

                <!-- Champ Étage (facultatif) -->
                <div class="form-group">
                    <label for="floor"><i class="fas fa-building"></i> Étage (facultatif)</label>
                    <input 
                        type="text" 
                        id="floor" 
                        name="floor" 
                        placeholder="Exemple : 2ème étage"
                    >
                </div>

                <!-- Bouton Soumettre -->
                <button type="submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i> S'inscrire
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer role="contentinfo">
        <p>&copy; 2025 AZUL Snack. Tous droits réservés.</p>
    </footer>

    <!-- Script JavaScript -->
    <script>
        function checkEmailAvailability() {
            // Récupérer l'élément du champ email
            const emailInput = document.getElementById('email');
            // Récupérer l'élément où afficher les messages d'erreur
            const emailError = document.getElementById('email-error');
            // Récupérer la valeur saisie par l'utilisateur
            const emailValue = emailInput.value.trim();
        
            // Effacer les messages d'erreur précédents
            emailError.textContent = '';
        
            // Vérifier si le champ email est vide
            if (!emailValue) {
                return; // Ne rien faire si le champ est vide
            }
        
            // Envoyer une requête AJAX au fichier PHP 'check_email.php'
            fetch('check_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: emailValue })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    emailError.textContent = 'Cet email est déjà utilisé.';
                    emailError.style.color = 'red';
                } else {
                    emailError.textContent = 'Email disponible.';
                    emailError.style.color = 'green';
                }
            })
            .catch(error => {
                console.error("Erreur lors de la vérification de l'email :", error);
                emailError.textContent = "Une erreur est survenue. Veuillez réessayer.";
                emailError.style.color = 'red';
            });
        }
    </script>
</body>
</html>