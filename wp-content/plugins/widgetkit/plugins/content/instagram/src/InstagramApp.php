<?php

namespace YOOtheme\Widgetkit\Content\instagram;

use YOOtheme\Framework\Application;
use YOOtheme\Framework\ApplicationAware;


class InstagramApp extends ApplicationAware
{
    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function fetch($params, $content)
    {
        // Cache settings
        $now = time();
        $expires = 5 * 60;
        $max_items = $params['limit'] ?: '12';

        $posts = array();

        // Cache invalid?
        if (array_key_exists('hash', $content) // never cached
            || $now - $content['hashed'] > $expires // cached values too old
            || md5(serialize($params)) != $content['hash']) // content settings have changed
        {

            try {

                $twitter_user = $this->url_get_contents("https://www.instagram.com/{$params['username']}/?__a=1")['user'];

                $variables = json_encode(array('id' => $twitter_user['id'], 'first' => $max_items));
                $data = $this->url_get_contents("https://www.instagram.com/graphql/query/?query_id=17888483320059182&variables={$variables}");

                foreach ($data['data']['user']['edge_owner_to_timeline_media']['edges'] as $item) {

                    $post = array(
                        'title' => "{$twitter_user['full_name']} ({$twitter_user['username']})",
                        'content' => $item['node']['edge_media_to_caption']['edges'][0]['node']['text'],
                        'date' => date('d-m-Y H:i:s O', $item['node']['taken_at_timestamp']),
                        'link' => "//instagram.com/p/{$item['node']['shortcode']}/?taken-by={$twitter_user['username']}",
                        'location' => null,
                        'media' => $item['node']['thumbnail_src'],
                        'options' => array(
                            'media' => array(
                                'width' => $item['node']['dimensions']['width'],
                                'height' => $item['node']['dimensions']['height']
                            )
                        )
                    );

                    // seperate the hashtags
                    $post['content'] = preg_replace('/#/', ' #', $post['content']);
                    // make hashtags clickable
                    $post['content'] = preg_replace('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))\#([\P{Z}]+)/', '<a href="https://instagram.com/explore/tags/$1">#$1</a>', $post['content']);

                    // make user names clickable
                    $post['content'] = preg_replace('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))\@([\P{Z}]+)/', '<a href="https://instagram.com/$1">@$1</a>', $post['content']);

                    // convert emoticons to UTF-8 code
                    $post['content'] = mb_convert_encoding($post['content'], 'UTF-8');


                    //                    if($item['type'] == 'video'){
                    //                        $post['media'] = $item['videos']['standard_resolution']['url'];
                    //                        $post['options']['media'] = array(
                    //                            'poster' => $item['images']['standard_resolution']['url'],
                    //                            'width'  => $item['videos']['standard_resolution']['width'],
                    //                            'height' => $item['videos']['standard_resolution']['height']
                    //                        );
                    //                    }

                    if ($params['title'] == 'username') {
                        $post['title'] = $twitter_user['username'];
                    } elseif ($params['title'] == 'fullname') {
                        $post['title'] = $twitter_user['full_name'];
                    }

                    $posts[] = $post;

                }

                // write cache
                $content['prepared'] = json_encode($posts);
                $content['hash'] = md5(serialize($params));
                $content['hashed'] = $now;
                unset($content['error']);

                $this->app['content']->save($content->toArray());

                return $posts;
            } catch (\Exception $e) {
                // Fallback to cache and log of API error
                $content['error'] = $e->getMessage();
                $this->app['content']->save($content->toArray());
            }
        }

        // read from cache
        $posts = json_decode($content['prepared'], true);

        return $posts ? $posts: array();
    }

    protected function url_get_contents ($url) {

        $content = '';

        if (function_exists('curl_exec') && ini_get('open_basedir') === ''){
            $conn = curl_init($url);
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($conn,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($conn, CURLOPT_AUTOREFERER, true);
            curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($conn, CURLOPT_VERBOSE, 0);

            $content = (curl_exec($conn));
            curl_close($conn);
        }

        if (!$content && function_exists('file_get_contents')){
            $content = @file_get_contents($url);
        }

        if (!$content && function_exists('fopen') && function_exists('stream_get_contents')){
            $handle  = @fopen ($url, "r");
            $content = @stream_get_contents($handle);
        }

        if (!is_array($response = json_decode($content, true)) || $response['status'] == 'fail') {
            throw new \Exception(isset($response['errors']) ? $response['errors'][0]['message'] : 'Instagram API error');
        }

        return $response;
    }


    /**
     * Hashes request parameters.
     *
     * @param $params
     * @return string
     */
    protected function hash($params)
    {
        $fields = array($params);

        return md5(serialize($fields));
    }
}