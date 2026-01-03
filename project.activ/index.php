<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<?php
$prenom = htmlspecialchars($_SESSION['user_info']['prenom'] ?? 'Visiteur');
?>
<section class="hero">
    <div class="hero-content">
        <h1>Bienvenue, <?php echo $prenom; ?></h1>
        <p>Ministère de la Culture - Direction Régionale de Tanger</p>
        <p style="font-size: 1rem; margin-top: -1rem; color: var(--accent-gold);">Découvrez le patrimoine et la création
            artistique du Nord.</p>



        <div class="hero-actions">
            <a href="activites.php" class="btn">Voir les Activités</a>
            <a href="inscription.php" class="btn-outline">S'inscrire</a>
        </div>
    </div>
</section>

<div class="container">
    <?php
    // (Cette section a été déplacée vers mes_reservations.php pour plus de clarté)
    ?>
    <!-- Intro -->
    <section class="section-intro">
        <h2>La Culture à Tanger</h2>
        <p>
            Carrefour des civilisations et porte de l'Afrique, Tanger vibre au rythme de l'art.
            La Direction Régionale de la Culture s'engage à promouvoir le patrimoine et la créativité locale.
        </p>
    </section>

    <!-- Featured Activities -->
    <section>
        <h2>Activités en Vedette</h2>
        <div class="grid-3">
            <?php
            // Affichage des activités "En Vedette"
            // Ici, nous utilisons un tableau statique pour contrôler exactement ce qui s'affiche sur la page d'accueil.
            // Dans une version plus avancée, on pourrait faire une requête SQL : "SELECT * FROM activites WHERE featured = 1 LIMIT 3"
            $featured_activites = [
                [
                    'id' => 1,
                    'titre' => 'Kasbah Museum',
                    'description' => 'Expositions permanentes au cœur de la Médina.',
                    'image' => 'https://tse1.mm.bing.net/th/id/OIP.-BDeeNhMk9v-JqOBeKCqpAHaE8',
                    'tag' => 'Histoire',
                    'prix' => '20 MAD'
                ],
                [
                    'id' => 2,
                    'titre' => 'Villa Harris',
                    'description' => 'Musée dédié à l’art et au patrimoine dans un cadre moderne.',
                    'image' => 'https://www.vh.ma/wp-content/uploads/2021/03/20210316_141959-scaled.jpg',
                    'tag' => 'Art',
                    'prix' => '20 MAD'
                ],
                [
                    'id' => 5,
                    'titre' => 'Cinémathèque de Tanger',
                    'description' => 'Projections films d’auteur au légendaire Cinéma Rif.',
                    'image' => 'https://4.bp.blogspot.com/-IaHrfQZD3KM/VFD9SjEi8yI/AAAAAAAAS-8/fgUK3WQzJkw/s1600/A7a.jpg',
                    'tag' => 'Cinéma',
                    'prix' => '30 MAD'
                ]
            ];

            // Boucle d'affichage pour générer le HTML de chaque carte
            foreach ($featured_activites as $row) {
                ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>"
                        alt="<?php echo htmlspecialchars($row['titre']); ?>" style="height: 250px; object-fit: cover;">
                    <div class="card-content">
                        <span class="card-tag">
                            <?php echo htmlspecialchars($row['tag']); ?>
                        </span>
                        <h3><?php echo htmlspecialchars($row['titre']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="card-footer">
                            <span class="price-tag"><?php echo $row['prix']; ?></span>
                            <a href="activites.php" class="btn-outline"
                                style="color: var(--tangier-blue); border-color: var(--tangier-blue);">Voir détails</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="actions-center">
            <a href="activites.php" class="btn">Voir toutes les activités</a>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section-dark" style="background-color: #f9f9f9; color: #333;">
        <h2 style="color: var(--tangier-blue);">Témoignages</h2>
        <div class="grid-3">
            <div class="testimonial-card">
                <p>"J'ai découvert une vraie passion pour la peinture grâce à l'atelier du samedi. Merci !"</p>
                <div class="testimonial-author">- Sarah B.</div>
            </div>
            <div class="testimonial-card">
                <p>"L'ambiance est géniale et les profs sont très pédagogues. Je recommande le théâtre."</p>
                <div class="testimonial-author">- Ahmed K.</div>
            </div>
            <div class="testimonial-card">
                <p>"Mes enfants adorent les cours de danse. Un lieu incontournable."</p>
                <div class="testimonial-author">- Fatima Z.</div>
            </div>
        </div>
    </section>

    <!-- Quick Access -->
    <section class="section-dark">
        <h2>Accès Rapide</h2>
        <div class="quick-access">
            <a href="activites.php" class="btn-outline">Activités</a>
            <a href="calendrier.php" class="btn-outline">Calendrier</a>
            <a href="galerie.php" class="btn-outline">Galerie</a>
            <a href="contact.php" class="btn-outline">Contact</a>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>