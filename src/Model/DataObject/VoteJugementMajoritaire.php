<?php

namespace App\Votee\Model\DataObject;

class VoteJugementMajoritaire extends Vote
{

    public function getVoteDesign($idQuestion, $idProposition): string
    {
        return '
        <div class="flex">
            <div class="p-3 rounded-l-xl w-28 text-white font-semibold" style="background-color: #c6c6f4">
                <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition) . '&value=-2">Insuffisant</a>
            </div>
             <div class="p-3 rounded-l-xl w-28 -ml-3.5 text-white font-semibold text-center" style="background-color: #b8b8f8">
               <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition) . '&value=-1">Passable</a>
             </div>
             <div class="p-3 rounded-l-xl w-28 -ml-3.5 text-white font-semibold text-center" style="background-color: #aea4ff">
               <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition) . '&value=1">Bien</a>
             </div>
             <div class="p-3 rounded-xl w-28 -ml-3.5 text-white font-semibold text-center" style="background-color: #8080d7">
               <a href="./frontController.php?action=createVote&idQuestion=' . rawurlencode($idQuestion) . '&idProposition=' . rawurlencode($idProposition) . '&value=2">Très Bien</a>
             </div>
         </div>
        ';
    }

}