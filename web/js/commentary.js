let popup;

const commentary = {
    idQuestion: 0,
    idProposition: 0,
    numeroParagraphe: -1,
    indexCharDebut: -1,
    indexCharFin: -1,
    texteCommentaire: "",
};

window.onload = () => {

    const ids = document.getElementById("ids");
    commentary.idQuestion = ids.children[0].value;
    commentary.idProposition = ids.children[1].value;

    const createCommentaryButton = document.getElementById('create-commentary');
    const commentaryButton = document.getElementById('commentary-button');
    const pCommentaryButton = commentaryButton.children[1];
    const commentaries = document.getElementsByClassName('commentary');

    popup = document.getElementById('popup');

    let selectedText = "";

    let moved = false;

    const createTooltip = (span, textCommentaire) => {
        const tooltip = document.createElement('div');
        tooltip.id = 'tooltip';
        tooltip.classList.add('z-1', 'absolute', 'bg-main', 'text-white', 'rounded', 'p-2', 'text-sm', 'shadow-lg');
        tooltip.style.top = event.pageY + 'px';
        tooltip.style.left = event.pageX + 'px';
        const p = document.createElement('p');
        p.innerText = textCommentaire;
        tooltip.appendChild(p);
        span.appendChild(tooltip);
    }

    console.log(commentaries)
    for (let i = 0; i < commentaries.length; i++) {
        let commentary = commentaries[i];
        commentary.addEventListener("mouseover", e => {
            const id = e.target.getAttribute("data-id");
            if (id) createTooltip(e.target, id);
        });

        commentary.addEventListener("mouseout", e => {
            const tooltip = document.getElementById('tooltip');
            if (tooltip) tooltip.remove();
        });
    }


    createCommentaryButton.addEventListener('click', () => {
        commentary.texteCommentaire = popup.children[0].children[1].value;
        popup.style.display = "none";
        fetch("frontController.php?action=createdCommentaire", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: "commentaire=" + JSON.stringify(commentary),
        }).then((res) => window.location.reload());
    });

    commentaryButton.addEventListener('click', () => pCommentaryButton.classList.toggle('line-through'));

    const performHidePopup = (e) => {
        if (!moved && !popup.contains(e.target))
            popup.style.display = "none";
    }

    document.addEventListener('mousedown', () => moved = false);

    document.addEventListener('mousemove', (e) => { performHidePopup(e); moved = true; });

    document.addEventListener('mouseup', (e) => {
        const selection = document.getSelection();
        selectedText = selection.toString();
        if (selection.type !== 'Range') return;
        if (popup.style.display !== 'none') return;
        if (pCommentaryButton.classList.contains('line-through')) return;
        const selectedParagraph = selection.anchorNode.parentElement;
        if (selectedParagraph !== selection.focusNode.parentElement) return;
        if (selectedParagraph.id === "") return;
        let numParagraph = parseInt(selectedParagraph.id);
        performHidePopup(e);
        commentary.numeroParagraphe = numParagraph;
        commentary.indexCharDebut = selectedParagraph.innerHTML.indexOf(selectedText);
        commentary.indexCharFin = commentary.indexCharDebut + selectedText.length;
        popup.style.display = "block";
        popup.style.top = (selectedParagraph.offsetTop + selectedParagraph.offsetHeight - window.scrollY + 5) + "px";
        popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
    });

}

window.onbeforeunload = () => {
    popup.style.display = "none";
}