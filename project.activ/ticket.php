<?php
require_once 'includes/db.php';

// Vérification de la session
if (!isset($_SESSION['user_info']['email'])) {
    header("Location: welcome.php");
    exit();
}

$user_email = strtolower(trim($_SESSION['user_info']['email']));
$reservation_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$reservation_id) {
    die("ID de réservation manquant.");
}

// Chargement des données JSON
$json_file = 'data/inscriptions.json';
if (!file_exists($json_file)) {
    die("Erreur système : fichier de données introuvable.");
}

$inscriptions = json_decode(file_get_contents($json_file), true);
$reservation = null;

foreach ($inscriptions as $ins) {
    if ($ins['id'] === $reservation_id) {
        $reservation = $ins;
        break;
    }
}

if (!$reservation) {
    die("Réservation introuvable.");
}

$res_email = strtolower(trim($reservation['email']));
if ($res_email !== $user_email) {
    die("Accès non autorisé : cette réservation ne vous appartient pas.");
}

if ($reservation['statut'] !== 'Confirmée') {
    die("Le document n'est disponible que pour les réservations confirmées.");
}

$is_pack = ($reservation['activite_id'] === 'PACK');
$doc_title = $is_pack ? "Carte Membre" : "Billet";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $doc_title; ?> - Culture Tanger</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,600;1,600&family=Share+Tech+Mono&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #E05A2B;
            --secondary-color: #003366;
            --gold: #D4AF37;
            --bg-color: #f4f6f9;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .controls {
            margin-bottom: 2rem;
            display: flex;
            gap: 15px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-print {
            background: var(--primary-color);
        }

        .btn-back {
            background: #6c757d;
        }

        /* --- STYLES TICKET (Classique) --- */
        .ticket-container {
            background: white;
            width: 100%;
            max-width: 800px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            position: relative;
        }

        .ticket-left {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #001f3f 100%);
            color: white;
            width: 250px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            border-right: 2px dashed rgba(255, 255, 255, 0.3);
        }

        .ticket-right {
            flex: 1;
            padding: 2rem;
        }

        /* --- STYLES CARTE (Pack - Modern Cultural) --- */
        .card-container {
            width: 500px;
            height: 300px;
            /* Default fallback bg */
            background: linear-gradient(135deg, #E05A2B 0%, #B54018 100%);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            color: white;
            padding: 0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s;
        }

        /* Pack Specific Themes */
        .theme-student {
             background: linear-gradient(135deg, #E05A2B 0%, #B54018 100%); /* Terracotta (Standard) */
        }
        .theme-family {
             background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); /* Teal/Green for Family/Growth */
        }
        .theme-vip {
             background: linear-gradient(135deg, #1c1c1c 0%, #3a3a3a 100%); /* Premium Black */
             border: 1px solid rgba(212, 175, 55, 0.3);
        }
        .theme-vip .card-title, .theme-vip .card-badge {
             color: #D4AF37; /* Gold Text */
             border-color: #D4AF37;
        }
        .theme-vip .card-pattern {
             opacity: 0.15;
        }

        /* Decorative Pattern */
        .card-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle at 20% 120%, rgba(255,255,255,0.1) 10%, transparent 10.5%),
                              radial-gradient(circle at 80% -20%, rgba(255,255,255,0.1) 15%, transparent 15.5%);
            pointer-events: none;
        }

        .card-content {
            padding: 2.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-logo {
            display: flex; 
            align-items: center; 
            gap: 10px;
        }
        .card-logo i { font-size: 2rem; }
        .card-logo span { 
            font-family: 'Playfair Display', serif; 
            font-size: 1.2rem; 
            font-weight: 600; 
        }

        .card-badge {
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin: 1rem 0;
            line-height: 1.1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: end;
        }

        .card-label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .card-value {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-id {
            position: absolute;
            bottom: 1.5rem;
            right: 2rem;
            text-align: right;
            opacity: 0.6;
            font-family: monospace;
            font-size: 0.9rem;
        }

        @media print {
            body { 
                background: white; 
                padding: 0;
            }
            .controls { display: none; }
            .ticket-container, .card-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="controls">
        <a href="mes_reservations.php" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>

    <?php if ($is_pack): ?>
        <?php
        // Détermination du thème en fonction du titre
        $pack_title_clean = str_replace('Pack : ', '', $reservation['activite_titre']);
        $theme_class = 'theme-student'; // Default

        if (stripos($pack_title_clean, 'Famille') !== false) {
            $theme_class = 'theme-family';
        } elseif (stripos($pack_title_clean, 'VIP') !== false) {
            $theme_class = 'theme-vip';
        }
        ?>
        <!-- --- VUE CARTE (Nouveau Design) --- -->
        <div class="card-container <?php echo $theme_class; ?>">
            <div class="card-pattern"></div>
            
            <div class="card-content">
                <div class="card-header">
                    <div class="card-logo">
                        <i class="fas fa-landmark"></i>
                        <span>Culture Tanger</span>
                    </div>
                    <div class="card-badge">Membre</div>
                </div>

                <div class="card-title">
                    <?php echo htmlspecialchars(str_replace('Pack : ', '', $reservation['activite_titre'])); ?>
                </div>

                <div class="card-footer">
                    <div>
                        <span class="card-label">Titulaire</span>
                        <span class="card-value"><?php echo htmlspecialchars($reservation['nom'] . ' ' . $reservation['prenom']); ?></span>
                    </div>
                    <div>
                         <span class="card-label">Valable jusqu'au</span>
                         <span class="card-value">31/12/2026</span>
                    </div>
                </div>
            </div>
            
            <div class="card-id">
                ID: <?php echo substr($reservation['id'], 0, 8); ?>
            </div>
        </div>

    <?php else: ?>
        <!-- --- VUE TICKET (Classique) --- -->
        <div class="ticket-container">
            <div class="ticket-left">
                <div style="text-align: center;">
                    <i class="fas fa-landmark" style="font-size: 3rem; margin-bottom: 10px;"></i>
                    <div style="font-family: 'Playfair Display'; font-size: 1.2rem;">Culture Tanger</div>
                </div>
                <div style="text-align: center;">
                    <div style="opacity: 0.7; font-size: 0.8rem;">DATE</div>
                    <div style="font-size: 1.2rem; font-weight: bold;">
                        <?php echo date('d M Y', strtotime($reservation['date_inscription'])); ?>
                    </div>
                </div>
                <div style="text-align: center; opacity: 0.6; font-family: monospace;">
                    #<?php echo substr($reservation['id'], 0, 8); ?>
                </div>
            </div>
            <div class="ticket-right">
                <h1
                    style="font-family: 'Playfair Display'; font-size: 1.8rem; color: var(--secondary-color); margin-top: 0; margin-bottom: 0.5rem; line-height: 1.2;">
                    <?php echo htmlspecialchars($reservation['activite_titre']); ?>
                </h1>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 1rem 0;">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span
                            style="display: block; font-size: 0.8rem; color: #888; text-transform: uppercase;">Bénéficiaire</span>
                        <span
                            style="font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($reservation['nom'] . ' ' . $reservation['prenom']); ?></span>
                    </div>
                    <div>
                        <span
                            style="display: block; font-size: 0.8rem; color: #888; text-transform: uppercase;">Statut</span>
                        <span style="color: #155724; font-weight: bold;"><i class="fas fa-check-circle"></i> Confirmé</span>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <div
                        style="height: 50px; background: repeating-linear-gradient(90deg, #000, #000 2px, #fff 2px, #fff 4px); width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>