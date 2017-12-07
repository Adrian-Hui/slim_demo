<?php

class common_function
{

  public function __construct($pdo){
     $this->db = $pdo;
  }

  public function shopid($request, $response, $args){

    $sql = $this->db->prepare("SELECT shopid FROM shop_list");

    $sql->execute();

  //wrong way to use json format, need recode
    $data = array('shopid' => []);
    foreach ($sql as $row) {
     array_push($data['shopid'],$row['shopid']);
      }
    $response = $response -> withJson($data);
    return $response;
  }
}

?>
