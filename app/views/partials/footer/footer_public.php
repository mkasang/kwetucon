<!-- /kwetu_con/app/views/partials/footer/footer_public.php -->
<footer class="public-footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-md-4">
                    <img src="<?= View::asset('images/logo-white.png') ?>" alt="<?= $site_name ?>" height="30">
                    <p>Trouvez l'amour en toute simplicité avec KWETU CON, l'application de rencontre qui vous correspond.</p>
                </div>
                <div class="col-md-2">
                    <h5>Liens rapides</h5>
                    <ul>
                        <li><a href="<?= View::url('/') ?>">Accueil</a></li>
                        <li><a href="<?= View::url('/about') ?>">À propos</a></li>
                        <li><a href="<?= View::url('/contact') ?>">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Légal</h5>
                    <ul>
                        <li><a href="#">CGU</a></li>
                        <li><a href="#">Confidentialité</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Suivez-nous</h5>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= $site_name ?>. Tous droits réservés.</p>
        </div>
    </div>
</footer>