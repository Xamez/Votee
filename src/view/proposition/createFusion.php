<?php require "propositionHeader.php"; ?>
<script type="text/javascript" src="assets/js/accordion.js"></script>
<script type="text/javascript" src="assets/js/proposition.js"></script>
<form method="post" class="flex flex-col gap-7" action="frontController.php?controller=proposition&action=createdFusion">
    <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
        <div class="flex flex-col gap-2">
            <label class="text-main text-lg font-semibold" for="titre">Titre de la proposition :</label>
            <input type="text" minlength="10" maxlength="130" id="titre" placeholder="Rôle de l'État : fonction régalienne" name="titreProposition" required>
        </div>
    <?php
    foreach ($sections as $index => $section) {
        $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
        echo '<h1 class="text-main text-2xl font-bold">' . $index + 1 . ' - ' . $sectionTitreHTML . '</h1>';
        foreach ($textes as $indexTexte => $texte) {
            echo '<div>
                    <div class="accordion text-left w-full p-2 cursor-pointer flex justify-between p-2 items-center rounded">
                        <div class="flex items-center gap-2">
                            <p class="font-bold text-dark">Section ' . $index + 1 . ' de : </p>
                            <div class="bg-white items-center flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                                <span class="material-symbols-outlined">account_circle</span>' .
                                htmlspecialchars($responsables[$indexTexte]->getNom()) . ' ' . htmlspecialchars($responsables[$indexTexte]->getPrenom()) . '
                            </div>
                        </div>
                        <span class="accordion-arrow material-symbols-outlined">arrow_forward_ios</span>
                    </div>                        
                    <div class="p-4 overflow-hidden hidden panel">
                        <div class="proposition-markdown break-all text-justify">' . $texte[$index]->getTexte() . '</div>
                    </div>
                </div>';
        }
        echo '<textarea class="section border-2 max-h-96 h-52" maxlength="2000" placeholder="Il est important de rédiger ..." name="section' . $index . '" id="section' . $index . '" required></textarea>
              <input type="hidden" name="idSection' . $index . '" value="' . $section-> getIdSection() . '">';
    }
    ?>
    </div>
    <div class="flex justify-center">
        <input type="hidden" name="respCourant" value="<?= $responsables[0]->getLogin() ?>">
        <input type="hidden" name="respAMerge" value="<?= $responsables[1]->getLogin() ?>">
        <input type="hidden" name="idPropCourant" value="<?= $idPropositions[0] ?>">
        <input type="hidden" name="idPropAMerge" value="<?= $idPropositions[1] ?>">
        <input type="hidden" name="idQuestion" value="<?= $question->getIdQuestion() ?>">
        <input type="hidden" name="nbSections" value="<?= sizeof($sections); ?>">
        <input type="hidden" name="isFusion" value="1">
        <?php foreach ($coAuteurs as $coAuteur) echo '<input type="hidden" name="coAuteurs[]" value="' . $coAuteur->getLogin() . '">' ?>
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider"/>
    </div>
</form>
