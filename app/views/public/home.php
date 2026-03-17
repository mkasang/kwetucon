<!-- /kwetu_con/app/views/public/home.php -->
<div class="home-page">
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title animate-fade-in">
                    Trouvez l'amour <br>
                    <span class="highlight">en toute simplicité</span>
                </h1>
                <p class="hero-subtitle animate-fade-in-up">
                    KWETU CON est l'application de rencontre qui vous correspond.<br>
                    Rejoignez des milliers de célibataires en RDC.
                </p>
                <div class="hero-buttons animate-fade-in-up">
                    <a href="<?= View::url('register') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus"></i>
                        Créer un compte gratuit
                    </a>
                    <a href="<?= View::url('login') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </a>
                </div>
                <div class="hero-stats animate-fade-in-up">
                    <div class="stat-item">
                        <span class="stat-number"><?= $total_users ?></span>
                        <span class="stat-label">Membres actifs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $total_matches ?></span>
                        <span class="stat-label">Rencontres</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $active_today ?></span>
                        <span class="stat-label">Connectés aujourd'hui</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave divider -->
        <div class="wave-divider">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>
    
    <!-- Publicités Header (si disponibles) -->
    <?php if (!empty($header_ads)): ?>
        <div class="header-ads">
            <div class="container">
                <?php foreach ($header_ads as $ad): ?>
                    <div class="ad-banner">
                        <a href="<?= View::url('ad/click/' . $ad['id']) ?>" target="_blank">
                            <img src="<?= View::asset('images/ads/' . $ad['image_url']) ?>" alt="<?= $ad['title'] ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Comment ça marche -->
    <section class="how-it-works section-padding">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Comment ça marche ?</h2>
                <p class="section-subtitle">Commencez votre histoire en 4 étapes simples</p>
            </div>
            
            <div class="steps-grid">
                <?php foreach ($how_it_works as $step): ?>
                    <div class="step-card">
                        <div class="step-number"><?= $step['step'] ?></div>
                        <div class="step-icon">
                            <i class="<?= $step['icon'] ?>"></i>
                        </div>
                        <h3 class="step-title"><?= $step['title'] ?></h3>
                        <p class="step-description"><?= $step['description'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Caractéristiques -->
    <section class="features-section section-padding bg-light">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Pourquoi choisir KWETU CON ?</h2>
                <p class="section-subtitle">Des fonctionnalités conçues pour faciliter vos rencontres</p>
            </div>
            
            <div class="features-grid">
                <?php foreach ($features as $feature): ?>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="<?= $feature['icon'] ?>"></i>
                        </div>
                        <h3 class="feature-title"><?= $feature['title'] ?></h3>
                        <p class="feature-description"><?= $feature['description'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Témoignages -->
    <section class="testimonials-section section-padding">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Ils ont trouvé l'amour</h2>
                <p class="section-subtitle">Des histoires vraies de membres KWETU CON</p>
            </div>
            
            <div class="testimonials-slider">
                <div class="testimonials-grid">
                    <?php foreach ($testimonials as $testimonial): ?>
                        <div class="testimonial-card">
                            <div class="testimonial-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $testimonial['rating'] ? 'active' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="testimonial-text">"<?= $testimonial['text'] ?>"</p>
                            <div class="testimonial-author">
                                <img src="<?= View::asset('images/testimonials/' . $testimonial['photo']) ?>" 
                                     alt="<?= $testimonial['name'] ?>" 
                                     class="author-photo">
                                <div class="author-info">
                                    <h4 class="author-name"><?= $testimonial['name'] ?></h4>
                                    <p class="author-details"><?= $testimonial['age'] ?> ans, <?= $testimonial['city'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Publicités Sidebar -->
    <?php if (!empty($sidebar_ads)): ?>
        <div class="sidebar-ads-section">
            <div class="container">
                <div class="ads-grid">
                    <?php foreach ($sidebar_ads as $ad): ?>
                        <div class="ad-card">
                            <a href="<?= View::url('ad/click/' . $ad['id']) ?>" target="_blank">
                                <img src="<?= View::asset('images/ads/' . $ad['image_url']) ?>" alt="<?= $ad['title'] ?>">
                                <div class="ad-content">
                                    <h4><?= $ad['title'] ?></h4>
                                    <p><?= $ad['description'] ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Statistiques -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-wrapper">
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <span class="stat-number"><?= $total_users ?></span>
                    <span class="stat-label">Membres</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-heart"></i>
                    <span class="stat-number"><?= $total_matches ?></span>
                    <span class="stat-label">Couples formés</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-comments"></i>
                    <span class="stat-number">50K+</span>
                    <span class="stat-label">Messages/jour</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-shield-alt"></i>
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Modération</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Appel à l'action -->
    <section class="cta-section section-padding">
        <div class="container">
            <div class="cta-content text-center">
                <h2 class="cta-title">Prêt à commencer votre histoire ?</h2>
                <p class="cta-text">
                    Rejoignez des milliers de célibataires qui ont déjà trouvé l'amour sur KWETU CON.
                </p>
                <div class="cta-buttons">
                    <a href="<?= View::url('register') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus"></i>
                        Créer un compte gratuit
                    </a>
                    <a href="<?= View::url('about') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle"></i>
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section mobile app (placeholder) -->
    <section class="app-section">
        <div class="container">
            <div class="app-content">
                <div class="app-info">
                    <h2>Téléchargez l'application</h2>
                    <p>Restez connecté partout avec notre application mobile disponible sur iOS et Android.</p>
                    <div class="app-buttons">
                        <a href="#" class="app-button">
                            <i class="fab fa-apple"></i>
                            App Store
                        </a>
                        <a href="#" class="app-button">
                            <i class="fab fa-google-play"></i>
                            Google Play
                        </a>
                    </div>
                </div>
                <div class="app-preview">
                    <img src="<?= View::asset('images/app-preview.png') ?>" alt="Application KWETU CON">
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Styles supplémentaires pour la landing page -->
<style>
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    color: white;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    z-index: -2;
}

.hero-background .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    padding: 2rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero-title .highlight {
    color: #ffd700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    margin-bottom: 3rem;
}

.hero-buttons .btn {
    margin: 0 0.5rem 1rem;
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.wave-divider {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
}

.wave-divider svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 120px;
}

.wave-divider .shape-fill {
    fill: #FFFFFF;
}

.section-padding {
    padding: 80px 0;
}

.section-header {
    margin-bottom: 50px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.step-card {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    position: relative;
    transition: transform 0.3s;
}

.step-card:hover {
    transform: translateY(-10px);
}

.step-number {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 30px;
    background: #667eea;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.step-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step-icon i {
    font-size: 2rem;
    color: white;
}

.step-title {
    font-size: 1.25rem;
    margin-bottom: 10px;
    color: #333;
}

.step-description {
    color: #666;
    line-height: 1.6;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.feature-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.feature-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-icon i {
    font-size: 1.75rem;
    color: white;
}

.feature-title {
    font-size: 1.2rem;
    margin-bottom: 15px;
    color: #333;
}

.feature-description {
    color: #666;
    line-height: 1.6;
}

.bg-light {
    background-color: #f8f9fa;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
}

.testimonial-card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.testimonial-rating {
    margin-bottom: 20px;
}

.testimonial-rating i {
    color: #ddd;
    font-size: 1.2rem;
    margin-right: 2px;
}

.testimonial-rating i.active {
    color: #ffd700;
}

.testimonial-text {
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
    margin-bottom: 20px;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-photo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.author-name {
    font-size: 1.1rem;
    margin-bottom: 5px;
    color: #333;
}

.author-details {
    color: #666;
    font-size: 0.9rem;
}

.stats-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
}

.stats-wrapper {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 30px;
}

.stats-wrapper .stat-item {
    text-align: center;
}

.stats-wrapper .stat-item i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    display: block;
}

.cta-section {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                url('<?= View::asset('images/cta-bg.jpg') ?>') center/cover;
    color: white;
}

.cta-title {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.cta-text {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.app-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.app-content {
    display: flex;
    align-items: center;
    gap: 50px;
}

.app-info {
    flex: 1;
}

.app-info h2 {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #333;
}

.app-info p {
    color: #666;
    margin-bottom: 30px;
    font-size: 1.1rem;
}

.app-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.app-button {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: #333;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: background 0.3s;
}

.app-button:hover {
    background: #000;
    color: white;
}

.app-button i {
    font-size: 1.5rem;
}

.app-preview {
    flex: 1;
    text-align: center;
}

.app-preview img {
    max-width: 100%;
    height: auto;
    max-height: 500px;
}

.ad-banner {
    margin: 20px 0;
    text-align: center;
}

.ad-banner img {
    max-width: 100%;
    border-radius: 8px;
}

.ads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 40px 0;
}

.ad-card {
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s;
}

.ad-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.ad-card a {
    text-decoration: none;
    color: inherit;
}

.ad-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.ad-content {
    padding: 15px;
}

.ad-content h4 {
    margin-bottom: 10px;
    color: #333;
}

.ad-content p {
    color: #666;
    font-size: 0.9rem;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-fade-in-up {
    animation: fadeInUp 1s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .hero-stats {
        gap: 1.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .app-content {
        flex-direction: column;
    }
    
    .app-buttons {
        justify-content: center;
    }
}
</style>