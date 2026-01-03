<?php
// Initialisation de la variable d'erreur pour stocker les messages de problème
$error = '';

// Vérifie si un ID d'activité est passé dans l'URL (GET) et le stocke, sinon vide
$activite_id = isset($_GET['activite']) ? $_GET['activite'] : '';

// Inclusion du fichier contenant les données des activités (centralisé)
require_once 'includes/activites_data.php';

// Fonction pour récupérer le titre d'une activité à partir de son ID
function getActivityTitle($id, $activites_data)
{
    // Parcourt toutes les activités disponibles
    foreach ($activites_data as $act) {
        // Si l'ID correspond, on retourne le titre
        if ($act['id'] == $id)
            return $act['titre'];
    }
    // Si aucune activité n'est trouvée, on retourne un message par défaut
    return 'Activité Inconnue';
}

// Vérifie si le formulaire a été soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et sécurisation des données envoyées par le formulaire
    $activite_id = $_POST['activite_id'];
    $nom = htmlspecialchars($_POST['nom']); // htmlspecialchars protège contre les failles XSS
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $paiement_effectue = isset($_POST['paiement']) ? true : false; // Vérifie si la case paiement est cochée

    // Vérification que les champs obligatoires sont remplis
    if (!empty($activite_id) && !empty($nom) && !empty($prenom) && !empty($email)) {

        // --- VALIDATIONS SUPPLÉMENTAIRES ---

        // 1. Validation Email Gmail
        if (!str_ends_with($email, '@gmail.com')) {
            $error = "L'adresse email doit être une adresse Gmail (@gmail.com).";
        }
        // 2. Validation Carte Bancaire (Si paiement coché)
        elseif ($paiement_effectue) {
            $card_number = isset($_POST['card_number']) ? str_replace(' ', '', $_POST['card_number']) : '';
            // Regex : ^\d{16}$ vérifie qu'il y a exactement 16 chiffres
            if (!preg_match('/^\d{16}$/', $card_number)) {
                $error = "Le numéro de carte bancaire doit contenir exactement 16 chiffres.";
            }
        }

        if (empty($error)) {
            // Création du tableau de la nouvelle inscription
            $new_inscription = [
                'id' => uniqid(), // Génère un identifiant unique
                'activite_id' => $activite_id,
                'activite_titre' => getActivityTitle($activite_id, $activites_data),
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'paiement_en_ligne' => $paiement_effectue ? 'Oui' : 'Non', // Enregistre si le paiement a été initié
                'date_inscription' => date('Y-m-d H:i:s'), // Date et heure actuelles
                'statut' => 'En attente' // Statut par défaut
            ];

            // Chemin du fichier JSON où sont stockées les inscriptions
            $json_file = 'data/inscriptions.json';

            // Chargement des données existantes si le fichier existe
            $current_data = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

            // Si le fichier est vide ou corrompu, on initialise un tableau vide
            if (!is_array($current_data))
                $current_data = [];

            // Ajout de la nouvelle inscription au tableau existant
            $current_data[] = $new_inscription;

            // Sauvegarde du tableau mis à jour dans le fichier JSON (formaté proprement avec PRETTY_PRINT)
            if (file_put_contents($json_file, json_encode($current_data, JSON_PRETTY_PRINT))) {
                // Redirection vers la page de confirmation avec l'ID de l'inscription
                header("Location: confirmation.php?id=" . $new_inscription['id']);
                exit(); // Arrête l'exécution du script après la redirection
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
    <h1 style="text-align: center; color: var(--accent-gold);">Inscription à une Activité</h1>

    <div class="centered-form-card">
        <?php if ($error): ?>
            <div class="alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="inscription.php">
            <div class="form-group">
                <label for="activite_id">Activité ou Pack choisi *</label>
                <?php
                $pack_param = isset($_GET['pack']) ? htmlspecialchars($_GET['pack']) : '';
                ?>
                <select id="activite_id" name="activite_id" class="form-control" required>
                    <option value="" style="color: #666;">-- Sélectionnez une activité ou un pack --</option>
                    <?php
                    // Use centralized data
                    require_once 'includes/activites_data.php';
                    $static_activites = $activites_data;

                    if ($pack_param) {
                        echo "<option value='PACK_" . $pack_param . "' selected style='color: #1a1a1a; font-weight:bold;'>Pack : " . $pack_param . "</option>";
                        echo "<option disabled>────────────────</option>";
                    }

                    foreach ($static_activites as $row) {
                        $selected = ($row['id'] == $activite_id && !$pack_param) ? 'selected' : '';
                        echo "<option value='" . $row['id'] . "' $selected style='color: #1a1a1a;'>" . htmlspecialchars($row['titre']) . "</option>";
                    }
                    ?>
                </select>
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
                    style="color: var(--tangier-blue); font-weight: 600; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <span><input type="checkbox" name="paiement" value="1"> Payer maintenant (Optionnel)</span>
                    <span style="font-size: 1.5rem; color: var(--tangier-blue);">
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

            <!-- PRICE SUMMARY SECTION -->
            <div id="price-summary"
                style="display:none; background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin: 1.5rem 0; border: 1px solid #e9ecef;">
                <h4 style="margin-top: 0; color: var(--tangier-blue);">Résumé du tarif</h4>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Prix standard :</span>
                    <span id="price-standard" style="font-weight: bold;">-</span>
                </div>
                <div id="row-discount"
                    style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #28a745; display: none;">
                    <span>Réduction (<span id="discount-label"></span>) :</span>
                    <span id="price-discount">-</span>
                </div>
                <hr style="margin: 0.5rem 0;">
                <div
                    style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; color: var(--primary-color);">
                    <span>Total à payer :</span>
                    <span id="price-final">-</span>
                </div>
            </div>

            <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Valider l'inscription</button>
        </form>

        <?php
        // --- LOGIQUE PHP : RÉCUPÉRATION DES PACKS ACTIFS ---
        $active_packs_js = [];
        if (isset($_SESSION['user_info']['email'])) {
            $u_email = strtolower(trim($_SESSION['user_info']['email']));
            $json_file = 'data/inscriptions.json';
            if (file_exists($json_file)) {
                $all_ins = json_decode(file_get_contents($json_file), true) ?: [];
                foreach ($all_ins as $ins) {
                    // Check ownership + status + is a pack
                    if (
                        isset($ins['email']) && strtolower(trim($ins['email'])) === $u_email &&
                        $ins['statut'] === 'Confirmée' &&
                        strpos($ins['activite_titre'], 'Pack :') !== false
                    ) {
                        // Extract pack name (e.g. "Pack Étudiant")
                        $p_name = str_replace('Pack : ', '', $ins['activite_titre']);
                        $active_packs_js[] = $p_name;
                    }
                }
            }
        }

        // Prepare Activity Data for JS (ID => Price)
        $activities_js_data = [];
        foreach ($static_activites as $act) {
            $activities_js_data[$act['id']] = [
                'price' => is_numeric($act['prix']) ? $act['prix'] : 0,
                'title' => $act['titre']
            ];
        }
        ?>

        <script>
            // Data injected from PHP
            const userPacks = <?php echo json_encode($active_packs_js); ?>;
            const activities = <?php echo json_encode($activities_js_data); ?>;

            const selectActivity = document.getElementById('activite_id');
            const summaryDiv = document.getElementById('price-summary');
            const elStandard = document.getElementById('price-standard');
            const elDiscountRow = document.getElementById('row-discount');
            const elDiscountLabel = document.getElementById('discount-label');
            const elDiscountPrice = document.getElementById('price-discount');
            const elFinal = document.getElementById('price-final');

            selectActivity.addEventListener('change', function () {
                const val = this.value;

                // Reset
                summaryDiv.style.display = 'none';
                elDiscountRow.style.display = 'none';

                // Check if it's a real activity (not empty, not purely a PACK selection from URL if handled differently)
                // Note: The select values are IDs (1, 2...) OR "PACK_Name"

                if (val && activities[val]) {
                    const act = activities[val];
                    let price = parseFloat(act.price);
                    let finalPrice = price;
                    let discountName = '';
                    let discountAmount = 0;

                    // --- RULES ENGINE ---

                    // Rule 1: Student Pack -> 50% off
                    // We assume "Atelier" check is simulated by just checking confirmed Student Pack for now 
                    // or applying to all paid activities if the user wants generic "benefits"
                    if (userPacks.includes('Pack Étudiant')) {
                        discountName = 'Pack Étudiant';
                        discountAmount = price * 0.50;
                        finalPrice = price - discountAmount;
                    }

                    // Rule 2: VIP -> 100% off? (Example)
                    if (userPacks.includes('Pass Culture VIP')) {
                        discountName = 'Pass Culture VIP';
                        discountAmount = price * 1.00; // Free
                        finalPrice = 0;
                    }

                    // Rule 3: Family -> Logic implies "Child" check, but let's say 20% global for simplicity
                    // unless caught by previous rules. Priority matters.
                    if (!discountName && userPacks.includes('Pack Famille')) {
                        discountName = 'Pack Famille';
                        discountAmount = price * 0.20;
                        finalPrice = price - discountAmount;
                    }


                    // --- DISPLAY ---
                    if (price > 0) {
                        summaryDiv.style.display = 'block';
                        elStandard.textContent = price.toFixed(2) + ' MAD';

                        if (discountAmount > 0) {
                            elDiscountRow.style.display = 'flex';
                            elDiscountLabel.textContent = discountName;
                            elDiscountPrice.textContent = '-' + discountAmount.toFixed(2) + ' MAD';
                        }

                        elFinal.textContent = finalPrice.toFixed(2) + ' MAD';
                    }
                }
            });

            // Trigger on load if selected
            if (selectActivity.value) {
                selectActivity.dispatchEvent(new Event('change'));
            }

            // Existing toggle logic for payment
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
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>