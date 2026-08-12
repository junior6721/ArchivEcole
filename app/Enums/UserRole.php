<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case INSTITUTION_ADMIN = 'institution_admin';
    case AGENT = 'agent';
    case VERIFIER = 'verifier';
    case STUDENT = 'student';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Administrateur',
            self::INSTITUTION_ADMIN => 'Administrateur Institution',
            self::AGENT => 'Agent',
            self::VERIFIER => 'Vérificateur',
            self::STUDENT => 'Étudiant',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Gère la plateforme globale',
            self::INSTITUTION_ADMIN => 'Gère l\'institution',
            self::AGENT => 'Effectue les opérations autorisées',
            self::VERIFIER => 'Vérifie les diplômes',
            self::STUDENT => 'Étudiant',
        };
    }
}
