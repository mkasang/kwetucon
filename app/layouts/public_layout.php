<!-- /kwetu_con/app/layouts/public_layout.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title><?= isset($site_name) ? $site_name : 'KWETU CON' ?> - <?= $page_title ?? 'Accueil' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= View::asset('css/public/style.css') ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= View::asset('images/favicon.png') ?>">
    
    <!-- Meta tags pour mobile -->
    <meta name="theme-color" content="#ff6b6b">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>
    <div class="site-wrapper">
        <!-- Header Public -->
        <?php 
        $headerData = [
            'site_name' => $site_name ?? 'KWETU CON',
            'current_user' => $current_user ?? null
        ];
        View::header('public', $headerData); 
        ?>
        
        <!-- Contenu principal -->
        <main class="main-content">
            <div class="container">
                <?= View::flashMessage() ?>
                <?= View::flashError() ?>
            </div>
            <?= $content ?? '' ?>
        </main>
        
        <!-- Footer Public -->
        <?php 
        $footerData = [
            'site_name' => $site_name ?? 'KWETU CON',
            'current_year' => date('Y')
        ];
        View::footer('public', $footerData); 
        ?>
    </div>
    
    <!-- JavaScript -->
    <script src="<?= View::asset('js/public/app.js') ?>"></script>
</body>
</html>