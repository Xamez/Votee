<?php
echo "<ul>
        <li> idQuestion" . htmlspecialchars($question->getIdQuestion()). "</li>
        <li> visibilite" . htmlspecialchars($question->getVisibilite()). "</li>
        <li> systemeVoteQuestion" . htmlspecialchars($question->getSystemeVoteQuestion()). "</li>
        <li> planTexteQuestion" . htmlspecialchars($question->getPlanTexteQuestion()). "</li>
        <li> dateDebutQuestion" . htmlspecialchars($question->getDateDebutQuestion()). "</li>
        <li> dateFinQuestion" . htmlspecialchars($question->getDateFinQuestion()). "</li>
        <li> dateDebutVote" . htmlspecialchars($question->getDateDebutVote()). "</li>
        <li> dateFinVote" . htmlspecialchars($question->getDateFinVote()). "</li>
        <li> idCategorie" . htmlspecialchars($question->getIdCategorie()). "</li>
        <li> login" . htmlspecialchars($question->getLogin()). "</li>
      </ul>";