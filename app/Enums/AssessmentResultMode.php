<?php

namespace App\Enums;

enum AssessmentResultMode: string
{
    case PASS_FAIL = 'pass_fail';
    case SCORE_ONLY = 'score_only';
}
