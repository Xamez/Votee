<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pagetitle; ?></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/tailwindcss.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="assets/js/nav-burger.js"></script>
    <link rel="icon" type="image/x-icon" href="assets/resources/logo_votee.png">
</head>
<body>
<div class="everything">
<header>
    <nav class="flex justify-between items-center gap-10 p-2 mx-10 border-b-2">
        <div class="flex gap-3 items-center">
            <img class="w-14" src="assets//resources/logo_votee.png" alt="logo">
            <span class="text-xl font-semibold text-dark hidden sm:block">Votee</span>
        </div>
        <div class="flex-grow pl-10 text-xl hidden md:flex gap-10 text-dark">
            <?php
            use App\Votee\Controller\AbstractController;
            use App\Votee\Lib\Notification;
            AbstractController::afficheVue('navbar.php');
            ?>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex gap-4 items-center">
                <?php
                AbstractController::afficheVue('userLogin.php', ['small' => false]);
                ?>
            </div>
            <div class="flex sm:hidden gap-4 items-center">
                <?php
                AbstractController::afficheVue('userLogin.php', ['small' => true]);
                ?>
            </div>
            <div class="flex md:hidden gap-4 items-center">
                <span id="open-icon" class="material-symbols-outlined text-xl cursor-pointer text-dark" style="font-size: 2rem;">menu</span>
                <div id="nav-burger" class="hidden gap-2 absolute flex flex-col bg-main w-40 z-10 rounded-lg text-white text-xl p-2 pl-2">
                    <span id="close-icon" class="material-symbols-outlined cursor-pointer text-red-500 text-right pb-0" style="font-size: 1.5rem;">close</span>
                    <?php
                    AbstractController::afficheVue('navbar.php');
                    ?>
                </div>
            </div>
        </div>
    </nav>
</header>
<?php
    foreach (['success', 'warning', 'danger'] as $type) {
        if (Notification::contientMessage($type)) {
            foreach (Notification::lireMessages($type) as $key => $message) {
                echo '<div class="z-30 fixed shadow-lg bottom-14 right-14 flex justify-between items-center gap-5 toast toast-' . $type. '">
                        <div class="flex justify-center shadow-lg items-center justify-items-center w-12 h-12 p-2.5 toast-icon toast-icon-'. $type .'">
                        <span class="material-symbols-outlined">';
                            if ($type == 'success')  echo 'check_circle';
                            else echo 'error';
                            echo '</span>
                        </div>
                        <span>' . $message . '</span>              
                      </div>';
            }
        }
    }

    if (!isset($mainType)) {
        echo '
        <main class="flex flex-col gap-5 mx-auto w-11/12 md:w-8/12">
            <div class="flex flex-col items-center pt-6">
                <h1 class="text-4xl break-all font-bold text-center text-dark">'. htmlspecialchars($title).'</h1>
                ';
                if (isset($subtitle)) echo '<p class="text-main">'. htmlspecialchars($subtitle) .'</p>';
            echo '</div>';
        require __DIR__ . "/{$cheminVueBody}";
        echo '</main>';
    } else if ($mainType == 1) {
        require __DIR__ . "/{$cheminVueBody}";
    }

AbstractController::afficheVue('footer.php', ['footerType' => $footerType ?? 0]);
?>
</div>
</body>
</html>