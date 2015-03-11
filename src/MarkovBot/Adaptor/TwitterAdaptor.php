<?php
/**
 * TwitterAdaptor
 */

namespace MarkovBot\Adaptor;

use MarkovBot\MarkovBot;
use TTools\App;

class TwitterAdaptor extends DefaultAdaptor
{
    /** @var  App $ttools */
    protected $ttools;

    /**
     * @param MarkovBot $app
     * @return void
     */
    public function register(MarkovBot $app)
    {
        $this->ttools = $app->get('twitter');
    }

    /**
     * @param $source
     * @return string
     */
    public function load($source)
    {
        $source = explode('://', $source);
        $user = $source[1];

        return $this->fetchTimelineSample($user);
    }

    /**
     * @param $user
     * @return string
     */
    public function fetchTimelineSample($user)
    {
        $twitter = $this->ttools;
        $iterations = 6;
        $lastId = 0;
        $sample = '';

        $params = [
            'screen_name' => $user,
            'count' => 200,
            'trim_user' => 'true',
            'exclude_replies' => 'true',
            'include_rts' => 'false'
        ];

        for ($i = 1; $i <= $iterations; $i++) {
            if ($lastId) {
                $params['max_id'] = $lastId;
            }

            $tweets = $twitter->get('/statuses/user_timeline.json', $params);

            foreach ($tweets as $tweet) {
                $sample .= ' ' . $this->sanitizeSample($tweet['text']);
                $lastId = $tweet['id_str'];
            }
        }

        return $sample;
    }
}
