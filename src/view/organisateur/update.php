<form method="get" action="frontController.php?&action=updated">
    <div class="bg-light p-10 rounded-3xl">
        <!--        <p>-->
        <!--            <label for="visibilite_id">visibilite</label> :-->
        <!--            <input type="text" placeholder="visible" name="visibilite" id="visibilite_id" required/>-->
        <!--        </p>-->
        <div class="flex flex-col">
            <label for="systeme_vote_id">Titre :</label>
            <input type="text" placeholder="Question" name="titreQuestion" id="systeme_vote_id" required/>
        </div>
        <div class="flex flex-col">
            <label for="vote_desc_id">Description :</label>
            <textarea class="max-h-52" type="text" placeholder="Description" name="descriptionQuestion" id="vote_desc_id" required></textarea>
        </div>

        <p class="text-center my-6">Organisation</p>
        <?php
        for ($i = 1; $i <= $nbSections; $i++) {
            echo '<div class="flex flex-col">
                <label for="systeme_vote_id' . $i . '">Section ' . $i . ' :</label>
                <input type="text" placeholder="Nom de la section" name="section' . $i .'" id="systeme_vote_id'. $i . '" required/>
            </div>';
        }
        ?>
        <p class="text-center my-6">Calendrier</p>
        <div class="flex gap-10 items-end">
            <p class="w-36 font-semibold">Période d'écriture :</p>
            <div class="flex flex-col">
                <label for="date_debut_question_id<_id">Débute le </label>
                <input type="date" name="dateDebutQuestion" id="date_debut_question_id" required/>
            </div>
            <div class="flex flex-col">
                <label for="date_fin_question_id">Termine le</label>
                <input type="date" name="dateFinQuestion" id="date_fin_question_id" required/>
            </div>
        </div>
        <div class="flex gap-10 items-end">
            <p class="w-36 font-semibold">Période de vote :</p>
            <div class="flex flex-col">
                <label for="date_debut_vote_id">Débute le </label>
                <input type="date" name="dateDebutVote" id="date_debut_vote_id" required/>
            </div>
            <div class="flex flex-col">
                <label for="date_fin_vote_id">Termine le</label>
                <input type="date" name="dateFinVote" id="date_fin_vote_id" required/>
            </div>
        </div>
        <p class="text-center my-6">Votant</p>
        <div>
            <label for="login_id">login</label> :
            <input type="text" placeholder="tjean" name="login" id="login_id" required/>
        </div>
    </div>
    <div>
        <input type="text" hidden value="visible" placeholder="visible" name="visibilite" id="visibilite_id" required/>
        <input class="p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Créer le vote" />
    </div>
</form>
