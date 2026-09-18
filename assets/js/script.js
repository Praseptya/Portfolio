document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Inisialisasi Efek Animasi (AOS)
    AOS.init({
        once: true, // Animasi hanya berjalan sekali
        offset: 50, // Jarak trigger animasi lebih pendek agar di HP tetap muncul
        duration: 800, // Durasi halus 0.8 detik
        easing: 'ease-out-cubic',
    });

    // 2. Efek Navbar Berubah Saat Di-Scroll
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            // Jika halaman digulir lebih dari 50px ke bawah
            navbar.classList.add('scrolled');
        } else {
            // Jika halaman berada di paling atas
            navbar.classList.remove('scrolled');
        }
    });

});