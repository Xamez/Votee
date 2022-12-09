<?php

use App\Votee\Lib\ConnexionUtilisateur;

echo '<div class="flex flex-col gap-3">';
if($questions) {
    echo '<p>Organisateur</p>';
    foreach ($questions as $question) {
        if (ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'organisateur'){
            echo '
            <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">
                ' . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">
                                ' . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
            </a>';
        }
    }
    echo '<p>Representant</p>';
    foreach ($questions as $question) {
        if (ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'representant'){
            echo '
            <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">
                ' . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">
                                ' . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
            </a>';
        }
    }
    echo '<p>CoAuteur</p>';
    foreach ($questions as $question) {
        if (ConnexionUtilisateur::getRoleQuestion($question->getIdQuestion()) == 'coauteur'){
            echo '
            <a href="./frontController.php?controller=question&action=readQuestion&idQuestion=' . rawurlencode($question->getIdQuestion()) . '">
            <div class="flex justify-between items-center bg-light p-2 rounded">
                ' . htmlspecialchars($question->getTitre()) . '
                <div class="flex items-center gap-2">
                    <div class="bg-white flex text-main shadow-md rounded-2xl w-fit p-1.5">
                                ' . $question->getPeriodeActuelle() . '
                    </div>
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>
            </div>
            </a>';
        }
    }
}
echo '</div><a class="w-36 flex p-2 justify-center text-white bg-main font-semibold rounded-lg" href="./frontController.php?controller=question&action=section">Créer un vote</a>';
