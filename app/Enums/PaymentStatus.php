<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Initiated = 'Initiated';
    case WaitForOwnerRequisites = 'Wait For Owner Requisites';
    case NotAllowed = 'NotAllowed';
    case Allowed = 'Allowed';
    case WaitingForSign = 'WaitingForSign';
    case WaitingForCreate = 'WaitingForCreate';
    case Created = 'Created';
    case Paid = 'Paid';
    case Canceled = 'Canceled';
    case Rejected = 'Rejected';

    public static function getNumberByName(string $name): ?int
    {
        $order = [
            self::Initiated->value => 1,
            self::WaitForOwnerRequisites->value => 2,
            self::NotAllowed->value => 3,
            self::Allowed->value => 4,
            self::WaitingForSign->value => 5,
            self::WaitingForCreate->value => 6,
            self::Created->value => 7,
            self::Paid->value => 8,
            self::Canceled->value => 9,
            self::Rejected->value => 10,
        ];

        return $order[$name] ?? 999;
    }

    public static function getNameByNumber(int $number): ?string
    {
        $order = [
            1 => self::Initiated->value,
            2 => self::WaitForOwnerRequisites->value,
            3 => self::NotAllowed->value,
            4 => self::Allowed->value,
            5 => self::WaitingForSign->value,
            6 => self::WaitingForCreate->value,
            7 => self::Created->value,
            8 => self::Paid->value,
            9 => self::Canceled->value,
            10 => self::Rejected->value,
        ];

        return $order[$number] ?? 'Не определён';
    }
}
