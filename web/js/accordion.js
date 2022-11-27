window.onload = () => {

    const accordions = document.getElementsByClassName("accordion");

    for (let i = 0; i < accordions.length; i++) {
        accordions[i].addEventListener("click", () => {
            this.classList.toggle("active");
            let panel = this.nextElementSibling;
            panel.style.display = (panel.style.display === "block") ? "none" : "block";
        });
    }

};