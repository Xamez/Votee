<form method="post" class="flex flex-col gap-7 mt-10" action="frontController.php?controller=question&action=createdQuestion">
    <div class="flex flex-col gap-7">
        <div class="flex flex-col gap-3 rounded-xl py-4 p-7 bg-lightPurple">
            <h1 class="title text-dark text-2xl font-semibold">Informations</h1>
            <div class="flex flex-col pb-2 mt-4">
                <input type="text" placeholder="La légalisation du cannabis" minlength="10" maxlength="150" name="titreQuestion" id="systeme_vote_id" required/>
            </div>
            <div class="flex flex-col">
                <textarea class="border-gray-200 max-h-52 h-40" minlength="20" maxlength="2000" placeholder="La légalisation du cannabis est un sujet complexe et divise les avis..." name="descriptionQuestion" id="vote_desc_id" required></textarea>
            </div>
        </div>
        <div class="flex flex-col gap-7 rounded-xl py-4 p-7 bg-lightPurple">
            <h1 class="title text-dark text-2xl font-semibold">Plan</h1>
            <?php

            use App\Votee\Lib\ConnexionUtilisateur;
            use App\Votee\Model\DataObject\Periodes;

            for ($i = 1; $i <= $nbSections; $i++) {
                echo '<div class="flex flex-col gap-3">
                         <label for="systeme_vote_id' . $i . '">Section ' . $i . ' :</label>
                         <input type="text" placeholder="Nom de la section" minlength="10" maxlength="350" name="sections[]" id="systeme_vote_id'. $i . '" required/>
                         <textarea class="border-gray-200 max-h-52 h-40" minlength="20" maxlength="600" placeholder="Description" name="descriptionsSection[]" id="vote_desc_id'. $i . '" required></textarea>
                      </div>';
            }
            ?>
        </div>
        <div class="flex flex-col gap-7 rounded-xl py-4 p-7 bg-lightPurple">
            <h1 class="title text-dark text-2xl font-semibold">Calendrier</h1>
            <div class="flex flex-col md:flex-col sm:flex-row justify-center gap-5">
                <div class="flex gap-4 md:gap-10 items-center flex-col md:flex-row">
                    <p class="w-36 font-semibold"><?= Periodes::ECRITURE->value ?> :</p>
                    <div class="flex flex-col">
                        <label for="date_debut_question_id">Débute le </label>
                        <input type="date" min="<?=date('Y-m-d')?>" value="<?= date('Y-m-d') ?>" name="dateDebutQuestion" id="date_debut_question_id" required/>
                    </div>
                    <div class="flex flex-col">
                        <label for="date_fin_question_id">Termine le</label>
                        <input type="date" min="<?=date('Y-m-d')?>" name="dateFinQuestion" id="date_fin_question_id" required/>
                    </div>
                </div>
                <div class="flex gap-4 md:gap-10 items-center flex-col md:flex-row">
                    <p class="w-36 font-semibold"><?= Periodes::VOTE->value ?> :</p>
                    <div class="flex flex-col">
                        <label for="date_debut_vote_id">Débute le </label>
                        <input type="date" min="<?=date('Y-m-d')?>" name="dateDebutVote" id="date_debut_vote_id" required/>
                    </div>
                    <div class="flex flex-col">
                        <label for="date_fin_vote_id">Termine le</label>
                        <input type="date" min="<?=date('Y-m-d')?>" name="dateFinVote" id="date_fin_vote_id" required/>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-7 rounded-xl py-4 p-7 bg-lightPurple">
            <h1 class="title text-dark text-2xl font-semibold">Type de Vote<h2/>
            <div>
                <div class="flex gap-10 items-center flex-col md:flex-row">
                    <p class="w-36 font-semibold">Type de Vote :</p>
                    <select name="voteType" class="p-2 rounded-md border-2 border-zinc-800 bg-white">
                        <?php
                        foreach ($voteTypes as $key => $value)
                            echo '<option value="' . $key . '">' . $value . '</option>';
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-7 rounded-xl py-4 p-7 bg-lightPurple">
            <h1 class="title text-dark text-2xl font-semibold">Spécialiste<h2/>
            <div>
                <div class="flex gap-10 items-center flex-col md:flex-row">
                    <label for="loginSpe" class="w-36 font-semibold">Ajouter un spécialiste :</label>
                    <input list="users" name="loginSpe" class="p-2 rounded-md border-2 border-zinc-800">
                    <datalist id="users">
                        <?php
                        foreach ($users as $user)
                            echo '<option value="' . $user->getLogin() . '">' . $user->getNom() . ' ' . $user->getPrenom() . '</option>';
                        ?>
                    </datalist>
                </div>
            </div>
        </div>
    </div>
    <input type="text" hidden name="loginOrga" value="<?= (ConnexionUtilisateur::getUtilisateurConnecte())->getLogin() ?>" required/>
    <input type="number" hidden value="<?=$nbSections?>" name="nbSections" required/>
    <div class="flex justify-center">
        <input class="w-36 p-2 text-white bg-main font-semibold rounded-lg" type="submit" value="Suivant" />
    </div>
</form>