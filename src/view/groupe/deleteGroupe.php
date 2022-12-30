<div class="flex flex-col justify-center items-center gap-7">
    <h1>Cette action supprimera définitivement le groupe</h1>
    <div class="flex items-center gap-7">
        <a class="p-2 w-48 text-center text-white bg-main font-semibold rounded-lg"
           href="./frontController.php?controller=groupe&action=deletedGroupe&idGroupe=<?= rawurlencode($idGroupe) ?>">Oui</a>
        <a class="p-2 w-48 text-center text-white bg-main font-semibold rounded-lg"
           href="./frontController.php?controller=groupe&action=readGroupe&idGroupe=<?= rawurlencode($idGroupe)?>">Non</a>
    </div>
</div>