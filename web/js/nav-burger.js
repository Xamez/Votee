let popup;

window.onload = () => {

    const navBurger = document.getElementById('nav-burger');
    const openIcon = document.getElementById('open-icon');
    const closeIcon = document.getElementById('close-icon');

    popup = document.getElementById('popup');
    const createCommentary = document.getElementById('create-commentary');

    let nbParagraph;
    let indexCharacter;

    let moved = false;

    createCommentary.addEventListener('click', () => {
        console.log("C'est créer");
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

    document.addEventListener('selectionchange', (e) => {
        const selection = document.getSelection();
        if (selection.type === 'Range' && popup.style.display === "none") {
            performHidePopup(e);
            const selectedParagraph = selection.anchorNode.parentElement;
            const selectedText = selection.toString();
            nbParagraph = selectedParagraph.id;
            indexCharacter = selectedParagraph.innerHTML.indexOf(selectedText);
            popup.style.display = "block";
            popup.style.top = `${selectedParagraph.offsetTop + selectedParagraph.offsetHeight}px`;
            popup.style.left = `${window.innerWidth / 2 - popup.offsetWidth / 2}px`;
        }
    });

    const toogleNav = () => {
        navBurger.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    };

    openIcon.addEventListener('click', toogleNav);
    closeIcon.addEventListener('click', toogleNav);

};

window.onbeforeunload = () => {
    popup.style.display = "none";
}