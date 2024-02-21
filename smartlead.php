<?php
/*
    Plugin Name: Smartlead
    Description: Adiciona endpoints personalizados à API do WordPress.
*/

include __DIR__."/admin/index.php";

include __DIR__."/includes/BaseRemote.php";

include __DIR__."/includes/ApiRemote.php";



function custom_api_endpoints_init()
{
    register_rest_route('smartlead-api/v1', '/categorias', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_categorias',
    ));
}
add_action('rest_api_init', 'custom_api_endpoints_init');

function custom_api_get_categorias()
{
    $api = new ApiRemote();
    $data = array();
    return rest_ensure_response($data);
}

