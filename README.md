<img alt="Drupal Logo" src="https://www.drupal.org/files/Wordmark_blue_RGB.png" height="60px">

Drupal app is a simple site with a spotify custom module. Module will connect to spotify to retrieve artist infomation.

## Setup
Clone repository<br >
Cd into cloned folder<br >
Create a .env file in the root folder<br >
Add 
<pre>
SPOTIFY_CLIENT_ID=**************(spotify client id)
SPOTIFY_CLIENT_SECRET=************** (spotify client id)
</pre>
lando rebuild -y<br >
composer install<br >
lando db-import database.2021-10-09-1633810728.sql.gz<br >

## Use app
Log into site, and go to the module page and check if the Spotify Artist Finder module is enable.<br >
Enable if disabled.<br >
Go to the configuration page. Media => Spotify Artist Info.<br >
In the Spotify Artist Info page, use autocomplete to search for artist.<br >
Add artist to the list by clicking on the submit button.<br >
A maximum of 20 artist can be added to the list.<br >
Go to the front end to view the selected artist display.<br >
