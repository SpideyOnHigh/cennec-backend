<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Google\Auth\OAuth2;

class NotificationService
{
    public function pushNotification($notificationArray = array(), $messageObject)
    {
        try {
            $fcmTokenService = new FCMTokenService();
            $accessToken = $fcmTokenService->generateAccessToken();
            // Define the URL for the FCM HTTP v1 API endpoint
            $url = "https://fcm.googleapis.com/v1/projects/cennec---development/messages:send";

            $deviceToken = $notificationArray['deviceToken'];
            $senderUserName = User::where('id', $notificationArray['sender_user_id'])->first();
            $senderUserName = $senderUserName->username ?? '--';
            $notificationArray['sender_user_name'] = $notificationArray['notification_type'] == 'connection_request' ? env('APP_NAME') : $senderUserName;
            $data = array_merge($notificationArray, $messageObject);
            $data = array_map('strval', $data);
            $notification = [
                'title' => env('APP_NAME'),
                'body' => $notificationArray['message_content'],
            ];
            // Prepare the message payload
            $message = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => $notification,
                    'data' => $data,
                    'android' => [
                        'priority' => 'HIGH',
                        'ttl' => '3600s',
                    ],
                ],
            ];

            // Encode the message payload to JSON
            $jsonMessage = json_encode($message);

            // Initialize cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonMessage)
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonMessage);

            // Execute the request and capture the response
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Decode and output the response
            $responseBody = json_decode($response, true);
            // Handle response and errors
            if ($httpCode == 200) {
                // Success - handle successful response
                return true;
            } else {
                // Error - handle errors and unsuccessful responses
                // You may want to log or handle the error details
                return false;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
