<?php

namespace App\Services\OTP;

use App\Contracts\SendSms;

class SmartSoftwareBd implements SendSms {
    
   public function send($to, $from, $text)
{
    $url = "https://sms.smartsoftwarebd.com/api/v3/sms/send"; // ✅ ঠিক URL

    // ✅ Remove "+" if present
    if (substr($to, 0, 1) === '+') {
        $to = substr($to, 1);
    }
// Make sure the number starts with 880
if (substr($to, 0, 2) === '01') {
    $to = '880' . substr($to, 1);
}
    // ✅ Data array
    $data = [
        "recipient" => $to,
        "sender_id" => env('SMART_SOFTWARE_BD_SENDER_ID'),  // 🔄 key name ঠিক করলাম
        "type" => "plain",
        "message" => $text,
    ];

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . env('SMART_SOFTWARE_BD_API_KEY'), // ✅ Authorization header
    ];

    // ✅ cURL setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 🔒 Suggest turning this ON in production
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return response()->json(['success' => false, 'error' => $error], 500);
    }
dd($response); // ✅ Debugging line, remove in production
    return json_decode($response, true); // ✅ Structured return
}

}
