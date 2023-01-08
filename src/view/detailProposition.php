<?php

if (!$inAccordion)
    echo '<div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">            
            <div class="flex flex-col gap-2 pb-3">
                <span class="text-main font-semibold text-lg">Titre de la proposition :</span>
                <span>' . htmlspecialchars($titreProposition) . '</span>
            </div>';

foreach ($sections as $numParagraphe => $section) {
    $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
    $sectionDescHTML = $textes[$numParagraphe]->getTexte();
    $paragraph = "";

    if ($commentaryEnabled) {
        // le str_split ne marche pas en raison des accents qui sont considérés comme 2 caractères
        $sectionDescHTMLChars = preg_split('//u', $sectionDescHTML, -1, PREG_SPLIT_NO_EMPTY); // \u match les caractères unicode

        foreach ($sectionDescHTMLChars as $key => $char) {
            foreach ($commentaires as $commentaire) {
                if ($commentaire->getNumeroParagraphe() == $numParagraphe) {
                    if ($commentaire->getIndexCharDebut() == $key) {
                        $paragraph .= '<span id="' . $commentaire->getIdCommentaire() . '" class="commentary cursor-pointer ' . ($inAccordion ? "bg-main" : "bg-light") . '" data-id="' . htmlspecialchars($commentaire->getTexteCommentaire()) . '">';
                    } else if ($commentaire->getIndexCharFin() == $key)
                        $paragraph .= '</span>';
                }
            }
            $paragraph .= $char;
        }
    } else {
        $paragraph = $sectionDescHTML;
    }

    echo '
        <div class="flex flex-col">
            <h1 class="text-main text-2xl font-bold pb-3">'. $numParagraphe + 1 . ' - ' . $sectionTitreHTML . '</h1>
            <div data-id="' . $sectionDescHTML . '" id="' . $numParagraphe .'" class="proposition-markdown break-all text-justify">
                ' . $paragraph . '
            </div>
        </div>
    ';
}

if (!$inAccordion)
    echo '</div>';