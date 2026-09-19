document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Inisialisasi Animation On Scroll (AOS)
    AOS.init({
        once: true,
        offset: 50,
        duration: 800,
        easing: 'ease-out-cubic',
    });

    // 2. Inisialisasi VanillaTilt Mandiri per Card
    const projectCards = document.querySelectorAll(".project-card");
    
    if (projectCards.length > 0 && typeof VanillaTilt !== 'undefined') {
        projectCards.forEach((card) => {
            VanillaTilt.init(card, {
                max: 6,              // Kemiringan yang lembut
                speed: 1000,         // Kecepatan transisi melingkar
                glare: true,         // Efek pantulan cahaya
                "max-glare": 0.15,
                reset: true,         // Mengembalikan kartu ke posisi semula saat kursor keluar
                gyroscope: false
            });
        });
    }

    // 3. Efek Transisi Navbar Scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

});