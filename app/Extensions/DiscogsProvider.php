<?php

namespace App\Extensions;

use Laravel\Socialite\One\AbstractProvider;
use Laravel\Socialite\One\User;
use Illuminate\Support\Facades\Http;


class DiscogsProvider extends AbstractProvider {

    /**
     * {@inheritdoc}
     */
    // protected $scopes = ['basic_access', 'email', 'manage_library', 'manage_community', 'listening_history'];

    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'DISCOGS';


    /**
     * {@inheritdoc}
     */
    public function user()
    {
        if (!$this->hasNecessaryVerifier()) {
            throw new \InvalidArgumentException('Invalid request. Missing OAuth verifier.');
        }

        $token = $this->getToken();
        $user = $this->server->getUserDetails($token);

        $url = $user->extra['resource_url'];
        $headers = $this->server->getHeaders($token, 'POST', $url);

        try {
            $response = $this->server->createHttpClient()->get($url, [
                'headers' => $headers,
            ]);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();
            $statusCode = $response->getStatusCode();

            throw new \Exception(
                "Received error [$body] with status code [$statusCode] when retrieving user's profile."
            );
        }        

        $profile = json_decode((string) $response->getBody(), true);

        return (new User())->setRaw($user->extra)->map([
            'id'       => $user->uid,
            'name'     => $user->name,
            'email'    => $profile['email'],
        ])->setToken($token->getIdentifier(), $token->getSecret());
    }

    protected function headers() {
        $token = $this->getToken();
        return [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
        ];
    }
}