window.addEventListener("load", () => {

    const propositions = [...document.getElementsByClassName('proposition')];
    const users = [...document.getElementsByClassName('user')];

    const activeProposition = [];
    const propositionsMap = [];
    const usersMap = [];

    propositions.forEach(proposition => {
        const idProposition = proposition.getAttribute('data-id');
        propositionsMap[idProposition] = proposition;
        proposition.children[0].addEventListener('click', () => hideProposition(idProposition));
    });
    users.forEach(user => usersMap[user.getAttribute('data-id')] = user);

    users.forEach(user => {
        user.addEventListener('click', () => {
            const idProposition = user.getAttribute('data-id');
            if (propositionsMap[idProposition].classList.contains('hidden'))
                showProposition(idProposition);
            else
                hideProposition(idProposition);
        });
    });

    function hideProposition(idProposition) {
        propositionsMap[idProposition].classList.add('hidden');
        activeProposition.splice(activeProposition.indexOf(idProposition), 1);
    }

    function showProposition(idProposition) {
        activeProposition.forEach(id => hideProposition(id));
        const proposition = propositionsMap[idProposition];
        const user = usersMap[idProposition];
        proposition.style.top = (user.offsetTop + user.offsetHeight - window.scrollY + 5) + "px";
        proposition.classList.remove('hidden');
        activeProposition.push(idProposition);
    }

});