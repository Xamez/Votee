<form method="get" class="flex flex-col gap-7" action="frontController.php?action=updatedQuestion">
    <div class="flex flex-col gap-5 border-2 p-8 rounded-3xl">
<!--TODO-->
        <?php
        $sectionDescHTML = htmlspecialchars($question->getDescription());
        echo '<textarea class="border-2 max-h-96 h-25" maxlength="2000" name="description" required>'. $sectionDescHTML.'</textarea>';
        ?>
    <input type="hidden" name="action" value="updatedQuestion">
    <div class="flex justify-center">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Valider" />
    </div>
</form>