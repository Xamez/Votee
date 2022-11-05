<form method="post" class="flex flex-col gap-7" action="frontController.php?action=createdProposition">
    <div class="flex gap-2 items-center">
        <label class="text-main font-semibold" for="representant_id">Représentant :</label>
        <input placeholder="Login" class="border-2" type="text" name="representant" id="representant_id" required>
    </div>
    <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
        <?php
        foreach ($sections as $index=>$section) {
            $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
            echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>';
            echo '<textarea class="border-2 max-h-96 h-52" maxlength="2000"  name="section'.$index.'" id="section'.$index.'" required></textarea>';
            echo '<input type="hidden" name="idSection' . $index . '" value="'. $section->getIdSection(). '">';
        }
        ?>
    </div>
    <input type="hidden" name="nbSections" value="<?= sizeof($sections);?>">
    <input class=" p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
</form>