<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $pagetitle; ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<header>
    <nav>
        <div class="logo">

        </div>
        <div class="nav-link-container">
            <div class="nav-link">
                <a href="">Accueil</a>
            </div>
            <div class="nav-link">
                <a href="">Vote</a>
            </div>
            <div class="nav-link">
                <a href="">Contact</a>
            </div>
        </div>
        <div>

        </div>
    </nav>
</header>
<main>
    <?php
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>

</footer>
</body>
</html>