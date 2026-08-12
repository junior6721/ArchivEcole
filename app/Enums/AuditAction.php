<?php

namespace App\Enums;

enum AuditAction: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case CREATE_DIPLOMA = 'create_diploma';
    case UPDATE_DIPLOMA = 'update_diploma';
    case DELETE_DIPLOMA = 'delete_diploma';
    case IMPORT_DIPLOMAS = 'import_diplomas';
    case VIEW_DIPLOMA = 'view_diploma';
    case VERIFY_DIPLOMA = 'verify_diploma';
    case DOWNLOAD_CERTIFICATE = 'download_certificate';
    case CREATE_VERIFICATION_REQUEST = 'create_verification_request';
    case UPDATE_VERIFICATION_REQUEST = 'update_verification_request';
    case PAYMENT_PROCESSED = 'payment_processed';
    case OTP_GENERATED = 'otp_generated';
    case OTP_VERIFIED = 'otp_verified';
    case CREATE_USER = 'create_user';
    case UPDATE_USER = 'update_user';
    case DELETE_USER = 'delete_user';
    case CREATE_INSTITUTION = 'create_institution';
    case UPDATE_INSTITUTION = 'update_institution';
    case DISABLE_INSTITUTION = 'disable_institution';
    case CREATE_STUDENT = 'create_student';
    case UPDATE_STUDENT = 'update_student';
    case DELETE_STUDENT = 'delete_student';

    public function label(): string
    {
        return match($this) {
            self::LOGIN => 'Connexion',
            self::LOGOUT => 'Déconnexion',
            self::CREATE_DIPLOMA => 'Création diplôme',
            self::UPDATE_DIPLOMA => 'Modification diplôme',
            self::DELETE_DIPLOMA => 'Suppression diplôme',
            self::IMPORT_DIPLOMAS => 'Import diplômes',
            self::VIEW_DIPLOMA => 'Consultation diplôme',
            self::VERIFY_DIPLOMA => 'Vérification diplôme',
            self::DOWNLOAD_CERTIFICATE => 'Téléchargement certificat',
            self::CREATE_VERIFICATION_REQUEST => 'Création demande vérification',
            self::UPDATE_VERIFICATION_REQUEST => 'Modification demande vérification',
            self::PAYMENT_PROCESSED => 'Paiement traité',
            self::OTP_GENERATED => 'OTP généré',
            self::OTP_VERIFIED => 'OTP vérifié',
            self::CREATE_USER => 'Création utilisateur',
            self::UPDATE_USER => 'Modification utilisateur',
            self::DELETE_USER => 'Suppression utilisateur',
            self::CREATE_INSTITUTION => 'Création institution',
            self::UPDATE_INSTITUTION => 'Modification institution',
            self::DISABLE_INSTITUTION => 'Désactivation institution',
            self::CREATE_STUDENT => 'Création étudiant',
            self::UPDATE_STUDENT => 'Modification étudiant',
            self::DELETE_STUDENT => 'Suppression étudiant',
        };
    }
}
