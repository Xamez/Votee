<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?controller=utilisateur&action=updatedMdpUtilisateur">
        <div class="border-2 p-2 rounded-md bg-red-100 border-red-300 text-dark font-semibold flex flex-col items-center">
            <h1>Cette action modifera votre mot de passe.</h1>
        </div>
        <div class="flex flex-col gap-5">
            <input class="w-full" type="password" name="ancienMotDePasse" placeholder="Ancien mot de passe" required>
            <input class="w-full" type="password" name="nouveauMotDePasse" placeholder="Nouveau mot de passe" required>
            <input class="w-full" type="password" name="nouveauMotDePasseConfirm" placeholder="Confirmation mot de passe" required>
            <div class="flex gap-10">
                <a class="w-28 p-2 text-white bg-main font-semibold rounded-lg text-center" href="frontController.php?controller=utilisateur&action=readUtilisateur">Annuler</a>
                <input class="w-28 p-2 text-white bg-red font-semibold rounded-lg" type="submit" value="Confirmer" />
            </div>
        </div>

    </form>
</div>
