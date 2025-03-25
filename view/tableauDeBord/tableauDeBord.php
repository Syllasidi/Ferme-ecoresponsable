<?php
// Connexion à la base de données
require_once __DIR__ . '/../../db/db_connect.php';

// Récupérer les ateliers à venir
function getAteliersAVenir($pdo) {
    $sql = "SELECT a.id, a.nom, a.thematique, a.date,
                   u.prenom AS woofer_prenom, u.nom AS woofer_nom,
                   a.categorie AS produit_categorie,
                   p.nom AS produit_nom
            FROM atelier a
            JOIN woofer w ON a.woofer_id = w.id
            JOIN utilisateur u ON w.id = u.id
            LEFT JOIN produit p ON a.categorie = p.categorie";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les stocks actuels
function getStocks($pdo) {
    $sql = "SELECT p.nom, s.quantiteactuelle
            FROM produit p
            JOIN stock_produit sp ON p.id = sp.id_produit
            JOIN stock s ON sp.id_stock = s.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les ventes récentes
function getVentesRecentes($pdo) {
    $sql = "SELECT v.*, p.nom AS nom_produit, u.nom AS nom_woofer, u.prenom AS prenom_woofer
            FROM vente v
            JOIN produit p ON v.id_produit = p.id
            JOIN utilisateur u ON v.id_woofer = u.id
            ORDER BY v.datevente DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les woofers actifs
function getWoofersActifs($pdo) {
    $sql = "SELECT u.id, u.nom, u.prenom, w.datearrivee, w.datedepart, w.photo
            FROM utilisateur u
            JOIN woofer w ON u.id = w.id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les données nécessaires
$ateliersAVenir = getAteliersAVenir($pdo);
$stocks = getStocks($pdo);
$ventesRecentes = getVentesRecentes($pdo);
$woofersActifs = getWoofersActifs($pdo);

$totalAteliers = count($ateliersAVenir);
$totalWoofers = count($woofersActifs);
$totalStocks = array_sum(array_column($stocks, 'quantiteactuelle'));
$totalVentes = array_sum(array_column($ventesRecentes, 'prixtotal'));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="../../public/css/styleT.css">
   
  
</head>
<body>
    <header>
        <div class="logo">Ferme Écoresponsable</div>
        <button class="logout-button">Déconnexion</button>
    </header>
    <nav>
    <a href="tableauDeBord.php">Tableau de Bord</a>
        <a href="../../view/produit/produit.php">Gestion des Stocks</a>
        <a href="../../view/vente/vente.php">Gestion des Ventes</a>
        <a href="../../view/Atelier/atelier.php">Gestion des Ateliers</a>
        <a href="../../view/woofer/woofer.php">Gestion des Woofers</a>
    </nav>
    <main>
        <!-- Début du Tableau de Bord -->
        <section>
            <h1>Tableau de Bord</h1>
            <div class="dashboard">
                <div class="card">
                    <h3>Stock Actuel</h3>
                    <p>Total d'articles en stock: <?php echo $totalStocks; ?></p>
                    <?php foreach ($stocks as $stock): ?>
                        <p><?php echo htmlspecialchars($stock['nom']); ?>: <?php echo $stock['quantiteactuelle']; ?></p>
                    <?php endforeach; ?>
                    <button>Voir les stocks</button>
                </div>
                <div class="card">
                    <h3>Ventes Récentes</h3>
                    <p>Total des ventes du jour: <?php echo $totalVentes; ?>€</p>
                    <?php foreach ($ventesRecentes as $vente): ?>
                        <p><?php echo htmlspecialchars($vente['nom_produit']); ?>: <?php echo $vente['quantite']; ?>kg (<?php echo $vente['datevente']; ?>)</p>
                    <?php endforeach; ?>
                    <button>Voir toutes les ventes</button>
                </div>
                <div class="card">
                    <h3>Woofers Actifs</h3>
                    <p>Nombre de woofers présents: <?php echo $totalWoofers; ?></p>
                    <?php foreach ($woofersActifs as $woofer): ?>
                        <p><?php echo htmlspecialchars($woofer['prenom']); ?>: <?php echo htmlspecialchars($woofer['nom']); ?> (<?php echo $woofer['datearrivee']; ?>)</p>
                    <?php endforeach; ?>
                    <button>Gérer les woofers</button>
                </div>
                <div class="card">
                    <h3>Ateliers à Venir</h3>
                    <p>Nombre d'ateliers à venir: <?php echo $totalAteliers; ?></p>
                    <?php foreach ($ateliersAVenir as $atelier): ?>
                        <p><?php echo htmlspecialchars($atelier['nom']); ?> (<?php echo $atelier['date']; ?>)</p>
                    <?php endforeach; ?>
                    <button>Gérer les ateliers</button>
                </div>
            </div>
        </section>
        <!-- Fin du Tableau de Bord -->
    </main>
    <footer>
        <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
    </footer>
</body>
</html>
