window.addEventListener("load", () => {

    const textAreas = [...document.getElementsByClassName('section')];

    textAreas.forEach(textArea => {
        textArea.onkeydown = (e) => {
            if (e.key === '<' || e.key === '>' || e.key === '&')
                e.preventDefault();
        }
        textArea.addEventListener('paste', (e) => {
            const text = e.clipboardData.getData("text/plain");
            if (text.includes('<') || text.includes('>') || text.includes('&'))
                e.preventDefault();
        });
    });

});