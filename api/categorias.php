<?php

function custom_api_categorias()
{
    register_rest_route('smartlead-api/v1', '/categorias', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_categorias',
    ));
}
add_action('rest_api_init', 'custom_api_categorias');

function custom_api_get_categorias()
{
    $api = new ApiRemote();
    $data = $api->getAllCategoriesAndSubcategories();
    return rest_ensure_response($data);
}