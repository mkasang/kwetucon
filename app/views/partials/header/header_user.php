<!-- /kwetu_con/app/views/partials/header/header_user.php -->
<header class="user-header">
    <div class="header-left">
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="header-search">
            <input type="text" placeholder="Rechercher...">
            <button><i class="fas fa-search"></i></button>
        </div>
    </div>
    
    <div class="header-right">
        <!-- Notifications -->
        <div class="notifications-dropdown">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>
            <div class="dropdown-menu">
                <div class="dropdown-header">
                    <h6>Notifications</h6>
                    <a href="#">Tout marquer comme lu</a>
                </div>
                <div class="dropdown-body">
                    <a href="#" class="notification-item">
                        <div class="notification-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="notification-content">
                            <p><strong>Marie</strong> vous a envoyé un message</p>
                            <small>Il y a 5 minutes</small>
                        </div>
                    </a>
                    <!-- Plus de notifications -->
                </div>
                <div class="dropdown-footer">
                    <a href="<?= View::url('/notifications') ?>">Voir toutes</a>
                </div>
            </div>
        </div>
        
        <!-- Messages -->
        <div class="messages-dropdown">
            <button class="messages-btn">
                <i class="fas fa-comment"></i>
                <span class="badge">5</span>
            </button>
            <div class="dropdown-menu">
                <div class="dropdown-header">
                    <h6>Messages</h6>
                    <a href="<?= View::url('/conversations') ?>">Voir tous</a>
                </div>
                <div class="dropdown-body">
                    <!-- Liste des derniers messages -->
                </div>
            </div>
        </div>
        
        <!-- Profil -->
        <div class="profile-dropdown">
            <button class="profile-btn">
                <img src="<?= View::asset('images/uploads/' . ($current_user['profile_pic'] ?? 'default-avatar.jpg')) ?>" 
                     alt="Profil" class="profile-img">
                <span><?= $current_user['first_name'] ?? 'Mon Compte' ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu">
                <a href="<?= View::url('/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user"></i> Mon Profil
                </a>
                <a href="<?= View::url('/settings') ?>" class="dropdown-item">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= View::url('/logout') ?>" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</header>