<?php

namespace App\Votee\Model\DataObject;

abstract class AbstractDataObject {

    public abstract function formatTableau(): array;
}