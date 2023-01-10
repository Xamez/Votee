<div class="flex flex-col justify-center items-center gap-7">
    <form method="post" class="flex flex-col gap-10 items-center" action="frontController.php?controller=proposition&action=deletedProposition">
        <div class="border-2 p-2 rounded-md bg-red-100 border-red-300 text-dark font-semibold flex flex-col items-center">
            <h1>Cette action archivera définitivement la proposition :</h1>
            <span class="font-normal">Elle ne sera accessible en lecture que par l’organisateur et les auteurs.</span>
        </div>
        <div class="flex flex-col gap-5">
            <input class="w-full" type="password" name="motDePasse" placeholder="Mot de passe" required>
            <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>">
            <input type="hidden" name="idProposition" value="<?= $idProposition ?>">
            <div class="flex gap-10">
                <a class="w-28 p-2 text-white bg-main font-semibold rounded-lg text-center" href="frontController.php?controller=proposition&action=readProposition&idQuestion=<?= rawurldecode($idQuestion) ?>&idProposition=<?= rawurldecode($idProposition) ?>">Annuler</a>
                <input class="w-28 p-2 text-white bg-red font-semibold rounded-lg cursor-pointer" type="submit" value="Confirmer" />
            </div>
        </div>
    </form>
</div>