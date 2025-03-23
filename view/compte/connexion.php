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
    <title>Connexion</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <header>
        <h1>Ferme Écoresponsable</h1>
    </header>

    <main>
        <div class="card">
            <h2>Connexion</h2>
            <?php if (isset($_GET['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form action="../../controllers/utilisateurController/utilisateurController.php" method="POST">
                <input type="hidden" name="action" value="connexion">
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="motDePasse" placeholder="Mot de passe" required>
                <button type="submit">Connexion</button>
            </form>
            <a href="creer_compte.php">Créer un compte</a>
            
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
    </footer>
</body>
</html>
