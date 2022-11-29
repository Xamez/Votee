<?php
require "propositionHeader.php";
?>
<form method="get" class="flex flex-col gap-7" action="frontController.php?action=updatedProposition">
    <input placeholder="Login" class="border-2" type="text" name="coAuteur" id="coAuteur_id">
    <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
        <?php
        foreach ($sections as $index=>$section) {
            $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
            $sectionTexteHTML = preg_replace('#<br\s*/?>#i', "", htmlspecialchars_decode($textes[$index]->getTexte()));
            echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>';
            echo '<textarea class="border-2 max-h-96 h-52" maxlength="2000"  name="section'.$index.'" id="section'.$index.'" required>'. $sectionTexteHTML.'</textarea>';
            echo '<input type="hidden" name="idSection' . $index . '" value="'. $section->getIdSection(). '">';
        }
        ?>
    </div>
    <input type="hidden" name="nbSections" value="<?= sizeof($sections);?>">
    <input type="hidden" name="idProposition" value="<?= $idProposition;?>">
    <input type="hidden" name="idQuestion" value="<?= $question->getIdQuestion();?>">
    <input type="hidden" name="action" value="updatedProposition">
    <div class="flex justify-center">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>
