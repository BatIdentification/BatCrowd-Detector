<?php

  require_once("../vendor/autoload.php");
  require_once("../includes/dbconnect.php");

  session_start();

  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId' => '7DOKVNGQC2N8D1I7JRY0MT6VPL0YO4ZZ',
    'clientSecret' => '73PKBLqyPRsGVMaWivow96bfsbUYcN1iaUbXGQuzf5rnkRi6a6YQrA76cxW2HlOchRju99IfWHxC8D1A',
    'redirectUri' => 'http://batcrowd.local/endpoints/auth.php',
    'response_type' => 'code',
    'urlAuthorize' => 'https://api.batidentification.com/api/authorize',
    'urlAccessToken' => 'https://api.batidentification.com/api/token',
    'urlResourceOwnerDetails' => 'https://api.batidentification.com/api/user',
  ]);

  if(!isset($_GET['code'])){

    $authorizationURL = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header("Location: " . $authorizationURL);
    exit;

  // Check given state against previously stored one to mitigate CSRF attack
  }elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])){

    if (isset($_SESSION['oauth2state'])) {
       unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');

  }else{

    try{

      $accessToken = $provider->getAccessToken('authorization_code', [
             'code' => $_GET['code']
      ]);

      $stmt = $db->prepare("INSERT INTO oauth_tokens (access_token, refresh_token, expires) VALUES (:token, :refresh, :expires)");

      $stmt->execute([":token" => $accessToken->getToken(), ":refresh" => $accessToken->getRefreshToken(), ":expires" => $accessToken->getExpires()]);
      $stmt = null;

      header("Location: ../hello.php");

    }catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e){
       echo "Error";
       exit($e->getMessage());
    }
  }

?>
