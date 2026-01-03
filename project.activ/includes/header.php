<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to welcome.php if not logged in and not on welcome/admin pages
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user_info']) && $current_page !== 'welcome.php' && strpos($_SERVER['REQUEST_URI'], '/admin/') === false) {
    header('Location: welcome.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre Culturel</title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <p>Culture Tanger — Explorez et vivez la culture à Tanger.</p>

                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <nav>
                    <div class="branding">
                        <a href="index.php" class="logo-text">
                            <i class="fas fa-landmark"></i> Culture Tanger
                        </a>
                    </div>
                    <ul class="nav-links">
                        <li><a href="index.php">Accueil</a></li>
                        <li class="dropdown">
                            <a href="activites.php" class="dropdown-toggle">Catégories <i
                                    class="fa-solid fa-chevron-down arrow-down"></i></a>
                            <div class="dropdown-content">
                                <a href="activites.php?categorie=Music">Musique</a>
                                <a href="activites.php?categorie=Theatre">Théâtre</a>
                                <a href="activites.php?categorie=Cinema">Cinéma</a>
                                <a href="activites.php?categorie=Sport">Sport</a>
                                <a href="activites.php?categorie=Arts">Arts</a>
                                <a href="activites.php?categorie=Lecture">Lecture</a>
                            </div>
                        </li>
                        <li><a href="calendrier.php">Calendrier</a></li>
                        <li><a href="offres.php">Offres</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                    <div class="nav-actions">
                        <?php if (isset($_SESSION['user_info'])): ?>
                            <a href="mes_reservations.php" class="icon-link" title="Mes Réservations">
                                <i class="fas fa-clipboard-list"></i>
                            </a>
                            <a href="profile.php" class="icon-link" title="Mon Profil">
                                <i class="fa-regular fa-user"></i>
                            </a>
                        <?php elseif (isset($_SESSION['admin_id'])): ?>
                            <a href="admin/dashboard.php" class="icon-link" title="Administration">
                                <i class="fa-regular fa-user-circle"></i>
                            </a>
                        <?php else: ?>
                            <a href="welcome.php" class="icon-link" title="Connexion">
                                <i class="fa-regular fa-user"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user_info'])): ?>
                            <a href="logout.php" class="icon-link" title="Déconnexion">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </a>
                        <?php elseif (isset($_SESSION['admin_id'])): ?>
                            <a href="admin/logout.php" class="icon-link" title="Déconnexion">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </a>
                        <?php endif; ?>


                        <a href="inscription.php" class="btn btn-sm">
                            <i class="fa-solid fa-ticket"></i> Réserver
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>