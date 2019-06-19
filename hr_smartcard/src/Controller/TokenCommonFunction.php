<?php

namespace Drupal\hr_smartcard\Controller;

/**
 * Simple page controller for drupal.
 */
class TokenCommonFunction {

    /**
     * Create Smart Card
     * @param type $title
     */
    public function smartCardCurl($title, $fid_url, $url) {
 
        $body = array(
            'card' =>
            array(
                'message' => $title,
                'content_type' => 'video',
            ),
            'resource' =>
            array(
                'title' => $title,
                'url' => $url,
                'image_url' => $fid_url
            ),
        );
        $encoded_value = json_encode($body);
        $jwttokenencode = $this->authToken();
        $api_url = trim($GLOBALS['api_url']);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $encoded_value,
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json",
                "X-ACCESS-TOKEN: " . $jwttokenencode,
                "X-API-KEY: " . $GLOBALS['apikey']
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        else {
            echo $response;
        }
        return $response;
    }

    function authToken() {
        $jwttoken = $this->createJWTToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://schwabsandbox.edcast.com/api/developer/v5/auth",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 3546e08b-9f58-4850-9b7c-36e3bd48e393",
                "X-API-KEY: " . $GLOBALS['apikey'],
                "X-AUTH-TOKEN:" . $jwttoken
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        else {
//            echo $response;
        }
     
        $decoded_value = json_decode($response);
        $decodetoken = $decoded_value->jwt_token;
        if ($decodetoken) {
//            echo $decodetoken;
            return $decodetoken;
        }
        else {
            echo 'error in Token Generation. Please contact To administarator';
        }
    }

    function createJWTToken() {
        $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
        $headers_encoded = $this->base64url_encode(json_encode($headers));
//build the payload
        $payload = array(
            "email" => "Sandeep.lingampally@schwab.com"
        );
        $payload_encoded = $this->base64url_encode(json_encode($payload));

//build the signature
        $key = $GLOBALS['secretkey'];
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $key, true);
        $signature_encoded = $this->base64url_encode($signature);
        $token = "$headers_encoded.$payload_encoded.$signature_encoded";
        
        return $token;
    }

    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

}
