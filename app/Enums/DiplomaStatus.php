<?php

namespace App\Enums;

enum DiplomaStatus: string
{
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
    case VERIFIED = 'verified';
    case REVOKED = 'revoked';
    case PENDING_REVIEW = 'pending_review';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Brouillon',
            self::ARCHIVED => 'Archivé',
            self::VERIFIED => 'Vérifié',
            self::REVOKED => 'Révoqué',
            self::PENDING_REVIEW => 'En attente de révision',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::ARCHIVED => 'blue',
            self::VERIFIED => 'green',
            self::REVOKED => 'red',
            self::PENDING_REVIEW => 'orange',
        };
    }
}
