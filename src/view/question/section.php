<div class="flex flex-col gap-5 items-center pt-6">
    <form class="flex flex-col gap-10 items-center" method="post" action="frontController.php?&action=create">
        <div>
            <input class="w-80 border-2" type="number" placeholder="Nombre de sections" name="nbSections" min="1" max="15" required/>
        </div>
        <input class="p-2 w-48 text-white bg-main font-semibold rounded-lg" type="submit" value="Confirmer" />
    </form>
</div>