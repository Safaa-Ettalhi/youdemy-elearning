const burgerIcon = document.getElementById('burger-icon');
        const mobileMenu = document.getElementById('mobile-menu');
        const menu = document.getElementById('menu');

        burgerIcon.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });