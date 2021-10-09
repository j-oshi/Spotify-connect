<?php
/**
 * @file
 * ArtistService class.
 */
namespace Drupal\spotify;

use Symfony\Component\HttpFoundation\JsonResponse;

class ArtistService
{
    protected $connection;

    public function __construct() {
      $this->connection = \Drupal::service('database');
    }

  /**
    * Add artist to database
    *
    */
    public function addToDatabase($artistData)
    {
      if (isset($artistData["artist_id"]) && isset($artistData["name"])) {
        $result = $this->$connection->insert('spotify_artist_info')
          ->fields([
            'artist_id' => $artistData["artist_id"],
            'name' => $artistData["name"],
            'popularity' => $artistData["popularity"],
            'external_url' => $artistData["external_url"],
          ])
          ->execute();
      }
    }

  /**
    * Remove artist from database
    *
    */
    public function removeFromDatabase($nid)
    {
      if (isset($nid)) {
        $query = $this->connection->delete('spotify_artist_info');
        $query->condition('nid', $nid);
        $query->execute();
      }
    }

  /**
    * Get all artist profile
    *
    * @return array
    *   An array with all artist info.
    */
    public function fetchAllArtistsinfo()
    {
      $connection = $this->connection;
      $schema = $connection->schema();
      if (!$schema->tableExists('spotify_artist_info')) {
        return;
      }
      $query = $connection->select('spotify_artist_info', 'nid')
      ->fields('nid')
      ->execute()
      ->fetchAll();
      return $query;
    }

  /**
    * Check if artist exist
    *
    * @return boolean
    *   A boolean value
    */
    public function artistInfoExist($artist_id)
    {
      if (isset($artist_id)) {
        $connection = $this->connection;
        $schema = $connection->schema();
        if (!$schema->tableExists('spotify_artist_info')) {
          return;
        }
        $artist_exist = $connection->select('spotify_artist_info', 'u')
        ->fields('u', ['artist_id'])
        ->condition('artist_id', $artist_id)
        ->execute()
        ->fetchField();

        return $artist_exist;
      }
    }

  /**
    * Check if count artist 
    *
    * @return int
    *   A int value
    */
    public function countArtist()
    {
      $query = $this->connection->select('spotify_artist_info', 'u')
        ->countQuery()->execute()->fetchField();

      return $query;
    }

  /**
    * Get intagram artist info
    *
    * @return array
    *   An array with artist info.
    */
    public function fetchArtistInfo($id)
    {
      $connection = $this->connection;
      $schema = $connection->schema();
      if (!$schema->tableExists('spotify_artist_info')) {
        return;
      }
      $artist = $connection->select('spotify_artist_info', 'u')
      ->condition('nid', $id)
      ->fields('u')
      ->execute()
      ->fetchAll();
  
      return $artist;
    }
}