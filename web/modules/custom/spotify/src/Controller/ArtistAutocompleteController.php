<?php

namespace Drupal\spotify\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Defines a route controller for Spotify Artist autocomplete form elements.
 *
 * @see \Drupal\Spotify\Plugin\Field\FieldWidgetArtistWidget
 */
class ArtistAutocompleteController extends ControllerBase {
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
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
    $results = [];
    $input = $request->query->get('q');

    if (!$input) {
      return new JsonResponse($results);
    }

    $spotify = \Drupal::service('spotify.api');
    $ouath = $spotify->spotifyApiToken();

    $input = Xss::filter($input);
    $response = $this->httpClient->get('https://api.spotify.com/v1/search?q=' . $input . '&type=artist&limit=10', $ouath);

    if ($response->getStatusCode() !== 200) {
      return new JsonResponse($results);
    }

    $response = json_decode($response->getBody()->getContents(), TRUE);

    foreach ($response['artists']['items'] as $artist) {
      if (!isset($artist['id'], $artist['name'])) {
        return;
      }

      $results[] = [
        'value' => $artist['id'],
        'label' => $artist['name'],
      ];
    }

    return new JsonResponse($results);
  }

}