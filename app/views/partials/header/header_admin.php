<!-- /kwetu_con/app/views/partials/header/header_admin.php -->
<header class="admin-header">
    <div class="header-left">
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <h2>Tableau de bord</h2>
    </div>
    
    <div class="header-right">
        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="btn btn-sm btn-primary" onclick="location.href='<?= View::url('/admin/users/create') ?>'">
                <i class="fas fa-user-plus"></i> Nouvel utilisateur
            </button>
            <button class="btn btn-sm btn-success" onclick="location.href='<?= View::url('/admin/ads/create') ?>'">
                <i class="fas fa-ad"></i> Nouvelle pub
            </button>
        </div>
        
        <!-- Admin Profile -->
        <div class="admin-profile-dropdown">
            <button class="profile-btn">
                <img src="<?= View::asset('images/uploads/' . ($current_user['profile_pic'] ?? 'admin-avatar.jpg')) ?>" 
                     alt="Admin" class="profile-img">
                <span>Admin</span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu">
                <a href="<?= View::url('/admin/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user-cog"></i> Mon Profil
                </a>
                <a href="<?= View::url('/admin/settings') ?>" class="dropdown-item">
                    <i class="fas fa-globe"></i> Paramètres site
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= View::url('/') ?>" class="dropdown-item">
                    <i class="fas fa-eye"></i> Voir le site
                </a>
                <a href="<?= View::url('/logout') ?>" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</header>