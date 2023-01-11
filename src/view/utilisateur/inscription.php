<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?controller=utilisateur&action=inscrit">
        <div class="flex flex-col gap-2">
            <div>
                <input class="w-80 border-2" type="text" placeholder="Identifiant" name="login" required/>
            </div>
            <div>
                <input class="w-80 border-2" type="text" placeholder="Prénom" name="prenom" required/>
            </div>
            <div>
                <input class="w-80 border-2" type="text" placeholder="Nom" name="nom" required/>
            </div>
            <div>
                <textarea class="w-80 border-2 border-gray-200 rounded-md max-h-40 resize-none" placeholder="Je suis un étudiant..." name="description" id="description" rows="10" required></textarea>
            </div>
            <div>
                <input class="w-80 border-2" type="password" placeholder="Mot de passe" name="password" required/>
            </div>
            <div>
                <input class="w-80 border-2" type="password" placeholder="Verification du mot de passe" name="passwordVerif" required/>
            </div>
        </div>
        <div>
            <input class="p-2 w-48 text-white bg-main font-semibold rounded-lg" type="submit" value="Inscription" />
        </div>
    </form>
    <div class="flex gap-1">
        <p>Vous n’avez pas de compte ? </p><a class="text-main" href="frontController.php?controller=utilisateur&action=connexion">Connectez-vous.</a>
    </div>
</div>