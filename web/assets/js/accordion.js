window.addEventListener("load", () => {

    const accordions = document.getElementsByClassName("accordion");

    for (let i = 0; i < accordions.length; i++) {
        accordions[i].addEventListener("click", (e) => {
            accordions[i].classList.toggle("active");
            let panel = accordions[i].nextElementSibling;
            panel.style.display = (panel.style.display === "block") ? "none" : "block";
        });
    }
});
