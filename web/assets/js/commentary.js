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

function getElementId(element) {
    let myElement = element;
    while (myElement.id === "")
        myElement = myElement.parentElement;
    return myElement.id;
}

window.onload = () => {

    const ids = document.getElementById("ids");
    commentary.idQuestion = ids.children[0].value;
    commentary.idProposition = ids.children[1].value;

    const createCommentaryButton = document.getElementById('create-commentary');
    const commentaryButton = document.getElementById('commentary-button');
    const pCommentaryButton = commentaryButton.children[1];
    const commentaryText = document.getElementById('text-commentary');
    const commentaries = document.getElementsByClassName('commentary');
    
    const popup = document.getElementById('popup');

    let selectedText = "";

    let moved = false;

    const createTooltip = (span, textCommentaire) => {
        const tooltip = document.createElement('p');
        tooltip.id = 'tooltip';
        tooltip.classList.add('flex', 'z-1', 'absolute', 'bg-main', 'text-white', 'text-sm', 'p-2', 'rounded', 'shadow-lg');
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

        const p = document.createElement('p');
        p.contentEditable = 'true';
        p.addEventListener('keyup', debounce( () => {
            const data = {'idCommentaire': span.id, 'texteCommentaire': p.innerText};
            performRequest("updatedCommentaire", "commentaire=" + JSON.stringify(data))
                .then(() => window.location.reload());
        }, 1000));
        p.innerText = textCommentaire;

        const closeButton = document.createElement('span');
        closeButton.classList.add('cursor-pointer', 'material-symbols-outlined', 'text-red-500', 'hover:text-red-600', 'ml-4');
        closeButton.innerText = 'close';
        closeButton.addEventListener('click', () => tooltip.remove());
        container.appendChild(p);
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

    for (let i = 0; i < commentaries.length; i++) {
        let commentary = commentaries[i];
        commentary.addEventListener("mouseover", e => {
            const id = e.target.getAttribute("data-id");
            if (id && pCommentaryButton.classList.contains('line-through')) createTooltip(e.target, id);
        });

        commentary.addEventListener("mouseout", e => {
            const tooltip = document.getElementById('tooltip');
            if (tooltip) tooltip.remove();
        });

        commentary.addEventListener("click", e => {
            const id = e.target.getAttribute("data-id");
            if (id && !pCommentaryButton.classList.contains('line-through')) createEditableCommentary(e.target, id);
        });
    }

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
        // TODO: revoir ça
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
        // FIN TODO
        const selectedText = window.getSelection();
        if (selection.type !== 'Range') return;
        if (popup.style.display !== 'none') return;
        if (pCommentaryButton.classList.contains('line-through')) return;
        const selectedParagraph = selection.anchorNode.parentElement;
        if (selectedParagraph !== selection.focusNode.parentElement) return;
        let idElement = getElementId(selectedParagraph);
        if (idElement === "") return;
        performHidePopup(e);
        let numParagraph = parseInt(idElement);
        commentary.numeroParagraphe = numParagraph;
        if (selectedParagraph.parentElement.id === "") return;
        performHidePopup(e);
        commentary.numeroParagraphe = numParagraph;
        // TODO: Problème en raison des balises compté dans outerHTML (pour avoir le bon index et selectedText qui prends pas en compte)
        commentary.indexCharDebut = selectedParagraph.outerHTML.indexOf(selectedHtml);
        commentary.indexCharFin = commentary.indexCharDebut + selectedText.toString().length;
        // FIN TODO
        popup.style.display = "block";
        popup.style.top = (selectedParagraph.offsetTop + selectedParagraph.offsetHeight - window.scrollY + 5) + "px";
        popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
    });

}