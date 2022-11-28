<?php
require "propositionHeader.php";
echo '<script type="text/javascript" src="js/commentary.js"></script>';
echo '
<div id="ids">
    <input type="hidden" value="' . rawurlencode($question->getIdQuestion()) . '">
    <input type="hidden" value="' . rawurlencode($idProposition) . '">
</div>
';
echo '</p><div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">';

foreach ($sections as $index => $section) {
        $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
        $sectionDescHTML = htmlspecialchars($textes[$index]->getTexte());

        $paragraph = "";

        $sectionDescHTMLChars = str_split($sectionDescHTML);
        foreach ($sectionDescHTMLChars as $key => $char) {
            foreach ($commentaires as $commentaire) {
                if ($commentaire->getNumeroParagraphe() === $index)
                    if ($commentaire->getIndexCharDebut() === $key)
                        $paragraph .= '<span class="commentary cursor-pointer bg-light" data-id="' . $commentaire->getTexteCommentaire() . '">';
                    else if ($commentaire->getIndexCharFin() == $key)
                        $paragraph .= '</span>';
            }
            $paragraph .= $char;
        }

        echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
              <p id="' . $index . '" class="break-all text-justify">' . $paragraph . '</p>';
}

echo '
<div id="popup" class="hidden fixed z-1 bg-main text-white rounded-xl p-4">
    <div class="flex flex-col items-center justify-center gap-2">
        <p class="text-md font-bold border-0 select-none">Ecrivez un commentaire</p>
        <textarea id="text-commentary" class="border-2 max-h-60 h-44 w-96 bg-main ring-0 focus:outline-none" maxlength="300" placeholder="Entrez votre commentaire..." type="text" required></textarea>
        <button id="create-commentary" class="text-xl font-bold hover:underline underline-1">Ajouter un commentaire</button>
    </div>
</div>
';

echo '</div>
        <div class="flex gap-2 justify-between select-none">
            <div class="flex in items-align gap-4">
                <a href="./frontController.php?action=updateProposition&idQuestion=' . rawurlencode($question->getIdQuestion()). '&idProposition='. rawurlencode($idProposition) . '">
                    <div class="flex gap-2">
                        <span class="material-symbols-outlined">edit</span>
                        <p>Editer</p>
                    </div
                </a>
                <a class="cursor-pointer">
                    <div id="commentary-button" class="flex gap-2">
                        <span class="material-symbols-outlined">sticky_note_2</span>
                        <p class="line-through">Commentaire</p>
                    </div>
                </a>
            </div>
            <a href="./frontController.php?action=deleteProposition&idProposition=' . rawurlencode($idProposition) . '">
                <div class="flex gap-2">
                    <p>Supprimer</p>
                    <span class="material-symbols-outlined">delete</span>
                </div>
            </a>
       </div>';