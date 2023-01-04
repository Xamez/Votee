<h1 class="title text-dark text-2xl font-semibold">Informations</h1>

<!-- créer une carte utilisateur avec logo sur la gauche et toutes les informations sur la droite (nom, prénom, description) dans des champs avec des flex tout ça tenant sur la hauteur de l'image de profil-->
<div class="flex justify-center gap-2">
    <div>
        <span style="font-size: 250px" class="material-symbols-outlined select-none">face</span>
    </div>
    <div class="flex flex-col gap-3 text-xl justify-start">
        <span><?=htmlspecialchars($utilisateurC->getPrenom()). ' ' . htmlspecialchars($utilisateurC->getNom())?></span>
        <span>description olala</span>
    </div>
</div>


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