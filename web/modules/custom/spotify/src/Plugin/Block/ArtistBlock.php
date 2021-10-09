<?php

namespace Drupal\spotify\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Artist list' Block.
 *
 * @Block(
 *   id = "artist_block",
 *   admin_label = @Translation("Artist block"),
 *   category = @Translation("Artist List Block"),
 * )
 */
class ArtistBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $artist = \Drupal::service('spotify.artist');
    $results = $artist->fetchAllArtistsinfo();
    return [
      '#theme' => 'artist_list',
      '#artists' => $results,
      '#title' => 'Artist list for display'
    ];
  }
}