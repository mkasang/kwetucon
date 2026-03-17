<!-- /kwetu_con/app/views/public/about.php -->
<div class="about-page">
    <!-- Hero section -->
    <section class="page-hero">
        <div class="container">
            <h1 class="page-title">À propos de KWETU CON</h1>
            <p class="page-subtitle">Découvrez l'histoire derrière la première application de rencontre congolaise</p>
        </div>
    </section>
    
    <!-- Notre histoire -->
    <section class="story-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="section-title">Notre histoire</h2>
                    <p class="story-text">
                        KWETU CON est né d'un constat simple : les célibataires congolais méritent une plateforme de rencontre qui comprend leur culture, leurs valeurs et leurs attentes.
                    </p>
                    <p class="story-text">
                        Fondée en 2024 à Kinshasa, notre application a rapidement conquis le cœur de milliers d'utilisateurs à travers la RDC. Notre mission est de faciliter les rencontres authentiques et durables dans un environnement sécurisé et respectueux.
                    </p>
                    <p class="story-text">
                        Aujourd'hui, KWETU CON est fière d'avoir contribué à la création de nombreuses histoires d'amour, et nous continuons d'innover pour offrir la meilleure expérience possible à nos membres.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="story-image">
                        <img src="<?= View::asset('images/about-story.jpg') ?>" alt="Notre histoire" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Nos valeurs -->
    <section class="values-section section-padding bg-light">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Nos valeurs</h2>
                <p class="section-subtitle">Ce qui nous guide au quotidien</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Sécurité</h3>
                    <p>La protection de nos membres est notre priorité absolue. Nous vérifions les profils et modérons les échanges 24/7.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Authenticité</h3>
                    <p>Nous encourageons les relations sincères et luttons contre les faux profils et comportements inappropriés.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Inclusion</h3>
                    <p>Notre plateforme est ouverte à tous, sans discrimination, dans le respect des différences de chacun.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-globe-africa"></i>
                    </div>
                    <h3>Culture locale</h3>
                    <p>Nous comprenons et respectons les spécificités culturelles congolaises pour des rencontres adaptées.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- L'équipe -->
    <section class="team-section section-padding">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Notre équipe</h2>
                <p class="section-subtitle">Des passionnés au service de votre vie amoureuse</p>
            </div>
            
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-photo">
                        <img src="<?= View::asset('images/team/team-1.jpg') ?>" alt="Jean-Pierre K.">
                    </div>
                    <h3>Jean-Pierre K.</h3>
                    <p class="team-role">Fondateur & CEO</p>
                    <p class="team-bio">Entrepreneur passionné par les technologies et les relations humaines.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-photo">
                        <img src="<?= View::asset('images/team/team-2.jpg') ?>" alt="Marie-Claire M.">
                    </div>
                    <h3>Marie-Claire M.</h3>
                    <p class="team-role">Directrice de la modération</p>
                    <p class="team-bio">Psychologue de formation, elle veille à la qualité des échanges.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-photo">
                        <img src="<?= View::asset('images/team/team-3.jpg') ?>" alt="Alain T.">
                    </div>
                    <h3>Alain T.</h3>
                    <p class="team-role">Développeur principal</p>
                    <p class="team-bio">Expert en sécurité et en expérience utilisateur.</p>
                </div>
                
                <div class="team-card">
                    <div class="team-photo">
                        <img src="<?= View::asset('images/team/team-4.jpg') ?>" alt="Sarah B.">
                    </div>
                    <h3>Sarah B.</h3>
                    <p class="team-role">Community Manager</p>
                    <p class="team-bio">À l'écoute des membres pour améliorer constamment l'application.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Chiffres clés -->
    <section class="stats-mini-section">
        <div class="container">
            <div class="stats-mini-grid">
                <div class="stat-mini-item">
                    <span class="stat-mini-number">20K+</span>
                    <span class="stat-mini-label">Membres inscrits</span>
                </div>
                <div class="stat-mini-item">
                    <span class="stat-mini-number">5K+</span>
                    <span class="stat-mini-label">Couples formés</span>
                </div>
                <div class="stat-mini-item">
                    <span class="stat-mini-number">50+</span>
                    <span class="stat-mini-label">Villes couvertes</span>
                </div>
                <div class="stat-mini-item">
                    <span class="stat-mini-number">4.8/5</span>
                    <span class="stat-mini-label">Note moyenne</span>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.about-page .page-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.about-page .page-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.about-page .page-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.about-page .story-section {
    padding: 80px 0;
}

.about-page .story-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 20px;
}

.about-page .values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.about-page .value-card {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.about-page .value-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.about-page .value-icon i {
    font-size: 2rem;
    color: white;
}

.about-page .value-card h3 {
    font-size: 1.25rem;
    margin-bottom: 15px;
    color: #333;
}

.about-page .value-card p {
    color: #666;
    line-height: 1.6;
}

.about-page .team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}

.about-page .team-card {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.about-page .team-photo {
    width: 150px;
    height: 150px;
    margin: 0 auto 20px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #667eea;
}

.about-page .team-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.about-page .team-card h3 {
    font-size: 1.25rem;
    margin-bottom: 5px;
    color: #333;
}

.about-page .team-role {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 15px;
}

.about-page .team-bio {
    color: #666;
    line-height: 1.6;
}

.about-page .stats-mini-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
}

.about-page .stats-mini-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    text-align: center;
}

.about-page .stat-mini-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.about-page .stat-mini-label {
    font-size: 1rem;
    opacity: 0.9;
}
</style>