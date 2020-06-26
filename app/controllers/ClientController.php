<?php

class ClientController extends ControllerBase {


    public function registerAction()
    {

        $dataRequest = $this->request->getJsonPost();


        $fields = array(
            "tipoDocumento",
            "password",
            "correo",
            "fecha_nacimiento",
            "telefonoMovil",
            "apellidos",
            "nombres",
            "numeroIdentificacion"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                // $access_t = $dataRequest->access_type;

                $user = Users::findFirst(array(
                    "conditions" => "numeroidentificacion = ?1",
                    "bind" => array(1 => $dataRequest->numeroIdentificacion)
                ));

                if (!isset($user->id_user)) {

                    $user = new Users();

                    $user->tipodocumento = (string) $dataRequest->tipoDocumento;
                    $user->password = (string) $dataRequest->password;
                    $user->correo = (string) $dataRequest->correo;
                    $user->fecha_nacimiento = (string) $dataRequest->fecha_nacimiento;
                    $user->telefonomovil = (string) $dataRequest->telefonoMovil;
                    $user->apellidos = (string) $dataRequest->apellidos;
                    $user->nombres = (string) $dataRequest->nombres;
                    $user->numeroidentificacion = (string) $dataRequest->numeroIdentificacion;
                    $user->rol = ControllerBase::ROL_CLIENTE;

                    if ($user->save()) {
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => "ok",
                            "status" => ControllerBase::SUCCESS
                        ));
                        die;
                    } else {

                        $errors = array();
                        foreach ($user->getMessages() as $msj) {
                            $errors[] = (string) $msj;
                        }
                        $this->setJsonResponse(ControllerBase::SUCCESS, " Failed register client", array(
                            "return" => false,
                            "message" => $errors,
                            "status" => ControllerBase::FAILED
                        ));
                    }
                } else {

                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                        "return" => false,
                        "message" => "El cliente ya se encuentra registrado",
                        "status" => ControllerBase::FAILED
                    ));
                }
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }


    public function loginAction()
    {

        $dataRequest = $this->request->getJsonPost();


        $fields = array(
            "password",
            "correo"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {


            try {

                $user = User::findFirst(array(
                    "conditions" => "correo = ?1  and password = ?2",
                    "bind" => array(1 => $dataRequest->correo, 2 => $dataRequest->password)
                ));

                $access_t = $dataRequest->access_type;

                if (isset($user->id_user) && $access_t == ControllerBase::ROL_CLIENTE) {
                    $data = [
                        "email" => "$user->correo",
                        "name_cli" => "$user->nombres",
                        "id" => "$user->id_user",
                        "access_type" => ControllerBase::ROL_CLIENTE
                    ];

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "status" => ControllerBase::SUCCESS
                    ));
                }
                if (isset($user->id) && $access_t == ControllerBase::ROL_SOPORTE) {
                    $data = [
                        "email" => "$user->correo",
                        "name_cli" => "$user->nombres",
                        "id" => "$user->id_user",
                        "access_type" => ControllerBase::ROL_SOPORTE
                    ];

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "status" => ControllerBase::SUCCESS
                    ));
                } else {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => ControllerBase::CLIENT_AUTHWEB_BAD,
                        "status" => ControllerBase::SUCCESS
                    ));
                }
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }

    public function createAction()
    {

    $dataRequest = $this->request->getJsonPost();
    /**
     * questions = [ question: string ]
     */
    $fields = array(
        "nombre_caso",
        "id_user",
        "questions"
    );

    $optional = array();

    if ($this->_checkFields($dataRequest, $fields, $optional)) {

        try {
                /**
                 * Se crea el caso
                 */
                $pqrs = new Pqrs(); 

                $pqrs->id_user = (string) $dataRequest->id_user;
                $pqrs->nombre_caso = (string) $dataRequest->nombre_caso;
                $pqrs->estado = '1';


                if (!$pqrs->save()) {
                    $this->exceptionErrorSaveDb($pqrs);
                }

                /**
                 * Se crea las preguntas relacionadas con el caso
                 */

                // foreach ($dataRequest->questions as $question) {
                    
                // }
                $pregunta = new Pregunta();
                $pregunta->id_caso = intval($pqrs->id_caso);
                $pregunta->respuesta = null;
                $pregunta->pregunta = $dataRequest->questions;
                if ($pregunta->save()) {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $pregunta,
                        "status" => ControllerBase::SUCCESS
                    ));
                }
           
        } catch (Exception $e) {
            $this->logError($e, $dataRequest);
        }
    }
}


    public function postAction() {

        $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass",
            "email",
            "name_cli",
        );

        $optional = array(
            "document",
            "access_type",
            "id_cellar",
            "name",
            "city",
            "address",
            "deposit_capacity",
            "storage_cost",
            "initial_date",
            "final_date",
            "supervisor",
            "space_require",
            "deptotal",
            "id_truck",
            "name_city",
            "name_city2",
            "capacity",
            "cost",
            "initial_date_truck",
            "hour",
            "conductor",
            "type_load",
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

                try {
                    
                    $access_t = $dataRequest->access_type;

                    $client = Client::findFirst(array(
                        "conditions" => "email = ?1",
                        "bind" => array(1 => $dataRequest->email)
                    ));

                    if (!isset($client->id) && $access_t == 0){
                       
                        $client = new Client();
                        $client->name = $dataRequest->name_cli;
                        if ($dataRequest->document){
                            $client->document_client = isset($dataRequest->document) ? $dataRequest->document : null ;
                        }                    
                        $client->password = $dataRequest->pass ;
                        $client->email = $dataRequest->email;
                        $client->created_at = $dateTime->format('Y-m-d H:i:s');
                        $client->token = hash('sha256',str_shuffle("p4q6r8s10t2u3v5a7b9cdefghijklmnowxyz" . uniqid()));
                        
                        if($client->save()){

                            $data = [
                                    "email" => "$client->email",
                                    "name_cli" => "$client->name",
                                    "id" => "$client->id",
                                    "access_type" => 1,
                            ];


                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "data" => $data,
                                "status" => ControllerBase::SUCCESS
                            ));
                            die;

                        }else{

                            $errors = array();
                            foreach ($client->getMessages() as $msj) {
                                $errors[] = (string)$msj;
                            }
                            $this->setJsonResponse(ControllerBase::SUCCESS, " Failed register client", array(
                                "return" => false,
                                "message" => $errors,
                                "status" => ControllerBase::FAILED
                            ));

                        }
                        
                    } if (!isset($client->id) && $access_t == 1){
                       
                        $client = new Client();
                        $client->name = $dataRequest->name_cli;
                        if ($dataRequest->document){
                            $client->document_client = isset($dataRequest->document) ? $dataRequest->document : null ;
                        } 
                        $client->password = $dataRequest->pass ;
                        $client->email = $dataRequest->email;
                        $client->created_at = $dateTime->format('Y-m-d H:i:s');
                        $client->token = hash('sha256',str_shuffle("p4q6r8s10t2u3v5a7b9cdefghijklmnowxyz" . uniqid()));

                        
                        if($client->save()){

                            $data = [
                                    "email" => "$client->email",
                                    "name_cli" => "$client->name",
                                    "id" => "$client->id",
                                    "access_type" => 1,
                            ];


                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "data" => $data,
                                "status" => ControllerBase::SUCCESS
                            ));
                            die;

                        }else{

                            $errors = array();
                            foreach ($client->getMessages() as $msj) {
                                $errors[] = (string)$msj;
                            }
                            $this->setJsonResponse(ControllerBase::SUCCESS, " Failed register client", array(
                                "return" => false,
                                "message" => $errors,
                                "status" => ControllerBase::FAILED
                            ));

                        }
                        
                    } if (!isset($client->id) && $access_t == 2) {

                        $client = new Client();
                        $client->name = $dataRequest->name_cli;
                        if ($dataRequest->document){
                            $client->document_client = isset($dataRequest->document) ? $dataRequest->document : null ;
                        }
                        $client->password = $dataRequest->pass ;
                        $client->email = $dataRequest->email;
                        $client->created_at = $dateTime->format('Y-m-d H:i:s');
                        $client->token = hash('sha256',str_shuffle("p4q6r8s10t2u3v5a7b9cdefghijklmnowxyz" . uniqid()));
                        
                        if($client->save()){

                            $data = [

                                    "email" => "$client->email",
                                    "name_cli" => "$client->name",
                                    "id" => "$client->id",
                                    "document" => "$client->document_client",
                                    "access_type" => 2,
                                    "id_cellar" => $dataRequest->id_cellar,
                                    "name" => $dataRequest->name,
                                    "city" => $dataRequest->city,
                                    "address" => $dataRequest->address,
                                    "deposit_capacity" => $dataRequest->deposit_capacity,
                                    "storage_cost" => $dataRequest->storage_cost,
                                    "initial_date" => $dataRequest->initial_date,
                                    "final_date" => $dataRequest->final_date,
                                    "supervisor" => $dataRequest->supervisor,
                                    "space_require" => $dataRequest->space_require,
                                    "deptotal" => $dataRequest->deptotal,
                            ];

                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "data" => $data,
                                "status" => ControllerBase::SUCCESS
                            ));
                            die;

                        }else{

                            $errors = array();
                            foreach ($client->getMessages() as $msj) {
                                $errors[] = (string)$msj;
                            }
                            $this->setJsonResponse(ControllerBase::SUCCESS, " Failed register client", array(
                                "return" => false,
                                "message" => $errors,
                                "status" => ControllerBase::FAILED
                            ));

                        }

                    } 
                    if (!isset($client->id) && $access_t == 3) {

                        $client = new Client();
                        $client->name = $dataRequest->name_cli;

                        if ($dataRequest->document){
                            $client->document_client = isset($dataRequest->document) ? $dataRequest->document : null ;
                        }
    
                        $client->password = $dataRequest->pass;
                        $client->email = $dataRequest->email;
                        $client->created_at = $dateTime->format('Y-m-d H:i:s');
                        $client->token = hash('sha256',str_shuffle("p4q6r8s10t2u3v5a7b9cdefghijklmnowxyz" . uniqid()));
                        if($client->save()){

                            $data = [

                                    "email" => "$client->email",
                                    "name_cli" => "$client->name",
                                    "id" => "$client->id",
                                    "document" => "$client->document_client",
                                    "access_type" => 3,
                                    "id_truck" => $dataRequest->id_truck,
                                    "name" => $dataRequest->name,
                                    "name_city" => $dataRequest->name_city,
                                    "name_city2" => $dataRequest->name_city2,
                                    "capacity" => $dataRequest->capacity,
                                    "cost" => $dataRequest->cost,
                                    "initial_date_truck" => $dataRequest->initial_date_truck,
                                    "hour" => $dataRequest->hour,
                                    "conductor" => $dataRequest->conductor,
                                    "type_load" => $dataRequest->type_load,
                            ];


                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "data" => $data,
                                "status" => ControllerBase::SUCCESS
                            ));

                        }else{

                            $errors = array();
                            foreach ($client->getMessages() as $msj) {
                                $errors[] = (string)$msj;
                            }
                            $this->setJsonResponse(ControllerBase::SUCCESS, " Failed register client", array(
                                "return" => false,
                                "message" => $errors,
                                "status" => ControllerBase::FAILED
                            ));

                        }

                    } 
                    else {

                        $register = $this->validateRegister($client);

                        if ( $register == 2 ) {

                             $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "message" => "Cliente con cuenta en Facebook",
                                "status" => ControllerBase::SUCCESS
                            ));

                        }elseif( $register == 1 ){
                            
                            $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                "return" => false,
                                "message" => "Cliente con cuenta en Google",
                                "status" => ControllerBase::FAILED
                            ));

                        }else{

                             $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                "return" => false,
                                "message" => "El cliente ya se encuentra registrado",
                                "status" => ControllerBase::FAILED
                            ));
                        }

                    }
                

                }catch (Exception $e) {
                    $this->logError($e, $dataRequest);
                }   
            
        }
    }

     /*Registro facebook cliente almagrario */

    public function postFacebookAction()
    {

        $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass",
            "email",
            "name",
            "commerce_id",
            "transaction",
            "signature",
            "ammount"
        );
        $optional = array(

        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {
            if ($this->_checkFieldsGateway($dataRequest)){
                try {

                    $result = $this->verifyToken($dataRequest->pass, "fb");

                    if (isset($result['id'])) { // si el token es valido en facebook

                        $id_red_social = 2;
                        $id = $result['id'];

                        $client_gateway = ClientGateway::findFirstByEmail($dataRequest->email);

                        if (!isset($client_gateway->id)) {
                             $this->registerRedSocial($dateTime, $result, $id_red_social, $dataRequest, $id);
                        }else{

                            $register = $this->validateRegister($client_gateway);

                            if ( $register == 2 ) {

                                $redSocial = ClienteRedSocial::findFirst($client_gateway->client_red_social_id);
                                if ($redSocial->social_id == $result['id']) {

                                    $commerce = $this->dataCommerce($dataRequest->commerce_id);
                                    $data = [
                                        "email" => "$client_gateway->email",
                                        "name" => "$client_gateway->name",
                                        "client_id" => "$client_gateway->id",
                                        "commerce" => $commerce
                                    ];

                                     $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));

                                } else {
                                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => false,
                                        "message" => "Credenciales inválidas",
                                        "status" => ControllerBase::FAILED
                                    ));
                                }     

                            }elseif( $register == 1 ){
                                
                                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "Cliente con cuenta en Google",
                                    "status" => ControllerBase::FAILED
                                ));

                            }else{

                                 $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "El cliente ya se encuentra registrado",
                                    "status" => ControllerBase::FAILED
                                ));
                            }

                        }
                        
                    } else {
                        $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => "Credenciales inválidas",
                            "status" => ControllerBase::FAILED
                        ));
                    }

                } catch (Exception $e) {

                    $this->logError($e, $dataRequest);
                }
            }

        }

    }


    public function postGoogleAction()
    {

       $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass",
            "commerce_id",
            "transaction",
            "signature",
            "ammount"
        );
        $optional = array(

        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {
            if ($this->_checkFieldsGateway($dataRequest)){
                try {

                    $result = $this->verifyToken($dataRequest->pass, "g+");

                    if (isset($result['kid'])) { // si el token es valido en google

                        $id_red_social = 1;
                        $id = $result['kid'];

                        $client_gateway = ClientGateway::findFirstByEmail($result['email']);

                        if (!isset($client_gateway->id)) {
                             $this->registerRedSocial($dateTime, $result, $id_red_social, $dataRequest, $id);
                        }else{

                            $register = $this->validateRegister($client_gateway);

                            if ( $register == 1 ) {

                                $redSocial = ClienteRedSocial::findFirst($client_gateway->client_red_social_id);
                                if ($redSocial->social_id == $result['kid']) {

                                    $commerce = $this->dataCommerce($dataRequest->commerce_id);
                                    $data = [
                                        "email" => "$client_gateway->email",
                                        "name" => "$client_gateway->name",
                                        "client_id" => "$client_gateway->id",
                                        "commerce" => $commerce
                                    ];

                                     $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));

                                } else {
                                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => false,
                                        "message" => "Credenciales inválidas",
                                        "status" => ControllerBase::FAILED
                                    ));
                                }     

                            }elseif( $register == 2 ){
                                
                                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "Cliente con cuenta en Facebook",
                                    "status" => ControllerBase::FAILED
                                ));

                            }else{

                                 $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "El cliente ya se encuentra registrado",
                                    "status" => ControllerBase::FAILED
                                ));
                            }

                        }
                        
                    } else {
                        $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => "Credenciales inválidas",
                            "status" => ControllerBase::FAILED
                        ));
                    }

                } catch (Exception $e) {

                    $this->logError($e, $dataRequest);
                }
            }

        }

    }


    public function validateSignatureAction()
    {
        $dataRequest = $this->request->getJsonPost();

        $fields =  array(
            "commerce_id",
            "transaction",
            "signature",
            "ammount"
        );

        if($this->_checkFields($dataRequest, $fields)){

            if($this->_checkFieldsGateway($dataRequest)){

                try {
                    $comercio =  Comercio::findFirst(array(
                        "conditions" => "id_comercio = ?1 and estado = ?2",
                        "bind" => array(1 => $dataRequest->commerce_id,
                                        2 => 1)
                    ));

                    $commerce = array(
                        'commerce_id' => $comercio->id_comercio, 
                        'commerce_name' => $comercio->nombre_comercio,
                        'state' => $comercio->estado,
                        'signature_key' => $comercio->signature_key,
                        'image' => $comercio->image
                    );

                    $data = ["commerce" => $commerce];

                    if (isset($comercio->id_comercio)) {

                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "status" => ControllerBase::SUCCESS
                        ));
                    }                
                } catch (Exception $e) {
                    $this->logError($e, $dataRequest);
                }
            }
        }
    }


    public function loginClientAction() {

        $dataRequest = $this->request->getJsonPost();


        $fields = array(
            "password",
            "correo"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {


            try {

                $user = Users::findFirst(array(
                    "conditions" => "correo = ?1  and password = ?2",
                    "bind" => array(1 => $dataRequest->correo, 2 => $dataRequest->password)
                ));
                if (isset($user->id_user)) {
                    $data = [
                        "email" => "$user->correo",
                        "name_cli" => "$user->nombres",
                        "id" => "$user->id_user",
                        "access_type" => $user->rol
                    ];

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "status" => ControllerBase::SUCCESS
                    ));
                } else 
                {
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => ControllerBase::CLIENT_AUTHWEB_BAD,
                        "status" => ControllerBase::SUCCESS
                    ));
                }
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }

     /**
     * 
     *
     */ 

    public function newQuotationGatewayAction() {
        $dataRequest = $this->request->getJsonPost();
        $fields = array(
            "user",//email
            "iva_ammount",
            "base_ammount",
            "payment_method", // Tipo de pago: 1 = Punto de pago fijo, 2 = Domicilio
            "commerce_id",
            "transaction",
            "ammount",
            "signature"
        );

        $optional = array(
            "description"
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            if ($this->_checkFieldsGateway($dataRequest)){
                try {

                    $total_amount = Rates::gatewayQuotation($dataRequest->payment_method, $dataRequest->commerce_id, $dataRequest->base_ammount);

                    $id_user = ClientGateway::findFirst(array(
                        "conditions" => "email = ?1",
                        "bind" => array(1 => $dataRequest->user)
                    ));

                    $quotation = new Quotation();
                    $quotation->base_amount = $dataRequest->base_ammount;
                    $quotation->total_amount = $total_amount;
                    $quotation->id_client = $id_user->id;
                    $quotation->id_payment = $dataRequest->payment_method;

                    if ($quotation->save()) {

                        $data = array(
                                'id_quotation' => $quotation->id, 
                                'total' => $total_amount
                            );

                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "message" => ClientGatewayConstants::QUOTATION_SUCCESS,
                            "status" => ControllerBase::SUCCESS
                        ));
                    } else {
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                            "return" => false,
                            "message" => ClientGatewayConstants::QUOTATION_FAILURE,
                             "status" => ControllerBase::FAILED
                         ));
                    }
                } catch (Exception $e) {
                        $this->logError($e, $dataRequest);
                }
            }
        }
    }


     /**
     * 
     *
     */ 

    public function newRequestGatewayAction() {
        $dataRequest = $this->request->getJsonPost();
        $fields = array(
            "user",//email
            "total_value",
            "id_quotation",
            "url_answer",
            "url_confirmation",
            "id_location",
            "half_payment",
            "transaction",
            "ammount",
            "signature"
        );

        $optional = array();

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            if ($this->_checkFieldsGateway($dataRequest)){
                try {

                    $request = new request();
                    
                    $request->email = $dataRequest->email;
                    $request->total_value = $dataRequest->total_value;
                    $request->iva_value = $dataRequest->iva_value;
                    $request->base_value = $dataRequest->base_value;
                    $request->half_payment = $dataRequest->half_payment;

                    if ($dataRequest->half_payment == "En punto") {
                        $data = array(
                                'id' => $request->id, 
                                'pin_code' => $request->pin_code,
                                'list_points' => $request->list_points,
                                'state' => $request->state,
                                'url' => $request->url
                            );
                    }

                    if ($dataRequest->half_payment == "Domicilio") {
                        $data = array(
                                'id' => $request->id, 
                                'state' => $dataRequest->state,
                                'url' => $request->url
                            );
                    }

                    if ($request->save()) {

                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "message" => LocationConstants::LOCATION_SAVE_SUCCESS,
                            "status" => ControllerBase::SUCCESS
                        ));
                    } else {
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                            "return" => false,
                            "message" => LocationConstants::LOCATION_SAVE_FAILURE,
                             "status" => ControllerBase::FAILED
                         ));
                    }
                } catch (Exception $e) {
                        $this->logError($e, $dataRequest);
                }
            }
        }
    } 


    public function loginAdminAction()
    {

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

                    if (isset($client->id)){
                        
                        $company = Company::findFirst(array(
                            "conditions" => "client_id = ?1",
                            "bind" => array(1 => $client->id)
                        ));

                        if (isset($company->id_company)) {
                            $data = array (
                                'id' => $client->id,
                                'name' => $client->name,
                                'token' => $client->token,
                                'access_type' => 2 // Validar que tipo de usuario esta ingresando (1 = Cliente / 2 = Compañia)
                            );
                        } else {
                            $data = array (
                                'id' => $client->id,
                                'name' => $client->name,
                                'token' => $client->token,
                                'access_type' => 1 // Validar que tipo de usuario esta ingresando (1 = Cliente / 2 = Compañia)
                            );
                        }

                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "status" => ControllerBase::SUCCESS
                        ));

                    } else {

                       $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => ClientConstant::CLIENT_AUTHWEB_BAD,
                            "status" => ControllerBase::FAILED
                        ));
                    }
                    
                } catch (Exception $e) {
                    $this->logError($e, $dataRequest);
                }
         
        }
    } 


    public function postAdminAction() 
    {

        $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass",
            "email"
        );

        $optional = array(
            "name"
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {
         
            try {


                $client = ClientGateway::findFirst(array(
                                "conditions" => "email = ?1",
                                "bind" => array(1 => $dataRequest->email)
                          ));

                if (!isset($client->id)){

                    $client_gateway = new ClientGateway();
                    $client_gateway->password = $dataRequest->pass;
                    $client_gateway->email = $dataRequest->email;
                    $client_gateway->created_at = $dateTime->format('Y-m-d H:i:s');
                    $client_gateway->token = hash('sha256',str_shuffle("p4q6r8s10t2u3v5a7b9cdefghijklmnowxyz" . uniqid()));

                    if (isset($dataRequest->name))
                        $client_gateway->name = $dataRequest->name;

                    if($client_gateway->save()){
                        $data = $this->dataClientAdmin($client_gateway);
                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => true,
                            "data" => $data,
                            "status" => ControllerBase::SUCCESS
                        ));

                    }else{

                        $errors = array();
                        foreach ($client_gateway->getMessages() as $msj) {
                            $errors[] = (string)$msj;
                        }
                        $this->setJsonResponse(ControllerBase::FAILED, " Failed register client", array(
                            "return" => false,
                            "message" => $errors,
                            "status" => ControllerBase::FAILED
                        ));

                    }
                    
                } else {
                    
                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                        "return" => false,
                        "message" => "El cliente ya se encuentra registrado",
                        "status" => ControllerBase::FAILED
                    ));
                }
            

            }catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }   
        }

    }

    /*
    *Accion editClient: Actualizar datos del cliente 
    */
    public function editClientAction()
    {

        $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();

        $fields = array(
            "id_client"
        );

        $optional = array(
            "name",
            "phone",
            "document",
            "id_city",
            "address"
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                $client = Client::findFirst(array(
                    "conditions" => "id = ?1",
                    "bind" => array(1 => $dataRequest->id_client)
                ));

                if (isset($dataRequest->name))
                    $client->name = $dataRequest->name;

                if (isset($dataRequest->phone))
                    $client->phone = $dataRequest->phone;

                if (isset($dataRequest->document))
                    $client->document_client = $dataRequest->document;

                if (isset($dataRequest->id_city))
                    $client->id_city = $dataRequest->id_city;

                if (isset($dataRequest->address))
                    $client->address = $dataRequest->address;


                $client->updated_at = $dateTime->format('Y-m-d H:i:s');

                if ($client->save()){
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $client,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {

                   $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => ClientConstant::CLIENT_UPDATE_FAILURE,
                        "status" => ControllerBase::FAILED
                    ));
                }
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
         
        }
    }


    /*
    *Accion detailClient: Detalle del cliente 
    */
    public function detailClientAction()
    {
        $dataRequest = $this->request->getJsonPost();

        $fields = array(
            "id_client"
        );

        if ($this->_checkFields($dataRequest, $fields)) {

            try {

                $client = Client::findFirst(array(
                    "conditions" => "id = ?1",
                    "bind" => array(1 => $dataRequest->id_client)
                ));

                $cityModel = new Client();
                $citySql = $cityModel->searchCity();
                while ($row = $citySql->fetch(PDO::FETCH_ASSOC)) {


                    $city[] = array(
            
                            "id" => $row['id'],
                            "city" => $row['city'],

                        );
                }


                if ($client->id){
                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $client,
                        "city" => $city,
                        "status" => ControllerBase::SUCCESS
                    ));

                } else {

                   $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => "No se encontro el cliente",
                        "status" => ControllerBase::FAILED
                    ));
                }
                
            } catch (Exception $e) {
                $this->logError($e, $dataRequest);
            }
        }
    }


    public function loginAdminFacebookAction() 
    {

      $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass",
            "email"
        );
        $optional = array(

        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {
                try {

                    $result = $this->verifyToken($dataRequest->pass, "fb");

                    if (isset($result['id'])) { // si el token es valido en facebook

                        $id_red_social = 2;
                        $id = $result['id'];

                        $client_gateway = ClientGateway::findFirstByEmail($dataRequest->email);

                        if (!isset($client_gateway->id)) {

                             $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "No tiene una cuenta registrada",
                                    "status" => ControllerBase::FAILED
                            ));
                            
                        }else{

                            $register = $this->validateRegister($client_gateway);

                            if ( $register == 2 ) {

                                $redSocial = ClienteRedSocial::findFirst($client_gateway->client_red_social_id);
                                if ($redSocial->social_id == $result['id']){

                                     $data = $this->dataClientAdmin($client_gateway);
                                     $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                            "return" => true,
                                            "data" =>$data,
                                            "status" => ControllerBase::SUCCESS
                                     ));
                                }

                            }elseif( $register == 1 ){
                                
                                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "Cliente con cuenta en Google",
                                    "status" => ControllerBase::FAILED
                                ));

                            }else{

                                 $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "El cliente ya se encuentra registrado",
                                    "status" => ControllerBase::FAILED
                                ));
                            }

                        }
                        
                    } else {
                        $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => "Credenciales inválidas",
                            "status" => ControllerBase::FAILED
                        ));
                    }

                } catch (Exception $e) {

                    $this->logError($e, $dataRequest);
                }
            
        }

    }

    /*
    *Accion postAdminFacebook: Login y Registro con Facebook
    */
    public function postAdminFacebookAction()
    {

        $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass",
            "email",
            "name"
        );

        $optional = array(
            "access_type",
            "id_cellar",
            "name",
            "city",
            "address",
            "deposit_capacity",
            "storage_cost",
            "initial_date",
            "final_date",
            "supervisor",
            "space_require",
            "deptotal",
            "id_truck",
            "name_city",
            "name_city2",
            "capacity",
            "cost",
            "initial_date_truck",
            "hour",
            "conductor",
            "type_load",
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                $access_t = $dataRequest->access_type;
                $result = $this->verifyToken($dataRequest->pass, "fb");

                if (isset($result['id'])) { // si el token es valido en facebook

                    $id_red_social = 2;
                    $id = $result['id'];

                    $client = Client::findFirstByEmail($dataRequest->email);

                    if (!isset($client->id)) {
                         $this->registerRedSocial($dateTime, $result, $id_red_social, $dataRequest, $id);
                    }else{

                        $register = $this->validateRegister($client);

                        if ( $register == 2 ) {

                            $redSocial = ClienteRedSocial::findFirst($client->client_red_social_id);
                            if ($redSocial->social_id == $result['id']) {

                                if (isset($client->id) && $access_t == 0) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 1
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                } if (isset($client->id) && $access_t == 1) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 1
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                } if (isset($client->id) && $access_t == 2) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 2,
                                            "id_cellar" => $dataRequest->id_cellar,
                                            "name" => $dataRequest->name,
                                            "city" => $dataRequest->city,
                                            "address" => $dataRequest->address,
                                            "deposit_capacity" => $dataRequest->deposit_capacity,
                                            "storage_cost" => $dataRequest->storage_cost,
                                            "initial_date" => $dataRequest->initial_date,
                                            "final_date" => $dataRequest->final_date,
                                            "supervisor" => $dataRequest->supervisor,
                                            "space_require" => $dataRequest->space_require,
                                            "deptotal" => $dataRequest->deptotal
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                } if (isset($client->id) && $access_t == 3) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 3,
                                            "id_truck" => $dataRequest->id_truck,
                                            "name" => $dataRequest->name,
                                            "name_city" => $dataRequest->name_city,
                                            "name_city2" => $dataRequest->name_city2,
                                            "capacity" => $dataRequest->capacity,
                                            "cost" => $dataRequest->cost,
                                            "initial_date_truck" => $dataRequest->initial_date_truck,
                                            "hour" => $dataRequest->hour,
                                            "conductor" => $dataRequest->conductor,
                                            "type_load" => $dataRequest->type_load,
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                }

                            } else {
                                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                                    "return" => false,
                                    "message" => "Credenciales inválidas",
                                    "status" => ControllerBase::FAILED
                                ));
                            }     

                        }elseif( $register == 1 ){
                            
                            $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                "return" => false,
                                "message" => "Cliente con cuenta en Google",
                                "status" => ControllerBase::FAILED
                            ));

                        }else{

                             $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                "return" => false,
                                "message" => "El cliente ya se encuentra registrado",
                                "status" => ControllerBase::FAILED
                            ));
                        }

                    }
                    
                } else {
                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => "Credenciales inválidas",
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {

                $this->logError($e, $dataRequest);
            }
            

        }

    }

    /*
    *Accion postAdminGoogle: Login y Registro con Google
    */
    public function postAdminGoogleAction()
    {

        $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass"
        );

        $optional = array(
            "access_type",
            "id_cellar",
            "name",
            "city",
            "address",
            "deposit_capacity",
            "storage_cost",
            "initial_date",
            "final_date",
            "supervisor",
            "space_require",
            "deptotal",
            "id_truck",
            "name_city",
            "name_city2",
            "capacity",
            "cost",
            "initial_date_truck",
            "hour",
            "conductor",
            "type_load",
        );
        
        if ($this->_checkFields($dataRequest, $fields, $optional)) {
            
            try {

                $access_t = $dataRequest->access_type;
                $result = $this->verifyToken($dataRequest->pass, "g+");

                if (isset($result['kid'])) { // si el token es valido en google

                    $id_red_social = 1;
                    $id = $result['kid'];

                    $client = Client::findFirstByEmail($result['email']);
                    
                    if (!isset($client->id)) {
                        
                         $this->registerRedSocial($dateTime, $result, $id_red_social, $dataRequest, $id);
                        
                    }else{

                        $register = $this->validateRegister($client);

                        if ( $register == 1 ) {

                            $redSocial = ClienteRedSocial::findFirst($client->client_red_social_id);
                            if ($redSocial->social_id == $result['kid']) {
                                
                                if (isset($client->id) && $access_t == 0) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 1
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                } if (isset($client->id) && $access_t == 1) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 1
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                } if (isset($client->id) && $access_t == 2) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 2,
                                            "id_cellar" => $dataRequest->id_cellar,
                                            "name" => $dataRequest->name,
                                            "city" => $dataRequest->city,
                                            "address" => $dataRequest->address,
                                            "deposit_capacity" => $dataRequest->deposit_capacity,
                                            "storage_cost" => $dataRequest->storage_cost,
                                            "initial_date" => $dataRequest->initial_date,
                                            "final_date" => $dataRequest->final_date,
                                            "supervisor" => $dataRequest->supervisor,
                                            "space_require" => $dataRequest->space_require,
                                            "deptotal" => $dataRequest->deptotal
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                } if (isset($client->id) && $access_t == 3) {
                                    $data = [
                                            "email" => "$client->email",
                                            "name_cli" => "$client->name",
                                            "id" => "$client->id",
                                            "access_type" => 3,
                                            "id_truck" => $dataRequest->id_truck,
                                            "name" => $dataRequest->name,
                                            "name_city" => $dataRequest->name_city,
                                            "name_city2" => $dataRequest->name_city2,
                                            "capacity" => $dataRequest->capacity,
                                            "cost" => $dataRequest->cost,
                                            "initial_date_truck" => $dataRequest->initial_date_truck,
                                            "hour" => $dataRequest->hour,
                                            "conductor" => $dataRequest->conductor,
                                            "type_load" => $dataRequest->type_load,
                                    ];
            
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => true,
                                        "data" => $data,
                                        "status" => ControllerBase::SUCCESS
                                    ));
            
                                }

                            } else {
                                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                                    "return" => false,
                                    "message" => "Credenciales inválidas",
                                    "status" => ControllerBase::FAILED
                                ));
                            }     

                        }elseif( $register == 2 ){
                            
                            $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                "return" => false,
                                "message" => "Cliente con cuenta en Facebook",
                                "status" => ControllerBase::FAILED
                            ));

                        }else{

                             $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                "return" => false,
                                "message" => "El cliente ya se encuentra registrado",
                                "status" => ControllerBase::FAILED
                            ));
                        }

                    }
                    
                } else {
                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => "Credenciales inválidas",
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {

                $this->logError($e, $dataRequest);
                
            }
        }
    }


    public function loginAdminGoogleAction()
    {

       $dataRequest = $this->request->getJsonPost();

        $dateTime = new \DateTime();
        $fields = array(
            "pass"
        );
        $optional = array(

        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

                try {

                    $result = $this->verifyToken($dataRequest->pass, "g+");

                    if (isset($result['kid'])) { // si el token es valido en google

                        $id_red_social = 1;
                        $id = $result['kid'];

                        $client = Client::findFirstByEmail($result['email']);

                        if (!isset($client->id)) {

                            $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "No tiene una cuenta registrada",
                                    "status" => ControllerBase::FAILED
                            ));
                            
                        }else{

                            $register = $this->validateRegister($client);

                            if ( $register == 1 ) {

                                $redSocial = ClienteRedSocial::findFirst($client->client_red_social_id);
                                if ($redSocial->social_id == $result['kid']) {

                                     $data = $this->dataClientAdmin($client);
                                     $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                            "return" => true,
                                            "data" => $data,
                                            "status" => ControllerBase::SUCCESS
                                    ));

                                } else {
                                    $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                                        "return" => false,
                                        "message" => "Credenciales inválidas",
                                        "status" => ControllerBase::FAILED
                                    ));
                                }     

                            }elseif( $register == 2 ){
                                
                                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "Cliente con cuenta en Facebook",
                                    "status" => ControllerBase::FAILED
                                ));

                            }else{

                                 $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                                    "return" => false,
                                    "message" => "El cliente ya se encuentra registrado",
                                    "status" => ControllerBase::FAILED
                                ));
                            }

                        }
                        
                    } else {
                        $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => "Credenciales inválidas",
                            "status" => ControllerBase::FAILED
                        ));
                    }

                } catch (Exception $e) {

                    $this->logError($e, $dataRequest);
                }
        }

    }



    public function verifyToken($token, $social) {

        if ($social == "fb")
            $url = "https://graph.facebook.com/me?fields=id,name,email&access_token=" . $token;
        elseif ($social == "g+")
            $url = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=" . $token;

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $page = curl_exec($c);
        curl_close($c);

        $result = json_decode($page, true);

        return $result;
    }


    private function registerRedSocial($dateTime, $result, $id_red_social, $dataRequest, $id) {
        
        $clienteRedSocial = new ClienteRedSocial();
        $clienteRedSocial->social_id = $id;
        $clienteRedSocial->id_red_social = $id_red_social;
        $clienteRedSocial->estado = 1;
        $clienteRedSocial->fecha_registro = $dateTime->format("Y-m-d H:i:s");
        $clienteRedSocial->fecha_actualizacion = $dateTime->format("Y-m-d H:i:s");

        if ($clienteRedSocial->save() == false) {

            $transaction->rollback();
            $errors = array();
            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                "return" => false,
                "message" => $this->_checkError($clienteRedSocial),
                "status" => ControllerBase::FAILED
            ));

        } else {

            $this->saveClient($dateTime,$dataRequest,$clienteRedSocial->id_cliente_red_social,$result);
        }

    }


    private function saveClient($dateTime,$dataRequest,$redSocialId,$result)
    {

        $client = new Client();
        $client->password = $dataRequest->pass;
        $client->email = isset($dataRequest->email) ? $dataRequest->email : $result['email'] ;
        $client->name = isset($result['name']) ? $result['name'] : null;
        $client->created_at = $dateTime->format('Y-m-d H:i:s');
        $client->token = hash('sha256',str_shuffle("p4q6r8s10t2u3v5a7b9cdefghijklmnowxyz" . uniqid()));
        $client->client_red_social_id = $redSocialId; 
        
        if($client->save()){

            if (isset($dataRequest->commerce_id)) {
                # login plataforma pasarela de pagos

                    $commerce = $this->dataCommerce($dataRequest->commerce_id);
                    $data = [
                        "email" => "$client->email",
                        "name" => "$client->name",
                        "client_id" => "$client->id",
                        "commerce" => $commerce
                    ];

                     $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => true,
                        "data" => $data,
                        "status" => ControllerBase::SUCCESS
                    ));

            } else {
                
                # data login plataforma Admin
                /* $company = Company::findFirst(array(
                    "conditions" => "client_id = ?1",
                    "bind" => array(1 => $client->id)
                )); */
                
                //Validar que tipo de usuario esta ingresando (1 = Cliente / 2 = Compañia)  
                /* if (isset($company->id_company)) 
                    $access_type = 2; 
                else  */
                    $access_type = 1;
                
                $data = [
                    "email" => "$client->email",
                    "name" => "$client->name",
                    "client_id" => "$client->id",
                    'access_type' => $access_type
                ];

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                    "return" => true,
                    "data" => $data,
                    "status" => ControllerBase::SUCCESS
                ));
            }            

        }else{

            $errors = array();
            foreach ($client->getMessages() as $msj) {
                $errors[] = (string)$msj;
            }
            $this->setJsonResponse(ControllerBase::FAILED, " Failed register client", array(
                "return" => false,
                "message" => $errors,
                "status" => ControllerBase::FAILED
            ));

        }

    }

    private function validateRegister($client)
    {
        $redSocial = isset($client->client_red_social_id) ? ClienteRedSocial::findFirst($client->client_red_social_id)->id_red_social : 0 ;
        return  isset($redSocial) ? $redSocial : 0;

    }

    private function dataCommerce($commerce_id)
    {

         $comercio =  Comercio::findFirst(array(
            "conditions" => "id_comercio = ?1 and estado = ?2",
            "bind" => array(1 => $commerce_id,
                            2 => 1)
        ));


        $type_payment = (new TypePayCommerce)->getTypePayment($comercio->id_comercio);

        foreach ($type_payment as $key => $value) {

             $date_type_payment[] = (object) array(
                "type_payment_id" => $value['type_payment_id'],
                "tipo_pago" => $value['tipo_pago'],
                "image" => $value['image'],
                "type_code" => $value['type_code']
            );
        }

        $commerce = array(
            'commerce_id' => $comercio->id_comercio, 
            'commerce_name' => $comercio->nombre_comercio,
            'state' => $comercio->estado,
            'date_register' => $comercio->fecha_registro,
            'date_update' => $comercio->fecha_actualizacion,
            'signature_key' => $comercio->signature_key,
            'image' => $comercio->image,
            'payment_type'  => isset($date_type_payment) ? $date_type_payment : array(),
            'answer_page' => $comercio->answer_page
        );


        return $commerce;
    }


    private function dataClientAdmin($client_gateway)
    {

        return $data = array(
                                'id' => $client_gateway->id, 
                                'email' => $client_gateway->email,
                                'token' => $client_gateway->token,
                                'name' => $client_gateway->name
                            );


    }


    public function forgotpasswordAction() {

        $dataRequest = $this->request->getJsonPost();
        $fields = array(
            "route",
        );
        $optional = array(
            "email",
            "phone"
        );

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                if(isset($dataRequest->email)){

                    $cliente = ClientGateway::findFirst(array(
                        "conditions" => " email = ?1 ",
                        "bind" => array(1 => $dataRequest->email)
                    ));
    
                    if (is_object($cliente)) {
    
                        if(isset($cliente->client_red_social_id)){
    
                            $redsoc = RedSocial::findFirst(array(
                                "conditions" => " id_red_social = ?1 ",
                                "bind" => array(1 => $cliente->client_red_social_id)
                            ));
    
                            if (isset($redsoc->id_red_social)) {
                                if ($redsoc->id_red_social == 1 || $redsoc->id_red_social == 2) {
                                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                                        "message" => "El usuario esta registrado con la red social " . $redsoc->red_social,
                                        "status" => ControllerBase::FAILED
                                    ));
                                }
    
                            } else {
                              
                                $cod = $cliente->email;
                                $cod = base64_encode($cod);//codificamos la cadena en bas64
                                $cod = strrev($cod);//ponemos la cadena al reves
                                $this->recoveryMailWebAction($cliente,$dataRequest->route);
                            }
                            
                        }else{
    
                            $cod = $cliente->email;
                            $cod = base64_encode($cod);//codificamos la cadena en bas64
                            $cod = strrev($cod);//ponemos la cadena al reves
                            $this->recoveryMailWebAction($cliente,$dataRequest->route);
                        }
                            
                    }else{

                        if(isset($dataRequest->phone)){

                            $cliente_phone = ClientGateway::findFirst(array(
                                "conditions" => "phone = ?1 ",
                                "bind" => array(1 => $dataRequest->phone)
                            ));
    
                            if (is_object($cliente_phone)) {
        
        
                                if(isset($cliente_phone->client_red_social_id)){
        
                                    $redsoc = RedSocial::findFirst(array(
                                        "conditions" => " id_red_social = ?1 ",
                                        "bind" => array(1 => $cliente_phone->client_red_social_id)
                                    ));
            
                                    if (isset($redsoc->id_red_social)) {
                                        if ($redsoc->id_red_social == 1 || $redsoc->id_red_social == 2) {
                                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                                                "message" => "El usuario esta registrado con la red social " . $redsoc->red_social,
                                                "status" => ControllerBase::FAILED
                                            ));
                                        }
            
                                    } else {
                                        
                                        $cod = $cliente_phone->email;
                                        $cod = base64_encode($cod);//codificamos la cadena en bas64
                                        $cod = strrev($cod);//ponemos la cadena al reves
                                        $this->recoveryMailWebAction($cliente_phone,$dataRequest->route);
                                    }
                                    
                                }else{
            
                                    $cod = $cliente_phone->email;
                                    $cod = base64_encode($cod);//codificamos la cadena en bas64
                                    $cod = strrev($cod);//ponemos la cadena al reves
                                    $this->recoveryMailWebAction($cliente_phone,$dataRequest->route);
                                }
                                    
                             
        
                            }else{
        
                                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                                    "message" => "El usuario " . $dataRequest->phone . " no aparece en nuestro sistema phon",
                                    "status" => ControllerBase::FAILED
                                ));
            
                            }
    
                        }else{
    
                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                                "message" => "El usuario no registra en el sistema",
                                "status" => ControllerBase::FAILED
                            ));
    
                        }

                    }

                }
               

            } catch (Exception $e) {

                $this->logError($e, $dataRequest);

            }

        }
    }

    /**
     * @return mail
     */
    public function recoveryMailWebAction($client,$route = "") {
       
        include_once ControllerBase::URLMAIL;
        include_once ControllerBase::URLMAILCONFIG;

        $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789" . uniqid());
        $expiration = strtotime("+1 day");

        $tokenPass = new TokenPassword();
        $tokenPass->token = $token;
        $tokenPass->expiration = $expiration;

        if(isset($client->id))
            $tokenPass->client_gateway_id = $client->id;


        if ($tokenPass->save() == false) {

            $this->setJsonResponse(ControllerBase::SUCCESS, Controller::SUCCESS_MESSAGE, array(
                "return" => false,
                "message" => array("Track" => $this->_checkError($tokenPass)),
                "status" => ControllerBase::FAILED
            ));

        } else {

            $msg = "";

            $correo = $client->email;
            $nombre = $client->name;

            if (file_exists("../public/mailing/cashcommerce/recovery/recovery_password.html")) {

                $msg = file_get_contents('../public/mailing/cashcommerce/recovery/recovery_password.html');
                $msg = str_replace("[1]", $client->name, $msg);
                $msg = str_replace("[2]", "$route?token=" . $token, $msg);
        
        
            } else {
                $msg = "No existé plantilla";
            }

            $mail->From = "colombia@logisticapp.org";
            // $mail->FromName = $proveedor->nombre;
            $mail->Subject = utf8_decode("Recuperación contraseña");
            $mail->AltBody = utf8_decode("Recueprar contraseña");
            $mail->MsgHTML($msg);
            $mail->AddAddress($correo, $nombre);
            $mail->IsHTML(true);

            if (!$mail->Send()) {

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                    "return" => false,
                    "message" => "No se pudo enviar el correo",
                    "status" => ControllerBase::FAILED
                ));
            } else {

                $_msg = ClientConstant::CLIENT_FORGOT_PASSWORD;
                $_msg = str_replace("[1]", $client->email, $_msg);

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                    "return" => true,
                    "message" => $_msg,
                    "status" => ControllerBase::SUCCESS
                ));
            }
           
        }
    }

    public function changepasswordAction() {

        $dataRequest = $this->request->getJsonPost();
        $fields = [
            "token",
            "pass"
        ];
        $optional = [];

        if ($this->_checkFields($dataRequest, $fields, $optional)) {

            try {

                $tokenPass = TokenPassword::findFirst(array(
                    "conditions" => "token = ?1 ",
                    "bind" => array(1 => $dataRequest->token)
                ));

                if (isset($tokenPass->id)) {

                    $now = strtotime("now");

                    if ($now <= $tokenPass->expiration) { // si aun no se ha cumplido la fecha de expiracion

                        $client = ClientGateway::findFirst($tokenPass->client_gateway_id);

                        $client->password = $dataRequest->pass;

                        if ($client->save() == false) {

                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => false,
                                "message" => "Error actualizando la contraseña, por favor intente nuevamente",
                                "status" => ControllerBase::FAILED
                            ));

                        } else {

                            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                                "return" => true,
                                "message" => ClientConstant::CLIENT_CHANGE_PASSWORD,
                                "status" => ControllerBase::SUCCESS
                            ));
                        }
                    } else {

                        $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                            "return" => false,
                            "message" => "El link a expirado, por favor haga de nuevo el proceso de recuperar contraseña",
                            "status" => ControllerBase::FAILED
                        ));
                    }
                } else {

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::SUCCESS_MESSAGE, array(
                        "return" => false,
                        "message" => "token invalido",
                        "status" => ControllerBase::FAILED
                    ));
                }

            } catch (Exception $e) {

                $this->logError($e, $dataRequest);
            }
        }
        
    }

}