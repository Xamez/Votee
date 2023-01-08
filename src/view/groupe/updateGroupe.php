<form method="post" class="flex flex-col items-center gap-7" action="frontController.php?controller=groupe&action=updatedGroupe">
    <input class="w-full max-w-xs border-2" type="text" placeholder="Nom du groupe" name="nomGroupe" value="<?= htmlspecialchars($groupe->getNomGroupe()) ?>" required/>
    <input type="hidden" name="action" value="updatedGroupe">
    <div class="flex justify-center">
        <input type="hidden" name="idGroupe" value="<?= $groupe->getIdGroupe();?>">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>