<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\VerificationRequest;
use Illuminate\Support\Str;
use Exception;

class OtpService
{
    protected $smsService;
    protected $emailService;

    public function __construct(
        SmsService $smsService,
        EmailService $emailService
    ) {
        $this->smsService = $smsService;
        $this->emailService = $emailService;
    }

    public function generateAndSend(
        VerificationRequest $request,
        string $channel = 'sms',
        ?string $recipient = null
    ): OtpCode {
        if (!$recipient) {
            $recipient = $channel === 'sms' 
                ? $request->verifier_phone 
                : $request->verifier_email;
        }

        if (!$recipient) {
            throw new Exception("No {$channel} recipient provided");
        }

        // Invalidate previous OTP codes
        $request->otpCodes()
            ->where('verified_at', null)
            ->update(['expires_at' => now()]);

        // Create new OTP
        $otp = OtpCode::create([
            'verification_request_id' => $request->id,
            'channel' => $channel,
            'recipient' => $recipient,
            'ip_address' => request()->ip(),
        ]);

        // Send OTP
        if ($channel === 'sms') {
            $this->smsService->sendOtp($otp);
        } else {
            $this->emailService->sendOtp($otp);
        }

        return $otp;
    }

    public function verify(OtpCode $otp, string $code): bool
    {
        // Check if expired
        if ($otp->isExpired()) {
            return false;
        }

        // Check if already verified
        if ($otp->isVerified()) {
            return false;
        }

        // Check attempts
        if (!$otp->canAttempt()) {
            return false;
        }

        // Increment attempts
        $otp->incrementAttempts();

        // Check code
        if (!hash_equals($otp->code, $code)) {
            return false;
        }

        // Mark as verified
        $otp->markAsVerified();

        return true;
    }

    public function isValidForRequest(VerificationRequest $request): bool
    {
        $latestOtp = $request->otpCodes()
            ->orderByDesc('created_at')
            ->first();

        if (!$latestOtp) {
            return false;
        }

        return $latestOtp->isVerified() && !$latestOtp->isExpired();
    }
}
