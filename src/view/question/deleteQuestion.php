<div class="flex flex-col justify-center items-center gap-7">
    <form method="post" class="flex flex-col gap-10 items-center" action="frontController.php?controller=question&action=deletedQuestion">
        <div class="border-2 p-2 rounded-md bg-red-100 border-red-300 text-dark font-semibold flex flex-col items-center">
            <h1>Cette action supprimera définitivement la question.</h1>
        </div>
        <div class="flex flex-col gap-5">
            <input class="w-full" type="password" name="motDePasse" placeholder="Mot de passe" required>
            <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>">
            <div class="flex gap-10">
                <a class="w-28 p-2 text-white bg-main font-semibold rounded-lg text-center" href="frontController.php?controller=question&action=readQuestion&idQuestion=<?= rawurldecode($idQuestion) ?>">Annuler</a>
                <input class="w-28 p-2 text-white bg-red font-semibold rounded-lg" type="submit" value="Confirmer" />
            </div>
        </div>
    </form>
</div>