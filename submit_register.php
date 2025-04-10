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

    // Étape 1 : Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = ""; // Modifiez si vous avez défini un mot de passe
    $dbname = "azul_snack";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        $_SESSION['error_message'] = "Erreur de connexion à la base de données : " . $conn->connect_error;
        header("Location: error.php");
        exit();
    }

    try {
        // Étape 2 : Récupérer et valider les données du formulaire
        $firstName = trim($_POST['first-name']);
        $lastName = trim($_POST['last-name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Nettoyer l'email
        $plainPassword = $_POST['password'];
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $floor = isset($_POST['floor']) ? trim($_POST['floor']) : '';

        // Validation des champs obligatoires
        if (empty($firstName) || empty($lastName) || empty($email) || empty($plainPassword)) {
            throw new Exception("Erreur : Tous les champs obligatoires doivent être remplis.");
        }

        // Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Erreur : L'adresse email n'est pas valide.");
        }

        // Hacher le mot de passe
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Étape 3 : Vérifier si l'email existe déjà
        $sql_check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql_check_email);
        if (!$stmt) {
            throw new Exception("Erreur de préparation SQL : " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("Erreur : Cet email est déjà utilisé.");
        }

        // Insérer les données dans la base de données
        $sql_insert_user = "INSERT INTO users (first_name, last_name, email, password, phone, address, floor)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql_insert_user);
        if (!$stmt) {
            throw new Exception("Erreur de préparation SQL : " . $conn->error);
        }

        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $hashedPassword, $phone, $address, $floor);

        if ($stmt->execute()) {
            // Redirection vers la page de connexion avec un message de succès
            $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header("Location: login.php");
            exit();
        } else {
            throw new Exception("Erreur lors de l'inscription : " . $stmt->error);
        }
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