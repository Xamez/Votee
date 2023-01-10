<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?controller=utilisateur&action=updatedUtilisateur">
        <div class="border-2 p-2 rounded-md bg-red-100 border-red-300 text-dark font-semibold flex flex-col items-center">
            <h1>Cette action modifera vos information de compte.</h1>
        </div>
        <div class="flex flex-col gap-5">
            <input class="w-full" type="password" name="motDePasse" placeholder="Mot de passe" required>
            <input type="hidden" name="nom" value="<?= $nom ?>">
            <input type="hidden" name="prenom" value="<?= $prenom ?>">
            <input type="hidden" name="description" value="<?= $description ?>">
            <div class="flex gap-10">
                <a class="w-28 p-2 text-white bg-main font-semibold rounded-lg text-center" href="frontController.php?controller=utilisateur&action=readUtilisateur">Annuler</a>
                <input class="w-28 p-2 text-white bg-red font-semibold rounded-lg" type="submit" value="Confirmer" />
            </div>
        </div>

    </form>
</div>
