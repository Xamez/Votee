<form method="post" class="flex flex-col gap-7" action="frontController.php?controller=groupe&action=updatedGroupe">
        <input class="w-80 border-2" type="text" placeholder="Nom du groupe" name="nomGroupe" value="<?= htmlspecialchars($groupe->getNomGroupe()) ?>" required/>
        <input type="hidden" name="action" value="updatedGroupe">
        <div class="flex justify-center">
            <input type="hidden" name="idGroupe" value="<?= $groupe->getIdGroupe();?>">
            <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Suivant" />
        </div>
</form>