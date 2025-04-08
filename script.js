// Script para animações e interatividade

document.addEventListener('DOMContentLoaded', function() {
    // Animação suave de scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Botões de chamada para ação
    const ctaButtons = document.querySelectorAll('.cta-button, .footer-button, .green-button');
    ctaButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Redirecionar para a página de contato
            window.location.href = '/contact.html';
        });
    });

    // Botão de serviços
    // const servicesButton = document.querySelector('.green-button');
    // if (servicesButton) {
    //     servicesButton.addEventListener('click', function() {
    //         // Implementar funcionalidade de ver mais serviços
    //         alert('Mais serviços serão exibidos aqui!');
    //     });
    // }

    // Animação de fade-in para elementos quando são visíveis no scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.stat-item, .service-item, .excellence-item, .testimonial');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementTop < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };

    // Inicializar estilos para animação
    const elementsToAnimate = document.querySelectorAll('.stat-item, .service-item, .excellence-item, .testimonial');
    elementsToAnimate.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    });

    // Executar animação no carregamento e no scroll
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Executar uma vez no carregamento
});