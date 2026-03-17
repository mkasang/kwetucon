<!-- /kwetu_con/app/layouts/public_layout.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?> - <?= $page_title ?? 'Accueil' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= View::asset('css/public/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= View::asset('images/favicon.png') ?>">
</head>
<body>
    <!-- Header Public -->
    <?php View::header('public', ['site_name' => $site_name]) ?>
    
    <!-- Contenu principal -->
    <main class="main-content">
        <?= View::flashMessage() ?>
        <?= View::flashError() ?>
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer Public -->
    <?php View::footer('public') ?>
    
    <!-- JavaScript -->
    <script src="<?= View::asset('js/public/app.js') ?>"></script>
</body>
</html>