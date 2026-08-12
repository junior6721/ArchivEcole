<?php

namespace App\Enums;

enum VerificationStatus: string
{
    case PENDING = 'pending';
    case INFO_SENT = 'info_sent';
    case OTP_SENT = 'otp_sent';
    case OTP_VERIFIED = 'otp_verified';
    case PAYMENT_PENDING = 'payment_pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::INFO_SENT => 'Infos envoyées',
            self::OTP_SENT => 'OTP envoyé',
            self::OTP_VERIFIED => 'OTP vérifié',
            self::PAYMENT_PENDING => 'Paiement en attente',
            self::PROCESSING => 'En traitement',
            self::COMPLETED => 'Complété',
            self::REJECTED => 'Rejeté',
            self::EXPIRED => 'Expiré',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING, self::INFO_SENT => 'gray',
            self::OTP_SENT, self::OTP_VERIFIED => 'blue',
            self::PAYMENT_PENDING => 'orange',
            self::PROCESSING => 'yellow',
            self::COMPLETED => 'green',
            self::REJECTED, self::CANCELLED, self::EXPIRED => 'red',
        };
    }
}
