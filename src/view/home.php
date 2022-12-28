<div>
    <div class="flex flex-col lg:flex-row items-center justify-center pt-6 w-10/12 mx-auto">
        <div class="flex flex-col">
            <h1 class="text-4xl font-bold w-96 text-center lg:text-left"><span class="text-dark">Venez voter en</span><br/><span class="text-main">quelques secondes</span></h1>
            <h3 class="text-lg text-gray-400 pt-3 text-center lg:text-left">Exprime ton opinion en un clic !</h3>
            <div class="flex pt-8 mx-auto lg:mx-0">
                <a class="p-3 pl-6 pr-6 text-white bg-main font-bold rounded-md shadow-button" href="./frontController.php?action=section">Créer un vote</a>
            </div>
        </div>
        <img class="lg:w-10/12" src="assets/resources/undraw_voting.png" alt="Vote">
    </div>
    <?php
    echo '
    <svg class="w-full pt-10" viewBox="0 0 1200 197" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 164.554L59.375 143.923C116.75 123.293 231.5 82.0317 346.25 82.0317C461 82.0317 575.75 123.293 690.5 107.82C805.25 92.347 920 20.14 1034.75 20.14C1149.5 20.14 1264.25 92.347 1321.62 128.45L1379 164.554V195.5H1321.62C1264.25 195.5 1149.5 195.5 1034.75 195.5C920 195.5 805.25 195.5 690.5 195.5C575.75 195.5 461 195.5 346.25 195.5C231.5 195.5 116.75 195.5 59.375 195.5H2V164.554Z" fill="#AEA4FF" fill-opacity="0.8"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 70.7686H57.4583C114.917 70.7686 229.833 70.7686 344.75 58.1456C459.667 45.5226 574.583 20.2766 689.5 37.1073C804.417 53.9379 919.333 112.845 1034.25 121.261C1149.17 129.676 1264.08 87.5992 1321.54 66.5609L1379 45.5226V196.999H1321.54C1264.08 196.999 1149.17 196.999 1034.25 196.999C919.333 196.999 804.417 196.999 689.5 196.999C574.583 196.999 459.667 196.999 344.75 196.999C229.833 196.999 114.917 196.999 57.4583 196.999H0V70.7686Z" fill="#8080D7" fill-opacity="0.8"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 75.1952L33.3258 58.9546C65.5025 42.7139 131.005 10.2327 196.508 2.11234C263.159 -6.00798 328.662 10.2327 394.164 34.5936C459.667 58.9546 525.169 91.4358 590.672 119.857C656.174 148.278 722.826 172.639 788.328 176.699C853.831 180.759 919.333 164.519 984.836 132.037C1050.34 99.5562 1115.84 50.8342 1182.49 50.8342C1247.99 50.8342 1313.5 99.5562 1345.67 123.917L1379 148.278V197H1345.67C1313.5 197 1247.99 197 1182.49 197C1115.84 197 1050.34 197 984.836 197C919.333 197 853.831 197 788.328 197C722.826 197 656.174 197 590.672 197C525.169 197 459.667 197 394.164 197C328.662 197 263.159 197 196.508 197C131.005 197 65.5025 197 33.3258 197H0V75.1952Z" fill="#B8B8F8" fill-opacity="0.8"/>
    </svg>
    ';
    ?>
    <div class="flex bg-home relative -top-1 flex-col items-center mx-auto gap-y-16 pb-24"> <!-- permet d'éviter un bug visuel d'un petit trait blanc -->
        <div class="flex flex-col lg:flex-row w-4/5 gap-8 items-center pt-10">
            <div class="w-96 text-center lg:text-left">
                <i class="fa-regular fa-envelope text-white text-2xl px-3 py-2 bg-main rounded-lg"></i>
                <h3 class="text-white font-bold text-xl pt-2">Voter, c'est facile !</h3>
                <p class="text-dark text-md pt-4">Votee, un système de vote dématérialisée à bulletin secret fiable, simple et sécurisée.<br/>Disponible partout et maintenant</p>
                <div class="flex pt-3 justify-center lg:justify-start">
                    <a class="p-2 pl-6 pr-6 text-white bg-main rounded-md font-semibold shadow" href="./frontController.php?action=readAllQuestion">Vote</a>
                </div>
            </div>
            <img class="lg:w-8/12 rounded-3xl shadow-md" src="assets/resources/voter.png" alt="Voter">
        </div>
        <div class="flex flex-col flex-col-reverse lg:flex-row gap-8 items-center justify-center">
            <img class="w-10/12 lg:w-1/2 rounded-3xl shadow-md" src="assets/resources/creer_vote.png" alt="Créer un vote">
            <div class="w-96 text-center lg:text-left">
                <i class="fa fa-pen-to-square text-white text-2xl px-3 py-2 bg-main rounded-lg"></i>
                <h3 class="text-white font-bold text-xl pt-2">Créer une question, c'est trivial !</h3>
                <p class="text-dark text-md pt-4">Réaliser un vote avec Votee pour déterminer simplement et rapidement l’opinion du public.<br/>Personnaliser votre vote comme bon vous semble.</p>
                <div class="flex flex-row gap-x-4 justify-center lg:justify-start pt-3">
                    <a class="p-2 pl-6 pr-6 text-white bg-main rounded-lg font-semibold shadow" href="./frontController.php?action=section">Créer un vote</a>
                    <a class="p-2 pl-6 pr-6 text-main bg-white rounded-lg font-semibold shadow" href="./frontController.php?action=exemple">Voir un exemple</a>
                </div>
            </div>
        </div>
    </div>
    <div class="flex relative -top-1">
        <div class="flex bg-image-urne w-screen h-40 items-center justify-center">
            <div class="flex flex-row w-3/5 items-center justify-between">
                <div class="flex flex-col">
                    <h3 class="text-2xl md:text-4xl font-bold text-main">Étes vous prêt à vous lancer ?</h3>
                    <h4 class="text-xl md:text-3xl font-semibold text-white">Commencer maintenant !</h4>
                </div>
                <a class="p-2 pl-6 pr-6 text-white bg-dark rounded-md font-semibold shadow" href="./frontController.php?action=readAllQuestion">Voter</a>
            </div>
        </div>
    </div>
</div>
