<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Sécurité : Redirection si l'utilisateur n'est pas "connecté" (pas de session valide)
// L'accès au profil est protégé
if (!isset($_SESSION['user_info'])) {
    header('Location: welcome.php');
    exit();
}

// LOGIQUE : Mise à jour de l'Avatar
// Si un formulaire est soumis avec un champ 'avatar'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avatar'])) {
    // Mise à jour de la variable de session directement
    $_SESSION['user_info']['avatar'] = $_POST['avatar'];

    // On recharge la page pour afficher le nouvel avatar immédiatement
    header("Location: profile.php");
    exit();
}

// Récupération des infos utilisateur pour l'affichage
$user = $_SESSION['user_info'];

// Gestion de l'avatar par défaut si aucun n'est défini
$current_avatar = isset($user['avatar']) ? $user['avatar'] : 'fa-user';
?>

<style>
    /* ... existing styles ... */
    .profile-container {
        max-width: 800px;
        margin: 4rem auto;
        padding: 0 20px;
    }

    .profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 91, 181, 0.1);
        overflow: hidden;
        border-top: 5px solid var(--tangier-blue);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--tangier-blue) 0%, #004a94 100%);
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: repeating-linear-gradient(45deg,
                transparent,
                transparent 10px,
                rgba(255, 255, 255, 0.03) 10px,
                rgba(255, 255, 255, 0.03) 20px);
        pointer-events: none;
    }

    .profile-avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--sand-beige) 0%, #f0d9a8 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 5px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .profile-avatar i {
        font-size: 4rem;
        color: var(--tangier-blue);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .edit-avatar-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--accent-terracotta);
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }

    .edit-avatar-btn:hover {
        transform: scale(1.1);
    }

    /* Avatar Modal Styles */
    .avatar-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px);
    }

    .avatar-modal-content {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        text-align: center;
        position: relative;
        border: 1px solid var(--accent-terracotta);
    }

    .avatar-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 1.5rem;
        margin-bottom: 2rem;
    }

    .avatar-option {
        width: 100%;
        aspect-ratio: 1;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }

    .avatar-option:hover {
        background: #e9ecef;
        transform: scale(1.05);
    }

    .avatar-option.selected {
        border-color: var(--accent-terracotta);
        background: rgba(224, 90, 43, 0.1);
    }

    .avatar-option i {
        font-size: 1.5rem;
        color: var(--tangier-blue);
    }

    .profile-name {
        font-family: var(--font-heading);
        font-size: 2rem;
        color: #fff;
        margin-bottom: 0.5rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .profile-subtitle {
        color: var(--sand-beige);
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .profile-body {
        padding: 3rem 2rem;
    }

    .profile-section {
        margin-bottom: 2.5rem;
    }

    .profile-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-family: var(--font-heading);
        font-size: 1.5rem;
        color: var(--tangier-blue);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--sand-beige);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: var(--accent-terracotta);
        font-size: 1.3rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        background: #fafafa;
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid var(--tangier-blue);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 91, 181, 0.1);
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-secondary);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .info-label i {
        color: var(--tangier-blue);
        font-size: 1rem;
    }

    .info-value {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        word-break: break-word;
    }

    .profile-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding-top: 2rem;
        border-top: 1px solid #e8e8e8;
        flex-wrap: wrap;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--tangier-blue) 0%, #004a94 100%);
        color: #fff;
        border: 2px solid var(--tangier-blue);
        box-shadow: 0 4px 12px rgba(0, 91, 181, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 91, 181, 0.4);
        color: #fff;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: #fff;
        border: 2px solid #dc3545;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
        color: #fff;
    }

    .empty-info {
        color: var(--text-secondary);
        font-style: italic;
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 1.5rem;
        }

        .profile-name {
            font-size: 1.5rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
        }

        .profile-avatar i {
            font-size: 3rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-actions {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-container">
                <div class="profile-avatar">
                    <i class="fa-solid <?php echo htmlspecialchars($current_avatar); ?>"></i>
                </div>
                <button class="edit-avatar-btn" onclick="openAvatarModal()" title="Modifier l'avatar">
                    <i class="fas fa-camera" style="font-size: 1rem;"></i>
                </button>
            </div>
            <h1 class="profile-name">
                <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
            </h1>
            <p class="profile-subtitle">Membre Culture Tanger</p>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Personal Information -->
            <div class="profile-section">
                <h2 class="section-title">
                    <i class="fa-solid fa-id-card"></i>
                    Informations Personnelles
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fa-solid fa-user"></i>
                            Nom Complet
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                        </div>
                    </div>

                    <?php if (isset($user['age']) && !empty($user['age'])): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fa-solid fa-cake-candles"></i>
                                Âge
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($user['age']); ?> ans
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contact Information -->
            <?php if ((isset($user['email']) && !empty($user['email'])) || (isset($user['telephone']) && !empty($user['telephone']))): ?>
                <div class="profile-section">
                    <h2 class="section-title">
                        <i class="fa-solid fa-address-book"></i>
                        Coordonnées
                    </h2>
                    <div class="info-grid">
                        <?php if (isset($user['email']) && !empty($user['email'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fa-solid fa-envelope"></i>
                                    Email
                                </div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($user['telephone']) && !empty($user['telephone'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fa-solid fa-phone"></i>
                                    Téléphone
                                </div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($user['telephone']); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="profile-actions">
                <a href="index.php" class="action-btn btn-primary">
                    <i class="fa-solid fa-home"></i>
                    Retour à l'accueil
                </a>
                <a href="logout.php" class="action-btn btn-danger">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Selection Modal -->
<div id="avatarModal" class="avatar-modal">
    <div class="avatar-modal-content">
        <h3 style="color: var(--tangier-blue); margin-bottom: 1rem;">Choisir un Avatar</h3>

        <form method="POST" action="profile.php">
            <div class="avatar-grid">
                <?php
                $avatars = ['fa-user', 'fa-user-tie', 'fa-user-graduate', 'fa-user-astronaut', 'fa-user-ninja', 'fa-user-doctor', 'fa-child', 'fa-smile'];
                foreach ($avatars as $av) {
                    $selected = ($av == $current_avatar) ? 'selected' : '';
                    echo "
                    <label class='avatar-option $selected'>
                        <input type='radio' name='avatar' value='$av' style='display:none;' " . ($selected ? 'checked' : '') . " onclick='selectAvatar(this)'>
                        <i class='fa-solid $av'></i>
                    </label>";
                }
                ?>
            </div>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn-outline" onclick="closeAvatarModal()"
                    style="padding: 8px 20px;">Annuler</button>
                <button type="submit" class="btn" style="padding: 8px 20px;">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAvatarModal() {
        document.getElementById('avatarModal').style.display = 'flex';
    }

    function closeAvatarModal() {
        document.getElementById('avatarModal').style.display = 'none';
    }

    function selectAvatar(input) {
        document.querySelectorAll('.avatar-option').forEach(opt => opt.classList.remove('selected'));
        input.parentElement.classList.add('selected');
    }

    // Close modal if clicked outside
    window.onclick = function (event) {
        if (event.target == document.getElementById('avatarModal')) {
            closeAvatarModal();
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>