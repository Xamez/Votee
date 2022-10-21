<form method="post" action="frontController.php?&action=created">
    <fieldset>
        <legend>Créer une question :</legend>
        <p>
            <label for="visibilite_id">visibilite</label> :
            <input type="text" placeholder="Nimes" name="depart" id="visibilite_id" required/>
        </p>
        <p>
            <label for="systeme_vote_id">systemeVoteQuestion</label> :
            <input type="text" placeholder="Paris" name="arrivee" id="systeme_vote_id" required/>
        </p>
        <p>
            <label for="plan_texte_id">planTexteQuestion</label> :
            <input type="text" placeholder="2022-09-29" name="date" id="plan_texte_id" required/>
        </p>
        <p>
            <label for="date_debut_question_id<_id">dateDebutQuestion</label> :
            <input type="number" placeholder="5" name="places" id="date_debut_question_id" required/>
        </p>
        <p>
            <label for="date_fin_question_id">dateFinQuestion</label> :
            <input type="number" placeholder="12" name="prix" id="date_fin_question_id" required/>
        </p>
        <p>
            <label for="date_debut_vote_id">dateDebutVote</label> :
            <input type="text" placeholder="tnalix" name="login" id="date_debut_vote_id" required/>
        </p>
        <p>
            <label for="date_fin_vote_id">dateFinVote</label> :
            <input type="text" placeholder="tnalix" name="login" id="date_fin_vote_id" required/>
        </p>
        <p>
            <label for="categorie_id">idCategorie</label> :
            <input type="text" placeholder="tnalix" name="login" id="categorie_id" required/>
        </p>
        <p>
            <label for="login_id">login</label> :
            <input type="text" placeholder="tnalix" name="login" id="login_id" required/>
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>