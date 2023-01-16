<a href="./frontController.php?controller=proposition&action=readProposition&idQuestion=<?=$idQuestion . '&idProposition=' . $idProposition ?>">
    <div class="flex bg-light justify-between p-3 md:p-2 items-center rounded">
        <div class="flex justify-items-start md:items-center gap-3 md:gap-2 md:flex-row flex-col">
            <p class="font-bold text-dark hidden md:block">Proposition de : </p>
            <div class="flex">
                <div class="bg-white flex gap-1 text-main shadow-md rounded-2xl w-fit p-2">
                    <span class="material-symbols-outlined">account_circle</span><?=htmlspecialchars($responsable->getPrenom()) . ' ' . htmlspecialchars($responsable->getNom()) ?>
                </div>
            </div>
            <span class="text-ellipsis overflow-hidden whitespace-nowrap"><?= htmlspecialchars($proposition->getTitreProposition()) ?></span>
        </div>
        <div class="flex gap-2 md:flex-row flex-col">
           <?= (!$proposition->isVisible() ? '<span class="material-symbols-outlined">inventory_2</span>' : '') ?>
            <span class="material-symbols-outlined">arrow_forward_ios</span>
        </div>
    </div>
</a>