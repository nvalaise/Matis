<?php

namespace App\Extensions;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SpotifyProvider extends AbstractProvider implements ProviderInterface {

    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        'user-follow-read', // Follow
        'user-read-recently-played', 'user-top-read', 'user-read-playback-position', // Listening History
        'user-library-read', 'user-library-modify', // Library
        'playlist-read-collaborative', 'playlist-modify-private', 'playlist-modify-public', 'playlist-read-private', // Playlists
        'user-read-email', 'user-read-private' // Users
    ];

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'SPOTIFY';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://accounts.spotify.com/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://accounts.spotify.com/api/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {

        $response = $this->getHttpClient()->get(
            'https://api.spotify.com/v1/me',
            [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function formatScopes(array $scopes, $scopeSeparator = ', ')
    {
        return implode($scopeSeparator, $scopes);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
		return (new User())->setRaw($user)->map([
			'id' => $user['id'],
			'email' => $user['email']
		]);
    }
}