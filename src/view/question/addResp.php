<script type="text/javascript" src="assets/js/checkBox.js"></script>
<form class="flex flex-col gap-10 mt-7" method="post" action="frontController.php?controller=question&action=addedResp">
    <div class="flex flex-col gap-10 rounded-xl py-4 bg-lightPurple">
        <h1 class="title text-dark text-2xl font-semibold">Responsables</h1>
        <div class="flex justify-center">
            <div class="border-2 p-2 rounded-md bg-blue-100 border-blue-300 text-dark font-semibold flex flex-col items-center">
                <h1>Cette action donnera les permissions de création d'une proposition.</h1>
                <span class="font-normal">Les responsables que vous ajoutez seront automatiquement ajoutés en tant que votant.</span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2 justify-center">
            <?php
            foreach ($responsables as $responsable) {
                echo '<div class="flex items-center gap-2 border-transparent util-box text-main disable shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' . $responsable->getPrenom() . ' ' . $responsable->getNom() . '</label>
                      </div>';
            }

            foreach ($responsablesPossibles as $key=>$responsable) {
                echo '<div class="border-2 border-transparent util-box text-main items-center bg-white shadow-md rounded-2xl w-fit p-2">
                        <input type="checkbox" name="resps[]" id="resp' . $key . '" value="' . $responsable->getLogin() . '" checked/>
                        <label class="cursor-pointer flex gap-1 items-center" for="resp' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) . '</label>
                      </div>';
            }

            foreach ($utilisateurs as $key=>$utilisateur) {
                echo '<div class="border-2 border-transparent util-box text-main items-center bg-white shadow-md rounded-2xl w-fit p-2">
                        <input type="checkbox" name="utilisateurs[]" id="util' . $key . '" value="' . $utilisateur->getLogin() . '"/>
                        <label class="cursor-pointer flex gap-1 items-center" for="util' . $key . '"><span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($utilisateur->getPrenom()) . ' ' . htmlspecialchars($utilisateur->getNom()) . '</label>
                      </div>';
            }
            ?>
        </div>
    </div>

    <div class="flex justify-center">
        <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>"/>
        <input type="hidden" name="type" value="<?= $typeRedi ?>"/>
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg cursor-pointer" type="submit" value="Valider" />
    </div>
</form>