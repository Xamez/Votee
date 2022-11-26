let popup;

const commentary = {
    numParagraph: -1,
    indexCharacterStart: -1,
    indexCharacterEnd: -1,
    text: ""
};

window.onload = () => {

    const navBurger = document.getElementById('nav-burger');
    const openIcon = document.getElementById('open-icon');
    const closeIcon = document.getElementById('close-icon');

    const createCommentaryButton = document.getElementById('create-commentary');
    const commentaryButton = document.getElementById('commentary-button');
    const pCommentaryButton = commentaryButton.children[1];

    popup = document.getElementById('popup');

    let selectedText = "";

    let moved = false;

    createCommentaryButton.addEventListener('click', () => {
        commentary.text = popup.children[1].value;
        popup.style.display = "none";
        // créer commentaire ici dans bdd + afficher message confirmation jsp comment ?
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

    document.addEventListener('mouseup', (e) => {
        const selection = document.getSelection();
        selectedText = selection.toString();
        if (selection.type !== 'Range') return;
        if (popup.style.display !== 'none') return;
        if (pCommentaryButton.classList.contains('line-through')) return;
        const selectedParagraph = selection.anchorNode.parentElement;
        if (selectedParagraph !== selection.focusNode.parentElement) return;
        let numParagraph = selectedParagraph.id;
        if (numParagraph === "") return;
        performHidePopup(e);
        commentary.numParagraph = numParagraph;
        commentary.indexCharStart = selectedParagraph.innerHTML.indexOf(selectedText);
        commentary.indexCharEnd = commentary.indexCharStart + selectedText.length;
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