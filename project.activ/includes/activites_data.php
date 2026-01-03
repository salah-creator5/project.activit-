<?php
// =========================================================================
// DONNÉES CENTRALISÉES DES ACTIVITÉS
// =========================================================================
// Ce fichier agit comme une "base de données" statique. 
// Il contient un tableau associatif multidimensionnel stockant toutes les informations
// relatives aux activités proposées. Cette approche remplace une base de données MySQL
// pour simplifier le déploiement ou pour des projets statiques.
//
// Chaque élément du tableau principal représente une activité unique.

$activites_data = [
    [
        'id' => 1,
        'titre' => 'Kasbah Museum',
        'description' => "Expositions permanentes et temporaires au cœur de la Médina. Une plongée fascinante dans l'histoire de la région.\n\nCe musée, situé dans l'ancien palais du Sultan, offre une collection unique d'objets archéologiques, ethnographiques et artistiques retracant l'histoire de Tanger et du Nord du Maroc.",
        'lieu' => 'Kasbah (Médina)',
        'prix' => 20, // Prix utilisé pour les calculs internes
        'prix_display' => '20–30 MAD', // Affichage formatté pour l'utilisateur
        'image' => 'https://tse1.mm.bing.net/th/id/OIP.-BDeeNhMk9v-JqOBeKCqpAHaE8', // URL de l'image (peut être locale ou distante)
        'tag' => 'Arts & Histoire', // Tag court pour badges
        'categorie_nom' => 'Musée & Histoire', // Catégorie affichée
        'formateur' => 'Conservateur du Musée',
        'niveau' => 'Tout Public',
        'places_disponibles' => 'Illimité',
        'date_debut' => date('Y-m-d 09:00:00') // Génère la date du jour à 09h00 (exemple statique)
    ],
    [
        'id' => 2,
        'titre' => 'Villa Harris',
        'description' => "Musée dédié à l’histoire, à l’art et au patrimoine de Tanger dans un cadre moderne et un parc magnifique.\n\nLa Villa Harris, joyau architectural néo-mauresque, a été restaurée pour accueillir des expositions d'art moderne et contemporain, mettant en lumière les artistes ayant séjourné à Tanger.",
        'lieu' => 'Parc Villa Harris',
        'prix' => 20,
        'prix_display' => '20 MAD',
        'image' => 'https://www.vh.ma/wp-content/uploads/2021/03/20210316_141959-scaled.jpg',
        'tag' => 'Art & Patrimoine',
        'categorie_nom' => 'Art & Patrimoine',
        'formateur' => 'Guide Conférencier',
        'niveau' => 'Tout Public',
        'places_disponibles' => '50 / jour',
        'date_debut' => date('Y-m-d 10:00:00')
    ],
    [
        'id' => 3,
        'titre' => 'Dar Niaba',
        'description' => "Musée culturel mettant en valeur l’histoire politique et diplomatique de Tanger.\n\nDécouvrez les archives et les objets qui témoignent du rôle diplomatique unique de Tanger à travers les siècles. Le bâtiment lui-même est un chef-d'œuvre de l'architecture traditionnelle.",
        'lieu' => 'Rue Sekka – Médina',
        'prix' => 0,
        'prix_display' => 'Gratuit / 20 MAD',
        'image' => 'https://static.medias24.com/content/uploads/2022/08/19/musee-Dar-Niaba-2022-08-19-at-15.46.56.jpeg',
        'tag' => 'Diplomatie',
        'categorie_nom' => 'Diplomatie',
        'formateur' => 'Médiateur Culturel',
        'niveau' => 'Passionnés',
        'places_disponibles' => '30 / visite',
        'date_debut' => date('Y-m-d 09:30:00')
    ],
    [
        'id' => 4,
        'titre' => 'Musée Ibn Battûta',
        'description' => "Petit musée historique consacré au grand voyageur Ibn Battûta et à l’héritage culturel de Tanger.\n\nSuivez les traces du plus grand voyageur du monde musulman, né à Tanger. Cartes, récits de voyage et objets d'époque vous transportent au 14ème siècle.",
        'lieu' => 'Kasbah',
        'prix' => 20,
        'prix_display' => '20–50 MAD',
        'image' => 'https://tse4.mm.bing.net/th/id/OIP.UqDoqXg3Ps1jU1bcaeIaywHaE8',
        'tag' => 'Histoire',
        'categorie_nom' => 'Histoire',
        'formateur' => 'Historien Local',
        'niveau' => 'Tout Public',
        'places_disponibles' => '20 / groupe',
        'date_debut' => date('Y-m-d 10:00:00')
    ],
    [
        'id' => 5,
        'titre' => 'Cinémathèque de Tanger',
        'description' => "Projections films d’auteur, festivals et événements culturels au légendaire Cinéma Rif.\n\nPlus qu'un cinéma, c'est un lieu de vie, de débats et de culture au cœur du Grand Socco. Programmation riche et variée.",
        'lieu' => 'Grand Socco',
        'prix' => 15,
        'prix_display' => '15–30 MAD',
        'image' => 'https://4.bp.blogspot.com/-IaHrfQZD3KM/VFD9SjEi8yI/AAAAAAAAS-8/fgUK3WQzJkw/s1600/A7a.jpg',
        'tag' => 'Cinéma & Art',
        'categorie_nom' => 'Cinéma & Art',
        'formateur' => 'Programmateur',
        'niveau' => 'Cinéphiles',
        'places_disponibles' => '300 places',
        'date_debut' => date('Y-m-d 19:00:00')
    ],
    [
        'id' => 6,
        'titre' => 'Stade Ibn Batouta',
        'description' => "Terrains de football de proximité ouverts au public pour la pratique libre dans un esprit communautaire.\n\nVenez profiter des infrastructures sportives de qualité mises à disposition de la jeunesse tangéroise.",
        'lieu' => 'Stade Ibn Batouta',
        'prix' => 0,
        'prix_display' => 'Gratuit',
        'image' => 'https://th.bing.com/th/id/R.b2cc9faa6c490c948c06e3589ab9315e?rik=fCf5S%2funxPDOVQ&pid=ImgRaw&r=0',
        'tag' => 'Sport',
        'categorie_nom' => 'Sport',
        'formateur' => 'Coachs Bénévoles',
        'niveau' => 'Tous Niveaux',
        'places_disponibles' => 'Sur réservation',
        'date_debut' => date('Y-m-d 08:00:00')
    ],
    [
        'id' => 7,
        'titre' => 'Sport en Plein Air',
        'description' => "Marche, course et fitness sur la Corniche. Activité physique accessible à tous dans un cadre public.\n\nProfitez de l'air marin et de la vue imprenable sur le détroit pour vos séances de sport quotidiennes.",
        'lieu' => 'Corniche de Tanger',
        'prix' => 0,
        'prix_display' => 'Gratuit',
        'image' => 'https://www.h24info.ma/wp-content/uploads/2021/06/WhatsApp-Image-2021-06-26-at-15.26.53.jpeg',
        'tag' => 'Sport & Bien-être',
        'categorie_nom' => 'Sport & Bien-être',
        'formateur' => 'Accès Libre',
        'niveau' => 'Tous Niveaux',
        'places_disponibles' => 'Illimité',
        'date_debut' => date('Y-m-d 06:00:00')
    ],
    [
        'id' => 8,
        'titre' => 'Concert Andalou',
        'description' => "Soirée de musique andalouse traditionnelle avec l'orchestre local.\n\nVivez une soirée inoubliable au rythme des mélodies andalouses, un patrimoine musical ancestral qui fait la fierté de Tanger.",
        'lieu' => 'Palais Moulay Hafid',
        'prix' => 50,
        'prix_display' => '50 MAD',
        'image' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?q=80&w=2070',
        'tag' => 'Music',
        'categorie_nom' => 'Musique',
        'formateur' => 'Orchestre Andalou',
        'niveau' => 'Tout Public',
        'places_disponibles' => '200 places',
        'date_debut' => date('Y-m-d 20:30:00')
    ],

    [
        'id' => 10,
        'titre' => 'Club de Lecture',
        'description' => "Rencontre mensuelle des passionnés de littérature autour d'un thé à la menthe.\n\nPartagez vos coups de cœur littéraires et échangez avec d'autres passionnés dans une ambiance conviviale et chaleureuse.",
        'lieu' => 'Librairie Les Insolites',
        'prix' => 0,
        'prix_display' => 'Gratuit',
        'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=2073',
        'tag' => 'Lecture',
        'categorie_nom' => 'Littérature',
        'formateur' => 'Animateur Littéraire',
        'niveau' => 'Lecteurs',
        'places_disponibles' => '15 personnes',
        'date_debut' => date('Y-m-d 16:00:00')
    ]
];
?>