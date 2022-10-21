<form method="post" action="frontController.php?&action=created">
    <fieldset>
        <legend>Créer une question :</legend>
        <p>
            <label for="visibilite_id">visibilite</label> :
            <input type="text" placeholder="visible" name="visibilite" id="visibilite_id" required/>
        </p>
        <p>
            <label for="systeme_vote_id">systemeVoteQuestion</label> :
            <input type="text" placeholder="Question" name="systemeVoteQuestion" id="systeme_vote_id" required/>
        </p>
        <p>
            <label for="plan_texte_id">planTexteQuestion</label> :
            <input type="text" placeholder="Plan" name="planTexteQuestion" id="plan_texte_id" required/>
        </p>
        <p>
            <label for="date_debut_question_id<_id">dateDebutQuestion</label> :
            <input type="date" name="dateDebutQuestion" id="date_debut_question_id" required/>
        </p>
        <p>
            <label for="date_fin_question_id">dateFinQuestion</label> :
            <input type="date" name="dateFinQuestion" id="date_fin_question_id" required/>
        </p>
        <p>
            <label for="date_debut_vote_id">dateDebutVote</label> :
            <input type="date" name="dateDebutVote" id="date_debut_vote_id" required/>
        </p>
        <p>
            <label for="date_fin_vote_id">dateFinVote</label> :
            <input type="date" name="dateFinVote" id="date_fin_vote_id" required/>
        </p>
        <p>
            <label for="categorie_id">idCategorie</label> :
            <input type="number" placeholder="1" name="idCategorie" id="categorie_id" required/>
        </p>
        <p>
            <label for="login_id">login</label> :
            <input type="text" placeholder="tjean" name="login" id="login_id" required/>
        </p>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>