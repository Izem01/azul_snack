<?php
session_start();

// Générer un token CSRF s'il n'existe pas déjà
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation du token CSRF
    if (
        !isset($_POST['csrf_token']) || 
        !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        // Stocker le message d'erreur dans la session
        $_SESSION['error_message'] = "Erreur de sécurité : Token CSRF invalide ou manquant.";
        header("Location: error.php");
        exit();
    }

    // Réinitialiser le token CSRF après validation
    unset($_SESSION['csrf_token']);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Récupérer les données du formulaire
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // Valider les entrées
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        // Stocker le message d'erreur dans la session
        $_SESSION['error_message'] = "Erreur : Données invalides.";
        header("Location: error.php");
        exit();
    }

    // Connexion à la base de données
    $host = "localhost";
    $username = "root";
    $db_password = ""; // Modifiez si vous avez défini un mot de passe
    $dbname = "azul_snack";

    try {
        $conn = new mysqli($host, $username, $db_password, $dbname);
        if ($conn->connect_error) {
            throw new Exception("Erreur de connexion MySQL : " . $conn->connect_error);
        }

        // Requête préparée pour vérifier les identifiants
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Erreur de préparation SQL : " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Authentification réussie
                session_regenerate_id(true); // Régénération de l'ID de session pour éviter les attaques
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $email;

                // Redirection vers la page du tableau de bord
                header("Location: dashboard.php");
                exit();
            }
        }

        // Identifiants incorrects
        $_SESSION['error_message'] = "Erreur : Email ou mot de passe incorrect.";
        header("Location: error.php");
        exit();
    } catch (Exception $e) {
        // Stocker le message d'erreur dans la session
        $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
        header("Location: error.php");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
}
?>