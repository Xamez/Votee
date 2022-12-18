<form method="post" class="flex flex-col gap-7" action="frontController.php?controller=proposition&action=createdProposition">
    <div class="flex gap-2 items-center">
        <p class="text-main font-semibold">Responsable :</p>
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>
            <?php echo htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) ?>
        </div>
        <label class="text-main font-semibold" for="coAuteur_id">CoAuteur :</label>
        <input placeholder="Login" class="border-2" type="text" name="coAuteur" id="coAuteur_id">
    </div>
    <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
        <?php
        foreach ($sections as $index=>$section) {
            $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
            echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
                  <textarea class="border-2 max-h-96 h-52 whitespace-pre-wrap" maxlength="2000"  name="section'.$index.'" id="section'.$index.'" required></textarea>
                  <input type="hidden" name="idSection' . $index . '" value="'. $section->getIdSection(). '">';
        }
        ?>
    </div>
    <input type="hidden" name="organisateur" value="<?= $responsable->getLogin() ?>">
    <input type="hidden" name="nbSections" value="<?= sizeof($sections) ?>">
    <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>">
    <div class="flex justify-center">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>