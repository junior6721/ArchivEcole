<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    protected $provider;
    protected $config;

    public function __construct()
    {
        $this->provider = config('sms.default');
        $this->config = config("sms.providers.{$this->provider}");
    }

    public function sendOtp(OtpCode $otp): bool
    {
        if (!$this->config['enabled'] ?? false) {
            Log::warning('SMS provider not enabled');
            return false;
        }

        try {
            $message = $this->buildOtpMessage($otp);
            
            if ($this->provider === 'ikoddi') {
                return $this->sendViaIkoddi($otp->recipient, $message);
            }

            if ($this->provider === 'twilio') {
                return $this->sendViaTwilio($otp->recipient, $message);
            }

            throw new Exception("Unknown SMS provider: {$this->provider}");
        } catch (Exception $e) {
            Log::error('SMS sending failed', [
                'otp_id' => $otp->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function buildOtpMessage(OtpCode $otp): string
    {
        return "Votre code de vérification ArchivEcole est: {$otp->code}. Valable 15 minutes.";
    }

    protected function sendViaIkoddi(string $phone, string $message): bool
    {
        // Placeholder for Ikoddi API integration
        // Will be implemented in PHASE 7
        Log::info('SMS via Ikoddi', ['phone' => $phone, 'message' => $message]);
        return true;
    }

    protected function sendViaTwilio(string $phone, string $message): bool
    {
        // Placeholder for Twilio integration
        Log::info('SMS via Twilio', ['phone' => $phone, 'message' => $message]);
        return true;
    }

    public function send(string $phone, string $message): bool
    {
        return $this->sendViaIkoddi($phone, $message);
    }
}
