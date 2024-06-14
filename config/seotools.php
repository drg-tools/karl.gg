<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults' => [
            'title' => 'Karl: Born Ready', // set false to total remove
            'titleBefore' => true, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description' => 'Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content.', // set false to total remove
            'separator' => ' - ',
            'keywords' => ['Deep Rock Galactic builds', 'drg builds'],
            'canonical' => false, // Set null for using Url::current(), set false to total remove
            'robots' => false, // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title' => 'Karl.gg: The best Deep Rock Galactic Builds', // set false to total remove
            'description' => 'Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content.', // set false to total remove
            'url' => null, // Set null for using Url::current(), set false to total remove
            'type' => false,
            'site_name' => false,
            'images' => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card' => 'summary',
            'site' => '@DrgTools',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title' => 'Karl.gg: The best Deep Rock Galactic Builds', // set false to total remove
            'description' => 'Best builds for the best game. Your go to partner for Deep Rock Galactic builds and content.', // set false to total remove
            'url' => false, // Set null for using Url::current(), set false to total remove
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];
