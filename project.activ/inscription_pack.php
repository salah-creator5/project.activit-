<?php
// Initialisation des variables
$error = '';
// Récupère le nom du pack depuis l'URL si disponible
$pack_name = isset($_GET['pack']) ? htmlspecialchars($_GET['pack']) : '';

// Traitement du formulaire lors de la soumission (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération sécurisée des données du formulaire
    $pack_name = htmlspecialchars($_POST['pack_name']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $paiement_effectue = isset($_POST['paiement']) ? true : false; // Case à cocher paiement

    // Validation des champs obligatoires
    if (!empty($pack_name) && !empty($nom) && !empty($prenom) && !empty($email)) {

        // --- VALIDATIONS SUPPLÉMENTAIRES ---

        // 1. Validation Email Gmail
        if (!str_ends_with($email, '@gmail.com')) {
            $error = "L'adresse email doit être une adresse Gmail (@gmail.com).";
        }
        // 2. Validation Carte Bancaire (Si paiement coché)
        elseif ($paiement_effectue) {
            $card_number = isset($_POST['card_number']) ? str_replace(' ', '', $_POST['card_number']) : '';
            if (!preg_match('/^\d{16}$/', $card_number)) {
                $error = "Le numéro de carte bancaire doit contenir exactement 16 chiffres.";
            }
        }

        if (empty($error)) {
            // Construction de l'objet inscription pour le Pack
            $new_inscription = [
                'id' => uniqid(), // ID unique
                'activite_id' => 'PACK', // ID spécial pour distinguer les packs des activités
                'activite_titre' => 'Pack : ' . $pack_name, // Nom complet du pack
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'paiement_en_ligne' => $paiement_effectue ? 'Oui' : 'Non',
                'date_inscription' => date('Y-m-d H:i:s'),
                'statut' => 'En attente'
            ];

            // Définition du fichier de stockage
            $json_file = 'data/inscriptions.json';

            // Lecture du fichier JSON existant
            $current_data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];
            if (!is_array($current_data))
                $current_data = [];

            // Ajout de la nouvelle inscription
            $current_data[] = $new_inscription;

            // Sauvegarde dans le fichier JSON
            if (file_put_contents($json_file, json_encode($current_data, JSON_PRETTY_PRINT))) {
                // Redirection vers la confirmation en cas de succès
                header("Location: confirmation.php?id=" . $new_inscription['id']);
                exit();
            } else {
                $error = "Erreur lors de l'enregistrement. Veuillez réessayer.";
            }
        }

    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 6rem;">
    <h1 style="text-align: center; color: var(--accent-gold);">Souscription Pack</h1>
    <p style="text-align: center; color: #666; margin-bottom: 2rem;">Vous avez choisi le <strong>Pack
            <?php echo $pack_name; ?></strong></p>

    <div class="centered-form-card">
        <?php if ($error): ?>
            <div class="alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="inscription_pack.php">

            <input type="hidden" name="pack_name" value="<?php echo $pack_name; ?>">

            <div class="form-group">
                <label for="display_pack">Pack Sélectionné</label>
                <input type="text" id="display_pack" class="form-control" value="<?php echo $pack_name; ?>" disabled
                    style="background: #e9ecef; color: #495057;">
            </div>

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" class="form-control"
                    value="<?php echo isset($_SESSION['user_info']['nom']) ? htmlspecialchars($_SESSION['user_info']['nom']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" class="form-control"
                    value="<?php echo isset($_SESSION['user_info']['prenom']) ? htmlspecialchars($_SESSION['user_info']['prenom']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="<?php echo isset($_SESSION['user_info']['email']) ? htmlspecialchars($_SESSION['user_info']['email']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" class="form-control">
            </div>

            <div class="form-group"
                style="background: rgba(212, 175, 55, 0.1); padding: 1rem; border-radius: 5px; margin-top: 1.5rem; border-left: 3px solid var(--secondary-color);">
                <label
                    style="color: white; font-weight: normal; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <span><input type="checkbox" name="paiement" value="1"> Payer maintenant (Optionnel)</span>
                    <span style="font-size: 1.5rem; color: #fff;">
                        <i class="fab fa-cc-visa" style="margin-right: 5px;"></i>
                        <i class="fab fa-cc-mastercard" style="margin-right: 5px;"></i>
                        <i class="fas fa-credit-card"></i>
                    </span>
                </label>
                <p style="font-size: 0.8rem; color: #aaa; margin-top: 5px;">Le paiement en ligne est sécurisé. Vous
                    pouvez aussi payer sur place.</p>

                <div id="card-info"
                    style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(0,0,0,0.1);">
                    <div class="form-group">
                        <label for="card_number">Numéro de carte</label>
                        <input type="text" id="card_number" name="card_number" class="form-control"
                            placeholder="XXXX XXXX XXXX XXXX">
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label for="expiry">Date d'expiration</label>
                            <input type="text" id="expiry" name="expiry" class="form-control" placeholder="MM/AA">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="cvc">CVC</label>
                            <input type="text" id="cvc" name="cvc" class="form-control" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.querySelector('input[name="paiement"]').addEventListener('change', function () {
                    const cardInfo = document.getElementById('card-info');
                    if (this.checked) {
                        cardInfo.style.display = 'block';
                        cardInfo.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        cardInfo.style.display = 'none';
                    }
                });
            </script>

            <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Valider la souscription</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>