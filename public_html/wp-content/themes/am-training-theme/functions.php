<?php
function am_training_force_preview_urls() {
    $preview_url = 'https://musical-space-spoon-q7w9jgq4v97q3xrpg-8080.app.github.dev';

    update_option('siteurl', $preview_url);
    update_option('home', $preview_url);
}

add_action('init', 'am_training_force_preview_urls');

add_filter('site_url', function ($url) {
    return str_replace('localhost', 'musical-space-spoon-q7w9jgq4v97q3xrpg-8080.app.github.dev', $url);
}, 10, 3);

add_filter('home_url', function ($url) {
    return str_replace('localhost', 'musical-space-spoon-q7w9jgq4v97q3xrpg-8080.app.github.dev', $url);
}, 10, 3);
