window.onload = () => {

    const navBurger = document.getElementById('nav-burger');
    const openIcon = document.getElementById('open-icon');
    const closeIcon = document.getElementById('close-icon');

    const accordions = document.getElementsByClassName("accordion");

    const toogleNav = () => {
        navBurger.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    };

    openIcon.addEventListener('click', toogleNav);
    closeIcon.addEventListener('click', toogleNav);

    for (let i = 0; i < accordions.length; i++) {
        accordions[i].addEventListener("click", function() {
            this.classList.toggle("active");
            let panel = this.nextElementSibling;
            panel.style.display = (panel.style.display === "block") ? "none" : "block";
        });
    }

};