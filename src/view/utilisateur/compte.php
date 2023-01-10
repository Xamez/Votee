<div class="flex flex-col lg:flex-row justify-between gap-5">

    <a class="cursor-pointer w-full h-96 flex flex-col items-center shadow-md hover:shadow-lg rounded-xl" href="./frontController.php?controller=utilisateur&action=readAllQuestion">
        <div class="flex items-center justify-center max-w-xs h-48 overflow-hidden">
            <img class="w-full min-w-full min-h-full" src="assets/resources/questionProfil.png" alt="logo">
        </div>
        <div class="flex flex-col gap-5 p-4">
            <span class="font-bold text-2xl text-center text-main">Mes rôles</span>
            <span class="lg:text-left text-center">Accédez à tous vos rôles sur les questions du site.</span>
        </div>
    </a>

    <a class="cursor-pointer w-full h-96 flex flex-col items-center shadow-md hover:shadow-lg rounded-xl" href="./frontController.php?controller=utilisateur&action=historiqueDemande">
        <div class="flex items-center justify-center max-w-xs h-48 overflow-hidden">
            <img class="w-full min-w-full min-h-full" src="assets/resources/demandeProfil.png" alt="logo">
        </div>
        <div class="flex flex-col gap-5 p-4">
            <span class="font-bold text-2xl text-center text-main">Mes demandes</span>
            <span class="lg:text-left text-center">Accédez à l'historique de toutes vos demandes de fusion et de création et vérifiez l'état de celles-ci.</span>
        </div>
    </a>

    <a class="cursor-pointer w-full h-96 flex flex-col items-center shadow-md hover:shadow-lg rounded-xl" href="./frontController.php?controller=utilisateur&action=readUtilisateur">
        <div class="flex items-center justify-center max-w-xs h-48 overflow-hidden">
            <img class="w-full min-w-full min-h-full" src="assets/resources/account.png" alt="logo">
        </div>
        <div class="flex flex-col gap-5 p-4">
            <span class="font-bold text-2xl text-center text-main">Mon compte</span>
            <span class="lg:text-left text-center">Modifiez vos informations personnelles, votre mot de passe ou votre description.</span>
        </div>
    </a>
</div>