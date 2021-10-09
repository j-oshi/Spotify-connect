<?php
/**
 * @file
 * SpotifyService class.
 */
namespace Drupal\spotify;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Site\Settings;
use SpotifyWebAPI;

class SpotifyApiService
{
  const SPOTIFY_AUTH_URL = 'https://accounts.spotify.com/authorize';
  const SPOTIFY_TOKEN_URL = 'https://accounts.spotify.com/token';

  protected $clientId;
  protected $clientSecret;
  protected $accessToken;

  public function __construct() {
    $this->clientId = Settings::get('spotify_client_id', '');
    $this->clientSecret = Settings::get('spotify_client_secret', '');
  }

  public function setToken($token) {
      $this->$accessToken = $token;
  }

  public function getToken() {
      return $this->accessToken;
  }

  public function resetToken() {
    $this->accessToken = '';
  }

  public function accessWIthCodeAuthorization() {
    $session = new SpotifyWebAPI\Session(
      $this->clientId,
      $this->clientSecret
    );
    
    $session->requestCredentialsToken();
    $token = $session->getAccessToken();    
    $this->setToken($token);
    return $token;
  }

  /**
    * Get fetch from api
    *
    * @return array
    *   An array with artist info.
    */
    public function spotifyApiToken()
    {
      // if ($this->getToken() != null) {
      //   $token = $this->getToken();
      // } else {
      //   $token = $this->accessWIthCodeAuthorization();  
      // }

      $token = 'BQAN13NjW-bJWVtmCogGm5_35ypiZa2PJFAqMFdv8kkGnX1Lyu7MB2qxQ0MdewMb9dKoYe2Lh1PXfybEGhc';
      $ouath = [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type'=> 'application/json',
          'Authorization' => 'Bearer ' . $token,
        ],
      ];
  
      return $ouath;
    }
}