<?php
// Vérification stricte : si aucun ID n'est passé dans l'URL, on redirige vers l'accueil.
// Cela empêche d'accéder à la page de confirmation sans contexte.
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$json_file = 'data/inscriptions.json';

// Chargement des données d'inscriptions depuis le fichier JSON
// Si le fichier n'existe pas, on initialise un tableau vide pour éviter les erreurs
$inscriptions = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

// Recherche de l'inscription correspondant à l'ID fourni
$inscription = null;
foreach ($inscriptions as $ins) {
    if ($ins['id'] == $id) {
        $inscription = $ins;
        break; // On arrête la boucle dès qu'on a trouvé, pour la performance
    }
}

// Si l'inscription n'est pas trouvée (cas rare ou ID invalide)
if (!$inscription) {
    echo "<h1>Inscription introuvable (ID: " . htmlspecialchars($id) . ")</h1>";
    exit();
}

// Récupération des données centralisées des activités
// Cela permet de récupérer le prix, qui n'est pas stocké dans l'inscription elle-même
require_once 'includes/activites_data.php';
$prix = 'N/A'; // Valeur par défaut si le prix n'est pas trouvé

// On parcourt les activités pour trouver celle liée à l'inscription
foreach ($activites_data as $act) {
    // Note : Pour les Packs, 'activite_id' vaut 'PACK', donc ça ne correspondra à aucune activité ici
    // Il faudrait idéalement récupérer le prix du pack depuis une source de données dédiée ou le stocker
    // Mais pour l'instant, cela gère les activités standards.
    if ($act['id'] == $inscription['activite_id']) {
        $prix = $act['prix'];
        break;
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 4rem; text-align: center;">
    <div
        style="background: var(--card-bg); padding: 3rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.5); max-width: 700px; margin: 0 auto;">
        <?php
        // Logique d'affichage dynamique basée sur le statut de l'inscription
        // Cela permet de changer l'icône, la couleur et le message selon l'état du dossier.
        $status = $inscription['statut'];

        // Valeurs par défaut (pour le statut "En attente")
        $icon = 'fa-hourglass-half';
        $color = '#ffc107'; // Jaune (Avertissement/Attente)
        $bg_color = '#fff3cd';
        $text_color = '#856404';
        $title = 'Inscription En Attente';
        $message = "votre demande pour l'activité";
        $animation = 'animation: spin 3s infinite linear;'; // Animation de rotation pour le sablier
        
        // Cas : Inscription Confirmée
        if ($status === 'Confirmée' || $status === 'Confirmé') {
            $icon = 'fa-check-circle';
            $color = '#28a745'; // Vert (Succès)
            $bg_color = '#d4edda';
            $text_color = '#155724';
            $title = 'Inscription Confirmée !';
            $message = "votre inscription à l'activité";
            $animation = ''; // Pas d'animation
        }
        // Cas : Inscription Refusée
        elseif ($status === 'Refusée' || $status === 'Refusé') {
            $icon = 'fa-times-circle';
            $color = '#dc3545'; // Rouge (Erreur/Refus)
            $bg_color = '#f8d7da';
            $text_color = '#721c24';
            $title = 'Inscription Refusée';
            $message = "votre demande pour l'activité";
            $animation = '';
        }
        ?>

        <i class="fas <?php echo $icon; ?>"
            style="font-size: 5rem; color: <?php echo $color; ?>; margin-bottom: 2rem; <?php echo $animation; ?>"></i>
        <style>
            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>

        <h1 style="color: var(--text-primary);"><?php echo $title; ?></h1>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; color: var(--text-secondary);">Merci <strong
                style="color: var(--secondary-color);"><?php echo htmlspecialchars($inscription['prenom']); ?></strong>,
            <?php echo $message; ?> <strong
                style="color: var(--secondary-color);"><?php echo htmlspecialchars($inscription['activite_titre']); ?></strong>
            a bien été <?php echo ($status === 'En attente') ? 'reçue' : 'traitée'; ?>.
        </p>

        <div
            style="background: #0d0d0d; padding: 1.5rem; text-align: left; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #333;">
            <p style="color: #ccc; margin-bottom: 8px;"><strong>Numéro de dossier :</strong>
                #<?php echo $inscription['id']; ?></p>
            <p style="color: #ccc; margin-bottom: 8px;"><strong>Date :</strong>
                <?php echo date('d/m/Y H:i', strtotime($inscription['date_inscription'])); ?></p>
            <p style="color: #ccc; margin-bottom: 8px;"><strong>Montant à régler :</strong> <span
                    style="color: var(--secondary-color); font-weight: bold;"><?php echo $prix; ?>
                    MAD</span></p>
            <p style="color: #ccc;"><strong>Statut :</strong>
                <span
                    style="background: <?php echo $color; ?>; color: #fff; padding: 4px 12px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">
                    <?php echo htmlspecialchars($status); ?>
                </span>
            </p>
        </div>

        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">

            <a href="index.php" class="btn">Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>