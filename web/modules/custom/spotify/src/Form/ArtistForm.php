<?php

namespace Drupal\spotify\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\spotify\ArtistProfile;

/**
 * Implements an artist form.
 */
class ArtistForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'artist_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['artist_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add new artist by spotify id'),
      '#placeholder' => $this->t('Search by name to get id'),
      '#autocomplete_route_name' => 'spotify.artist_autocomplete',
      '#attributes' => array(
        'class' => array('input-lg', 'form-control'),
      ),
      '#suffix' => '<div class="form-group"></div>',
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add to artist list'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('artist_name')) < 3) {
      $form_state->setErrorByName('artist_name', $this->t('The artist name is short. Please enter a valid name.'));
    }

    $artist_id = $form_state->getValue('artist_name');
    if (!empty($artist_id)) {
      $trim_id = trim($artist_id);
      
      $artist = \Drupal::service('spotify.artist');
      $doesArtistExist = $artist->artistInfoExist($trim_id);
        
      if ($doesArtistExist) {
        $form_state->setErrorByName('artist', $this->t('Artist exist'));
      }

      $limitCount = $artist->countArtist();
      if ($limitCount > 20) {
        $form_state->setErrorByName('artist', $this->t('Maximum twenty artist are allowed.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $artist_id = $form_state->getValue('artist_name');
    if (!empty($artist_id)) {
      $trim_id = trim($artist_id);
      $url = \Drupal\Core\Url::fromRoute('spotify.add_artist_list')->setRouteParameters(['artist_id'=>$trim_id]);
      $form_state->setRedirectUrl($url);       
    };
    // $this->messenger()->addStatus($this->t('Artist name is @artist', ['@artist' => $form_state->getValue('artist_name')]));
  }
}
