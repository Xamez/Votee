<?php

$traceTitles = ['Fichier', 'Ligne', 'Appel'];
$traceKeys = ['file', 'line'];

echo '<div class="flex flex-col p-6 text-dark mt-8 gap-4">';

    echo '<div class="flex flex-col gap-1">';
    echo '<h2 class="text-3xl font-bold text-main">Message</h2>';
    echo '<p class="md:text-xl text-dark">' . $error['message'] . '</p>';
    echo '</div>';

    echo '<div class="flex flex-col gap-1">';
    echo '<h2 class="text-3xl font-bold text-main">Fichier</h2>';
    echo '<p class="md:text-xl text-dark">' . $error['file'] . '</p>';
    echo '</div>';

    echo '<div class="flex flex-col gap-1">';
    echo '<h2 class="text-3xl font-bold text-main">Ligne</h2>';
    echo '<p class="md:text-xl text-dark">' . $error['line'] . '</p>';
    echo '</div>';

    echo '<div class="overflow-x-auto">';
    if (sizeof($error['trace']) > 0) {
        echo '<h2 class="text-3xl font-bold text-main">Traces</h2>';
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
            echo '<td class="border px-4 py-2">' . $trace['class'] . $trace['type'] . $trace['function'] . '(' . implode(', ', $trace['args']) . ')' . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
    }
    echo '</div>';
echo '</div>';