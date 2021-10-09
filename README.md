<img alt="Drupal Logo" src="https://www.drupal.org/files/Wordmark_blue_RGB.png" height="60px">

Drupal app is a simple site with a spotify custom module. Module will connect to spotify to retrieve artist infomation.

## Setup
Clone repository<br >
Cd into repository<br >
lando start<br >
composer install<br >
lando db-import database.2021-10-09-1633810728.sql.gz<br >

## Use app
Log into site and go to configuration. Media => Spotify Artist Info.<br >
Use autocomplete to search for artist. Add artist to list by clicking on submit button.<br >
Maximum of 20 artist can be added to the list.<br >
Go to the front end to view the selected artist display.<br >




