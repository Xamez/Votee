<?php
// affiche le login de l'utilisateur qui a créé la organisateur
echo '<div class="flex items-center gap-2"><p class="text-main font-semibold">Représentant : 
        <div class="flex gap-1 text-main bg-white shadow-md rounded-2xl w-fit p-2">
            <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($responsable->getNom()) . ' ' . htmlspecialchars($responsable->getPrenom()) .
        '</div>
        </p>
     </div>';


echo '<div class="flex items-center flex-wrap gap-2 pt-0"><p class="text-main font-semibold">Co-auteur :';
foreach ($coAuteurs as $coAuteur) {
    echo '<div class="flex gap-1 text-main bg-white shadow-md rounded-2xl p-2">
            <span class="material-symbols-outlined">account_circle</span>'
                . htmlspecialchars($coAuteur->getNom()) . ' ' . htmlspecialchars($coAuteur->getPrenom()) .
        '</div>';
}

echo '</div></p><div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';
foreach ($sections as $index=>$section) {
        $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
        $sectionDescHTML = htmlspecialchars($textes[$index]->getTexte());

        echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
              <p class="text-justify">' . $sectionDescHTML . '</p>';
}
echo '</div><div class="flex gap-2 justify-end">
        <a class="w-36 flex p-2 justify-center text-white bg-dark font-semibold rounded-lg" 
            href="./frontController.php?action=deletePropsition&idProposition=' . $textes[0]->getIdProposition() . '">Supprimer</a>
        <a class="w-36 flex p-2 justify-center text-white bg-main font-semibold rounded-lg" 
            href="./frontController.php?action=update">Editer</a>
</div>';