<?php
session_start();
require_once '../includes/db.php'; // Connexion à la base de données MySQL

$error = '';

/**
 * TRAITEMENT DU FORMULAIRE DE CONNEXION
 * Vérifie les identifiants de l'administrateur
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécurisation des entrées
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Vérification que les champs ne sont pas vides
    if (!empty($email) && !empty($password)) {
        // Préparation de la requête SQL pour chercher l'admin par email
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        // Vérification du mot de passe haché (password_verify est crucial pour la sécurité)
        if ($admin && password_verify($password, $admin['password'])) {
            // Création de la session Admin
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];

            // Redirection vers le tableau de bord
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Identifiants incorrects."; // Message d'erreur générique pour sécurité
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CultureArt</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url('https://i.pinimg.com/736x/40/1e/e2/401ee22d3abe5e3349e2cc72bcc0260c.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Outfit', sans-serif;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 3.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            width: 100%;
            max-width: 420px;
            color: white;
            transition: transform 0.3s ease;
        }

        .login-box:hover {
            transform: translateY(-5px);
        }

        .login-box h2 {
            color: #fff;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .login-box i {
            color: var(--accent-terracotta) !important;
            text-shadow: 0 2px 10px rgba(224, 90, 43, 0.3);
        }

        .form-group label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent-terracotta);
            box-shadow: 0 0 10px rgba(224, 90, 43, 0.2);
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .btn {
            background: linear-gradient(135deg, var(--accent-terracotta) 0%, #c14e26 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            border-radius: 50px;
            margin-top: 1.5rem;
            text-transform: uppercase;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(224, 90, 43, 0.4);
        }
    </style>

</head>

<body>

    <div class="login-box">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fas fa-user-shield" style="font-size: 3.5rem; margin-bottom: 1rem;"></i>
            <h2>Espace Admin</h2>
            <p style="color: rgba(255,255,255,0.6); font-size: 0.9rem;">Authentification sécurisée</p>
        </div>

        <?php if ($error): ?>
            <div
                style="background-color: rgba(220, 53, 69, 0.2); color: #ffadad; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(220, 53, 69, 0.3); backdrop-filter: blur(5px);">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@culture.com"
                    required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                    required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">Se connecter</button>
        </form>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="../index.php"
                style="font-size: 0.9rem; color: rgba(255,255,255,0.7); text-decoration: none; border-bottom: 1px dotted rgba(255,255,255,0.4);">&larr;
                Retour au site</a>
        </div>
    </div>

</body>

</html>