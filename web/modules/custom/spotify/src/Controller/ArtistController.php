<?php
namespace Drupal\spotify\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Site\Settings;

/**
 * Provides route responses for the Artist module.
 */
class ArtistController extends ControllerBase {
  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * Returns a list page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function artistListPage() {
    $spotify = \Drupal::service('spotify.api');
    $ouath = $spotify->spotifyApiToken();

    $response = $this->httpClient->get('https://api.spotify.com/v1/search?q=justin&type=artist&limit=10', $ouath);

    $response = json_decode($response->getBody()->getContents(), TRUE);
    dump($response);
    $form['form'] = $this->formBuilder()->getForm('Drupal\spotify\Form\ArtistForm');
    // Create table header.
    $header = [
        'id' => $this->t('Id'),
        'artist_id' => $this->t('artist_id'),
        'name' => $this->t('name'),
        'opt' => $this->t('Operations')
    ];
    $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => get_artist_list(),
        '#responsive' => true,
        '#empty' => $this->t('No artists found'),
    ];
    $form['pager'] = [
        '#type' => 'pager'
    ];
    return $form;
  }

  /**
    * Limit of artist to display.
    *
    */
  public function limitArtistNumber() {
    return [
      '#markup' => 'list page',
    ];
  }

  /**
    * Returns add to database.
    *
    */
  public function artistAdd(Request $request) {
    //Get parameter value while submitting add form  
    $artist_id = \Drupal::request()->query->get('artist_id');

    if (!$artist_id) {
      return new JsonResponse('No Id');
    }

    $spotify = \Drupal::service('spotify.api');
    $ouath = $spotify->spotifyApiToken();

    $response = $this->httpClient->get('https://api.spotify.com/v1/artists/' . $artist_id, $ouath);

    if ($response->getStatusCode() !== 200) {
      return new JsonResponse($results);
    }

    $response = json_decode($response->getBody()->getContents(), TRUE);

    if (isset($response['id']) && isset($response['name'])) {
      $artistData = [
        'type' => "spotify_artist",
        'artist_id' => $response['id'],
        'name' => $response['name'],
        'external_url' => $response['external_urls']['spotify'],
        "popularity" => $response['popularity'],
        // "genres" => $response['genres'],
      ];

      $artist = \Drupal::service('spotify.artist');
      $artist->addToDatabase($artistData);
      unset($artist);
    }

    return $this->redirect('spotify.artist_list');
  }

  /**
    * Returns remove from database.
    *
    */
  public function removeArtistPage($nid) {
    if (!empty($nid)) {
      $artist = \Drupal::service('spotify.artist');
      $artist->removeFromDatabase($nid);
      unset($artist);
      $account_deleted = 'artist  deleted';
      \Drupal::messenger()->addMessage($account_deleted);
      return $this->redirect('spotify.artist_list');
    }
  }

  /**
    * Returns remove from database.
    *
    */
    public function artistPage($nid) {
      $artist = \Drupal::service('spotify.artist');
      $results = $artist->addToDatabase($artistData);
      return [
        '#theme' => 'artist_page',
        '#artist' => $results,
        '#title' => 'Artist profile'
      ];
    }
}
