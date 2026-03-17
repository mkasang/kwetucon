<!-- /kwetu_con/app/views/public/faq.php -->
<div class="faq-page">
    <!-- Hero section -->
    <section class="page-hero">
        <div class="container">
            <h1 class="page-title">Foire Aux Questions</h1>
            <p class="page-subtitle">Trouvez rapidement des réponses à vos questions</p>
        </div>
    </section>
    
    <!-- FAQ section -->
    <section class="faq-section section-padding">
        <div class="container">
            <div class="faq-grid">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFaq(<?= $index ?>)">
                            <h3><?= $faq['question'] ?></h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer" id="faq-<?= $index ?>">
                            <p><?= $faq['answer'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Section contact si pas de réponse -->
            <div class="faq-contact">
                <h3>Vous n'avez pas trouvé votre réponse ?</h3>
                <p>Notre équipe est là pour vous aider</p>
                <a href="<?= View::url('contact') ?>" class="btn btn-primary">
                    <i class="fas fa-envelope"></i>
                    Contactez-nous
                </a>
            </div>
        </div>
    </section>
</div>

<script>
function toggleFaq(index) {
    const answer = document.getElementById('faq-' + index);
    const icon = event.currentTarget.querySelector('i');
    
    if (answer.style.maxHeight) {
        answer.style.maxHeight = null;
        icon.style.transform = 'rotate(0deg)';
    } else {
        // Fermer toutes les autres FAQs
        document.querySelectorAll('.faq-answer').forEach(el => {
            el.style.maxHeight = null;
        });
        document.querySelectorAll('.faq-question i').forEach(el => {
            el.style.transform = 'rotate(0deg)';
        });
        
        // Ouvrir celle-ci
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

<style>
.faq-page .page-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
}

.faq-page .page-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.faq-page .page-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.faq-page .faq-section {
    max-width: 800px;
    margin: 0 auto;
}

.faq-page .faq-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 50px;
}

.faq-page .faq-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.faq-page .faq-question {
    padding: 20px;
    background: white;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s;
}

.faq-page .faq-question:hover {
    background: #f8f9fa;
}

.faq-page .faq-question h3 {
    font-size: 1.1rem;
    margin: 0;
    color: #333;
}

.faq-page .faq-question i {
    transition: transform 0.3s;
    color: #667eea;
}

.faq-page .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    background: #f8f9fa;
}

.faq-page .faq-answer p {
    padding: 20px;
    margin: 0;
    color: #666;
    line-height: 1.6;
}

.faq-page .faq-contact {
    text-align: center;
    padding: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    color: white;
}

.faq-page .faq-contact h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.faq-page .faq-contact p {
    margin-bottom: 20px;
    opacity: 0.9;
}

.faq-page .faq-contact .btn {
    background: white;
    color: #667eea;
    border: none;
    padding: 12px 30px;
    font-size: 1.1rem;
}

.faq-page .faq-contact .btn:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
}
</style>