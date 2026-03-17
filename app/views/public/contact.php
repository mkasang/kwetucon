<!-- /kwetu_con/app/views/public/contact.php -->
<div class="contact-page">
    <!-- Hero section -->
    <section class="page-hero">
        <div class="container">
            <h1 class="page-title">Contactez-nous</h1>
            <p class="page-subtitle">Une question ? Une suggestion ? Nous sommes là pour vous aider</p>
        </div>
    </section>
    
    <!-- Contact section -->
    <section class="contact-section section-padding">
        <div class="container">
            <div class="contact-grid">
                <!-- Formulaire de contact -->
                <div class="contact-form-wrapper">
                    <h2 class="form-title">Envoyez-nous un message</h2>
                    
                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['flash_message'] ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['flash_error'] ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= View::url('contact') ?>" method="POST" class="contact-form">
                        <div class="form-group">
                            <label for="name">Nom complet *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control <?= isset($_SESSION['form_errors']['name']) ? 'is-invalid' : '' ?>"
                                   value="<?= $_SESSION['old_input']['name'] ?? '' ?>"
                                   required>
                            <?php if (isset($_SESSION['form_errors']['name'])): ?>
                                <div class="invalid-feedback">
                                    <?= $_SESSION['form_errors']['name'][0] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control <?= isset($_SESSION['form_errors']['email']) ? 'is-invalid' : '' ?>"
                                   value="<?= $_SESSION['old_input']['email'] ?? '' ?>"
                                   required>
                            <?php if (isset($_SESSION['form_errors']['email'])): ?>
                                <div class="invalid-feedback">
                                    <?= $_SESSION['form_errors']['email'][0] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Sujet *</label>
                            <input type="text" 
                                   id="subject" 
                                   name="subject" 
                                   class="form-control <?= isset($_SESSION['form_errors']['subject']) ? 'is-invalid' : '' ?>"
                                   value="<?= $_SESSION['old_input']['subject'] ?? '' ?>"
                                   required>
                            <?php if (isset($_SESSION['form_errors']['subject'])): ?>
                                <div class="invalid-feedback">
                                    <?= $_SESSION['form_errors']['subject'][0] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5" 
                                      class="form-control <?= isset($_SESSION['form_errors']['message']) ? 'is-invalid' : '' ?>"
                                      required><?= $_SESSION['old_input']['message'] ?? '' ?></textarea>
                            <?php if (isset($_SESSION['form_errors']['message'])): ?>
                                <div class="invalid-feedback">
                                    <?= $_SESSION['form_errors']['message'][0] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </form>
                    
                    <?php 
                    // Nettoyer les variables de session
                    unset($_SESSION['form_errors']);
                    unset($_SESSION['old_input']);
                    ?>
                </div>
                
                <!-- Informations de contact -->
                <div class="contact-info-wrapper">
                    <h2 class="info-title">Autres moyens de nous joindre</h2>
                    
                    <div class="info-cards">
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Adresse</h3>
                                <p>
                                    123 Avenue de la Gombe<br>
                                    Kinshasa, RDC
                                </p>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Téléphone</h3>
                                <p>
                                    <a href="tel:+243123456789">+243 123 456 789</a><br>
                                    <small>Lun-Ven, 9h-18h</small>
                                </p>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p>
                                    <a href="mailto:contact@kwetucon.com">contact@kwetucon.com</a><br>
                                    <a href="mailto:support@kwetucon.com">support@kwetucon.com</a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Horaires</h3>
                                <p>
                                    Support technique : 24h/24, 7j/7<br>
                                    Service client : Lun-Ven 9h-18h
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Réseaux sociaux -->
                    <div class="social-section">
                        <h3>Suivez-nous</h3>
                        <div class="social-links">
                            <a href="#" class="social-link facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Carte (placeholder) -->
    <section class="map-section">
        <div class="map-placeholder">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1983.1419999999997!2d15.2667!3d-4.3167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a33f0b3c3c3c3%3A0x0!2zNMKwMTknMDAuMCJTIDE1wrAxNicwMC4wIkU!5e0!3m2!1sfr!2scd!4v1234567890" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </section>
</div>

<style>
.contact-page .page-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.contact-page .page-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.contact-page .page-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.contact-page .contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
}

.contact-page .form-title,
.contact-page .info-title {
    font-size: 1.75rem;
    margin-bottom: 30px;
    color: #333;
}

.contact-page .contact-form {
    max-width: 500px;
}

.contact-page .form-group {
    margin-bottom: 20px;
}

.contact-page .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #555;
}

.contact-page .form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.contact-page .form-control:focus {
    outline: none;
    border-color: #667eea;
}

.contact-page .form-control.is-invalid {
    border-color: #dc3545;
}

.contact-page .invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
}

.contact-page .btn-block {
    width: 100%;
    padding: 12px;
    font-size: 1.1rem;
}

.contact-page .info-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.contact-page .info-card {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: transform 0.3s;
}

.contact-page .info-card:hover {
    transform: translateX(10px);
}

.contact-page .info-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-page .info-icon i {
    font-size: 1.25rem;
    color: white;
}

.contact-page .info-content h3 {
    font-size: 1.1rem;
    margin-bottom: 5px;
    color: #333;
}

.contact-page .info-content p {
    color: #666;
    line-height: 1.6;
}

.contact-page .info-content a {
    color: #667eea;
    text-decoration: none;
}

.contact-page .info-content a:hover {
    text-decoration: underline;
}

.contact-page .social-section {
    margin-top: 30px;
}

.contact-page .social-section h3 {
    font-size: 1.2rem;
    margin-bottom: 15px;
    color: #333;
}

.contact-page .social-links {
    display: flex;
    gap: 15px;
}

.contact-page .social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: transform 0.3s;
}

.contact-page .social-link:hover {
    transform: translateY(-3px);
}

.contact-page .social-link.facebook {
    background: #1877f2;
}

.contact-page .social-link.twitter {
    background: #1da1f2;
}

.contact-page .social-link.instagram {
    background: #e4405f;
}

.contact-page .social-link.linkedin {
    background: #0077b5;
}

.contact-page .map-section {
    height: 450px;
}

.contact-page .map-placeholder {
    width: 100%;
    height: 100%;
}

@media (max-width: 768px) {
    .contact-page .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-page .info-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .contact-page .social-links {
        justify-content: center;
    }
}
</style>