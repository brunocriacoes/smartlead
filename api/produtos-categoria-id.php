<?php

function custom_api_produtos_categoria_id()
{
    register_rest_route('smartlead-api/v1', '/produtos-categoria-id', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_produtos_categoria_id',
        'args' => array(),
    ));
}
add_action('rest_api_init', 'custom_api_produtos_categoria_id');

function custom_api_get_produtos_categoria_id($request)
{
    $categoria_id = $request['categoria_id'];
    $api = new ApiRemote();
    $data = $api->getProductsByCategory($categoria_id);
    return rest_ensure_response($data);
}
