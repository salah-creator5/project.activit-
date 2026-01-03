<?php
session_start();
// Vérification d'accès administrateur
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/db.php'; // Connexion DB pour certaines stats

// STATISTIQUES GLOBALES
// Comptage simple via requêtes SQL directes (plus rapide que de tout charger)
$nb_activites = $pdo->query("SELECT COUNT(*) FROM activites")->fetchColumn();
$nb_inscriptions = $pdo->query("SELECT COUNT(*) FROM inscriptions")->fetchColumn();

// STATISTIQUES DES MESSAGES (Stockés en JSON)
$messages_file = dirname(__DIR__) . '/data/messages.json';
$messages_count = 0;
$unread_messages = 0;

if (file_exists($messages_file)) {
    // Décodage du JSON
    $messages = json_decode(file_get_contents($messages_file), true) ?: [];

    // Total des messages
    $messages_count = count($messages);

    // Calcul des messages non lus en filtrant le tableau
    $unread_messages = count(array_filter($messages, function ($msg) {
        return !$msg['lu']; // On garde ceux où 'lu' est FAUX
    }));
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Culture Tanger</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">

    <!-- Icons -->
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
                    <a href="dashboard.php" class="nav-link active">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="activites_gestion.php" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Activités</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="inscriptions_gestion.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Réservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php" class="nav-link">
                        <div style="position: relative;">
                            <i class="fas fa-envelope"></i>
                            <?php if ($unread_messages > 0): ?>
                                <span
                                    style="position: absolute; top: -5px; right: -5px; width: 8px; height: 8px; background: #E05A2B; border-radius: 50%;"></span>
                            <?php endif; ?>
                        </div>
                        <span>Messages</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="../index.php" target="_blank" class="nav-link">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Voir le site</span>
                </a>
                <a href="logout.php" class="nav-link" style="color: #ff6b6b;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <header>
                <div class="page-title">
                    <h1>Vue d'ensemble</h1>
                    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>. Voici ce qui se passe
                        aujourd'hui.</p>
                </div>

                <div class="user-profile">
                    <div style="text-align: right;">
                        <span
                            style="display: block; font-weight: 600;"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Administrateur</span>
                    </div>
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
                    </div>
                </div>
            </header>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(58, 110, 165, 0.1); color: var(--secondary-blue);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Activités en ligne</h3>
                        <div class="stat-number">
                             <?php echo $pdo->query("SELECT COUNT(*) FROM activites")->fetchColumn(); ?>
                        </div>
                    </div>
                </div>
                
                <?php
                    // Load Inscriptions from JSON
                    $json_file = '../data/inscriptions.json';
                    $inscriptions = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];
                    $total_inscriptions = count($inscriptions);
                    
                    // Count unread messages from JSON
                    $msg_file = '../data/messages.json';
                    $messages = file_exists($msg_file) ? json_decode(file_get_contents($msg_file), true) : [];
                    $unread_msgs = count(array_filter($messages, fn($m) => !$m['lu']));
                ?>

                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #FFC107;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Inscriptions Totales</h3>
                        <div class="stat-number"><?php echo $total_inscriptions; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(224, 90, 43, 0.1); color: var(--accent-terracotta);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Nouveaux Messages</h3>
                        <div class="stat-number">
                            <?php echo $unread_msgs; ?>
                            <?php if($unread_msgs > 0): ?>
                            <span style="font-size: 0.8rem; background: #e74c3c; color: white; padding: 2px 6px; border-radius: 50%; margin-left: 5px;">!</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT INSCRIPTIONS -->
            <div class="table-container">
                <div class="section-header">
                    <h2>Dernières Inscriptions</h2>
                    <a href="inscriptions_gestion.php" style="color: var(--secondary-blue); text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                        Tout voir <i class="fas fa-arrow-right" style="font-size: 0.8rem; margin-left: 5px;"></i>
                    </a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Participant</th>
                            <th>Activité Choisie</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Sort by date DESC
                            usort($inscriptions, function ($a, $b) {
                                return strtotime($b['date_inscription']) - strtotime($a['date_inscription']);
                            });
                            
                            // Get top 5
                            $recent_inscriptions = array_slice($inscriptions, 0, 5);
                            
                            if (empty($recent_inscriptions)):
                        ?>
                            <tr><td colspan="5" style="text-align:center;">Aucune inscription récente.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recent_inscriptions as $row): 
                                $badgeClass = 'badge-brown';
                                if (strpos($row['statut'], 'Confirm') !== false) $badgeClass = 'badge-gold';
                            ?>
                            <tr>
                                <td style="font-family: monospace; opacity: 0.7;">#<?php echo substr($row['id'], 0, 8); ?>...</td>
                                <td style="font-weight: 600;"><?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?></td>
                                <td style="color: var(--secondary-blue);"><?php echo htmlspecialchars($row['activite_titre']); ?></td>
                                <td><?php echo date('d M, H:i', strtotime($row['date_inscription'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo htmlspecialchars($row['statut']); ?>
                                    </span>
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