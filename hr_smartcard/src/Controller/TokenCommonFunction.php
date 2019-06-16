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
    public function smartCardCurl($title) {
        $body = array(
            'card' =>
            array(
                'message' => $title,
                'content_type' => 'video',
            ),
        );
        $encoded_value = json_encode($body);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $GLOBALS['api_url'],
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
                "X-ACCESS-TOKEN: " . $GLOBALS['jwt_token'],
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
             return $response;
        }
    }
   

}
