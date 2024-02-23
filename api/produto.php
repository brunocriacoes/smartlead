<?php

function custom_api_produto()
{
    register_rest_route('smartlead-api/v1', '/produto', array(
        'methods'  => 'GET',
        'callback' => 'custom_api_get_produto',
        'args' => array(
            'produto_id' => array(
                'required' => true,
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric( $param );
                }
            ),
        ),
    ));
}
add_action('rest_api_init', 'custom_api_produto');

function custom_api_get_produto($request)
{
    $produto_id = $request['produto_id'];
    $api = new ApiRemote();
    $data = $api->getProductsById($produto_id);
    return rest_ensure_response($data);
}