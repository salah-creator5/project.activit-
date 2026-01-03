<?php require_once 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<?php
// Variables pour gérer l'état du formulaire
$message_sent = false;
$error_message = '';

// Traitement du formulaire de contact (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des données pour éviter les failles XSS
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $message_text = htmlspecialchars($_POST['message']);

    // Validation simple : tous les champs doivent être remplis
    if (!empty($nom) && !empty($email) && !empty($message_text)) {

        // Validation Email (Gmail uniquement)
        if (!str_ends_with($email, '@gmail.com')) {
            $error_message = "Veuillez utiliser une adresse @gmail.com valide.";
        } else {
            // Définition du chemin vers le dossier 'data'
            // __DIR__ retourne le dossier actuel du script
            $data_dir = __DIR__ . '/data';

            // Création du dossier 'data' s'il n'existe pas (droits 0777 pour être sûr de pouvoir écrire)
            if (!file_exists($data_dir)) {
                mkdir($data_dir, 0777, true);
            }

            // Fichier JSON pour stocker les messages
            $messages_file = $data_dir . '/messages.json';

            // Chargement des messages existants pour ne pas les écraser
            $messages = [];
            if (file_exists($messages_file)) {
                $json_content = file_get_contents($messages_file);
                $messages = json_decode($json_content, true) ?: []; // Tableau vide si échec du décodage
            }

            // Construction du nouveau message
            $new_message = [
                'id' => uniqid(), // ID unique généré par PHP
                'nom' => $nom,
                'email' => $email,
                'message' => $message_text,
                'date' => date('Y-m-d H:i:s'), // Date et heure actuelles
                'lu' => false // Statut par défaut : non lu
            ];

            // Ajout du message au début du tableau (le plus récent en premier)
            array_unshift($messages, $new_message);

            // Sauvegarde dans le fichier JSON avec indentation (PRETTY_PRINT) pour la lisibilité
            if (file_put_contents($messages_file, json_encode($messages, JSON_PRETTY_PRINT))) {
                $message_sent = true; // Succès !
            } else {
                $error_message = "Erreur lors de l'envoi du message. Veuillez réessayer.";
            }
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}
?>

<div class="container" style="padding-top: 6rem;">
    <h1 style="text-align: center;">Contactez-nous</h1>

    <div class="contact-layout">
        <!-- Infos -->
        <div class="contact-info">
            <div class="info-card">
                <h3>Direction Régionale - Tanger</h3>
                <p class="contact-item"><i class="fas fa-map-marker-alt"></i> 52, Rue de Belgique, Tanger, Maroc</p>
                <p class="contact-item"><i class="fas fa-phone"></i> +212 539 99 99 99</p>
                <p class="contact-item"><i class="fas fa-envelope"></i> contact@culture-tanger.ma</p>
                <p class="contact-item"><i class="fab fa-whatsapp"></i> +212 699 999 999</p>

                <h3 style="margin-top: 2rem;">Horaires d'ouverture</h3>
                <p>Lundi - Vendredi : 09h00 - 20h00</p>
                <p>Samedi : 10h00 - 18h00</p>
                <p>Dimanche : Fermé</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="contact-form">
            <?php if ($message_sent): ?>
                <div class="alert-success">
                    Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.
                </div>
            <?php elseif ($error_message): ?>
                <div class="alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="contact.php" class="form-card">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="6" required></textarea>
                </div>
                <button type="submit" class="btn">Envoyer le message</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>