<?php

class coupon_function {

  protected $pdo ;

  public function __construct($pdo) {
    $this->db = $pdo ;
  }

/*fetch coupon list for member*/

  public function coupon_list($request, $response, $args){

  $sql = $this->db->prepare("SELECT * FROM coupon_list(?)");

  $user_id = $request->getHeaderLine('uid');

  if ($user_id !== '') {

    $sql->bindParam(1, $user_id);

    $sql->execute();

    $data = array('coupon_code' => [],'expiry' => []);

//wrong way to use json format, need recode
    foreach ($sql as $row) {
     array_push($data['coupon_code'],$row['coupon_code']);
     array_push($data['expiry'],$row['expiry']);
      }

//Return JSON data
    $response = $response -> withJson($data);
    }else {
    $response = $response -> withStatus(400,'empty member id');

  }
    return $response;
  }

}
