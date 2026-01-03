<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Redirection si non connecté
if (!isset($_SESSION['user_info']['email'])) {
    header("Location: welcome.php");
    exit();
}

$user_email = $_SESSION['user_info']['email'];
$prenom = htmlspecialchars($_SESSION['user_info']['prenom']);

// Chargement et filtrage des réservations
$json_file = 'data/inscriptions.json';
$my_reservations = [];

if (file_exists($json_file)) {
    $all_inscriptions = json_decode(file_get_contents($json_file), true) ?: [];

    // Filtrage par email utilisateur (insensible à la casse et aux espaces)
    $my_reservations = array_filter($all_inscriptions, function ($ins) use ($user_email) {
        // On normalise les deux emails
        $email_ins = isset($ins['email']) ? strtolower(trim($ins['email'])) : '';
        $email_session = strtolower(trim($user_email));

        return $email_ins === $email_session;
    });

    // Tri par date décroissante
    usort($my_reservations, function ($a, $b) {
        return strtotime($b['date_inscription']) - strtotime($a['date_inscription']);
    });
}
?>

<div class="container" style="padding-top: 6rem; min-height: 60vh;">
    <h1
        style="color: var(--tangier-blue); margin-bottom: 2rem; border-left: 5px solid var(--accent-terracotta); padding-left: 15px;">
        <i class="fas fa-clipboard-list"></i> Mes Réservations
    </h1>
    <p style="color: #666; margin-bottom: 2rem;">
        Compte actuel : <strong><?php echo htmlspecialchars($user_email); ?></strong>
    </p>

    <!-- SECTION : AVANTAGES ACTIFS (PACKS) -->
    <?php
    require_once 'includes/packs_data.php';

    // On cherche les packs CONFIRMÉS pour cet utilisateur
    $active_packs = [];
    foreach ($my_reservations as $res) {
        if ($res['activite_id'] === 'PACK' && $res['statut'] === 'Confirmée') {
            // On extrait le nom du pack (format "Pack : Nom du Pack")
            $pack_name_raw = str_replace('Pack : ', '', $res['activite_titre']);

            // On cherche les détails dans notre fichier de données
            foreach ($packs_data as $p_data) {
                if ($p_data['title'] === $pack_name_raw) {
                    $active_packs[] = $p_data;
                }
            }
        }
    }

    if (!empty($active_packs)): ?>
        <div
            style="background: linear-gradient(135deg, #E05A2B 0%, #d44d1f 100%); color: white; padding: 2rem; border-radius: 15px; margin-bottom: 3rem; box-shadow: 0 10px 20px rgba(224, 90, 43, 0.2);">
            <h2 style="font-size: 1.5rem; margin-bottom: 1.5rem; color: white;"><i class="fas fa-crown"></i> Vos Avantages
                Actifs</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <?php foreach ($active_packs as $pack): ?>
                    <div
                        style="background: rgba(255,255,255,0.15); padding: 1.5rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                        <h3 style="font-size: 1.2rem; margin-bottom: 1rem; color: white;">
                            <i class="fas <?php echo $pack['icon']; ?>"></i> <?php echo $pack['title']; ?>
                        </h3>
                        <ul style="list-style: none; padding: 0;">
                            <?php foreach ($pack['features'] as $feature): ?>
                                <li style="margin-bottom: 0.5rem; font-size: 0.95rem;">
                                    <i class="fas fa-check-circle" style="margin-right: 8px; color: #fff;"></i>
                                    <?php echo $feature; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($my_reservations)): ?>
        <div
            style="background: white; padding: 3rem; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
            <p style="color: #666; font-size: 1.1rem;">Vous n'avez pas encore effectué de réservation.</p>
            <a href="activites.php" class="btn" style="margin-top: 1.5rem;">Découvrir les activités</a>
        </div>
    <?php else: ?>
        <div
            style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid var(--border-color);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <tr>
                        <th style="padding: 15px; text-align: left; color: var(--text-secondary);">Activité / Pack</th>
                        <th style="padding: 15px; text-align: left; color: var(--text-secondary);">Date demande</th>
                        <th style="padding: 15px; text-align: left; color: var(--text-secondary);">Statut</th>
                        <th style="padding: 15px; text-align: right; color: var(--text-secondary);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($my_reservations as $res): ?>
                        <?php
                        // Style du badge
                        $badge_style = "background: #fff3cd; color: #856404;"; // Jaune (En attente)
                        $icon_status = "fa-hourglass-half";
                        $is_confirmed = false;

                        if (in_array($res['statut'], ['Confirmée', 'Confirmé'])) {
                            $badge_style = "background: #d4edda; color: #155724;"; // Vert
                            $icon_status = "fa-check-circle";
                            $is_confirmed = true;
                        } elseif (in_array($res['statut'], ['Refusée', 'Refusé'])) {
                            $badge_style = "background: #f8d7da; color: #721c24;"; // Rouge
                            $icon_status = "fa-times-circle";
                        }
                        ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: 500;">
                                <?php echo htmlspecialchars($res['activite_titre']); ?>
                            </td>
                            <td style="padding: 15px; color: #666;">
                                <?php echo date('d/m/Y', strtotime($res['date_inscription'])); ?>
                            </td>
                            <td style="padding: 15px;">
                                <span
                                    style="<?php echo $badge_style; ?> padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas <?php echo $icon_status; ?>"></i>
                                    <?php echo htmlspecialchars($res['statut']); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <?php if ($is_confirmed): ?>
                                    <?php 
                                    $is_pack = (isset($res['activite_id']) && $res['activite_id'] === 'PACK');
                                    $btn_label = $is_pack ? "Carte" : "Ticket";
                                    $btn_icon = $is_pack ? "fa-id-card" : "fa-ticket-alt";
                                    $btn_color = $is_pack ? "#E05A2B" : "var(--secondary-color)"; // Terracotta for cards
                                    ?>
                                    <a href="ticket.php?id=<?php echo $res['id']; ?>" class="btn" style="padding: 5px 15px; font-size: 0.85rem; background: <?php echo $btn_color; ?>;">
                                        <i class="fas <?php echo $btn_icon; ?>"></i> <?php echo $btn_label; ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #ccc; font-size: 0.85rem;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>