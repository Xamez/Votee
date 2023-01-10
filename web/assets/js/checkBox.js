window.addEventListener("load", () => {

    const checkBoxes = document.getElementsByClassName("util-box");

    for (let i = 0; i < checkBoxes.length; i++) {
        const childs = checkBoxes[i].children;
        for (let j = 0; j < childs.length; j++) {
            const child = childs[j];
            if (child.tagName === "INPUT") {
                child.addEventListener("click", () => {
                    checkBoxes[i].classList.toggle("checkBox-checked");
                    checkBoxes[i].classList.toggle("bg-white");
                    checkBoxes[i].classList.toggle("border-transparent");
                });
                if (child.checked) {
                    checkBoxes[i].classList.toggle("checkBox-checked");
                    checkBoxes[i].classList.toggle("bg-white");
                    checkBoxes[i].classList.toggle("border-transparent");
                }
            }
        }
    }
});
