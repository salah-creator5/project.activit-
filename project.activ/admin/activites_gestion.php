<?php
session_start();
// Vérification de sécurité : Seul l'administrateur connecté peut accéder à cette page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirection vers le login si non connecté
    exit();
}

// Connexion à la base de données via PDO
require_once '../includes/db.php';

// --- LOGIQUE DE SUPPRESSION ---
// Si un paramètre 'delete' est présent dans l'URL (ex: ?delete=3)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Requête préparée pour supprimer l'activité en base de données de manière sécurisée
    $pdo->prepare("DELETE FROM activites WHERE id = ?")->execute([$id]);

    // Redirection pour rafraîchir la liste et éviter de rejouer l'action
    header("Location: activites_gestion.php");
    exit();
}

// --- LOGIQUE D'AJOUT ---
// Si le formulaire est soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et sécurisation des champs textes
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $categorie_id = $_POST['categorie_id'];
    $prix = $_POST['prix'];
    $formateur = htmlspecialchars($_POST['formateur']);
    $niveau = $_POST['niveau'];
    $places = $_POST['places_disponibles'];
    $date_debut = $_POST['date_debut'];
    $lieu = htmlspecialchars($_POST['lieu']);
    $prix = htmlspecialchars($_POST['prix']);
    $image = htmlspecialchars($_POST['image']);
    $date_debut = htmlspecialchars($_POST['date_debut']);
    $places = htmlspecialchars($_POST['places']);

    if (!empty($titre) && !empty($prix)) {
        $sql = "INSERT INTO activites (titre, description, lieu, prix, image, date_debut, places, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$titre, $description, $lieu, $prix, $image, $date_debut, $places])) {
            $msg = "Activité ajoutée avec succès !";
            $msg_type = "success";
        } else {
            $msg = "Erreur lors de l'ajout.";
            $msg_type = "error";
        }
    } else {
        $msg = "Veuillez remplir les champs obligatoires.";
        $msg_type = "error";
    }
}

// SUPPRESSION
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM activites WHERE id = ?");
    if ($stmt->execute([$id])) {
        $msg = "Activité supprimée.";
        $msg_type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Activités - Culture Tanger</title>

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin Style -->
    <link rel="stylesheet" href="admin-style.css">

    <style>
        /* Styles spécifiques pour le formulaire */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            margin-bottom: 3rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            font-family: var(--font-body);
            font-size: 0.95rem;
            transition: all 0.3s;
            background: #FAFAFA;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-gold);
            background: white;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--secondary-brown) 0%, #6d360f 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.2);
        }

        /* Message Alert */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Table Image */
        .img-preview {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            transition: color 0.2s;
        }

        .btn-delete {
            color: #dc3545;
            opacity: 0.7;
        }

        .btn-delete:hover {
            opacity: 1;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">

        <!-- SIDEBAR (Copied structure) -->
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
                    <a href="activites_gestion.php" class="nav-link active">
                        <i class="fas fa-calendar-alt"></i> <span>Activités</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="inscriptions_gestion.php" class="nav-link">
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
                    <h1>Gestion des Activités</h1>
                    <p>Ajoutez, modifiez ou supprimez les événements culturels.</p>
                </div>
                <div class="user-profile">
                    <div class="avatar-circle"><?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?></div>
                </div>
            </header>

            <?php if ($msg): ?>
                <div class="alert <?php echo ($msg_type == 'success') ? 'alert-success' : 'alert-error'; ?>">
                    <i
                        class="fas <?php echo ($msg_type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <!-- FORMULAIRE D'AJOUT -->
            <div class="form-container">
                <div class="section-header">
                    <h2><i class="fas fa-plus-circle" style="color: var(--secondary-blue); margin-right: 10px;"></i>
                        Nouvelle Activité</h2>
                </div>

                <form method="POST" action="activites_gestion.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Titre de l'activité</label>
                            <input type="text" name="titre" class="form-control" placeholder="Ex: Concert Andalou"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Lieu</label>
                            <input type="text" name="lieu" class="form-control" placeholder="Ex: Palais Moulay Hafid">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Description courte..."></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Prix (MAD)</label>
                            <input type="number" name="prix" class="form-control" placeholder="0" required>
                        </div>
                        <div class="form-group">
                            <label>Places disponibles</label>
                            <input type="text" name="places" class="form-control" placeholder="Ex: 50">
                        </div>
                        <div class="form-group">
                            <label>Date & Heure</label>
                            <input type="datetime-local" name="date_debut" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image" class="form-control" placeholder="https://...">
                    </div>

                    <div style="text-align: right; margin-top: 1rem;">
                        <button type="submit" name="ajouter" class="btn-submit">
                            <i class="fas fa-save"></i> Enregistrer l'activité
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABLEAU DES ACTIVITÉS -->
            <div class="table-container">
                <div class="section-header">
                    <h2>Liste des Activités</h2>
                    <span
                        class="badge badge-gold"><?php echo $pdo->query("SELECT COUNT(*) FROM activites")->fetchColumn(); ?>
                        Total</span>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Img</th>
                            <th>Titre</th>
                            <th>Lieu</th>
                            <th>Date</th>
                            <th>Prix</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM activites ORDER BY date_debut DESC");
                        while ($row = $stmt->fetch()):
                            ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="img-preview" alt="img"
                                        onerror="this.src='../assets/img/placeholder.jpg'">
                                </td>
                                <td style="font-weight: 600; color: var(--text-main);">
                                    <?php echo htmlspecialchars($row['titre']); ?></td>
                                <td style="color: var(--text-muted);"><?php echo htmlspecialchars($row['lieu']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['date_debut'])); ?></td>
                                <td>
                                    <span style="font-weight: bold; color: var(--secondary-blue);">
                                        <?php echo ($row['prix'] == 0) ? 'Gratuit' : $row['prix'] . ' MAD'; ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="activites_gestion.php?supprimer=<?php echo $row['id']; ?>"
                                        class="action-btn btn-delete"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?');"
                                        title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>