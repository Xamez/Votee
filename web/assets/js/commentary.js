const commentary = {
    idQuestion: 0,
    idProposition: 0,
    numeroParagraphe: -1,
    indexCharDebut: -1,
    indexCharFin: -1,
    texteCommentaire: "",
};

function debounce(callback, wait) {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(function () { callback.apply(this, args); }, wait);
    };
}

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
    while (myElement.id === "")
        myElement = myElement.parentElement;
    return myElement;
}

let popup;

window.onload = () => {

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
        const tooltip = document.createElement('div');
        tooltip.classList.add('tooltipEditable', 'flex', 'flex-col', 'justify-center', 'cursor-default', 'z-1', 'absolute', 'bg-main', 'text-white', 'rounded', 'p-2', 'text-sm', 'shadow-lg', 'gap-3');
        tooltip.style.top = span.offsetTop + span.offsetHeight + 'px';
        tooltip.style.left = span.offsetLeft + 'px';

        const container = document.createElement('div');
        container.classList.add('flex', 'flex-row', 'justify-between', 'align-top');

        const input = document.createElement('input');
        input.addEventListener('keyup', debounce( () => {
            const data = {'idCommentaire': span.id, 'texteCommentaire': input.value};
            performRequest("updatedCommentaire", "commentaire=" + JSON.stringify(data))
                .then(() => window.location.reload());
        }, 1000));
        input.value = textCommentaire;

        const closeButton = document.createElement('span');
        closeButton.classList.add('cursor-pointer', 'material-symbols-outlined', 'text-red-500', 'hover:text-red-600', 'ml-4');
        closeButton.innerText = 'close';
        closeButton.addEventListener('click', () => tooltip.remove());
        container.appendChild(input);
        container.appendChild(closeButton);

        const deleteCommentary = document.createElement('button');
        deleteCommentary.classList.add('bg-red-500', 'hover:bg-red-600', 'border-none', 'text-white', 'rounded', 'p-1');
        deleteCommentary.innerText = 'Supprimer';
        deleteCommentary.addEventListener('click', () => {
            const data = {'idCommentaire': span.id};
            performRequest("deletedCommentaire", "commentaire=" + JSON.stringify(data))
                .then(() => window.location.reload());
        });

        tooltip.appendChild(container);
        tooltip.appendChild(deleteCommentary);
        span.appendChild(tooltip);
    }

    console.log("caled");
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
            if (id && !pCommentaryButton.classList.contains('line-through')) createEditableCommentary(e.target, id);
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

        const selectedHtmlEntityReference = selectedHtml
            .replaceAll("\"", "&quot;")
            .replaceAll("'", "&#039;")

        commentary.indexCharDebut = rawText.indexOf(selectedHtml);
        if (commentary.indexCharDebut === -1) return;

        let nbSpecialChars = 0;
        for (let i = 0; i < commentary.indexCharDebut; i++) {
            if (rawText[i] === '\"') nbSpecialChars++;
            else if (rawText[i] === '\'') nbSpecialChars++;
        }

        commentary.indexCharDebut += nbSpecialChars * 5;
        commentary.indexCharFin = commentary.indexCharDebut + selectedHtmlEntityReference.toString().length;

        popup.style.display = "block";
        popup.style.top = (selectedParagraph.offsetTop + selectedParagraph.offsetHeight - window.scrollY + 5) + "px";
        popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
    });

}

window.onresize = () => {
    if (popup.style.display === 'none') return;
    popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
}