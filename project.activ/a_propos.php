<?php require_once 'includes/header.php'; ?>

<div class="container" style="padding-top: 4rem; padding-bottom: 4rem;">
    <!-- Hero Section -->
    <div style="text-align: center; margin-bottom: 4rem;">
        <h1
            style="font-family: 'Playfair Display', serif; color: var(--primary-color); font-size: 3rem; margin-bottom: 1rem;">
            À Propos de <span style="color: var(--secondary-color);">Culture Tanger</span>
        </h1>
        <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto; color: #666;">
            Au cœur de la ville du Détroit, nous œuvrons pour la préservation et le rayonnement du patrimoine culturel
            tangérois.
        </p>
    </div>

    <!-- Main Content -->
    <div style="display: flex; gap: 4rem; align-items: center; flex-wrap: wrap; margin-bottom: 5rem;">
        <div style="flex: 1; min-width: 300px;">
            <div style="position: relative;">
                <!-- Decorative border -->
                <div
                    style="position: absolute; top: -15px; left: -15px; width: 100%; height: 100%; border: 2px solid var(--secondary-color); border-radius: 10px; z-index: 0;">
                </div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Tanger_Kasbah.jpg/800px-Tanger_Kasbah.jpg"
                    alt="Kasbah de Tanger"
                    style="width: 100%; border-radius: 10px; position: relative; z-index: 1; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            </div>
        </div>
        <div style="flex: 1; min-width: 300px;">
            <h2 style="font-family: 'Playfair Display', serif; color: var(--primary-color); margin-bottom: 1.5rem;">
                Notre Mission</h2>
            <p style="margin-bottom: 1.5rem; line-height: 1.8;">
                Fondée avec la vision de célébrer la richesse historique de Tanger, notre plateforme sert de pont entre
                le passé glorieux de la ville internationale et son avenir dynamique. Nous rassemblons les artistes, les
                historiens et les citoyens passionnés.
            </p>
            <p style="margin-bottom: 2rem; line-height: 1.8;">
                Du théâtre Cervantes aux grottes d'Hercule, en passant par les festivals de Jazz et de musique
                andalouse, <strong>Culture Tanger</strong> centralise l'accès à l'art et au savoir pour tous les
                Marocains et visiteurs.
            </p>

            <h3
                style="font-family: 'Playfair Display', serif; color: var(--secondary-color); font-size: 1.3rem; margin-bottom: 1rem;">
                Nos Engagements</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 0.8rem;"><i class="fas fa-star"
                        style="color: var(--secondary-color); margin-right: 10px;"></i> Valorisation du patrimoine local
                </li>
                <li style="margin-bottom: 0.8rem;"><i class="fas fa-star"
                        style="color: var(--secondary-color); margin-right: 10px;"></i> Accessibilité numérique des
                    événements</li>
                <li style="margin-bottom: 0.8rem;"><i class="fas fa-star"
                        style="color: var(--secondary-color); margin-right: 10px;"></i> Soutien aux jeunes talents
                    tangérois</li>
            </ul>
        </div>
    </div>

    <!-- Team Section -->
    <div style="background-color: #f9f9f9; padding: 3rem; border-radius: 20px;">
        <h2
            style="text-align: center; font-family: 'Playfair Display', serif; color: var(--primary-color); margin-bottom: 3rem;">
            L'Équipe de Direction
        </h2>
        <div class="grid-3" style="text-align: center; gap: 2rem;">
            <!-- Director -->
            <div class="card" style="padding: 2rem; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div
                    style="width: 120px; height: 120px; margin: 0 auto 1.5rem; border-radius: 50%; overflow: hidden; border: 3px solid var(--secondary-color);">
                    <img src="https://ui-avatars.com/api/?name=Karim+Benjelloun&background=0D1B2A&color=fff"
                        alt="Directeur" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">M. Karim Benjelloun</h3>
                <p style="color: var(--secondary-color); font-weight: 600; font-size: 0.9rem;">Directeur Général</p>
                <p style="font-size: 0.9rem; color: #777; margin-top: 1rem;">Expert en patrimoine et ancien
                    conservateur, il veille à la vision stratégique de la plateforme.</p>
            </div>

            <!-- Program Manager -->
            <div class="card" style="padding: 2rem; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div
                    style="width: 120px; height: 120px; margin: 0 auto 1.5rem; border-radius: 50%; overflow: hidden; border: 3px solid var(--secondary-color);">
                    <img src="https://ui-avatars.com/api/?name=Laila+Amrani&background=E05A2B&color=fff"
                        alt="Responsable Culture" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Mme. Laila Amrani</h3>
                <p style="color: var(--secondary-color); font-weight: 600; font-size: 0.9rem;">Responsable Programmation
                </p>
                <p style="font-size: 0.9rem; color: #777; margin-top: 1rem;">Coordinatrice des événements artistiques et
                    des partenariats avec les musées.</p>
            </div>

            <!-- Tech Lead -->
            <div class="card" style="padding: 2rem; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <div
                    style="width: 120px; height: 120px; margin: 0 auto 1.5rem; border-radius: 50%; overflow: hidden; border: 3px solid var(--secondary-color);">
                    <img src="https://ui-avatars.com/api/?name=Youssef+El+Idrissi&background=0D1B2A&color=fff"
                        alt="Chef de Projet" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">M. Youssef El Idrissi</h3>
                <p style="color: var(--secondary-color); font-weight: 600; font-size: 0.9rem;">Responsable Technique</p>
                <p style="font-size: 0.9rem; color: #777; margin-top: 1rem;">Ingénieur sénior assurant la fluidité et la
                    sécurité de l'expérience numérique.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>