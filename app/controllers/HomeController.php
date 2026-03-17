<?php

// use AdsModel;
// use AuthHelper;
// use BaseController;
// use ProfileModel;
// use UserModel;
// use Validator;
// /kwetu_con/app/controllers/HomeController.php

require_once app_path('core/BaseController.php');
require_once app_path('models/AdsModel.php');
require_once app_path('models/ProfileModel.php');
require_once app_path('models/UserModel.php');
require_once app_path('core/AuthHelper.php');

class HomeController extends BaseController {
    
    /**
     * Page d'accueil
     */
    public function index() {
        // Si l'utilisateur est connecté, rediriger vers la découverte
        if (AuthHelper::check()) {
            $this->redirect('discover');
        }
        
        // Charger les publicités pour la page d'accueil
        $adsModel = new AdsModel();
        $headerAds = $adsModel->getActiveAds('header', 1);
        $sidebarAds = $adsModel->getActiveAds('sidebar', 2);
        
        // Statistiques pour la section "Ils nous font confiance"
        $userModel = new UserModel();
        $profileModel = new ProfileModel();
        
        $totalUsers = $userModel->count(['status' => 'active']);
        $totalMatches = $this->getTotalMatches(); // À implémenter
        $activeToday = $this->getActiveToday(); // À implémenter
        
        // Témoignages (statiques pour le MVP)
        $testimonials = [
            [
                'name' => 'Marie K.',
                'age' => 28,
                'city' => 'Kinshasa',
                'photo' => 'testimonial-1.jpg',
                'text' => 'Grâce à KWETU CON, j\'ai rencontré l\'homme de ma vie. L\'application est simple et efficace !',
                'rating' => 5
            ],
            [
                'name' => 'Jean-Paul M.',
                'age' => 32,
                'city' => 'Lubumbashi',
                'photo' => 'testimonial-2.jpg',
                'text' => 'Je recommande vivement. Les profils sont authentiques et la modération est sérieuse.',
                'rating' => 5
            ],
            [
                'name' => 'Sarah T.',
                'age' => 25,
                'city' => 'Goma',
                'photo' => 'testimonial-3.jpg',
                'text' => 'Une application qui comprend vraiment les besoins des célibataires congolais. Bravo !',
                'rating' => 5
            ]
        ];
        
        // Caractéristiques
        $features = [
            [
                'icon' => 'fas fa-heart',
                'title' => 'Rencontres authentiques',
                'description' => 'Des profils vérifiés pour des rencontres sérieuses et sincères.'
            ],
            [
                'icon' => 'fas fa-comments',
                'title' => 'Chat illimité',
                'description' => 'Discutez sans limite avec les personnes qui vous intéressent.'
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Sécurité garantie',
                'description' => 'Modération 24/7 et système de signalement pour votre tranquillité.'
            ],
            [
                'icon' => 'fas fa-map-marker-alt',
                'title' => 'Géolocalisation',
                'description' => 'Trouvez des célibataires près de chez vous facilement.'
            ],
            [
                'icon' => 'fas fa-mobile-alt',
                'title' => 'Application mobile',
                'description' => 'Disponible sur iOS et Android pour rester connecté partout.'
            ],
            [
                'icon' => 'fas fa-star',
                'title' => 'Fonctionnalités premium',
                'description' => 'Des options exclusives pour maximiser vos chances.'
            ]
        ];
        
        // Comment ça marche
        $howItWorks = [
            [
                'step' => 1,
                'title' => 'Inscrivez-vous',
                'description' => 'Créez votre compte en quelques secondes, c\'est gratuit !',
                'icon' => 'fas fa-user-plus'
            ],
            [
                'step' => 2,
                'title' => 'Complétez votre profil',
                'description' => 'Ajoutez vos photos et décrivez-vous pour attirer l\'attention.',
                'icon' => 'fas fa-id-card'
            ],
            [
                'step' => 3,
                'title' => 'Découvrez des profils',
                'description' => 'Parcourez les profils qui correspondent à vos critères.',
                'icon' => 'fas fa-compass'
            ],
            [
                'step' => 4,
                'title' => 'Commencez à discuter',
                'description' => 'Envoyez un message et faites connaissance.',
                'icon' => 'fas fa-comment-dots'
            ]
        ];
        
        // Données pour la vue
        $data = [
            'page_title' => 'KWETU CON - Rencontres sérieuses en RDC',
            'meta_description' => 'Application de rencontre n°1 en RDC. Trouvez l\'amour à Kinshasa, Lubumbashi, Goma et partout au Congo.',
            'header_ads' => $headerAds,
            'sidebar_ads' => $sidebarAds,
            'total_users' => number_format($totalUsers, 0, ',', ' '),
            'total_matches' => '10K+',
            'active_today' => '500+',
            'testimonials' => $testimonials,
            'features' => $features,
            'how_it_works' => $howItWorks
        ];
        
        $this->view('public/home', $data);
    }
    
    /**
     * Page À propos
     */
    public function about() {
        $data = [
            'page_title' => 'À propos de KWETU CON',
            'meta_description' => 'Découvrez l\'histoire de KWETU CON, la première application de rencontre congolaise.'
        ];
        
        $this->view('public/about', $data);
    }
    
    /**
     * Page Contact
     */
    public function contact() {
        $data = [
            'page_title' => 'Contactez-nous',
            'meta_description' => 'Une question ? Un problème ? Contactez l\'équipe KWETU CON.'
        ];
        
        $this->view('public/contact', $data);
    }
    
    /**
     * Traitement du formulaire de contact
     */
    public function sendContact() {
        // Validation
        $rules = [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10'
        ];
        
        $validator = new Validator();
        if (!$validator->validate($_POST, $rules)) {
            $_SESSION['flash_error'] = 'Veuillez corriger les erreurs du formulaire.';
            $_SESSION['form_errors'] = $validator->getErrors();
            $_SESSION['old_input'] = $_POST;
            $this->redirect('contact');
        }
        
        // Envoyer l'email (simulé pour le MVP)
        $name = sanitize($_POST['name']);
        $email = sanitize($_POST['email']);
        $subject = sanitize($_POST['subject']);
        $message = sanitize($_POST['message']);
        
        // Log du message
        $logMessage = "Contact de {$name} ({$email}): {$subject}\n{$message}\n---\n";
        file_put_contents(storage_path('logs/contacts.log'), $logMessage, FILE_APPEND);
        
        // Ici, vous pourriez envoyer un vrai email
        // mail('contact@kwetucon.com', $subject, $message, "From: $email");
        
        $_SESSION['flash_message'] = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
        $this->redirect('contact');
    }
    
    /**
     * Obtenir le total des matches (simulé pour le MVP)
     */
    private function getTotalMatches() {
        // À implémenter avec une vraie requête
        return 15234;
    }
    
    /**
     * Obtenir le nombre d'utilisateurs actifs aujourd'hui
     */
    private function getActiveToday() {
        // À implémenter avec une vraie requête
        return 543;
    }
    
    /**
     * Page des conditions d'utilisation
     */
    public function terms() {
        $data = [
            'page_title' => 'Conditions d\'utilisation',
            'last_updated' => '15 Janvier 2024'
        ];
        
        $this->view('public/terms', $data);
    }
    
    /**
     * Page de confidentialité
     */
    public function privacy() {
        $data = [
            'page_title' => 'Politique de confidentialité',
            'last_updated' => '15 Janvier 2024'
        ];
        
        $this->view('public/privacy', $data);
    }
    
    /**
     * Page FAQ
     */
    public function faq() {
        $faqs = [
            [
                'question' => 'Comment créer un compte ?',
                'answer' => 'Pour créer un compte, cliquez sur le bouton "Inscription" en haut à droite. Remplissez le formulaire avec vos informations personnelles et validez votre email. C\'est gratuit et rapide !'
            ],
            [
                'question' => 'Est-ce vraiment gratuit ?',
                'answer' => 'Oui, l\'inscription et les fonctionnalités de base sont totalement gratuites. Nous proposons également des fonctionnalités premium optionnelles pour améliorer votre expérience.'
            ],
            [
                'question' => 'Comment signaler un utilisateur ?',
                'answer' => 'Si vous rencontrez un comportement inapproprié, vous pouvez signaler un utilisateur depuis son profil ou dans la conversation. Notre équipe de modération traite chaque signalement dans les 24h.'
            ],
            [
                'question' => 'Puis-je bloquer quelqu\'un ?',
                'answer' => 'Oui, vous pouvez bloquer n\'importe quel utilisateur. Une fois bloqué, il ne pourra plus vous contacter ni voir votre profil.'
            ],
            [
                'question' => 'Comment fonctionne la géolocalisation ?',
                'answer' => 'Nous utilisons votre position pour vous montrer des profils près de chez vous. Vous pouvez désactiver cette fonction à tout moment dans les paramètres.'
            ],
            [
                'question' => 'Mes données sont-elles sécurisées ?',
                'answer' => 'Absolument ! Nous utilisons un chiffrement avancé et ne partageons jamais vos données personnelles avec des tiers. Consultez notre politique de confidentialité pour plus de détails.'
            ]
        ];
        
        $data = [
            'page_title' => 'Foire Aux Questions',
            'faqs' => $faqs
        ];
        
        $this->view('public/faq', $data);
    }
}