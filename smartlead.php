<?php
/*
    Plugin Name: Smart Lead
    Description: Adiciona endpoints personalizados à API do WordPress.
*/

function custom_api_endpoints_init()
{
    register_rest_route('custom-api/v1', '/categorias', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_categorias',
    ));
}
add_action('rest_api_init', 'custom_api_endpoints_init');

function custom_api_get_categorias()
{
    $data = array();
    return rest_ensure_response($data);
}
