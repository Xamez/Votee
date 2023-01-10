<?php use App\Votee\Controller\AbstractController;

require "propositionHeader.php"; ?>
<script type="text/javascript" src="assets/js/accordion.js"></script>
<script type="text/javascript" src="assets/js/proposition.js"></script>
<form method="post" class="flex flex-col gap-7" action="frontController.php?controller=proposition&action=updatedProposition">
    <?php
    if (sizeof($commentaires) > 0) {
        echo '<div>
                <div class="accordion text-left w-full p-2 cursor-pointer flex justify-between p-2 items-center rounded">
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-dark">Ancienne proposition</p>
                    </div>
                    <span class="accordion-arrow material-symbols-outlined">arrow_forward_ios</span>
                </div>                      
                <div class="p-4 overflow-hidden hidden panel">';
                    AbstractController::afficheVue('detailProposition.php', ['commentaryEnabled' => true, 'inAccordion' => true, 'sections' => $sections, 'textes' => $textesMarkdown, 'commentaires' => $commentaires]);
             echo '</div>
              </div>';
    }
    ?>

    <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
        <div class="flex flex-col gap-2">
            <label class="text-main text-lg font-semibold" for="titre">Titre de la proposition :</label>
            <input type="text" minlength="10" id="titre" maxlength="130" placeholder="Rôle de l'État : fonction régalienne" name="titreProposition" value="<?= $proposition->getTitreProposition() ?>" required>
        </div>

        <?php
        foreach ($sections as $index=>$section) {
            $sectionTitreHTML = htmlspecialchars($section->getTitreSection());
            $sectionTexteHTML = preg_replace('#<br\s*/?>#i', "", htmlspecialchars_decode($textes[$index]->getTexte()));
            echo '<h1 class="text-main text-2xl font-bold">'. $index + 1 . ' - ' . $sectionTitreHTML . '</h1>
                  <textarea class="section border-2 max-h-96 h-52" placeholder="Il est important de rédiger ..." maxlength="2000"  name="section'.$index.'" id="section'.$index.'" required>'. $sectionTexteHTML.'</textarea>
                  <input type="hidden" name="old-section'.$index.'" value="' . $sectionTexteHTML . '">
                  <input type="hidden" name="idSection' . $index . '" value="'. $section->getIdSection(). '">';
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
