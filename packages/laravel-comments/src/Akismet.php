<?php

namespace Hazzard\Comments;

use RuntimeException;
use GuzzleHttp\Client;

class Akismet implements Contracts\Akismet
{
    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;

    /**
     * @param  string|null $key
     * @param  \GuzzleHttp\Client $http
     * @return void
     */
    public function __construct(string $key = null, Client $http)
    {
        $this->key = $key;
        $this->http = $http;
    }

    /**
     * Check comment for spam.
     *
     * @link https://akismet.com/development/api/#comment-check.
     *
     * @param  array $params
     * @return boolean
     */
    public function commentCheck(array $params): bool
    {
        $this->assertKey();

        $response = $this->http->post("https://{$this->key}.rest.akismet.com/1.1/comment-check", [
            'form_params' => $params,
        ]);

        return $response->getBody()->getContents() === 'true';
    }

    /**
     * @return void
     */
    protected function assertKey()
    {
        if (empty($this->key)) {
            throw new RuntimeException('You must set the "akismet_key" in config/services.php.');
        }
    }
}
