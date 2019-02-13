<?php

spl_autoload_register(function ($className) {
    require_once $className . '.php';
});

$vistoria = new Vistoria('xml');

$post = filter_input_array(INPUT_POST);
/* $post = [
  "inspection_type" => "entrada",
  "building_type" => "Casa",
  "area" => 75.0,
  "furnished" => "furnished",
  "modality" => "standard",
  "express" => false
  ]; */

//$vistoria->pricePreview($post);
$vistoria->building('CA2799');
//$vistoria->item('0F28B5D');
