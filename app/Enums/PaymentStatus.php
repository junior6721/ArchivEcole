<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::PROCESSING => 'En traitement',
            self::COMPLETED => 'Complété',
            self::FAILED => 'Échoué',
            self::CANCELLED => 'Annulé',
            self::EXPIRED => 'Expiré',
            self::REFUNDED => 'Remboursé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::PROCESSING => 'blue',
            self::COMPLETED => 'green',
            self::FAILED, self::CANCELLED, self::EXPIRED => 'red',
            self::REFUNDED => 'orange',
        };
    }
}
