let popup;

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
    return fetch('frontController.php?action=' + url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        },
        body: data,
    });
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

      
    const createEditableCommentary = (span, textCommentaire) => {
        const tooltip = document.createElement('div');
        tooltip.classList.add('tooltipEditable', 'flex', 'flex-col', 'justify-center', 'cursor-default', 'z-1', 'absolute', 'bg-main', 'text-white', 'rounded', 'p-2', 'text-sm', 'shadow-lg', 'gap-3');
        tooltip.style.top = span.offsetTop + span.offsetHeight + 'px';
        tooltip.style.left = span.offsetLeft + 'px';
        const container = document.createElement('div');
        container.classList.add('flex', 'flex-row', 'justify-between', 'align-top');
        const p = document.createElement('p');
        p.contentEditable = true;
        p.addEventListener('keyup', debounce( () => {
            commentary.texteCommentaire = p.innerText;
            performRequest("updatedCommentaire", "commentaire=" + JSON.stringify(commentary));
        }, 750));
        p.innerText = textCommentaire;
        const closeButton = document.createElement('span');
        closeButton.classList.add('cursor-pointer', 'material-symbols-outlined', 'text-red-500', 'hover:text-red-600', 'ml-4');
        closeButton.innerText = 'close';
        closeButton.addEventListener('click', () => {
            tooltip.remove();
        });
        container.appendChild(p);
        container.appendChild(closeButton);
        const deleteCommentary = document.createElement('button');
        deleteCommentary.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white', 'rounded', 'p-1');
        deleteCommentary.innerText = 'Supprimer';
        // TODO: Aucune erreur, la requête a bien un code de réponse de 200 mais le commentaire n'est pas supprimé.
        //       Le span.removeChild() est bien exécuté...
        deleteCommentary.addEventListener('click', () => {
            performRequest("deletedCommentaire", "commentaire=" + JSON.stringify(commentary))
            .then( () => {
                span.removeChild(tooltip);
            });
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
        performRequest("createdCommentaire", "commentaire=" + JSON.stringify(commentary)).then((res) => window.location.reload());
    });

    commentaryButton.addEventListener('click', () => {
        pCommentaryButton.classList.toggle('line-through');
        const tooltips = document.getElementsByClassName('tooltipEditable');
        if (tooltips.length > 0)
            // TODO: Pour une raison inconnu, il y'a toujours un qui n'est pas supprimé  
            for (let i = 0; i < tooltips.length; i++)
                tooltips[i].remove();
    });

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