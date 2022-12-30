<h1 class="title text-dark text-2xl font-semibold">Informations</h1>
<?php echo $utilisateurC->getPrenom() . ' ' . $utilisateurC->getNom();
?>

<h1 class="title text-dark text-2xl font-semibold">Groupes</h1>
<div class="flex flex-wrap gap-2 justify-center">
<?php
foreach ($groupes as $groupe) {
    echo '<a href="./frontController.php?controller=groupe&action=readGroupe&idGroupe=' . rawurlencode($groupe->getIdGroupe()) . '">
            <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                <span class="material-symbols-outlined">group</span>' . htmlspecialchars($groupe->getNomGroupe()) . '
            </div>
          </a>';
}
?>
</div>