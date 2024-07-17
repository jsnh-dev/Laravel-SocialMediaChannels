<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XApiCall extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getProfile()
    {
        $consumerKey = env('X_API_KEY');
        $consumerSecret = env('X_API_KEY_SECRET');
        $accessToken = env('X_ACCESS_TOKEN');
        $accessTokenSecret = env('X_ACCESS_TOKEN_SECRET');

        $this->url = 'https://api.twitter.com/1.1/account/verify_credentials.json';

        $params = [
            'oauth_consumer_key' => $consumerKey,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $accessToken,
            'oauth_version' => '1.0',
        ];

        $baseString = $this->buildBaseString('GET', $params);
        $compositeKey = rawurlencode($consumerSecret) . '&' . rawurlencode($accessTokenSecret);
        $params['oauth_signature'] = base64_encode(hash_hmac('sha1', $baseString, $compositeKey, true));

        $response = $this->makeRequest($params, 'GET');

        return json_decode($response, true);
    }

    /**
     * @param $method
     * @param $params
     * @return string
     */
    protected function buildBaseString($method, $params): string
    {
        $r = [];
        ksort($params);
        foreach ($params as $key => $value) {
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($this->url) . '&' . rawurlencode(implode('&', $r));
    }

    /**
     * @param null $postData
     * @param string $method
     * @param array $headers
     * @return bool|string
     * @throws \Exception
     */
    protected function makeRequest($postData = null, $method = 'POST', $headers = []): bool|string
    {
        $ch = curl_init();

        $url = $this->url;

        if ($method === 'GET' && $postData) {
            $url .= '?' . http_build_query($postData);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postData) {
                if (is_array($postData)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                }
            }
        } elseif ($method === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    /**
     * @param $data
     */
    public function updateProfile($data)
    {
        $profile = XProfile::firstOrCreate();

        $profile->update([
            'x_id' => $data['id'],
            'name' => $data['name'] ?? null,
            'screen_name' => $data['screen_name'] ?? null,
            'location' => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'expanded_url' => $data['entities']['url']['urls'][0]['expanded_url'] ?? null,
            'display_url' => $data['entities']['url']['urls'][0]['display_url'] ?? null,
            'profile_image_url_https' => isset($data['profile_image_url_https']) && $data['profile_image_url_https']
                ? str_replace('_normal.', '_400x400.', $data['profile_image_url_https'])
                : null,
            'profile_banner_url' => $data['profile_banner_url'] ?? null,
            'followers_count' => $data['followers_count'],
            'friends_count' => $data['friends_count'],
            'created_at' => $data['created_at'],
        ]);
    }
}
