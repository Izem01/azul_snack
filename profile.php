<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - AZUL Snack</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Mon Profil</h1>
    </header>
    <main>
        <div class="profile-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['first_name']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['last_name']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($user['address'] ?? 'Non renseignée') ?></p>
            <p><strong>Étage :</strong> <?= htmlspecialchars($user['floor'] ?? 'Non renseigné') ?></p>
        </div>
    </main>
</body>
</html>