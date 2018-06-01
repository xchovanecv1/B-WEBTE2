<?php

require_once 'config.php';
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';


	$client = new Google_Client();

	$client->setApplicationName('Login to webte example');
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_CLIENT_REDIRECT);
    $client->setAccessType('offline');   // Gets us our refreshtoken

    $client->setScopes(array('profile','email','openid'));

	$google_oauthV2 = new Google_Oauth2Service($client);



?>