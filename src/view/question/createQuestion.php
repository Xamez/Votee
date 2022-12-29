<form method="post" class="flex flex-col gap-7" action="frontController.php?controller=question&action=createdQuestion">
    <div class="flex flex-col gap-7 border-2 p-8 rounded-3xl">
        <div>
            <div class="flex flex-col pb-2">
                <input type="text" placeholder="Titre de la question" name="titreQuestion" id="systeme_vote_id" required/>
            </div>
            <div class="flex flex-col">
                <textarea class="max-h-52" maxlength="750" placeholder="Description" name="descriptionQuestion" id="vote_desc_id" required></textarea>
            </div>
        </div>
        <h1 class="title text-dark text-2xl font-semibold">Organisation</h1>
        <div>
            <?php

            use App\Votee\Lib\ConnexionUtilisateur;

            for ($i = 1; $i <= $nbSections; $i++) {
                echo '<div class="flex flex-col">
                         <label for="systeme_vote_id' . $i . '">Section ' . $i . ' :</label>
                         <input type="text" placeholder="Nom de la section" name="section' . $i .'" id="systeme_vote_id'. $i . '" required/>
                      </div>';
            }
            ?>
        </div>
        <h1 class="title text-dark text-2xl font-semibold">Calendrier</h1>
        <div>
            <div class="flex gap-10 items-center">
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
            <div class="flex gap-10 items-center">
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
        </div>
        <h1 class="title text-dark text-2xl font-semibold">Type de Vote<h1/>
        <div>
            <div class="flex gap-10 items-center">
                <p class="w-36 font-semibold">Type de Vote :</p>
                <select name="voteType" class="p-2 rounded-md">
                    <?php
                    foreach ($voteTypes as $key => $value) {
                        echo '<option value="' . $key . '">' . $value . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <h1 class="title text-dark text-2xl font-semibold">Spécialiste<h1/>
            <div>
                <div class="flex gap-10 items-center">
                    <p class="w-36 font-semibold">Ajouter un spécialiste</p>
                    <select name="loginSpe" class="p-2 rounded-md">
                        <?php
                        foreach ($users as $user) {
                            echo '<option value="' . $user->getLogin() . '">' . $user->getNom() . ' ' . $user->getPrenom() . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
    </div>
    <input type="text" hidden name="loginOrga" value="<?= (ConnexionUtilisateur::getUtilisateurConnecte())->getLogin() ?>" required/>
    <input type="number" hidden value=<?=$nbSections?> name="nbSections" required/>
    <input type="text" hidden value="visible" placeholder="visible" name="visibilite" required/>
    <div class="flex justify-center">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Suivant" />
    </div>
</form>