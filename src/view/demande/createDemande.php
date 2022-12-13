<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?controller=demande&action=createdDemande">
        <div>
            <textarea class="w-80 border-2" placeholder="Motif de la demande" name="motif" required></textarea>
        </div>
        <input type="hidden" name="titreDemande" value="<?= $titreDemande ?>">
        <input type="hidden" name="idQuestion" value="<?= $idQuestion ?>">
        <input class="p-2 w-48 text-white bg-main font-semibold rounded-lg" type="submit" value="Confirmer"/>
    </form>
</div>