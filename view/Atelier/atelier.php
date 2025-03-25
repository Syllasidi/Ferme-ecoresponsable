<?php
// Inclure les modèles
require_once __DIR__ . '/../../db/db_connect.php';
require_once __DIR__ . '/../../model/ModeleAtelier.php';
require_once __DIR__ . '/../../model/ModeleParticipant.php';
require_once __DIR__ . '/../../model/Utilisateur.php';

// Initialisation des modèles
$atelierModel = new ModeleAtelier($pdo);
$participantModel = new ModeleParticipant($pdo);
$utilisateurModel = new Utilisateur($pdo);

// Récupérer les ateliers
$ateliers = $atelierModel->getAllAteliers();
$categories = $atelierModel->getCategories();

// Récupérer tous les utilisateurs avec rôle 'Participant'
$utilisateursParticipants = $utilisateurModel->getUtilisateursParRole('Participant');
$utilisateursWoofer = $utilisateurModel->getUtilisateursParRole('Woofer');

// Récupérer les participants de chaque atelier
$participantsParAtelier = [];
foreach ($ateliers as $atelier) {
    $participantsParAtelier[$atelier['id']] = $atelierModel->getParticipantsAtelier($atelier['id']);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Ateliers</title>
    <link rel="stylesheet" href="../../public/css/styleWoofer.css"> <!-- Style commun à Woofer -->
</head>
<body>
    <header>
        <div class="logo">Ferme Écoresponsable</div>
        <button class="logout-button">Déconnexion</button>
    </header>

    <nav>
    <a href="atelier.php">Gestion des Ateliers</a>
        <a href="../../view/tableauDeBord/tableauDeBord.php">Tableau de Bord</a>
        <a href="../../view/produit/produit.php">Gestion de stock</a>
        <a href="../../view/woofer/woofer.php">Gestion des Woofers</a>
        <a href="../../view/vente/vente.php">Gestion des Ventes</a>
    </nav>

    <main>
        <!-- Formulaire pour Ajouter un Atelier -->
        <section>
            <h2>Ajouter un Atelier</h2>
            <form  class="form-container"  action="../../controllers/controleurAtelier/controleurAtelier.php" method="POST">
                <input type="hidden" name="action" value="ajouter_atelier">
                <label>Nom de l'atelier:</label>
                <input type="text" name="nom" required><br>
                <label>Thématique:</label>
                <textarea name="thematique" required></textarea><br>
                <label>Date:</label>
                <input type="date" name="date" required><br>
                <label>Responsable (Woofer):</label>
                <select name="woofer_id" required>
                    <option value="">-- Choisir un woofer --</option>
                    <?php foreach ($utilisateursWoofer as $util): ?>
                        <option value="<?= $util['id'] ?>"><?= htmlspecialchars($util['prenom'] . ' ' . $util['nom']) ?></option>
                    <?php endforeach; ?>
                </select><br>
                
                <label>Catégorie de Produit:</label>
                <select name="categorie" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= $categorie['categorie'] ?>"><?= htmlspecialchars($categorie['categorie']) ?></option>
                    <?php endforeach; ?>
                </select><br>
                <button type="submit">Ajouter</button>
            </form>
        </section>

        <!-- Formulaire pour Ajouter un Participant à un Atelier -->
        <section class="form-container">
            <h2>Ajouter un Participant à un Atelier</h2>
            <form action="../../controllers/controleurAtelier/controleurAtelier.php" method="POST">
                <input type="hidden" name="action" value="ajouter_participant">
                <label>Choisir un utilisateur (Participant):</label>
                <select name="utilisateur_id" required>
                    <option value="">-- Choisir un utilisateur --</option>
                    <?php foreach ($utilisateursParticipants as $participant): ?>
                        <option value="<?= $participant['id'] ?>"><?= htmlspecialchars($participant['prenom'] . ' ' . $participant['nom']) ?></option>
                    <?php endforeach; ?>
                </select><br>
                <label>Choisir un atelier:</label>
                <select name="atelier_id" required>
                    <option value="">-- Choisir un atelier --</option>
                    <?php foreach ($ateliers as $atelier): ?>
                        <option value="<?= $atelier['id'] ?>"><?= htmlspecialchars($atelier['nom']) ?></option>

                    <?php endforeach; ?>
                    
                </select><br>
                
                <button type="submit">Ajouter le participant</button>
            </form>
        </section>

        <!-- Liste des Ateliers -->
        <section>
            <h2>Liste des Ateliers</h2>
            <table class="planning-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Thématique</th>
                        <th>Date</th>
                        <th>Responsable</th>
                        <th>Produit</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ateliers as $atelier): ?>
                    <tr>
                        <td><?= htmlspecialchars($atelier['nom']) ?></td>
                        <td><?= htmlspecialchars($atelier['thematique']) ?></td>
                        <td><?= htmlspecialchars($atelier['date']) ?></td>
                        <td><?= htmlspecialchars($atelier['woofer_nom'] . ' ' . $atelier['woofer_prenom']) ?></td>
                        <td><?= htmlspecialchars($atelier['produit_categorie']) ?></td>
                      
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Liste des Inscriptions des Participants -->
        <section>
            <h2>Inscriptions aux Ateliers</h2>
            <table class="planning-table"><!-- le nom de la classe ? juste rapidié -->
                <thead>
                    <tr>
                        <th>Nom de l'Atelier</th>
                        <th>Nom du Participant</th>
                        <th>Email</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ateliers as $atelier): ?>
                        <?php
                        $participants = $participantsParAtelier[$atelier['id']] ?? [];
                        foreach ($participants as $participant):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($atelier['nom']) ?></td>
                                <td><?= htmlspecialchars($participant['prenom'] . ' ' . $participant['nom']) ?></td>
                                <td><?= htmlspecialchars($participant['email']) ?></td>
                               
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Ferme Écoresponsable. Tous droits réservés.</p>
    </footer>
</body>
</html>
