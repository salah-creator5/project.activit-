<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 4rem;">
    <h1>Activités Culturelles</h1>
    <p style="text-align: center; max-width: 800px; margin: 0 auto 3rem auto;">
        Découvrez la richesse du patrimoine de Tanger à travers nos musées, galeries et espaces culturels emblématiques.
    </p>

    <!-- Filtres (Visual Only for Static) -->
    <!-- Filtres -->
    <div
        style="margin-bottom: 2rem; text-align: center; display: flex; flex-wrap: wrap; justify-content: center; gap: 8px;">
        <?php $cat = $_GET['categorie'] ?? ''; ?>
        <strong style="display: flex; align-items: center; margin-right: 10px;">Catégories : </strong>
        <a href="activites.php" class="btn-outline <?php echo $cat === '' ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Tous</a>
        <a href="activites.php?categorie=Art"
            class="btn-outline <?php echo stripos($cat, 'Art') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Arts</a>
        <a href="activites.php?categorie=Music"
            class="btn-outline <?php echo stripos($cat, 'Music') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Musique</a>
        <a href="activites.php?categorie=Theatre"
            class="btn-outline <?php echo stripos($cat, 'Theatre') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Théâtre</a>
        <a href="activites.php?categorie=Cinema"
            class="btn-outline <?php echo stripos($cat, 'Cinéma') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Cinéma</a>
        <a href="activites.php?categorie=Sport"
            class="btn-outline <?php echo stripos($cat, 'Sport') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Sport</a>
        <a href="activites.php?categorie=Lecture"
            class="btn-outline <?php echo stripos($cat, 'Lecture') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Lecture</a>
        <a href="activites.php?categorie=Histoire"
            class="btn-outline <?php echo stripos($cat, 'Histoire') !== false ? 'active' : ''; ?>"
            style="padding: 6px 16px; font-size: 0.85rem; border-radius: 50px;">Histoire</a>
    </div>

    <!-- Liste des Activités -->
    <div class="grid-3">
        <?php
        // Importation des données centralisées
        // Plutôt que de faire une requête SQL complexe, on charge simplement notre tableau PHP
        require_once 'includes/activites_data.php';
        $activites = $activites_data;


        // Logique de Filtrage
        // On récupère la catégorie depuis l'URL (ex: activites.php?categorie=Art)
        $categorie_filter = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';

        // Si un filtre est actif, on filtre le tableau
        if (!empty($categorie_filter)) {
            $activites = array_filter($activites, function ($item) use ($categorie_filter) {
                // On vérifie si le 'tag' de l'activité contient le mot-clé recherché (insensible à la casse avec stripos)
                return isset($item['tag']) && stripos($item['tag'], $categorie_filter) !== false;
            });
            // Réindexation du tableau pour avoir des clés propres (0, 1, 2...) après le filtrage
            $activites = array_values($activites);
        }

        if (empty($activites)) {
            echo '<p style="text-align: center; grid-column: 1 / -1;">Aucune activité trouvée pour cette catégorie.</p>';
        }

        foreach ($activites as $row) {
            ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($row['image']); ?>"
                    alt="<?php echo htmlspecialchars($row['titre']); ?>"
                    style="width: 100%; height: 250px; object-fit: cover; display: block;"
                    onerror="this.style.display='none';">
                <div class="card-content">
                    <span class="card-tag"><?php echo htmlspecialchars($row['tag']); ?></span>
                    <h3><?php echo htmlspecialchars($row['titre']); ?></h3>

                    <p class="contact-item" style="font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--text-secondary);">
                        <i class="fas fa-map-marker-alt" style="color: var(--tangier-blue); width: 20px;"></i>
                        <?php echo htmlspecialchars($row['lieu']); ?>
                    </p>

                    <p><?php echo htmlspecialchars($row['description']); ?></p>

                    <div class="card-footer">
                        <span class="price-tag"><?php echo htmlspecialchars($row['prix_display']); ?></span>
                        <a href="activite_details.php?id=<?php echo $row['id']; ?>" class="btn-outline"
                            style="font-size: 0.8rem; padding: 8px 20px;">En savoir plus</a>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>