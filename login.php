<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AZUL Snack</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header role="banner">
        <nav role="navigation" aria-label="Menu principal">
            <div class="header-container">
                <div class="logo-container">
                    <a href="azul.html" aria-label="Retour à l'accueil">
                        <img src="assets/azul_logo.png" alt="Logo AZUL Snack" class="logo">
                    </a>
                </div>
                <ul class="nav-links">
                    <li><a href="azul.html"><i class="fas fa-home"></i> Accueil</a></li>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main role="main">
        <div class="login-container">
            <h2>Connexion</h2>
            <p>Connectez-vous pour accéder à votre compte.</p>

            <!-- Message d'erreur -->
            <div id="error-message" style="color: red; display: none;"></div>

            <!-- Formulaire de connexion -->
            <form id="login-form" action="submit_login.php" method="POST">
                <!-- Token CSRF généré côté serveur -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Entrez votre email" 
                        required
                        aria-required="true">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Entrez votre mot de passe" 
                        required
                        aria-required="true">
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>

                <div class="login-links">
                    <p><a href="forgot-password.html">Mot de passe oublié ?</a></p>
                </div>
            </form>
        </div>
    </main>

    <footer role="contentinfo">
        <p>&copy; 2025 AZUL Snack. Tous droits réservés.</p>
    </footer>
</body>
</html>