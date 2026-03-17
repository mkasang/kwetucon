<!-- /kwetu_con/app/views/partials/footer/footer_public.php -->
<footer class="public-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col footer-about">
                <img src="<?= View::asset('images/logo-white.png') ?>" alt="<?= $site_name ?>" class="footer-logo">
                <p>Trouvez l'amour en toute simplicité avec <?= $site_name ?>, l'application de rencontre qui vous correspond.</p>
                <div class="footer-social">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-col">
                <h4>Liens rapides</h4>
                <ul class="footer-links">
                    <li><a href="<?= View::url('/') ?>"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                    <li><a href="<?= View::url('/about') ?>"><i class="fas fa-chevron-right"></i> À propos</a></li>
                    <li><a href="<?= View::url('/contact') ?>"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    <li><a href="<?= View::url('/faq') ?>"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Légal</h4>
                <ul class="footer-links">
                    <li><a href="<?= View::url('/terms') ?>"><i class="fas fa-chevron-right"></i> CGU</a></li>
                    <li><a href="<?= View::url('/privacy') ?>"><i class="fas fa-chevron-right"></i> Confidentialité</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Cookies</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Mentions légales</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Contact</h4>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Avenue de la Gombe, Kinshasa, RDC</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <a href="tel:+243123456789">+243 123 456 789</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:contact@kwetucon.com">contact@kwetucon.com</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= $site_name ?>. Tous droits réservés.</p>
            <div class="footer-bottom-links">
                <a href="#">Plan du site</a>
                <a href="#">Accessibilité</a>
                <a href="#">Carrières</a>
            </div>
        </div>
    </div>
</footer>