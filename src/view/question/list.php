<?php
echo '<p><a href="./frontController.php?action=create">Créer une question</a></p>';
foreach ($questions as $question) {
    $questionHTML = htmlspecialchars($question->getIdQuestion());
    $questionURL = rawurlencode($question->getIdQuestion());
    echo '<p> Question : ' . '<a href="./frontController.php?action=read&idQuestion=' .
        $questionURL . '">' . $questionHTML . '</a>' . ' <a href="./frontController.php?action=delete&idQuestion=' .
        $questionURL . '">' . 'Supprimer' . '</a>' . ' <a href="./frontController.php?action=update&idQuestion=' .
        $questionURL . '">' . 'Modifier' . '</a></p>';
}