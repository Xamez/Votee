const commentary = {
    idQuestion: 0,
    idProposition: 0,
    numeroParagraphe: -1,
    indexCharDebut: -1,
    indexCharFin: -1,
    texteCommentaire: "",
};

function performRequest(url, data) {
    return fetch('frontController.php?controller=proposition&action=' + url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: data,
    });
}

function getElement(element) {
    let myElement = element;
    while (myElement.id === "") {
        myElement = myElement.parentElement;
        if (myElement === null) return "";
    }
    return myElement;
}

let popup;

window.addEventListener("load", () => {

    const ids = document.getElementById("ids");
    commentary.idQuestion = ids.children[0].value;
    commentary.idProposition = ids.children[1].value;

    const createCommentaryButton = document.getElementById('create-commentary');
    const commentaryButton = document.getElementById('commentary-button');
    const pCommentaryButton = commentaryButton?.children[1];
    const commentaryText = document.getElementById('text-commentary');
    const commentaries = [...document.getElementsByClassName('commentary')];

    popup = document.getElementById('popup');

    let moved = false;

    const createTooltip = (span, textCommentaire) => {
        const tooltip = document.createElement('p');
        tooltip.id = 'tooltip';
        tooltip.classList.add('flex', 'z-1', 'absolute', 'bg-main', 'text-white', 'text-sm', 'font-normal', 'p-2', 'rounded', 'shadow-lg');
        tooltip.style.top = event.pageY + 'px';
        tooltip.style.left = event.pageX + 'px';
        tooltip.innerText = textCommentaire;
        span.appendChild(tooltip);
    }

      
    const createEditableCommentary = (span, textCommentaire) => {
        const editableCommentary = document.createElement('div');
        editableCommentary.classList.add('absolute', 'tooltipEditable');
        const editableCommentaryHTML = `
        <div class="cursor-default shadow-4xl z-1 bg-white text-main rounded-xl p-4">
            <div class="flex flex-col items-center justify-center gap-2">
                <p class="text-md font-bold border-0">Éditer le commentaire</p>
                <textarea id="text-commentary" class="border-2 text-black resize-none h-36 w-96 rounded-xl ring-0 focus:outline-none" maxlength="750" placeholder="Entrez votre commentaire..." required>` + textCommentaire + `</textarea>
                <div class="flex flex-row flex-wrap gap-2">
                    <button id="cancel-commentary" class="cursor-pointer border-none text-lg font-bold p-2 text-white bg-main font-semibold rounded-lg">Annuler</button>
                    <button id="delete-commentary" class="cursor-pointer border-none text-lg font-bold p-2 text-white bg-red font-semibold rounded-lg">Supprimer</button>
                    <button id="update-commentary" class="cursor-pointer border-none text-lg font-bold p-2 text-white bg-green-400 font-semibold rounded-lg">Confirmer</button>
                </div>
            </div>
        </div>`;

        editableCommentary.innerHTML = editableCommentaryHTML;
        span.appendChild(editableCommentary);
        editableCommentary.style.left = (window.innerWidth / 2 - editableCommentary.offsetWidth / 2) + "px";
        editableCommentary.style.top = (span.getBoundingClientRect().top + span.offsetHeight + window.scrollY) + "px";

        const cancelCommentary = document.getElementById('cancel-commentary');
        const deleteCommentary = document.getElementById('delete-commentary');
        const updateCommentary = document.getElementById('update-commentary');
        const textCommentary = document.getElementById('text-commentary');

        cancelCommentary.addEventListener('click', () => span.removeChild(editableCommentary));
        deleteCommentary.addEventListener('click', () => {
            const data = {'idCommentaire': span.id};
            performRequest("deletedCommentaire", "commentaire=" + JSON.stringify(data))
                .then(() => window.location.reload());
        });
        updateCommentary.addEventListener('click', () => {
            const data = {'idCommentaire': span.id, 'texteCommentaire': textCommentary.value};
            performRequest("updatedCommentaire", "commentaire=" + JSON.stringify(data))
                .then(() => window.location.reload());
        });
    }

    commentaries.forEach(commentary => {
        commentary.addEventListener("mouseover", e => {
            const id = e.target.getAttribute("data-id");
            if (id && (pCommentaryButton === undefined || pCommentaryButton.classList.contains('line-through'))) createTooltip(e.target, id);
        });

        commentary.addEventListener("mouseout", e => {
            const tooltip = document.getElementById('tooltip');
            if (tooltip) tooltip.remove();
        });

        commentary.addEventListener("click", e => {
            const id = e.target.getAttribute("data-id");
            if (id && !pCommentaryButton.classList.contains('line-through') && document.getElementsByClassName("tooltipEditable").length === 0)
                createEditableCommentary(e.target, id);
        });
    });

    createCommentaryButton.addEventListener('click', () => {
        commentary.texteCommentaire = commentaryText.value;
        popup.style.display = "none";
        commentaryText.value = ""
        performRequest("createdCommentaire", "commentaire=" + JSON.stringify(commentary))
            .then(() => window.location.reload());
    });

    commentaryButton.addEventListener('click', () => {
        pCommentaryButton.classList.toggle('line-through');
        const tooltips = [...document.getElementsByClassName('tooltipEditable')];
        tooltips.forEach(tooltip => tooltip.remove());
    });

    const performHidePopup = (e) => {
        if (!moved && !popup.contains(e.target))
            popup.style.display = "none";
    }

    document.addEventListener('mousedown', () => moved = false);

    document.addEventListener('mousemove', (e) => { performHidePopup(e); moved = true; });

    document.addEventListener('mouseup', (e) => {

        const selection = window.getSelection ? window.getSelection() : document.selection.createRange();
        let selectedHtml = "";
        if (selection.rangeCount) {
            let container = document.createElement("div");
            for (let j = 0; j < selection.getRangeAt(0).cloneContents().childNodes.length; j++) {
                let node = selection.getRangeAt(0).cloneContents().childNodes[j];
                container.appendChild(node);
            }
            selectedHtml = container.innerHTML;
        }

        if (selection.type !== 'Range') return;
        if (popup.style.display !== 'none') return;
        if (pCommentaryButton.classList.contains('line-through')) return;
        const selectedParagraph = selection.anchorNode.parentElement;
        if (selectedParagraph !== selection.focusNode.parentElement) return;
        let element = getElement(selectedParagraph);
        if (element === "") return;

        performHidePopup(e);

        commentary.numeroParagraphe = parseInt(element.id);

        // On fait ça car le texte sélectionné considère que c'est des "<br>" mais dans le source code c'est des "<br />
        selectedHtml = selectedHtml.replaceAll("<br>", "<br />");

        const rawText = element.getAttribute('data-id');

        // ne devrait jamais arrivé mais au cas où un de ces caractères est dans le texte, on arrête tout pour éviter les bugs
        if (selectedHtml.includes("&amp;") || selectedHtml.includes("&lt;") || selectedHtml.includes("&gt;")) return;

        // avoir le bon nombre de caractère dans la sélection
        const selectedHtmlEntityReference = selectedHtml
            .replaceAll("\"", "&quot;")
            .replaceAll("'", "&#039;")

        commentary.indexCharDebut = rawText.indexOf(selectedHtml);
        if (commentary.indexCharDebut === -1) return;

        // avoir le bon nombre de caractère avant la sélection
        let nbSpecialChars = 0;
        for (let i = 0; i < commentary.indexCharDebut; i++) {
            if (rawText[i] === '\"') nbSpecialChars++;
            else if (rawText[i] === '\'') nbSpecialChars++;
        }

        commentary.indexCharDebut += nbSpecialChars * 5;
        commentary.indexCharFin = commentary.indexCharDebut + selectedHtmlEntityReference.toString().length;

        popup.style.display = "block";
        popup.style.top = (selectedParagraph.offsetTop + selectedParagraph.offsetHeight - window.scrollY + 5) + "px";
        popup.style.left = (window.innerWidth / 2 - popup.offsetWidth / 2) + "px";
    });

});

window.addEventListener('resize', () => {
    const tooltips = [...document.getElementsByClassName('tooltipEditable')];
    tooltips.forEach(tooltip => tooltip.style.left = (window.innerWidth / 2 - tooltip.offsetWidth / 2) + "px");
    if (popup.style.display === 'none') return;
    popup.style.left = (window.innerWidth / 2 - popup.offsetWidth / 2) + "px";
});

window.addEventListener('scroll', () => {
    const tooltips = [...document.getElementsByClassName('tooltipEditable')];
    tooltips.forEach(tooltip => tooltip.style.top += window.scrollY);
    if (popup.style.display === 'none') return;
    popup.style.top = (popup.offsetTop + window.scrollY) + "px";
});