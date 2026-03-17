<!-- /kwetu_con/app/views/partials/admin/sidebar.php -->
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <a href="<?= View::url('/admin') ?>" class="sidebar-brand">
            <img src="<?= View::asset('images/logo-white.png') ?>" alt="KWETU CON Admin">
            <span>Admin</span>
        </a>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-header">PRINCIPAL</li>
            <li class="nav-item">
                <a href="<?= View::url('/admin') ?>" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            
            <li class="nav-header">GESTION</li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/users') ?>" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                    <span class="badge">24</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/profiles') ?>" class="nav-link">
                    <i class="fas fa-id-card"></i>
                    <span>Profils</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/reports') ?>" class="nav-link">
                    <i class="fas fa-flag"></i>
                    <span>Signalements</span>
                    <span class="badge badge-danger">5</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/blocks') ?>" class="nav-link">
                    <i class="fas fa-ban"></i>
                    <span>Blocages</span>
                </a>
            </li>
            
            <li class="nav-header">CONTENU</li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/ads') ?>" class="nav-link">
                    <i class="fas fa-ad"></i>
                    <span>Publicités</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/pages') ?>" class="nav-link">
                    <i class="fas fa-file"></i>
                    <span>Pages</span>
                </a>
            </li>
            
            <li class="nav-header">SYSTÈME</li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/settings') ?>" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/logs') ?>" class="nav-link">
                    <i class="fas fa-history"></i>
                    <span>Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= View::url('/admin/backup') ?>" class="nav-link">
                    <i class="fas fa-database"></i>
                    <span>Sauvegardes</span>
                </a>
            </li>
            
            <li class="nav-header">AUTRES</li>
            <li class="nav-item">
                <a href="<?= View::url('/') ?>" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Voir le site</span>
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
</aside>