<?php
// DONNÉES CENTRALISÉES DES PACKS
// Ce fichier permet de partager la définition des packs entre la page des offres
// et la page de profil/réservations pour afficher les avantages actifs.

$packs_data = [
    [
        'title' => 'Pack Étudiant',
        'price' => '150',
        'period' => '/ mois',
        'icon' => 'fa-user-graduate',
        'desc' => 'Idéal pour les jeunes assoiffés de culture.',
        'features' => [
            'Accès illimité à la bibliothèque',
            '50% de réduction sur les ateliers',
            'Entrée gratuite au Cinéma Rif',
            'Wi-Fi gratuit espace cowork'
        ],
        'vip' => false
    ],
    [
        'title' => 'Pack Famille',
        'price' => '400',
        'period' => '/ mois',
        'icon' => 'fa-users',
        'desc' => 'Pour des moments inoubliables en famille.',
        'features' => [
            'Accès pour 2 adultes + 2 enfants',
            'Priorité places théâtre',
            '1 atelier gratuit par enfant/mois',
            'Réductions boutique souvenir'
        ],
        'vip' => false
    ],
    [
        'title' => 'Pass Culture VIP',
        'price' => '800',
        'period' => '/ an',
        'icon' => 'fa-crown',
        'desc' => 'L\'expérience culturelle ultime.',
        'features' => [
            'Accès prioritaire à TOUS les événements',
            'Invitations privées aux vernissages',
            'Rencontres artistes (Meet & Greet)',
            'Place de parking réservée'
        ],
        'vip' => true
    ]
];
?>