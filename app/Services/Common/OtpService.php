<?php

namespace App\Services\Common;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Tạo và lưu mã OTP vào Cache (Hết hạn sau 5 phút)
     */
    public function generateOtp($identifier)
    {
        // Tạo mã 6 chữ số
        $otp = rand(100000, 999999);
        
        // Lưu vào Cache với key là identifier (Email hoặc SĐT)
        Cache::put('otp_' . $identifier, $otp, now()->addMinutes(5));

        // Giả lập gửi tin nhắn/Email
        // Trong thực tế, bạn sẽ gọi API của eSMS, Twilio, hoặc gửi Mail ở đây
        Log::info("Mã OTP cho {$identifier} là: {$otp}");

        return $otp;
    }

    /**
     * Xác minh mã OTP
     */
    public function verifyOtp($identifier, $otp)
    {
        $cachedOtp = Cache::get('otp_' . $identifier);

        if ($cachedOtp && (string)$cachedOtp === (string)$otp) {
            // Xóa mã sau khi dùng xong
            Cache::forget('otp_' . $identifier);
            return true;
        }

        return false;
    }
}
