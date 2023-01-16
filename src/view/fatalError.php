<?php

use App\Votee\Controller\AbstractController;
use App\Votee\Lib\ConnexionUtilisateur;

$traceTitles = ['Fichier', 'Ligne', 'Appel'];
$traceKeys = ['file', 'line'];

echo '<div class="flex mx-auto items-center justify-center mt-2">';
if (!ConnexionUtilisateur::estAdministrateur() && !$debug) {
    echo '<div class="border-2 p-2 rounded-md bg-red-100 border-red-300 text-dark font-bold text-xl flex flex-col items-center">
            <h2>Une erreur critique est survenue !</h2>
            <h2>Merci de contacter un administrateur.</h2>
          </div>';
    echo '<div class="flex flex-col p-6 text-dark gap-4 hidden">';
} else {
    echo '<div class="flex flex-col p-6 text-dark gap-4">';
}
        echo '<div class="flex flex-col gap-1">';
        echo '<h2 class="text-xl font-bold text-main">Message</h2>';
        echo '<p class="md:text-sm text-dark">' . $error['message'] . '</p>';
        echo '</div>';

        echo '<div class="flex flex-col gap-1">';
        echo '<h2 class="text-xl font-bold text-main">Fichier</h2>';
        echo '<p class="md:text-sm text-dark">' . $error['file'] . '</p>';
        echo '</div>';

        echo '<div class="flex flex-col gap-1">';
        echo '<h2 class="text-xl font-bold text-main">Ligne</h2>';
        echo '<p class="md:text-sm text-dark">' . $error['line'] . '</p>';
        echo '</div>';

        echo '<div class="overflow-x-auto">';
        if (sizeof($error['trace']) > 0) {
            echo '<h2 class="text-xl font-bold text-main">Traces</h2>';
            echo '<table class="table-auto text-center">';
            echo '<thead>';
            echo '<tr>';
            foreach ($traceTitles as $title)
                echo '<th class="px-4 py-2">' . $title . '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($error['trace'] as $trace) {
                echo '<tr>';
                foreach ($traceKeys as $key)
                    echo '<td class="border px-4 py-2">' . $trace[$key] . '</td>';
                echo '<td class="border px-4 py-2">' . $trace['class'] . $trace['type'] . $trace['function'] . '()</td>';
                echo '</tr>';
            }
            echo '</tbody>';
        }
        echo '</div>';
    echo '</div>';
echo '</div>';