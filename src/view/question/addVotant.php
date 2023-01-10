<script type="text/javascript" src="assets/js/checkBox.js"></script>
<form class="flex flex-col gap-10 mt-7" method="post" action="frontController.php?controller=question&action=addedVotant">
    <div class="rounded-xl py-4 flex flex-col gap-3 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Groupes</h1>
        <div class="flex flex-wrap gap-2 justify-center">
            <?php

            foreach ($groupes as $key=> $groupe) {
                echo '<div class="border-2 border-transparent util-box text-main bg-white items-center shadow-md rounded-2xl w-fit p-2">
                        <input type="checkbox" name="groupesExist[]" id="groupeExist' . $key . '" value="' . $groupe->getIdGroupe() . '" checked/>
                        <label class="cursor-pointer flex gap-1 items-center" for="groupeExist' . $key . '"><span class="material-symbols-outlined">group</span>' . $groupe->getNomGroupe() . '</label>
                     </div>';
            }

            foreach ($newGroupes as $key=> $groupe) {
                echo '<div class="cursor-pointer border-2 border-transparent util-box items-center text-main bg-white shadow-md rounded-2xl w-fit p-2">
                        <input type="checkbox" name="groupes[]" id="groupe' . $key . '" value="' . $groupe->getIdGroupe() . '"/>
                        <label class="cursor-pointer flex gap-1 items-center" for="groupe' . $key . '"><span class="material-symbols-outlined">group</span>' . $groupe->getNomGroupe() . '</label>
                      </div>';
            }
            ?>
        </div>
    </div>
    <div class="rounded-xl py-4 flex flex-col gap-3 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Utilisateurs</h1>
        <div class="flex flex-wrap gap-2 justify-center">
            <?php
            foreach ($actors as $actor) {
                echo '<div class="flex items-center gap-2 border-transparent util-box text-main disable shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' . $actor->getPrenom() . ' ' . $actor->getNom() . '</label>
                      </div>';
            }

            foreach ($votants as $key=>$votant) {
                echo '<div class="border-2 border-transparent util-box text-main items-center bg-white shadow-md rounded-2xl w-fit p-2">
                        <input type="checkbox" name="votants[]" id="votant' . $key . '" value="' . $votant->getLogin() . '" checked/>
                        <label class="cursor-pointer flex gap-1 items-center" for="votant' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $votant->getPrenom() . ' ' . $votant->getNom() . '</label>
                      </div>';
            }

            foreach ($newUtilisateurs as $key=>$utilisateur) {
                echo '<div class="border-2 border-transparent util-box text-main items-center bg-white shadow-md rounded-2xl w-fit p-2">
                        <input type="checkbox" name="utilisateurs[]" id="util' . $key . '" value="' . $utilisateur->getLogin() . '"/>
                        <label class="cursor-pointer flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $utilisateur->getPrenom() . ' ' . $utilisateur->getNom() . '</label>
                      </div>';
            }
            ?>
        </div>
    </div>
    <div class="flex justify-center">
        <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>"/>
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg cursor-pointer" type="submit" value="Valider" />
    </div>
</form>