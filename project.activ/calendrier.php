<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 2rem;">
    <h1 style="color: var(--secondary-color);">Calendrier des Événements</h1>

    <!-- Navigation Mois -->
    <?php
    $month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('m');
    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Calc Prev/Next
    $prev_month = $month - 1;
    $prev_year = $year;
    if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
    }

    $next_month = $month + 1;
    $next_year = $year;
    if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
    }

    // Format Date for Display (French)
    $months_fr = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];
    ?>
    <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: var(--card-bg); padding: 1rem; border-radius: 8px;">
        <a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="btn-outline"
            style="text-decoration: none;">&larr; Mois précédent</a>
        <h2 style="margin: 0; color: var(--secondary-color);"><?php echo $months_fr[$month] . ' ' . $year; ?></h2>
        <a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="btn-outline"
            style="text-decoration: none;">Mois suivant &rarr;</a>
    </div>

    <!-- Calendrier Grid -->
    <style>
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        .calendar-day-header {
            font-weight: bold;
            text-align: center;
            padding: 15px;
            background: #000;
            color: var(--secondary-color);
            border-radius: 4px;
            text-transform: uppercase;
        }

        .calendar-day {
            border: 1px solid #333;
            min-height: 120px;
            padding: 10px;
            background: var(--card-bg);
            position: relative;
            border-radius: 4px;
            transition: 0.3s;
        }

        .calendar-day:hover {
            background: #252525;
            border-color: var(--secondary-color);
        }

        .event-badge {
            display: block;
            background: linear-gradient(45deg, var(--secondary-color), #f7d465);
            color: #000;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>

    <div class="calendar-grid">
        <div class="calendar-day-header">Lun</div>
        <div class="calendar-day-header">Mar</div>
        <div class="calendar-day-header">Mer</div>
        <div class="calendar-day-header">Jeu</div>
        <div class="calendar-day-header">Ven</div>
        <div class="calendar-day-header">Sam</div>
        <div class="calendar-day-header">Dim</div>

        <?php
        // Data Source (Duplicated for static reliability)
        // Data Source (Month-specific)
        $activities_by_month = [
            1 => [ // Janvier
                '5' => ['titre' => 'Kasbah Museum', 'type' => 'Musée'],
                '12' => ['titre' => 'Cinémathèque', 'type' => 'Cinéma'],
                '20' => ['titre' => 'Villa Harris', 'type' => 'Art'],
                '25' => ['titre' => 'Sport Corniche', 'type' => 'Fitness']
            ],
            2 => [ // Février
                '3' => ['titre' => 'Dar Niaba', 'type' => 'Histoire'],
                '14' => ['titre' => 'Stade Ibn Batouta', 'type' => 'Sport'],
                '18' => ['titre' => 'Musée Ibn Battûta', 'type' => 'Culture'],
                '28' => ['titre' => 'Cinémathèque', 'type' => 'Cinéma']
            ]
        ];

        // Select activities for current month, default to empty
        $activities_calendar = isset($activities_by_month[$month]) ? $activities_by_month[$month] : [];

        for ($i = 0; $i < 31; $i++) {
            $day = $i + 1;
            ?>
            <div class="calendar-day">
                <span style="font-weight: bold; color: #555;"><?php echo $day; ?></span>
                <?php
                if (isset($activities_calendar[$day])) {
                    $event = $activities_calendar[$day];
                    echo '<span class="event-badge">' . htmlspecialchars($event['titre']) . '</span>';
                    echo '<a href="activites.php" style="font-size: 0.7rem; color: var(--secondary-color); display:block; margin-top:5px;">+ Infos</a>';
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>

    <div style="margin-top: 2rem;">
        <h3 style="color: var(--secondary-color);">Événements du mois</h3>
        <ul style="list-style: none;">
            <?php foreach ($activities_calendar as $day => $event): ?>
                <li
                    style="background: var(--card-bg); padding: 1.5rem; margin-bottom: 1rem; border-left: 5px solid var(--secondary-color); border-radius: 4px; color: #ddd;">
                    <strong style="color: white; font-size: 1.1rem;"><?php echo $day; ?>     <?php echo $months_fr[$month]; ?>
                        :</strong>
                    <?php echo htmlspecialchars($event['titre']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>