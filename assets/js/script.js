document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Inisialisasi Animation On Scroll (AOS)
    AOS.init({
        once: true,
        offset: 50,
        duration: 800,
        easing: 'ease-out-cubic',
    });

    // 2. Efek Transisi Navbar Scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
    });

    // 3. Ambil Data dari JSON
    fetch('data/data.json')
        .then(response => response.json())
        .then(data => {
            renderSkills(data.skills);
            renderProjects(data.projects);

            // 4. Inisialisasi 3D Tilt SETELAH kartu proyek dirender
            const projectCards = document.querySelectorAll(".project-card");
            if (projectCards.length > 0 && typeof VanillaTilt !== 'undefined') {
                projectCards.forEach((card) => {
                    VanillaTilt.init(card, {
                        max: 6,
                        speed: 1000,
                        glare: true,
                        "max-glare": 0.15,
                        reset: true,
                        gyroscope: false
                    });
                });
            }
        })
        .catch(error => console.error("Gagal memuat data JSON:", error));

    // --- FUNGSI RENDER HTML DINAMIS --- //

    function renderSkills(skillsData) {
        const container = document.getElementById('skills-container');
        if (!container) return;

        for (const [category, skillsArray] of Object.entries(skillsData)) {
            let badgesHTML = skillsArray.map(skill => `<span class="skill-badge">${skill}</span>`).join('');
            
            let htmlTemplate = `
                <div class="skill-category" data-aos="fade-up">
                    <h4 class="category-title">${category}</h4>
                    <div class="layout-grid-left">
                        ${badgesHTML}
                    </div>
                </div>
            `;
            container.innerHTML += htmlTemplate;
        }
    }

    function renderProjects(projectsArray) {
        const container = document.getElementById('projects-grid');
        if (!container) return;

        projectsArray.forEach((project, index) => {
            let tagsHTML = project.tech_stack.map(tech => `<span class="tag">${tech}</span>`).join('');
            
            // Logika Fallback Gambar vs Inisial
            let imageDisplay = project.image_file && project.image_file !== "" 
                ? `<img src="${project.image_file}" alt="${project.title}" class="project-img" onerror="this.outerHTML='<h2 class=\\'project-initial\\'>${project.initial}</h2>'">`
                : `<h2 class="project-initial">${project.initial}</h2>`;

            let cardTemplate = `
                <div class="project-card" data-aos="fade-up" data-aos-delay="${index * 100}">
                    <div class="project-img-wrapper">
                        <span class="project-year">${project.year}</span>
                        ${imageDisplay}
                    </div>
                    
                    <div class="project-info">
                        <h3>${project.title}</h3>
                        <h4 class="project-short-desc">${project.short_desc}</h4>
                        <p class="project-role">${project.role}</p>
                        
                        <p class="project-description">${project.description}</p>
                        
                        <div class="project-tags">
                            ${tagsHTML}
                        </div>

                        <div style="margin-top: auto; padding-top: 10px;">
                            <a href="${project.link}" target="_blank" class="btn-outline btn-sm">View Source &#8599;</a>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += cardTemplate;
        });
    }

});