// public/assets/js/main.js

document.addEventListener("DOMContentLoaded", () => {

    /* ==========================================================
       1. Fade-in animation on page load
    ========================================================== */
    document.body.classList.add("page-loaded");


    /* ==========================================================
       2. Smooth scroll for anchor links (like #contact, #top)
    ========================================================== */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });


    /* ==========================================================
       3. Auto-hide navbar on scroll down
          (Professional websites often use this)
    ========================================================== */
    let lastScrollTop = 0;
    const navbar = document.querySelector(".navbar");

    window.addEventListener("scroll", () => {
        const current = window.pageYOffset;

        if (!navbar) return;

        if (current > lastScrollTop && current > 80) {
            // Scroll ke bawah → hide navbar
            navbar.style.top = "-80px";
        } else {
            // Scroll ke atas → show navbar
            navbar.style.top = "0";
        }
        lastScrollTop = current;
    });


    /* ==========================================================
       4. Back-to-top button (otomatis muncul saat scroll)
    ========================================================== */
    const backToTop = document.createElement("div");
    backToTop.innerHTML = "↑";
    backToTop.className = "back-to-top";
    document.body.appendChild(backToTop);

    window.addEventListener("scroll", () => {
        if (window.scrollY > 400) {
            backToTop.classList.add("show");
        } else {
            backToTop.classList.remove("show");
        }
    });

    backToTop.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });


});
