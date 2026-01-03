<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 6rem;">
    <h1 style="text-align: center;">Galerie Multimédia</h1>

    <!-- Filtres (Supprimés car déplacés dans le header) -->

    <div class="grid-3" id="gallery-grid">
        <!-- Images Hardcoded for visual, ideally from DB -->
        <div class="card gallery-item" data-category="Ateliers">
            <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?q=80&w=2070&auto=format&fit=crop"
                alt="Atelier Peinture" style="height: 250px; object-fit: cover;">
            <div class="card-content">
                <h3>Atelier Peinture</h3>
                <span class="card-tag">Ateliers</span>
            </div>
        </div>
        <div class="card gallery-item" data-category="Concerts">
            <img src="https://images.unsplash.com/photo-1511192336575-5a79af67a629?q=80&w=2070&auto=format&fit=crop"
                alt="Concert Jazz" style="height: 250px; object-fit: cover;">
            <div class="card-content">
                <h3>Concert Jazz</h3>
                <span class="card-tag">Concerts</span>
            </div>
        </div>
        <div class="card gallery-item" data-category="Théâtre">
            <img src="https://images.unsplash.com/photo-1507676184212-d033912996c7?q=80&w=2069&auto=format&fit=crop"
                alt="Représentation Molière" style="height: 250px; object-fit: cover;">
            <div class="card-content">
                <h3>Représentation Molière</h3>
                <span class="card-tag">Théâtre</span>
            </div>
        </div>
        <div class="card gallery-item" data-category="Expositions">
            <img src="https://images.unsplash.com/photo-1531243269054-5ebf6f34081e?q=80&w=2070&auto=format&fit=crop"
                alt="Expo Modern Art" style="height: 250px; object-fit: cover;">
            <div class="card-content">
                <h3>Expo Modern Art</h3>
                <span class="card-tag">Expositions</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction simple de filtrage côté client (JavaScript)
    // Contrairement à PHP, cela se fait sans recharger la page
    function filterGallery(category) {
        // Sélection de tous les éléments de la galerie
        const items = document.querySelectorAll('.gallery-item');

        items.forEach(item => {
            // Si 'all' est sélectionné OU si la catégorie correspond : on affiche
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
                // Petite animation CSS pour rendre l'apparition plus fluide
                item.style.animation = 'fadeIn 0.5s ease forwards';
            } else {
                // Sinon on cache
                item.style.display = 'none';
            }
        });
    }
</script>

<?php require_once 'includes/footer.php'; ?>