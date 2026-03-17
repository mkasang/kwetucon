<!-- /kwetu_con/app/views/partials/user/sidebar.php -->
<aside class="user-sidebar">
    <div class="sidebar-header">
        <a href="<?= View::url('/') ?>" class="sidebar-brand">
            <img src="<?= View::asset('images/logo.png') ?>" alt="KWETU CON">
        </a>
    </div>
    
    <div class="sidebar-user">
        <div class="user-avatar">
            <img src="<?= View::asset('images/uploads/' . ($current_user['profile_pic'] ?? 'default-avatar.jpg')) ?>" 
                 alt="Photo de profil">
        </div>
        <div class="user-info">
            <h4><?= $current_user['first_name'] ?? 'Utilisateur' ?> <?= $current_user['last_name'] ?? '' ?></h4>
            <p><i class="fas fa-map-marker-alt"></i> <?= $current_user['city'] ?? 'Kinshasa' ?></p>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item">
                <a href="<?= View::url('/discover') ?>" class="nav-link">
                    <i class="fas fa-compass"></i>
                    <span>Découvrir</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/conversations') ?>" class="nav-link">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                    <span class="badge">3</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/likes') ?>" class="nav-link">
                    <i class="fas fa-heart"></i>
                    <span>J'aime</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/favorites') ?>" class="nav-link">
                    <i class="fas fa-star"></i>
                    <span>Favoris</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/visits') ?>" class="nav-link">
                    <i class="fas fa-eye"></i>
                    <span>Visites</span>
                </a>
            </li>
            <li class="nav-item-divider"></li>
            <li class="nav-item">
                <a href="<?= View::url('/profile') ?>" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Mon Profil</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/settings') ?>" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/logout') ?>" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- Publicités dans la sidebar -->
    <div class="sidebar-ads">
        <!-- Les publicités seront chargées dynamiquement -->
    </div>
</aside>