<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<?php
$promo_message = '';
$promo_discount = 0;

// Traitement du formulaire si la méthode est POST et que 'promo_code' est défini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['promo_code'])) {

    // Nettoyage et mise en majuscules du code saisi par l'utilisateur
    $code = strtoupper(htmlspecialchars($_POST['promo_code']));
    $promo_discount = 0;

    // Tentative 1 : Vérifier le code dans la Base de Données (Si elle est connectée)
    try {
        // Préparation de la requête SQL pour chercher le code s'il n'est pas expiré
        // CURDATE() retourne la date actuelle de la base de données
        $stmt = $pdo->prepare("SELECT * FROM promo_codes WHERE code = ? AND expiration_date >= CURDATE()");
        $stmt->execute([$code]);
        $promo = $stmt->fetch();

        // Si un code est trouvé
        if ($promo) {
            $promo_discount = $promo['pourcentage'];
            $promo_message = "Code valide ! Vous bénéficiez de -{$promo_discount}% sur votre prochain pack.";
        }
    } catch (PDOException $e) {
        // En cas d'erreur (ex: table inexistante), on capture l'exception mais on continue
        // Cela permet au site de ne pas planter si la DB n'est pas prête
    }

    // Tentative 2 : Fallback Statique (Si la DB échoue ou ne trouve rien)
    if ($promo_discount === 0) {
        // Vérification manuelle d'un code "en dur" dans le code PHP
        if ($code === "CULTURE2024") {
            $promo_message = "Code valide ! Vous bénéficiez de -10% sur votre prochain pack.";
            $promo_discount = 10;
        } else {
            $promo_message = "Code promo invalide ou expiré.";
        }
    }
}
?>

<style>
    .offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .offer-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .offer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(10, 35, 66, 0.15);
        border-color: var(--sand-beige);
    }

    /* VIP Card styling */
    .offer-card.vip {
        background: linear-gradient(145deg, #1a1a1a, #2c2c2c);
        color: #fff;
        border: 1px solid var(--sand-beige);
    }

    .offer-card.vip h3,
    .offer-card.vip p,
    .offer-card.vip .price {
        color: #fff !important;
    }

    .offer-card.vip .icon-box {
        background: rgba(255, 255, 255, 0.1);
        color: var(--sand-beige);
    }

    .icon-box {
        width: 80px;
        height: 80px;
        background: #f0f7ff;
        color: var(--tangier-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }

    .offer-card:hover .icon-box {
        background: var(--tangier-blue);
        color: #fff;
    }

    .price {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--tangier-blue);
        margin: 1.5rem 0;
        font-family: 'Playfair Display', serif;
    }

    .price span {
        font-size: 1rem;
        font-weight: 400;
        color: #777;
    }

    .features-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
        text-align: left;
    }

    .features-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        color: #555;
    }

    .offer-card.vip .features-list li {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #ccc;
    }

    .features-list li i {
        color: var(--sand-beige);
        margin-right: 10px;
        width: 20px;
    }
</style>

<div class="offers-grid">
    <?php
    require_once 'includes/packs_data.php';

    foreach ($packs_data as $pack) {
        $isVip = $pack['vip'] ? 'vip' : '';
        echo '
            <div class="offer-card ' . $isVip . '">
                <div class="icon-box">
                    <i class="fas ' . $pack['icon'] . '"></i>
                </div>
                <h3>' . $pack['title'] . '</h3>
                <p>' . $pack['desc'] . '</p>
                <div class="price">' . $pack['price'] . ' MAD <span>' . $pack['period'] . '</span></div>
                <ul class="features-list">';
        foreach ($pack['features'] as $feature) {
            echo '<li><i class="fas fa-check"></i> ' . $feature . '</li>';
        }
        echo '</ul>
                <a href="inscription_pack.php?pack=' . urlencode($pack['title']) . '" class="btn" style="' . ($pack['vip'] ? "background: var(--sand-beige); border-color: var(--sand-beige); color: #fff;" : "") . '">Choisir ce pack</a>
            </div>';
    }
    ?>
</div>

<!-- Promo Code Section -->
<div style="max-width: 600px; margin: 5rem auto; text-align: center; position: relative;">
    <div
        style="background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); border: 1px solid var(--border-color);">
        <h3 style="color: var(--tangier-blue); margin-bottom: 1rem;"><i class="fas fa-ticket-alt"></i> Code Promo</h3>
        <p style="margin-bottom: 2rem; color: #666;">Vous avez un code ? Entrez-le ci-dessous pour vérifier votre
            réduction.</p>

        <?php if ($promo_message): ?>
            <div style="background: <?php echo $promo_discount > 0 ? 'rgba(76, 175, 80, 0.1)' : 'rgba(244, 67, 54, 0.1)'; ?>; 
                            color: <?php echo $promo_discount > 0 ? '#2e7d32' : '#c62828'; ?>; 
                            padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
                <?php echo $promo_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="offres.php" style="display: flex; gap: 10px; max-width: 400px; margin: 0 auto;">
            <input type="text" name="promo_code" class="form-control" placeholder="Ex: CULTURE2024" required
                style="text-transform: uppercase;">
            <button type="submit" class="btn" style="white-space: nowrap;">Vérifier</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>