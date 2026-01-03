<?php
require_once 'includes/db.php';

try {
    // 1. Disable Foreign Key Checks to allow truncation (careful!)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 2. Clear existing entries (Optional: depends if we want to keep old data. 
    // Since this is a total redesign, clearing is cleaner to avoid ID collisions).
    $pdo->exec("TRUNCATE TABLE activites");

    // 3. Re-enable Foreign Key Checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // 4. Data to Insert (Matches activites.php static list)
    $activites = [
        [1, 'Kasbah Museum', 'Expositions permanentes et temporaires au cœur de la Médina.', 'https://tse1.mm.bing.net/th/id/OIP.-BDeeNhMk9v-JqOBeKCqpAHaE8', 'Histoire', 20, 100, 'Cons. Musée'],
        [2, 'Villa Harris', 'Musée dédié à l’histoire, à l’art et au patrimoine de Tanger.', 'https://www.vh.ma/wp-content/uploads/2021/03/20210316_141959-scaled.jpg', 'Art', 20, 50, 'Guide'],
        [3, 'Dar Niaba', 'Musée culturel mettant en valeur l’histoire politique.', 'https://static.medias24.com/content/uploads/2022/08/19/musee-Dar-Niaba-2022-08-19-at-15.46.56.jpeg', 'Diplomatie', 0, 30, 'Médiateur'],
        [4, 'Musée Ibn Battûta', 'Petit musée historique consacré au grand voyageur.', 'https://tse4.mm.bing.net/th/id/OIP.UqDoqXg3Ps1jU1bcaeIaywHaE8', 'Histoire', 20, 20, 'Historien'],
        [5, 'Cinémathèque de Tanger', 'Projections de films d’auteur et événements culturels.', 'https://4.bp.blogspot.com/-IaHrfQZD3KM/VFD9SjEi8yI/AAAAAAAAS-8/fgUK3WQzJkw/s1600/A7a.jpg', 'Cinéma', 30, 300, 'Staff'],
        [6, 'Stade Ibn Batouta', 'Terrains de football de proximité ouverts au public.', 'https://th.bing.com/th/id/R.b2cc9faa6c490c948c06e3589ab9315e?rik=fCf5S%2funxPDOVQ&pid=ImgRaw&r=0', 'Sport', 0, 100, 'Coach'],
        [7, 'Sport en Plein Air', 'Marche, course et fitness sur la Corniche.', 'https://www.h24info.ma/wp-content/uploads/2021/06/WhatsApp-Image-2021-06-26-at-15.26.53.jpeg', 'Sport', 0, 1000, 'Libre']
    ];

    // Prepare Insert Statement (Assuming 'categorie_id' exists, setting defaults)
    // We need to check schema. Assuming categories exist. If not, we might need to fix that too.
    // For now, let's just insert with categorie_id = 1 (Assuming 1 exists) or NULL if allowed.
    // Safest bet: Check categories first.
    $stmtCats = $pdo->query("SELECT id FROM categories LIMIT 1");
    $catId = $stmtCats->fetchColumn();
    if (!$catId) {
        $pdo->exec("INSERT INTO categories (nom) VALUES ('Général')");
        $catId = $pdo->lastInsertId();
    }

    $sql = "INSERT INTO activites (id, titre, description, image, date_debut, prix, places_disponibles, formateur, categorie_id, created_at) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);

    foreach ($activites as $act) {
        $stmt->execute([
            $act[0], // id
            $act[1], // titre
            $act[2], // description
            $act[3], // image
            $act[5], // prix
            $act[6], // places
            $act[7], // formateur
            $catId   // categorie_id (generic)
        ]);
    }

    echo "<h1>Succès ! Base de données mise à jour.</h1>";
    echo "<p>Les activités ont été insérées avec les IDs corrects (1-7).</p>";
    echo "<a href='index.php'>Retour au site</a>";

} catch (PDOException $e) {
    echo "<h1>Erreur :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>