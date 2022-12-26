<?php

namespace App\Votee\Model\Repository;

use App\Votee\Model\DataObject\Groupe;

class GroupeRepository extends AbstractRepository {

    protected function getNomsColonnes(): array {
        return array(
            'IDGROUPE',
            'NOMGROUPE',
        );

    }
    function getNomTable(): string { return "Groupes"; }
    function getNomClePrimaire(): string { return "IDGROUPE"; }

    function getProcedureInsert(): string { return "AjouterGroupes"; }
    function getProcedureUpdate(): string { return ""; }
    function getProcedureDelete(): string { return ""; }

    public function construire(array $propositionFormatTableau) : Groupe {
        return new Groupe (
            $propositionFormatTableau['IDGROUPE'],
            $propositionFormatTableau['NOMGROUPE'],
        );
    }

}