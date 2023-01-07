window.addEventListener("load", () => {

    const navBurger = document.getElementById('nav-burger');
    const openIcon = document.getElementById('open-icon');
    const closeIcon = document.getElementById('close-icon');

    const toggleNav = () => {
        navBurger.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    };

    openIcon.addEventListener('click', toggleNav);
    closeIcon.addEventListener('click', toggleNav);

});