<?php

namespace YOOtheme\Wordpress;

use YOOtheme\Http\Response;
use YOOtheme\HttpClientInterface;

class HttpClient implements HttpClientInterface
{
    /**
     * Execute a GET HTTP request.
     *
     * @param string $url
     * @param array $options
     *
     * @return mixed
     */
    public function get($url, $options = [])
    {
        return $this->makeRequest($url, $options);
    }

    /**
     * Execute a POST HTTP request.
     *
     * @param string $url
     * @param string $data
     * @param array $options
     *
     * @return mixed
     */
    public function post($url, $data = null, $options = [])
    {
        $options['method'] = 'POST';

        if ($data) {
            $options['body'] = $data;
        }

        return $this->makeRequest($url, $options);
    }

    /**
     * Execute a PUT HTTP request.
     *
     * @param string $url
     * @param string $data
     * @param array $options
     *
     * @return mixed
     */
    public function put($url, $data = null, $options = [])
    {
        $options['method'] = 'PUT';

        if ($data) {
            $options['body'] = $data;
        }

        return $this->makeRequest($url, $options);
    }

    /**
     * Execute a DELETE HTTP request.
     *
     * @param string $url
     * @param array $options
     *
     * @return Response
     */
    public function delete($url, $options = [])
    {
        $options['method'] = 'DELETE';

        return $this->makeRequest($url, $options);
    }

    protected function makeRequest($url, $options)
    {
        $response = wp_remote_request($url, $options);

        if (is_wp_error($response)) {
            throw new \RuntimeException($response->get_error_message());
        }

        return (new Response(wp_remote_retrieve_response_code($response), wp_remote_retrieve_headers($response)->getAll()))->write(wp_remote_retrieve_body($response));
    }
}
