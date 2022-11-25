<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagetitle; ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="js/nav-burger.js"></script>
    <script type="text/javascript" src="js/accordion.js"></script>
</head>
<body>
<div class="everything">
<header>
    <nav class="flex justify-between items-center gap-10 p-2 mx-10 border-b-2">
        <div class="flex gap-3 items-center">
            <img class="w-14" src="../resources/logo_votee.png" alt="logo">
            <span class="text-xl font-semibold text-dark">Votee</span>
        </div>

        <div class="flex-grow pl-10 text-xl hidden md:flex gap-10 text-dark">
            <a href="./frontController.php?action=home"><span class="link-underline link-underline-color">Accueil</span></a>
            <a href="./frontController.php?action=readAllQuestion"><span class="link-underline link-underline-color">Vote</span></a>
            <a href=""><span class="link-underline link-underline-color">Demande</span></a>
        </div>

        <a class="hidden md:flex p-2 text-white bg-main font-semibold rounded-lg" href="./frontController.php?action=connexion">Se connecter</a>

        <div class="flex md:hidden gap-4 items-center">
            <a class="flex md:hidden p-2 text-white bg-main font-semibold rounded-lg" href="./frontController.php?action=connexion">Se connecter</a>
            <div>
                <i id="open-icon" class="fa fa-bars fa-2x w-7 text-center text-dark"></i>
                <i id="close-icon" class="hidden fa fa-xmark fa-2x w-7 text-center text-dark"></i>
            </div>
            <div id="nav-burger" class="hidden gap-2 absolute flex flex-col bg-main z-10 translate-y-10 rounded-lg text-white w-72 text-2xl p-2 pl-4">
                <a href="./frontController.php?action=home"><span class="link-underline link-underline-color">Accueil</span></a>
                <a href="./frontController.php?action=readAllQuestion"><span class="link-underline link-underline-color">Vote</span></a>
                <a href=""><span class="link-underline link-underline-color">Demande</span></a>
            </div>
        </div>
    </nav>
</header>
<?php
if (!isset($mainType)) {
    echo '
    <main class="flex flex-col gap-5 mx-auto w-8/12">
        <div class="flex flex-col items-center pt-6">
            <h1 class="text-4xl font-bold text-center text-dark">'. htmlspecialchars($title).'</h1>
            <p class="text-main">'. htmlspecialchars($subtitle) .'</p>
        </div>';
    require __DIR__ . "/{$cheminVueBody}";
    echo '</main>';
} else if ($mainType == 1) {
    require __DIR__ . "/{$cheminVueBody}";
}
?>
<footer>
    <?php
    if (!isset($footerType)) {
        echo '
        <svg class="w-full pt-10" viewBox="0 0 1200 197" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 164.554L59.375 143.923C116.75 123.293 231.5 82.0317 346.25 82.0317C461 82.0317 575.75 123.293 690.5 107.82C805.25 92.347 920 20.14 1034.75 20.14C1149.5 20.14 1264.25 92.347 1321.62 128.45L1379 164.554V195.5H1321.62C1264.25 195.5 1149.5 195.5 1034.75 195.5C920 195.5 805.25 195.5 690.5 195.5C575.75 195.5 461 195.5 346.25 195.5C231.5 195.5 116.75 195.5 59.375 195.5H2V164.554Z" fill="#AEA4FF" fill-opacity="0.8"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 70.7686H57.4583C114.917 70.7686 229.833 70.7686 344.75 58.1456C459.667 45.5226 574.583 20.2766 689.5 37.1073C804.417 53.9379 919.333 112.845 1034.25 121.261C1149.17 129.676 1264.08 87.5992 1321.54 66.5609L1379 45.5226V196.999H1321.54C1264.08 196.999 1149.17 196.999 1034.25 196.999C919.333 196.999 804.417 196.999 689.5 196.999C574.583 196.999 459.667 196.999 344.75 196.999C229.833 196.999 114.917 196.999 57.4583 196.999H0V70.7686Z" fill="#8080D7" fill-opacity="0.8"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 75.1952L33.3258 58.9546C65.5025 42.7139 131.005 10.2327 196.508 2.11234C263.159 -6.00798 328.662 10.2327 394.164 34.5936C459.667 58.9546 525.169 91.4358 590.672 119.857C656.174 148.278 722.826 172.639 788.328 176.699C853.831 180.759 919.333 164.519 984.836 132.037C1050.34 99.5562 1115.84 50.8342 1182.49 50.8342C1247.99 50.8342 1313.5 99.5562 1345.67 123.917L1379 148.278V197H1345.67C1313.5 197 1247.99 197 1182.49 197C1115.84 197 1050.34 197 984.836 197C919.333 197 853.831 197 788.328 197C722.826 197 656.174 197 590.672 197C525.169 197 459.667 197 394.164 197C328.662 197 263.159 197 196.508 197C131.005 197 65.5025 197 33.3258 197H0V75.1952Z" fill="#B8B8F8" fill-opacity="0.8"/>
        </svg>
        ';

    } else if ($footerType == 1) {
        echo '
        <div class="w-full pt-10">
            <div class="flex w-4/5 items-center justify-center mx-auto border-t border-main pb-12">
                <div class="flex flex-row text-main text-md lg:text-xl font-semibold pt-6">
                    <p>Tourniayre Maxence - Nalix Thomas - Cazaux Loris - Afonso Alexandre - Chevalier Julie</p>
                </div>
            </div>
        </div>
        ';
    }
    ?>
</footer>
</div>
</body>
</html>