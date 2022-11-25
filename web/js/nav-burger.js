let popup;

window.onload = () => {

    const navBurger = document.getElementById('nav-burger');
    const openIcon = document.getElementById('open-icon');
    const closeIcon = document.getElementById('close-icon');

    const createCommentaryButton = document.getElementById('create-commentary');
    const commentaryButton = document.getElementById('commentary-button');
    const pCommentaryButton = commentaryButton.children[1];

    popup = document.getElementById('popup');

    let numParagraph = -1;
    let indexCharacterStart = -1;
    let indexCharacterEnd = -1;

    let moved = false;

    createCommentaryButton.addEventListener('click', () => {
        console.log("C'est créer");
    });

    commentaryButton.addEventListener('click', () => {
        pCommentaryButton.classList.toggle('line-through');
    });

    const performHidePopup = (e) => {
        if (!moved && !popup.contains(e.target))
            popup.style.display = "none";
    }

    document.addEventListener('mousedown', () => {   
        moved = false;
    });

    document.addEventListener('mouseup', e => performHidePopup(e));

    document.addEventListener('mousemove', (e) => {
        performHidePopup(e);
        moved = true;
    });

    // TODO: giga problème le 'selectedText' envoie que 1 ou 2 caractères et pas le texte sélectionné
    document.addEventListener('selectionchange', (e) => {
        const selection = document.getSelection();
        if (selection.type !== 'Range') return;
        if (popup.style.display !== 'none') return;
        if (pCommentaryButton.classList.contains('line-through')) return;
        performHidePopup(e);
        const selectedParagraph = selection.anchorNode.parentElement;
        const selectedText = selection.toString();
        console.log(selectedText);
        numParagraph = selectedParagraph.id;
        indexCharacterStart = selectedParagraph.innerHTML.indexOf(selectedText);
        indexCharacterEnd = indexCharacterStart + selectedText.length;
        popup.style.display = "block";
        popup.style.top = (selectedParagraph.offsetTop + selectedParagraph.offsetHeight - window.scrollY + 5) + "px";
        popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
    });

    // document.addEventListener('selectionchange', e => {
    //     const selection = document.getSelection();
    //     if (selection.type === 'Range' && popup.style.display === "none" && !pCommentaryButton.classList.contains('line-through')) {
    //         performHidePopup(e);
    //         const selectedParagraph = selection.anchorNode.parentElement;
    //         const selectedText = selection.toString();
    //         numParagraph = parseInt(selectedParagraph.id);
    //         // if (numParagraph !== previousParagraph) {
    //         //     for (let i = 0; i < paragraphs.length; i++) {
    //         //         if (paragraphs[i].id !== selectedParagraph.id)
    //         //             paragraphs[i].classList.add('select-none');
    //         //     }
    //         //     previousParagraph = numParagraph;
    //         // }
    //         indexCharacterStart = selectedParagraph.innerHTML.indexOf(selectedText);
    //         console.log(selectedText);
    //         // if (indexCharacterStart === -1) {
    //         //     performHidePopup(e);
    //         // }
    //         indexCharacterEnd = indexCharacterStart + selectedText.length;
    //         popup.style.display = "block";
    //         popup.style.top = (selectedParagraph.offsetTop + selectedParagraph.offsetHeight - window.scrollY + 5) + "px";
    //         popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
    //     }
    // });

    const toggleNav = () => {
        navBurger.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    };

    openIcon.addEventListener('click', toggleNav);
    closeIcon.addEventListener('click', toggleNav);

};

window.onbeforeunload = () => {
    popup.style.display = "none";
}