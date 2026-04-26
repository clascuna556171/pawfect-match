<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Submitted = 'Submitted';
    case UnderReview = 'Under Review';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Withdrawn = 'Withdrawn';

    public static function openStatuses(): array
    {
        return [
            self::Submitted->value,
            self::UnderReview->value,
            self::Approved->value,
        ];
    }

    public static function transitionMap(): array
    {
        return [
            self::Submitted->value => [
                self::UnderReview->value,
                self::Approved->value,
                self::Rejected->value,
                self::Withdrawn->value,
            ],
            self::UnderReview->value => [
                self::Approved->value,
                self::Rejected->value,
                self::Withdrawn->value,
            ],
            self::Approved->value => [
                self::Withdrawn->value,
            ],
            self::Rejected->value => [
                self::UnderReview->value,
            ],
            self::Withdrawn->value => [
                self::UnderReview->value,
            ],
        ];
    }
}
