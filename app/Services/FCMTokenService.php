<?php

namespace App\Services;

use Google\Auth\OAuth2;

class FCMTokenService
{

    public function __construct()
    {
        //
    }

    /**

     * Generate OAuth2 access token for Firebase Cloud Messaging

     *

     * @return string

     * @throws \Exception

     */

    public function generateAccessToken()

    {
        $serviceAccountPath = base_path('cennec---development-60b593626cc1.json');

        $jsonKey = json_decode(file_get_contents($serviceAccountPath), true);
        if (!$jsonKey) {

            throw new \Exception("Invalid service account file.");
        }

        $oauth = new OAuth2([
            'audience' => 'https://oauth2.googleapis.com/token',  // Corrected audience
            'issuer' => $jsonKey['client_email'] ?? '',
            'signingAlgorithm' => 'RS256',
            'signingKey' => $jsonKey['private_key'],
            'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ]);

        $accessToken = $oauth->fetchAuthToken()['access_token'];

        if (!$accessToken) {

            throw new \Exception("Failed to generate access token.");
        }

        return $accessToken;
    }
}
