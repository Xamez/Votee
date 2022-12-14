<?php
echo '<script type="text/javascript" src="assets/js/accordion.js"></script>';

echo '<div>';
foreach ($propositions as $proposition) {

    $idProposition = $proposition->getIdProposition();

    echo '
       <div>
            <div class="accordion text-left w-full p-2 cursor-pointer flex justify-between p-2 items-center rounded">
                <div class="flex items-center gap-2">
                    <div class="bg-white items-center flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                        <span class="material-symbols-outlined">account_circle</span>' . htmlspecialchars($responsables[$idProposition]->getNom()) . ' ' . htmlspecialchars($responsables[$idProposition]->getPrenom()) . '
                    </div>
                </div>
                <span class="accordion-arrow material-symbols-outlined">arrow_forward_ios</span>
            </div>                        
            <div class="p-4 overflow-hidden hidden panel">';
            foreach ($sections as $index => $section) {
                $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
                $sectionDescHTML = $textes[$idProposition][$index]->getTexte();
                echo '
                    <h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
                    <div class="proposition-markdown break-all text-justify">' . $sectionDescHTML . '</div>
                    ';
            }
            echo '</div>';
        echo '</div>';
}

echo '</div>';