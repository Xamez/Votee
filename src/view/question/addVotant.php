<form class="flex flex-col gap-10" method="post" action="frontController.php?controller=question&action=addedVotant">
    <h1 class="title text-dark text-2xl font-semibold">Groupes</h1>
    <div class="flex flex-wrap gap-2 justify-center">
        <?php

        use App\Votee\Lib\ConnexionUtilisateur;
        //TODO Gerer les groupes qui ont deja été ajouté !
        foreach ($groupes as $key=> $groupe) {
            echo '<div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <input class="utilCheck" type="checkbox" name="groupes[]" id="groupe' . $key . '" value="' . $groupe->getIdGroupe() . '"/>
                    <label class="flex gap-1 items-center" for="groupe' . $key . '"><span class="material-symbols-outlined">group</span>' . $groupe->getNomGroupe() . '</label>
                  </div>';
        }
        ?>
    </div>
    <h1 class="title text-dark text-2xl font-semibold">Utilisateurs</h1>
    <div class="flex flex-wrap gap-2 justify-center">
        <?php
        foreach ($votants as $key=>$votant) {
            if (!ConnexionUtilisateur::estLoginAdministrateur($votant->getLogin())) {
                echo '<div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                        <input class="votantCheck" type="checkbox" name="votants[]" id="votant' . $key . '" value="' . $votant->getLogin() . '" checked/>
                        <label class="flex gap-1 items-center" for="votant' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $votant->getPrenom() . ' ' . $votant->getNom() . '</label>
                      </div>';
            }
        }

        foreach ($newUtilisateurs as $key=>$utilisateur) {
            if (!ConnexionUtilisateur::estLoginAdministrateur($utilisateur->getLogin())) {
                echo '<div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                        <input class="utilCheck" type="checkbox" name="utilisateurs[]" id="util' . $key . '" value="' . $utilisateur->getLogin() . '"/>
                        <label class="flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $utilisateur->getPrenom() . ' ' . $utilisateur->getNom() . '</label>
                      </div>';
            }
        }
        ?>
    </div>
    <div class="flex justify-center">
        <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>"/>
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>