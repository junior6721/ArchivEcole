<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class EmailService
{
    public function sendOtp(OtpCode $otp): bool
    {
        try {
            $message = $this->buildOtpMessage($otp);
            
            Mail::raw($message, function ($mail) use ($otp) {
                $mail->to($otp->recipient)
                    ->subject('[ArchivEcole] Code de vérification');
            });

            return true;
        } catch (Exception $e) {
            Log::error('Email sending failed', [
                'otp_id' => $otp->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function buildOtpMessage(OtpCode $otp): string
    {
        return <<<HTML
        <html>
        <body>
            <p>Bonjour,</p>
            <p>Votre code de vérification ArchivEcole est:</p>
            <h2>{$otp->code}</h2>
            <p>Ce code est valable pendant 15 minutes.</p>
            <p>Ne partagez ce code avec personne.</p>
            <br />
            <p>Cordialement,<br />L'équipe ArchivEcole</p>
        </body>
        </html>
        HTML;
    }
}
