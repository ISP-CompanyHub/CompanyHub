<?php

namespace App\Enums;

enum VacationStatus: string
{
    case PAID = 'paid';
    case UNPAID  = 'unpaid';
    case SICK = 'sick';
    case EDUCATION = 'education';
    case REMOTE = 'remote';
    case EMERGENCY = 'emergency';
    case VACATION = 'vacation';

};
