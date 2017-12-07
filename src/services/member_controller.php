<?php

class member_function{

  protected $pdo;

  public function __construct($pdo){
     $this->db = $pdo;
  }

  public function member_exist($request, $response, $args){

    $sql = $this->db->prepare("SELECT bool FROM member_exist(?)");

    $member_code = $request->getHeaderLine('member-code');

    if ($member_code <> NULL){
      $sql->bindParam(1,$member_code);
      $sql->execute();
    	$data = array('exist' => (($sql->fetchColumn()>0)?:false));
    	$response = $response -> withJson($data);
      return $response;
      }

    else{
	    $response = $response->withStatus(403);
	    return $response;
      }
  }


  public function member_register($request, $response, $args){

    $default = NULL;

    $application_param = array(
                               $request->getParsedBodyParam('member-name',$default),
                               $request->getParsedBodyParam('member-mobile',$default),
                               $request->getParsedBodyParam('member-email',$default),
                               $request->getParsedBodyParam('member-birth-date',$default),
                               $request->getParsedBodyParam('mobile-allow',$default),
                               $request->getParsedBodyParam('email-allow',$default)
                             );
    $sql = $this->db->prepare("EXEC member_register ?, ?, ?, ?, ?, ?");

    foreach ($application_param as $key => $value) {
      $sql->bindParam(($key+1),$value);
    }

    $result = $sql->execute($application_param);

    if ($result != 1) {

      $response = $response->withStatus(400);

    }
    else{
	$data = array( 'registration' => 'successful');
	$response = $response->withJson($data);
	return $response;
    }
  }



  public function phone_verify($request,$response, $args){
    $sql = $this->db->prepare("SELECT bool FROM mobile_verify");

    $default = NULL;

    $phone_number = $request->getParsedBodyParam('mobileno',$default);

    if ($phone_number <> NULL){
      $sql->bindParam(1, $phone_number);
      $sql->execute();
      $data = array('exist' => (($sql->fetchColumn()>0)?:false));
      $response = $response -> withJson($data);
      return $response;
      }

    else{
      $response = $response->withStatus(403);
      return $response;
      }
  }
}
