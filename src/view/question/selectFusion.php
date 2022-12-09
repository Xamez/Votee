<!--PAGE TEMPORAIRE-->
<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?controller=question&action=createFusion">
        <div>
            <input class="w-80 border-2" type="number" placeholder="Id de la proposition à fusionner" name="idProposition1" min="0" required/>
        </div>
        <input type="hidden" name="idQuestion" value="<?= $_GET['idQuestion'];?>">
        <input type="hidden" name="idProposition" value="<?= $_GET['idProposition'];?>">
        <input class="p-2 w-48 text-white bg-main font-semibold rounded-lg" type="submit" value="Confirmer" />
    </form>
</div>
