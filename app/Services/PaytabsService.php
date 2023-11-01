<?php
namespace App\Services;

class PaytabsService
{
    const PROFILE_ID = 131308,
          SERVER_KEY = 'SKJ9MTWLDL-JHDTDN2RNZ-GR9TTRZRKW',
          BASE_URL = 'https://secure-egypt.paytabs.com/';

    public function send_api_request($request_url, $data, $request_method = null)
    {
        $data['profile_id'] = self::PROFILE_ID;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => $this::BASE_URL . $request_url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 0,
            CURLOPT_CUSTOMREQUEST   => isset($request_method) ? $request_method : 'POST',
            CURLOPT_POSTFIELDS      => json_encode($data, true),
            CURLOPT_HTTPHEADER      => array(
                'authorization:' . self::SERVER_KEY,
                'Content-Type:application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        return $response;
    }

    // function getBaseUrl()
    // {
    //     $currentPath = $_SERVER['PHP_SELF'];
    //     $pathInfo = pathinfo($currentPath);
    //     $hostName = $_SERVER['HTTP_HOST'];
    //     $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'https://';
    //     $current_directory = substr(strrchr($pathInfo['dirname'],'/'), 1);
    //     $parent_directory = substr($pathInfo['dirname'], 0, - strlen($current_directory));
    //     return   $protocol.$hostName.'/'.$current_directory.'/';
    // }

    public function is_valid_redirect($post_values)
    {
        if (empty($post_values) || !array_key_exists('signature', $post_values)) {
            // file_put_contents('paytabs-return.txt' ,  "ya 3m error" . '   ' . ((empty($post_values)) ? 'empty':'non-empty') . '     ' . ((array_key_exists('signature', $post_values)) ? 'exists':'non-exists') );
            return false;
        }

        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        $requestSignature = $post_values["signature"];
        unset($post_values["signature"]);
        $fields = array_filter($post_values);

        // Sort form fields
        ksort($fields);

        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($fields);
        return $this->is_genuine($query, $requestSignature);
    }


    public function is_genuine($data, $requestSignature)
    {
        $genuineSignature = hash_hmac('sha256', $data, self::SERVER_KEY);

        if (hash_equals($genuineSignature, $requestSignature) === TRUE) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }

    public function get_signature($data)
    {
        $signtaure = hash_hmac('sha256', $data, self::SERVER_KEY);
        return $signtaure;
    }

}
