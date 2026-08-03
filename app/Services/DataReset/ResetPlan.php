<?php

namespace App\Services\DataReset;

abstract class ResetPlan
{
    abstract public function getResetType(): string;
    abstract public function getConfirmationPhrase(): string;
    abstract public function getDeletionSteps(): array;
}
