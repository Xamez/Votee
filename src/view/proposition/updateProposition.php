<?php
echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Représentant : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
    . htmlspecialchars($responsable->getNom()) . ' ' . htmlspecialchars($responsable->getPrenom()) .
    '</div>
        </p>
     </div>';

echo '<div class="flex items-center flex-wrap gap-2 pt-0"><p class="text-main font-semibold">Co-auteur :';
foreach ($coAuteurs as $coAuteur) {
    echo '<div class="flex gap-1 items-center text-main bg-white shadow-md rounded-2xl p-2">
            <span class="material-symbols-outlined">account_circle</span>'
        . htmlspecialchars($coAuteur->getNom()) . ' ' . htmlspecialchars($coAuteur->getPrenom()) .
        '<a class="flex" href="./frontController.php?controller=question&action=deletedCoAuteur&idQuestion='. rawurlencode($question->getIdQuestion())
        . '&idProposition=' . rawurlencode($idProposition) . '&utilisateur='. $coAuteur->getLogin() . '">
                <span class="text-red-600 material-symbols-outlined">delete</span>
            </a>
         </div>';
}
echo '</div>'
?>
<form method="post" action="frontController.php?controller=proposition&action=createdCoAuteur">
    <input placeholder="Login" class="border-2" type="text" name="login" id="coAuteur_id">
    <input type="hidden" name="idProposition" value="<?= $idProposition;?>">
    <input type="hidden" name="idQuestion" value="<?= $question->getIdQuestion();?>">
    <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Ajouter" />
</form>
<form method="get" class="flex flex-col gap-7" action="frontController.php?controller=proposition&action=updatedProposition">
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
