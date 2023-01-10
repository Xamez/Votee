<script type="text/javascript" src="assets/js/checkBox.js"></script>
<form class="flex flex-col gap-10 mt-7" method="post" action="frontController.php?controller=groupe&action=addedMembre">
    <div class="rounded-xl py-4 flex flex-col gap-10 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Utilisateurs</h1>
        <div class="flex flex-wrap gap-2 justify-center">
            <?php
            foreach ($membres as $key=> $membre) {
                echo '<div class="border-2 border-transparent items-center util-box text-main bg-white shadow-md rounded-2xl w-fit p-2">
                    <input type="checkbox" name="membres[]" id="membre' . $key . '" value="' . $membre->getLogin() . '" checked/>
                    <label class="cursor-pointer flex gap-1 items-center" for="membre' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . $membre->getPrenom() . ' ' . $membre->getNom() . '</label>
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
    </div>
    <div class="flex justify-center">
        <input type="hidden" name="idGroupe" value="<?= $idGroupe ?>"/>
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>