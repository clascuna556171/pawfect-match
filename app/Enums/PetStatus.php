<?php

namespace App\Enums;

enum PetStatus: string
{
    case Available = 'Available';
    case Pending = 'Pending';
    case Adopted = 'Adopted';
    case NotAvailable = 'Not Available';
    case OnHold = 'On Hold';
}
