<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <header>
        <h1>Ferme Écoresponsable</h1>
    </header>

    <main>
        <div class="card">
            <h2>Créer un Compte</h2>
            <?php if (isset($_GET['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form action="../../controllers/utilisateurController/utilisateurController.php" method="POST">
    <input type="hidden" name="action" value="inscription">
    
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="motDePasse" placeholder="Mot de passe" required>
    
    <select name="role" class="card select" required> 
    <option value="" disabled selected>Choisir un rôle</option> 
        <option value="Responsable">Responsable</option>
        <option value="Woofer">Woofer</option>
        <option value="Participant">Participant</option>
    </select>

    <button type="submit">Créer</button>
</form>

            <a href="connexion.php">Déjà un compte ? Connectez-vous</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
    </footer>
</body>
</html>
