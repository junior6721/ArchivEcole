<?php

namespace App\Services;

use App\Models\Diploma;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateForDiploma(Diploma $diploma): string
    {
        // Generate unique token if not exists
        if (!$diploma->qr_code_token) {
            $diploma->qr_code_token = Str::random(32);
            $diploma->save();
        }

        // Build verification URL
        $verifyUrl = route('verify.public', [
            'token' => $diploma->qr_code_token,
        ]);

        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(config('archivecole.qr_code.size'))
            ->errorCorrection(config('archivecole.qr_code.error_correction'))
            ->margin(config('archivecole.qr_code.margin'))
            ->generate($verifyUrl);

        return base64_encode($qrCode);
    }

    public function getQrCodeDataUrl(Diploma $diploma): string
    {
        $qrCode = $this->generateForDiploma($diploma);
        return 'data:image/png;base64,' . $qrCode;
    }

    public function saveDiplomaQrCode(Diploma $diploma): bool
    {
        try {
            $qrCode = QrCode::format('png')
                ->size(config('archivecole.qr_code.size'))
                ->errorCorrection(config('archivecole.qr_code.error_correction'))
                ->margin(config('archivecole.qr_code.margin'))
                ->generate(route('verify.public', [
                    'token' => $diploma->qr_code_token,
                ]));

            $path = "qrcodes/{$diploma->uuid}.png";
            Storage::disk('local')->put($path, $qrCode);

            $diploma->qr_code_data = $path;
            $diploma->save();

            return true;
        } catch (\Exception $e) {
            \Log::error('QR Code generation failed', [
                'diploma_id' => $diploma->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
