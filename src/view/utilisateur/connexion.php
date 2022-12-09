<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?controller=utilisateur&action=authentification">
        <div class="flex flex-col gap-2">
            <div>
                <input class="w-80 border-2" type="text" placeholder="Identifiant" name="login" required/>
            </div>
            <div>
                <input class="w-80 border-2" type="password" placeholder="Mot de passe" name="password" required/>
            </div>
        </div>
        <div>
            <input class="p-2 w-48 text-white bg-main font-semibold rounded-lg" type="submit" value="Connexion" />
        </div>
    </form>
    <div class="flex flex gap-1">
        <p>Vous n’avez pas de compte ? </p><a class="text-main" href="frontController.php?controller=utilisateur&action=inscription">Inscrivez-vous.</a>
    </div>
</div>