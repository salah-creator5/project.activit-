<?php require_once 'includes/db.php'; ?>
<?php
// Vérification stricte de l'ID passé en URL
// Doit exister et être un nombre, sinon : redirection
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: activites.php");
    exit();
}

$id = $_GET['id'];

// Chargement de la source de données unique
require_once 'includes/activites_data.php';
$static_activites = $activites_data;

// Recherche de l'activité spécifique dans le tableau
// C'est l'équivalent PHP d'un "SELECT * FROM activites WHERE id = $id"
$activite = null;
foreach ($static_activites as $item) {
    if ($item['id'] == $id) {
        $activite = $item;
        break; // Trouvé ! On arrête de chercher.
    }
}

// Si l'ID ne correspond à aucune activité (ex: ID=9999), on redirige
if (!$activite) {
    header("Location: activites.php");
    exit();
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 2rem;">
    <a href="activites.php" style="color: var(--secondary-color); margin-bottom: 1rem; display: inline-block;">&larr;
        Retour aux activités</a>

    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <div style="flex: 2; min-width: 300px;">
            <img src="<?php echo htmlspecialchars($activite['image']); ?>"
                alt="<?php echo htmlspecialchars($activite['titre']); ?>"
                style="width: 100%; border-radius: 8px; margin-bottom: 2rem; border: 1px solid var(--border-color); object-fit: cover; height: 400px;"
                onerror="this.src='https://via.placeholder.com/800x400'">

            <h1 style="color: var(--tangier-blue);"><?php echo htmlspecialchars($activite['titre']); ?></h1>
            <span class="card-tag"><?php echo htmlspecialchars($activite['categorie_nom']); ?></span>

            <div style="margin-top: 2rem;">
                <h3>Description</h3>
                <p style="color: var(--text-secondary);">
                    <?php echo nl2br(htmlspecialchars($activite['description'])); ?>
                </p>
            </div>


        </div>

        <div style="flex: 1; min-width: 300px;">
            <div
                style="background: var(--card-bg); padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.5); position: sticky; top: 100px;">
                <h3
                    style="margin-bottom: 1.5rem; color: var(--tangier-blue); border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                    Détails Pratiques</h3>

                <p style="margin-bottom: 1rem; color: var(--text-secondary);"><strong><i
                            class="fas fa-chalkboard-teacher" style="color: var(--tangier-blue);"></i> Formateur
                        :</strong><br>
                    <span
                        style="color: var(--text-primary); font-weight: 500;"><?php echo htmlspecialchars($activite['formateur']); ?></span>
                </p>

                <p style="margin-bottom: 1rem; color: var(--text-secondary);"><strong><i class="fas fa-layer-group"
                            style="color: var(--tangier-blue);"></i> Niveau :</strong><br>
                    <span
                        style="color: var(--text-primary); font-weight: 500;"><?php echo htmlspecialchars($activite['niveau']); ?></span>
                </p>

                <p style="margin-bottom: 1rem; color: var(--text-secondary);"><strong><i class="fas fa-users"
                            style="color: var(--tangier-blue);"></i> Places restantes :</strong><br>
                    <span
                        style="color: var(--text-primary); font-weight: 500;"><?php echo $activite['places_disponibles']; ?></span>
                </p>

                <p style="margin-bottom: 1rem; color: var(--text-secondary);"><strong><i class="fas fa-calendar-alt"
                            style="color: var(--tangier-blue);"></i> Date début :</strong><br>
                    <span
                        style="color: var(--text-primary); font-weight: 500;"><?php echo date('d/m/Y H:i', strtotime($activite['date_debut'])); ?></span>
                </p>

                <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid var(--border-color);">

                <div style="font-size: 2.5rem; font-weight: bold; color: var(--tangier-blue); margin-bottom: 1.5rem;">
                    <?php echo $activite['prix_display']; ?>
                </div>

                <a href="inscription.php?activite=<?php echo $activite['id']; ?>" class="btn"
                    style="width: 100%; text-align: center;"><i class="fa-solid fa-ticket"></i> Réserver</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>