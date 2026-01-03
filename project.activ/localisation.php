<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 2rem;">
    <h1 style="color: var(--secondary-color);">Nous Trouver</h1>

    <div style="display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 2rem;">
        <div style="flex: 2; min-width: 300px;">
            <!-- Google Maps Embed -->
            <!-- Intégration d'une iframe Google Maps. -->
            <!-- Les coordonnées 'pb' dans l'URL correspondent à Tanger. -->
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d103604.22822477382!2d-5.892461329242945!3d35.77382216839352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd0b875cf04c132d%3A0x7d819ae70e0c8b0!2sTanger!5e0!3m2!1sfr!2sma!4v1703867000000!5m2!1sfr!2sma"
                width="100%" height="450" style="border:0; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.5);"
                allowfullscreen="" loading="lazy">
            </iframe>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <div
                style="background: var(--card-bg); padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                <h3
                    style="color: var(--secondary-color); border-bottom: 1px solid #333; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                    Comment venir ?</h3>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="color: white;"><i class="fas fa-bus" style="color: var(--secondary-color);"></i> En Bus
                    </h4>
                    <p style="color: #ccc;">Lignes 10, 23, 45 - Arrêt "Place de la Culture".</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="color: white;"><i class="fas fa-subway" style="color: var(--secondary-color);"></i> En
                        Tramway</h4>
                    <p style="color: #ccc;">Ligne T1 - Station "Arts Modernes".</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <h4 style="color: white;"><i class="fas fa-car" style="color: var(--secondary-color);"></i> En
                        Voiture</h4>
                    <p style="color: #ccc;">Parking sous-terrain disponible (gratuit pour les membres).</p>
                </div>

                <hr style="border-color: #333;">

                <h3 style="margin-top: 1.5rem; color: var(--secondary-color);">Horaires d'ouverture</h3>
                <ul style="list-style: none; margin-top: 1rem; color: #ddd;">
                    <li style="margin-bottom: 0.5rem; display: flex; justify-content: space-between;"><span>Lundi -
                            Vendredi :</span> <strong>09h00 - 20h00</strong></li>
                    <li style="margin-bottom: 0.5rem; display: flex; justify-content: space-between;"><span>Samedi
                            :</span> <strong>10h00 - 18h00</strong></li>
                    <li style="margin-bottom: 0.5rem; display: flex; justify-content: space-between;"><span>Dimanche
                            :</span> <strong style="color: #f44336;">Fermé</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>