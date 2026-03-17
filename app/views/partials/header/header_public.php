<!-- /kwetu_con/app/views/partials/header/header_public.php -->
<header class="public-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= View::url('/') ?>">
                <img src="<?= View::asset('images/logo.png') ?>" alt="<?= $site_name ?>" height="40">
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= View::url('/') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= View::url('/about') ?>">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= View::url('/contact') ?>">Contact</a>
                    </li>
                    <?php if (!isset($current_user) || !$current_user): ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary" href="<?= View::url('/login') ?>">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="<?= View::url('/register') ?>">Inscription</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/profile') ?>">Mon Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= View::url('/logout') ?>">Déconnexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>