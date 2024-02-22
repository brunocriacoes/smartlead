<?php

function custom_api_produtos()
{
    register_rest_route('smartlead-api/v1', '/produtos', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_produtos',
    ));
}
add_action('rest_api_init', 'custom_api_produtos');

function custom_api_get_produtos()
{
    $api = new ApiRemote();
    $data = $api->getAllProducts();
    return rest_ensure_response($data);
}