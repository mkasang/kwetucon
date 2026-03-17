<!-- /kwetu_con/app/views/partials/header/header_public.php -->
<header class="public-header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?= View::url('/') ?>">
                    <img src="<?= View::asset('images/logo.png') ?>" alt="<?= $site_name ?>" class="logo">
                </a>
            </div>
            
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <div class="navbar-menu" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/kwetu_con/') === 8 ? 'active' : '' ?>" 
                           href="<?= View::url('/') ?>">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/about') !== false ? 'active' : '' ?>" 
                           href="<?= View::url('/about') ?>">
                            <i class="fas fa-info-circle"></i>
                            <span>À propos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/contact') !== false ? 'active' : '' ?>" 
                           href="<?= View::url('/contact') ?>">
                            <i class="fas fa-envelope"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
                
                <div class="navbar-buttons">
                    <?php if (!isset($current_user) || !$current_user): ?>
                        <a href="<?= View::url('/login') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Connexion</span>
                        </a>
                        <a href="<?= View::url('/register') ?>" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            <span>Inscription</span>
                        </a>
                    <?php else: ?>
                        <a href="<?= View::url('/profile') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i>
                            <span>Mon Profil</span>
                        </a>
                        <a href="<?= View::url('/logout') ?>" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navbarMenu.classList.toggle('show');
            document.body.classList.toggle('menu-open');
        });
    }
    
    // Fermer le menu quand on clique sur un lien
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            mobileToggle.classList.remove('active');
            navbarMenu.classList.remove('show');
            document.body.classList.remove('menu-open');
        });
    });
});
</script>