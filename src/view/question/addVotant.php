<form class="flex flex-col gap-10" method="post" action="frontController.php?controller=question&action=addedVotant">
    <h1 class="title text-dark text-2xl font-semibold">Groupes</h1>
    <div class="flex flex-wrap gap-2 justify-center">
        <?php
        foreach ($groupes as $key=>$groupe) {
            echo '<div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <input class="utilCheck" type="checkbox" name="groupes[]" id="groupe' . $key . '" value="' . $groupe->getNomGroupe() . '"/>
                    <label class="flex gap-1 items-center" for="groupe' . $key . '"><span class="material-symbols-outlined">group</span>' . $groupe->getIdGroupe() . '</label>
                  </div>';
        }
        ?>
    </div>
    <h1 class="title text-dark text-2xl font-semibold">Utilisateurs</h1>
    <div class="flex flex-wrap gap-2 justify-center">
        <?php
        foreach ($utilisateurs as $key=>$utilisateur) {
            echo '<div class="border-2 border-transparent util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <input class="utilCheck" type="checkbox" name="utilisateurs[]" id="util' . $key . '" value="' . $utilisateur->getLogin() . '"/>
                    <label class="flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $utilisateur->getPrenom() . ' ' . $utilisateur->getNom() . '</label>
                  </div>';
        }
        ?>
    </div>
    <div class="flex justify-center">
        <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>"/>
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>