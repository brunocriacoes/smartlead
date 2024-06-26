<?php

use function PHPSTORM_META\map;

function custom_api_produto()
{
    register_rest_route('smartlead-api/v1', '/produto', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_produto',
        'args' => array(),
    ));
}
add_action('rest_api_init', 'custom_api_produto');

function custom_api_get_produto($request)
{
    $produto_id = $request['produto_id'];
    $api = new ApiRemote();
    $data = $api->getProductsById($produto_id);
    $data['categorias'] = $api->getCategoryByProductId($produto_id);
    $data['relacionado'] = $api->getProductsRelated(array_map(function ($c) {
        return $c['id'];
    }, $data['categorias']));
    $allProdutos = $api->getAllProducts();
    $chunk = array_slice($allProdutos, 0, 4);
    $data['relacionado'] = array_merge($data['relacionado'], $chunk);
    return rest_ensure_response($data);
}
