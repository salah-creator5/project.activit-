<?php
// Cette page est le point d'entrée pour les nouveaux visiteurs.
// Elle collecte leurs informations de base avant de leur donner accès au site.

require_once 'includes/db.php'; // (Optionnel si pas d'usage DB ici, mais souvent inclus par habitude)

// Traitement du formulaire de bienvenue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sécurisation des données entrantes avec htmlspecialchars pour éviter les failles XSS
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $age = htmlspecialchars($_POST['age']);

    // Si les champs obligatoires sont remplis
    if (!empty($nom) && !empty($prenom)) {
        $email = htmlspecialchars($_POST['email']);

        // Validation stricte : l'email doit se terminer par @gmail.com
        if (!empty($email) && !str_ends_with($email, '@gmail.com')) {
            echo "<script>alert('Attention : Seules les adresses Gmail (@gmail.com) sont acceptées.'); window.history.back();</script>";
            exit();
        }

        // Stockage des informations dans la SESSION PHP
        // Cela permet de garder ces infos accessibles sur toutes les pages du site tant que le navigateur est ouvert
        $_SESSION['user_info'] = [
            'nom' => $nom,
            'prenom' => $prenom,
            'age' => $age,
            'email' => $email,
            'telephone' => htmlspecialchars($_POST['telephone'])
        ];

        // Redirection vers la page d'accueil principale
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Ministère de la Culture Tanger</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            /* Subtle geometric pattern */
            overflow: hidden;
            color: #333;
        }

        .welcome-gate {
            background: #fff;
            padding: 4rem;
            text-align: center;
            max-width: 600px;
            width: 90%;
            border-top: 5px solid var(--tangier-blue);
            box-shadow: 0 10px 40px rgba(0, 91, 181, 0.1);
            border-radius: 8px;
            /* Clean radius */
            position: relative;
            animation: moveUp 1s ease-out;
        }

        .welcome-logo {
            font-family: var(--font-heading);
            font-size: 2.2rem;
            color: var(--tangier-blue);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .welcome-sub {
            font-family: var(--font-body);
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-bottom: 3rem;
            border-bottom: 2px solid var(--sand-beige);
            display: inline-block;
            padding-bottom: 10px;
        }

        .input-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .input-line {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            width: 100%;
            padding: 15px;
            font-family: var(--font-body);
            font-size: 1rem;
            transition: 0.3s;
        }

        .input-line:focus {
            outline: none;
            border-color: var(--tangier-blue);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 91, 181, 0.1);
        }

        .enter-btn {
            background-color: var(--tangier-blue);
            color: #fff;
            border: none;
            padding: 15px 50px;
            font-family: var(--font-body);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 1rem;
            border-radius: 4px;
            width: 100%;
        }

        .enter-btn:hover {
            background-color: #004a94;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes moveUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Arch Decorative Element */
        .arch-decor {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 30px;
            background: var(--sand-beige);
            border-radius: 30px 30px 0 0;
            z-index: -1;
        }
    </style>
</head>

<body>

    <div class="welcome-gate">
        <div class="arch-decor"></div>
        <div class="welcome-logo">Ministère de la Culture</div>
        <div class="welcome-sub">Direction Régionale de Tanger</div>

        <form method="POST">
            <div class="input-group">
                <input type="text" name="nom" class="input-line" placeholder="Votre Nom" required autocomplete="off">
            </div>
            <div class="input-group">
                <input type="text" name="prenom" class="input-line" placeholder="Votre Prénom" required
                    autocomplete="off">
            </div>
            <div class="input-group">
                <input type="number" name="age" class="input-line" placeholder="Votre Âge" required min="5" max="120">
            </div>
            <div class="input-group">
                <input type="email" name="email" class="input-line" placeholder="Votre Email" required
                    autocomplete="off">
            </div>
            <div class="input-group">
                <input type="tel" name="telephone" class="input-line" placeholder="Votre Numéro de Téléphone" required
                    autocomplete="off">
            </div>

            <button type="submit" class="enter-btn">Accéder au Portail</button>
        </form>
    </div>

</body>

</html>