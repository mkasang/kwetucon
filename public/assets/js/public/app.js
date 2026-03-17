// /kwetu_con/public/assets/js/public/app.js
document.addEventListener('DOMContentLoaded', function() {
    // Menu mobile
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    
    if (mobileToggle && navbarMenu) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navbarMenu.classList.toggle('show');
            document.body.classList.toggle('menu-open');
        });
        
        // Fermer le menu en cliquant sur un lien
        navbarMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                mobileToggle.classList.remove('active');
                navbarMenu.classList.remove('show');
                document.body.classList.remove('menu-open');
            });
        });
        
        // Fermer le menu en cliquant en dehors
        document.addEventListener('click', function(e) {
            if (!navbarMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                mobileToggle.classList.remove('active');
                navbarMenu.classList.remove('show');
                document.body.classList.remove('menu-open');
            }
        });
    }
    
    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
    
    // Détection de la connexion internet
    window.addEventListener('online', function() {
        showNotification('Connexion rétablie', 'success');
    });
    
    window.addEventListener('offline', function() {
        showNotification('Connexion internet perdue', 'warning');
    });
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.textContent = message;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '250px';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }
    
    // Lazy loading des images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Détection du scroll pour le header
    let lastScroll = 0;
    const header = document.querySelector('.public-header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll <= 0) {
                header.classList.remove('scroll-up');
                return;
            }
            
            if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
                header.classList.remove('scroll-up');
                header.classList.add('scroll-down');
            } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
                header.classList.remove('scroll-down');
                header.classList.add('scroll-up');
            }
            
            lastScroll = currentScroll;
        });
    }
});