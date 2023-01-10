<script type="text/javascript" src="assets/js/checkBox.js"></script>
<h1 class="title text-dark text-2xl font-semibold">Utilisateurs</h1>
<form class="flex flex-col gap-10" method="post" action="frontController.php?controller=proposition&action=addedCoauteur">
    <div class="flex flex-wrap gap-2 justify-center">
        <?php
        foreach ($coAuteurs as $key=>$coAuteur) {
            echo '<div class="border-2 border-transparent items-center util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                <input type="checkbox" name="coAuteurs[]" id="coAuteur' . $key . '" value="' . $coAuteur->getLogin() . '" checked/>
                <label class="cursor-pointer flex gap-1 items-center" for="coAuteur' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $coAuteur->getPrenom() . ' ' . $coAuteur->getNom() . '</label>
              </div>';
        }

        foreach ($utilisateurs as $key=>$utilisateur) {
            echo '<div class="border-2 border-transparent items-center util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                <input type="checkbox" name="utilisateurs[]" id="util' . $key . '" value="' . $utilisateur->getLogin() . '"/>
                <label class="cursor-pointer flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $utilisateur->getPrenom() . ' ' . $utilisateur->getNom() . '</label>
              </div>';
        }
        ?>
    </div>
    <input type="hidden" name="idProposition" value="<?= $idProposition ?>"/>
    <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>"/>
    <div class="flex justify-center">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>