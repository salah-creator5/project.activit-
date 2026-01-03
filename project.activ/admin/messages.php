<?php
session_start();

// Vérifier si l'admin est connecté (Sécurité de base)
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Définition du chemin vers le fichier JSON des messages
// dirname(__DIR__) remonte d'un dossier (admin -> racine)
$messages_file = dirname(__DIR__) . '/data/messages.json';
$messages = [];

// Chargement des messages existants
if (file_exists($messages_file)) {
    $json_content = file_get_contents($messages_file);
    // Décodage du JSON en tableau PHP ou tableau vide en cas d'erreur
    $messages = json_decode($json_content, true) ?: [];
}

// LOGIQUE : Marquer un message comme lu
// Déclenché par un lien avec ?mark_read=1&id=...
if (isset($_GET['mark_read']) && isset($_GET['id'])) {
    // On utilise & pour modifier l'élément directement dans le tableau (référence)
    foreach ($messages as &$msg) {
        if ($msg['id'] === $_GET['id']) {
            $msg['lu'] = true; // Changement du statut
            break;
        }
    }
    // Sauvegarde des changements
    file_put_contents($messages_file, json_encode($messages, JSON_PRETTY_PRINT));
    // Redirection propre pour nettoyer l'URL
    header('Location: messages.php');
    exit();
}

// LOGIQUE : Supprimer un message
// Déclenché par un lien avec ?delete=1&id=...
if (isset($_GET['delete']) && isset($_GET['id'])) {
    // array_filter crée un nouveau tableau en gardant les éléments qui ne correspondent PAS à l'ID à supprimer
    $messages = array_filter($messages, function ($msg) {
        return $msg['id'] !== $_GET['id'];
    });
    $messages = array_values($messages); // Réindexation du tableau pour éviter les trous dans les clés (0, 2, 3 -> 0, 1, 2)
    file_put_contents($messages_file, json_encode($messages, JSON_PRETTY_PRINT));
    header('Location: messages.php');
    exit();
}

// CALCUL : Compter les messages non lus pour les statistiques
// Filtre les messages où 'lu' est faux, puis compte le résultat
$unread_count = count(array_filter($messages, function ($msg) {
    return !$msg['lu'];
}));
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Culture Tanger</title>

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin Style -->
    <link rel="stylesheet" href="admin-style.css">

    <style>
        /* Specific Message Styles reusing variables */
        .message-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .message-card.unread {
            border-left: 4px solid var(--secondary-blue);
            background: #F8FBFE;
        }

        .message-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .msg-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .sender-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sender-avatar {
            width: 45px;
            height: 45px;
            background: #EDF2F7;
            color: var(--secondary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .msg-content {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            padding-left: 57px;
            /* Align with text not avatar */
        }

        .msg-actions {
            padding-left: 57px;
            display: flex;
            gap: 15px;
        }

        .action-link {
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: opacity 0.2s;
        }

        .link-reply {
            color: var(--secondary-blue);
        }

        .link-delete {
            color: #dc3545;
        }

        .link-read {
            color: #28a745;
        }

        .action-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }
    </style>
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
                    <a href="inscriptions_gestion.php" class="nav-link">
                        <i class="fas fa-users"></i> <span>Réservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="messages.php" class="nav-link active">
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
                    <h1>Messagerie</h1>
                    <p>Vos retours clients et demandes d'informations.</p>
                </div>
                <div class="user-profile">
                    <div class="avatar-circle"><?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?></div>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Non lus</h3>
                        <div class="stat-number"><?php echo $unread_count; ?></div>
                    </div>
                    <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                </div>
                <!-- On pourrait ajouter d'autres stats ici -->
            </div>

            <div style="margin-top: 2rem;">
                <?php if (empty($messages)): ?>
                    <div
                        style="text-align: center; padding: 4rem; background: white; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--text-muted);">Aucun message</h3>
                    </div>
                <?php else: ?>
                    <?php
                    // Tri par date inverse (plus récent d'abord - si la date est stockée)
                    // Ici on suppose que le JSON est appendé donc on inverse juste
                    $display_messages = array_reverse($messages);

                    foreach ($display_messages as $msg):
                        ?>
                        <div class="message-card <?php echo !$msg['lu'] ? 'unread' : ''; ?>">
                            <div class="msg-header">
                                <div class="sender-info">
                                    <div class="sender-avatar">
                                        <?php echo strtoupper(substr($msg['nom'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h3 style="font-size: 1rem; margin-bottom: 2px;">
                                            <?php echo htmlspecialchars($msg['nom']); ?></h3>
                                        <div style="font-size: 0.85rem; color: var(--text-muted);">
                                            <?php echo htmlspecialchars($msg['email']); ?></div>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                        <?php echo isset($msg['date']) ? date('d/m H:i', strtotime($msg['date'])) : ''; ?>
                                    </div>
                                    <?php if (!$msg['lu']): ?>
                                        <span class="badge badge-gold"
                                            style="font-size: 0.7rem; margin-top: 5px; display: inline-block;">Nouveau</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="msg-content">
                                <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                            </div>

                            <div class="msg-actions">
                                <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="action-link link-reply">
                                    <i class="fas fa-reply"></i> Répondre
                                </a>

                                <?php if (!$msg['lu']): ?>
                                    <a href="?mark_read=1&id=<?php echo $msg['id']; ?>" class="action-link link-read">
                                        <i class="fas fa-check"></i> Marquer comme lu
                                    </a>
                                <?php endif; ?>

                                <a href="?delete=1&id=<?php echo $msg['id']; ?>" class="action-link link-delete"
                                    onclick="return confirm('Supprimer ce message ?');">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </main>
    </div>
</body>

</html>