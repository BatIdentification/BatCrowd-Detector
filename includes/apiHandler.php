<?php

  require_once(__DIR__ . '/../vendor/autoload.php');

  class batidAPI{

    private $apiUrl = "https://api.batidentification.com/api/";

    function __construct($db){

      $this->conn = $db;

      $stmt = $db->prepare("SELECT access_token, refresh_token, expires FROM oauth_tokens WHERE rowid = 1");

      $stmt->execute();

      $row = $stmt->fetch();

      if($row['access_token'] == ""){

        return false;

      }else{

        $this->access_token = new \League\OAuth2\Client\Token\AccessToken([
          'access_token' => $row['access_token'],
          'refresh_token' => $row['refresh_token'],
          'expires_in' => $row['expires'],
        ]);

        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
          'clientId' => '7DOKVNGQC2N8D1I7JRY0MT6VPL0YO4ZZ',
          'clientSecret' => '73PKBLqyPRsGVMaWivow96bfsbUYcN1iaUbXGQuzf5rnkRi6a6YQrA76cxW2HlOchRju99IfWHxC8D1A',
          'redirectUri' => 'http://batpi.loc/endpoints/auth.php',
          'response_type' => 'code',
          'urlAuthorize' => $this->apiUrl . 'authorize',
          'urlAccessToken' => $this->apiUrl . 'token',
          'urlResourceOwnerDetails' => $this->apiUrl . 'user',
        ]);

      }

    }

    function sendAuthRequest($url, $data, $options = []){

      try{

        $ch = curl_init();

        if($ch === false){

          throw new Exception('{"error": "connection_failed", "error_description": "Sorry, something went wrong file uploading the call"}');

        }

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Define our headers

        $options[] = "Authorization: Bearer " . $this->access_token->getToken();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $options);

        $server_output = curl_exec($ch);

        if (curl_error($ch)) {
            $server_output = curl_error($ch);
        }

        curl_close($ch);

      }catch (Exception $e){

        $server_output = $e->getMessage();

      }

      return $server_output;

    }

  }

?>
