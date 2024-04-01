<?php

function custom_api_orcamento()
{

  register_rest_route('smartlead-api/v1', '/orcamento', array(
    'methods'  => 'POST',
    'callback' => 'custom_api_get_orcamento',
  ));
}
add_action('rest_api_init', 'custom_api_orcamento');

function custom_api_get_orcamento()
{
  // error_reporting(E_ALL);
  // ini_set('display_errors', 1);
  $api = new ApiRemote();
  $id = $api->insertOrcamento($_POST['name'], $_POST['company'], $_POST['phone'], $_POST['email'], $_POST['description']);
  $inserts = [];
  foreach ($_POST['quant'] as $id_produto => $array_quant) {
    foreach ($array_quant as $quant) {
      $inserts[] = [
        'quantity' => $quant,
        'product_id' => $id_produto,
        'budget_id' => $id
      ];
    }
  }
  foreach ($inserts as $iten) {
    $api->insertItenOrcamento($iten['quantity'], $iten['product_id'], $iten['budget_id']);
  }
  return rest_ensure_response([
    'next' => true,
    'message' => 'Orçamento cadastrado com sucesso.',
    'payload' => $inserts
  ]);
}
