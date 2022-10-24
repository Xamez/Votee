<?php
$index=0;
foreach ($sections as $section) {
    $index++;
    $sectionTitreHTML = htmlspecialchars($section->getTitre());
    $sectionDescHTML = htmlspecialchars($section->getDescription());

    echo '<h1 class="text-main">' . $index . ' - ' . $sectionTitreHTML . '</h1><p>'. $sectionDescHTML .'</p>';
}




