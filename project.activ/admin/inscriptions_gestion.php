<?php
session_start();
// Sécurité : Redirection si l'administrateur n'est pas connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Chemin vers le fichier JSON stockant les inscriptions
$json_file = '../data/inscriptions.json';

// Chargement des données : on décode le JSON en tableau PHP associatif
// Si le fichier n'existe pas, on initialise un tableau vide
$inscriptions = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

// --- TRAITEMENT DES ACTIONS (VALIDER / REFUSER) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération de l'action demandée par le formulaire
    $action = $_POST['action']; // 'valider' ou 'refuser'
    $id = $_POST['id'];        // ID unique de l'inscription cible

    // On parcourt le tableau pour trouver l'inscription correspondante (passage par référence &$ins pour modifier directement)
    foreach ($inscriptions as &$ins) {
        if ($ins['id'] == $id) {
            if ($action === 'valider') {
                $ins['statut'] = 'Confirmée'; // Mise à jour du statut
            } elseif ($action === 'refuser') {
                $ins['statut'] = 'Refusée';
            }
            break; // On arrête la recherche une fois trouvé
        }
    }

    // Sauvegarde des modifications dans le fichier JSON
    // PRETTY_PRINT rend le fichier lisible par un humain
    file_put_contents($json_file, json_encode($inscriptions, JSON_PRETTY_PRINT));

    // Redirection (Post/Redirect/Get pattern) pour éviter de renvoyer le formulaire si on rafraîchit la page
    header("Location: inscriptions_gestion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Inscriptions - Culture Tanger</title>

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin Style -->
    <link rel="stylesheet" href="admin-style.css">
</head>

<body>
    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="brand">
                <h2>Culture</h2>
                <span>Tanger Admin</span>
            </div>
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-home"></i> <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="activites_gestion.php" class="nav-link">
                        <i class="fas fa-calendar-alt"></i> <span>Activités</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="inscriptions_gestion.php" class="nav-link active">
                        <i class="fas fa-users"></i> <span>Réservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php" class="nav-link">
                        <i class="fas fa-envelope"></i> <span>Messages</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../index.php" target="_blank" class="nav-link">
                    <i class="fas fa-external-link-alt"></i> <span>Voir le site</span>
                </a>
                <a href="logout.php" class="nav-link" style="color: #ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <header>
                <div class="page-title">
                    <h1>Inscriptions</h1>
                    <p>Gérez les réservations et les statuts des participants.</p>
                </div>
                <div class="user-profile">
                    <div class="avatar-circle"><?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?></div>
                </div>
            </header>

            <!-- STATS RAPIDES -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card" style="padding: 1.5rem;">
                    <div class="stat-info">
                        <h3>En attente</h3>
                        <div class="stat-number" style="font-size: 2rem;">
                            <?php
                            $attente = array_filter($inscriptions, fn($i) => $i['statut'] === 'En attente');
                            echo count($attente);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="stat-card" style="padding: 1.5rem;">
                    <div class="stat-info">
                        <h3>Confirmées</h3>
                        <div class="stat-number" style="font-size: 2rem; color: #28a745;">
                            <?php
                            $confirmee = array_filter($inscriptions, fn($i) => strpos($i['statut'], 'Confirm') !== false);
                            echo count($confirmee);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLEAU INSCRIPTIONS -->
            <div class="table-container">
                <div class="section-header">
                    <h2>Liste des Réservations</h2>
                    <div style="display: flex; gap: 10px;">
                        <!-- On pourrait ajouter un filtre ici -->
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Participant</th>
                            <th>Activité</th>
                            <th>Contact</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inscriptions)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    Aucune inscription trouvée.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            // Sort by date desc
                            usort($inscriptions, function ($a, $b) {
                                return strtotime($b['date_inscription']) - strtotime($a['date_inscription']);
                            });

                            foreach ($inscriptions as $row):
                                // Badge Logic
                                $badgeClass = 'badge-brown'; // Default grey/brown
                                if (strpos($row['statut'], 'Confirm') !== false)
                                    $badgeClass = 'badge-gold'; // Now Blue
                                elseif (strpos($row['statut'], 'Refus') !== false)
                                    $badgeClass = 'badge-red'; // To define or use default style
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['date_inscription'])); ?></td>
                                    <td style="font-weight: 600;">
                                        <?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?></td>
                                    <td style="color: var(--secondary-blue);">
                                        <?php echo htmlspecialchars($row['activite_titre']); ?></td>
                                    <td>
                                        <div style="font-size: 0.9rem;"><?php echo htmlspecialchars($row['email']); ?></div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                                            <?php echo htmlspecialchars($row['telephone']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $badgeClass; ?>"
                                            style="<?php if (strpos($row['statut'], 'Refus') !== false)
                                                echo 'background: #fee2e2; color: #991b1b;'; ?>">
                                            <?php echo htmlspecialchars($row['statut']); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php if ($row['statut'] === 'En attente'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                                                <button type="submit" name="action" value="valider"
                                                    style="background:none; border:none; cursor:pointer; color: #28a745; margin-right: 10px;"
                                                    title="Valider">
                                                    <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
                                                </button>

                                                <button type="submit" name="action" value="refuser"
                                                    style="background:none; border:none; cursor:pointer; color: #dc3545;"
                                                    title="Refuser">
                                                    <i class="fas fa-times-circle" style="font-size: 1.2rem;"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: #ccc;"><i class="fas fa-lock"></i></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</body>

</html>