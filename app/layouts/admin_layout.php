<!-- /kwetu_con/app/layouts/admin_layout.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?> - Administration</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= View::asset('css/admin/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= View::asset('images/favicon.png') ?>">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Admin -->
        <aside class="admin-sidebar">
            <?php View::partial('admin/sidebar') ?>
        </aside>
        
        <!-- Contenu principal -->
        <div class="admin-wrapper">
            <!-- Header Admin -->
            <?php View::header('admin') ?>
            
            <!-- Contenu -->
            <main class="admin-content">
                <?= View::flashMessage() ?>
                <?= View::flashError() ?>
                <?= $content ?? '' ?>
            </main>
            
            <!-- Footer Admin -->
            <?php View::footer('admin') ?>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="<?= View::asset('js/admin/app.js') ?>"></script>
</body>
</html>