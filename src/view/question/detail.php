<?php
echo "<ul>
        <li> idQuestion : " . htmlspecialchars($question->getIdQuestion()). "</li>
        <li> visibilite : " . htmlspecialchars($question->getVisibilite()). "</li>
        <li> Titre : " . htmlspecialchars($question->getTitre()). "</li>
        <li> Description : " . htmlspecialchars($question->getDescription()). "</li>
        <li> dateDebutQuestion : " . htmlspecialchars($question->getDateDebutQuestion()). "</li>
        <li> dateFinQuestion : " . htmlspecialchars($question->getDateFinQuestion()). "</li>
        <li> dateDebutVote : " . htmlspecialchars($question->getDateDebutVote()). "</li>
        <li> dateFinVote : " . htmlspecialchars($question->getDateFinVote()). "</li>
        <li> idCategorie : " . htmlspecialchars($question->getIdCategorie()). "</li>
        <li> login : " . htmlspecialchars($question->getLogin()). "</li>
      </ul>";