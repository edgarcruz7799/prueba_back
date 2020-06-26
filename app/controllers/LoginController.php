<?php

 class LoginController extends ControllerBase {

 public function loginAction() {

        $dataRequest = $this->request->getJsonPost();
        $fields = array(
            "pass",
            "email"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {
          
                $client = Client::findFirst(array(
                    "conditions" => "email = ?1  and password = ?2",
                    "bind" => array(1 => $dataRequest->email, 2 => $dataRequest->pass)
                ));
            
                if (isset($client->id)) {
                    
                    $data = $this->dataClientCrypto($client);

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "message" => "Cliente logueado + exitosamente",
                        "data" => $data,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {

                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                        "return" => false,
                        "message" => "no inicio",
                        "status" => ControllerBase::FAILED
                    ));
                }
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
            
        }
    }

     private function dataClientCrypto($client)
    {

        return $data = array(
                                'id' => $client->id,
                                'name' => $client->name,
                                'email' => $client->email,
                                'token' => $client->token,
                                'phone' => $client->phone,
                                'document_client' => $client->document_client
                            );


    }

}