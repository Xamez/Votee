<?php
require "propositionHeader.php";
echo '</p><div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
foreach ($sections as $index=>$section) {
        $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
        $sectionDescHTML = htmlspecialchars($textes[$index]->getTexte());

        echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
              <p class="text-justify">' . $sectionDescHTML . '</p>';
}
echo '</div><div class="flex gap-2 justify-end">
        <a class="w-36 flex p-2 justify-center text-white bg-dark font-semibold rounded-lg" 
            href="./frontController.php?action=deletePropsition&idProposition=' . rawurlencode($idProposition) . '">Supprimer</a>
        <a class="w-36 flex p-2 justify-center text-white bg-main font-semibold rounded-lg" 
            href="./frontController.php?action=update&idQuestion=' . rawurlencode($question->getIdQuestion()). '&idProposition='. rawurlencode($idProposition) . '">Editer</a>
</div>';