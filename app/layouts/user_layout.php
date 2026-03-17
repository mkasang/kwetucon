<!-- /kwetu_con/app/layouts/user_layout.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?> - Espace Membre</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= View::asset('css/user/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= View::asset('images/favicon.png') ?>">
</head>
<body>
    <div class="user-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <?php View::partial('user/sidebar', ['current_user' => $current_user]) ?>
        </aside>
        
        <!-- Contenu principal -->
        <div class="main-wrapper">
            <!-- Header User -->
            <?php View::header('user') ?>
            
            <!-- Contenu -->
            <main class="content">
                <?= View::flashMessage() ?>
                <?= View::flashError() ?>
                <?= $content ?? '' ?>
            </main>
            
            <!-- Footer User -->
            <?php View::footer('user') ?>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="<?= View::asset('js/user/app.js') ?>"></script>
</body>
</html>