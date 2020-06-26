<?php

use Phalcon\Mvc\Controller;

/**
 *
 * @author Julián Arturo Molina Castiblanco
 * @version 0.1
 * @copyright Logisticapp.sa
 */
require __DIR__ . '/../../vendor/autoload.php';



class ControllerBase extends Controller {

    const SUCCESS = 200;
    const FAILED_CARD = 210;
    const FAILED_CARD_PAY = 211;
    const FAILED = 409;
    const EMPTYFIELD = "";
    const WARNING_MESSAGE = "WARNING OPERATION";
    const FAILED_MESSAGE = "FAILED OPERATION";
    const NOTICE_MESSAGE = "NOTICE MESSAGE";
    const SUCCESS_MESSAGE = "SUCCESS OPERATION";
    const IPDEV = "52.43.247.174";
    const EARTHRADIUS = 6371;
    const WEB3 = __DIR__ .'/../library/web3/Web3.php';
    const URLS3 = __DIR__ . '/../library/s3_config.php';
    const URLMAIL = __DIR__ . '/../library/PHPMailer/PHPMailerAutoload.php';
    const URLMAILCONFIG = __DIR__ . '/../library/PHPMailer/phpmailer_config.php';
    const GoogleAPIKey = 'AIzaSyA5WvXLnXXHvhi3JvlOAqjCBOFIAQ7VUtU';
    const currencies = array(1 => 'COP',2 => 'EUR',3 => 'USD');//Monedas de cambio
    const process_payment =  __DIR__ . '/../../scripts/process-payment.php';
    const FUEC = '213 0029 16 2018 1099 ';
    /*definimos constantes de arreglo*/
    const SERVICEREDSERVI = array(4, 5);//servicios cajeros,pagos
    const SERVIPAY = array(5);//servicios pagos
    const SERVIGIRO = array(7);//servicios giros
    const SERVIDIVISA = array(9);//servicios divisas
    const SERVIMIXPAY = "35,28,52,59";
    const SERVICEBAG = array(1, 2, 3, 6);//servicios de la bolsa
    const SERVICETRANS = array(4, 5, 7);//servicios modelo transaccional
    const SERVICEDEST = array(1, 2, 4, 10);//servicios calificar
    const classification = array(1 => 'CENTRO DE CONSOLIDACION',2 => 'CENTRO DE CORRESPONDENCIA',3 => 'CENTRO DE PROCESAMIENTO',4 => 'DEPENDENCIAS CLIENTE', 5 => 'OFICINA COMERCIAL', 6 => 'PROVEEDOR CLIENTE');//Calsificación compañias
    const activity = array(1 => 'ENTREGAR',2 => 'RECOGER',3 => 'ENTREGAR Y RECOGER');//Actividad en punto
    const product = array(1 => 'BAJOS MONTOS',2 => 'BASE DE CAMBIO',3 => 'BONOS', 4 => 'CANJE Y CORRESPONDENCIA', 5 => 'CANJE Y GARANTIAS', 6 => 'CANJE Y REMESA', 7 => 'COMPENSACION', 8 => 'CORRESPONDENCIA', 9 => 'DEVOLUCION', 10 => 'DEVOLUCION Y CANJE', 11 => 'DEVOLUCION Y CORRESPONDENCIA', 12 => 'DISTRIBUCIONES ESPECIALIZADAS', 13 => 'LOGISTICA', 14 => 'CANJE');//Producto en punto
    /*definimos constantes de arreglo*/
    const STATE_PIN =  array( 1 => 'GENERADO', 2 => 'PAGADO' , 3 => 'VENCIDO' , 4 =>'PENDIENTE' );
    const DistributionStates = array(1 => 'PENDIENTE GESTION',2 => 'ENTREGADO',3 => 'DEVUELTO');

    const ROL_CLIENTE = 1;
    const ROL_SOPORTE = 2;

    const ESTADO_PENDIENTE = 1;
    const ESTADO_PROCESO = 2;
    const ESTADO_CERRADO = 3;

    const CLIENT_AUTHWEB_BAD = "Credenciales incorrectas";


    protected $provider_id;
    protected $_dateTime = null;

    /**
     * Google GCM and ApplePush
     */
    protected $uuid;
    protected $title;
    protected $body;
    protected $data;
    protected $type;
    protected $typeDevice = 1;
    protected $web3;

    /**
     *
     */
    public function initialize() {
        $this->_dateTime = new \DateTime();
        $this->view->disable();
    }

    /**
     * Send Http JSON response
     */
    public function setJsonResponse($code, $msj, array $content) {
        $this->response->setStatusCode($code, $msj);
        $this->response->setJsonContent($content);
        $this->response->send();
    }

    public function getJsonResponse() {
        if (isset($this->response)) {
            return $this->response->getContent();
        } else {
            return null;
        }
    }

    /**
     * Store trace to error models.
     */
    protected function _checkError($model) {
        if ($model) {

            $errors = array();
            foreach ($model->getMessages() as $msg) {
                $errors[] = $msg;
            }
            return $errors;

        } else {
            return false;
        }
    }

    /**
     * Check if parameters are valid fields.
     *
     * @param Array $_POST $compare
     * @param
     */
    protected function _checkFields($dataRequest, array $fields, array $optional = array(), $method = "POST", $itemHead = 0) {

        $dataRequest = (array)$dataRequest;
        $check[] = array();
        $error = array();
        $i = 1;
        $item = null;

        foreach ($fields as $key => $value) {

            $rest = array_key_exists($value, $dataRequest);

            if ($rest) {

            } else {

                $check[] = "false";
                $error[] = empty($value) ? "" : $value;
                $item[] = $i;

            }
            $i++;
        }

        $item = $itemHead > 0 ? $itemHead : $item;

        if (array_search("false", $check)) {

            $this->setJsonResponse(self::SUCCESS, "CHECK FIELDS PARAMETER ERROR", array(
                "return" => false,
                "item" => $item,
                "messages" => array("This parameters are wrong" => array_unique($error)),
                "optional_fields" => $optional,
                "status" => self::FAILED
            ));

            return false;

        } else {

            foreach ($dataRequest as $key => $value) {

                if ($value == '')
                    $error[] = $key . " parameter is empty";
            }

            if (count($error) > 0) {

                $this->setJsonResponse(self::SUCCESS, "CHECK FIELDS PARAMETER ERROR", array(
                    "return" => false,
                    "item" => $item,
                    "messages" => array("This parameters are wrong" => array_unique($error)),
                    "optional_fields" => $optional,
                    "status" => self::FAILED
                ));

                return false;

            } else {

                return true;

            }
        }
    }

    protected function _checkFieldsGateway($dataRequest){


        $comercio =  Comercio::findFirst(array(
            "conditions" => "id_comercio = ?1 and estado = ?2",
            "bind" => array(1 => $dataRequest->commerce_id,
                            2 => 1)
        ));
        if (isset($comercio->id_comercio) && $comercio->id_comercio > 0) {
            $hash = hash('sha256',$dataRequest->commerce_id.'~'.$dataRequest->transaction.'~'.$dataRequest->ammount.'~'.$comercio->signature_key);

            if ($dataRequest->signature == $hash) {
                return true;
            } else {
                $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                    "return" => false,
                    "message" => ClientConstant::CLIENT_WRONG_SIGNATURE,
                    "status" => ControllerBase::FAILED
                ));

                return false;
            }
        }else{
            $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::SUCCESS_MESSAGE, array(
                "return" => false,
                "message" => ClientConstant::COMMERCE_NOT_FOUND,
                "status" => ControllerBase::FAILED
            ));
            return false;
        }       
    }
    
    protected function _updateHeader($token, $id) {

        $client = Cliente::findFirst($id);

        $client->token = $token;

        if ($client->save() == false) {

            $this->setJsonResponse(self::SUCCESS, "CHECK FIELDS PARAMETER ERROR", array(
                "return" => false,
                "messages" => "Error de token",
                "status" => self::FAILED
            ));

            return false;

        } else {

            return true;
        }

    }

    protected function _generateHeaderAuth() {

        $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTVWXYZ" . uniqid());

        return $token;

    }

    protected function _validateHeader($headers) {

        if (isset($headers['x_token'])) {

            $client = Cliente::findFirst(array(
                "conditions" => "token = ?1",
                "bind" => array(1 => $headers['x_token'])
            ));

        } else {

            goto Failed;
        }


        if (!isset($client->id_cliente)) {

            Failed:

            $this->setJsonResponse(self::SUCCESS, "CHECK FIELDS PARAMETER ERROR", array(
                "return" => false,
                "messages" => "Petición invalida",
                "status" => self::FAILED
            ));

            return false;

        } else {

            return $client;
        }
    }

    protected function _get_available_time($id_horario) {
        switch (date("w")) {
            case 0:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->domingo_fin);
                break;
            case 1:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->lunes_fin);
                break;
            case 2:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->martes_fin);
                break;
            case 3:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->miercoles_fin);
                break;
            case 4:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->jueves_fin);
                break;
            case 5:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->viernes_fin);
                break;
            case 6:
                $horariorecurso = strtotime(Horario::findFirst($id_horario)->sabado_fin);
                break;
        }
        $horaactual = strtotime(date('H:i:s'));
        $hora = round(($horariorecurso - $horaactual) / 60);
        return $hora;
    }

    public function logError($e, $dataRequest) {

        $log = New Logs();
        $log->params = json_encode($dataRequest);
        $log->platform = isset($dataRequest->platform) ? $dataRequest->platform : "N/A";
        $log->file = $e->getFile();
        $log->line = $e->getLine();
        $log->message = $e->getMessage();
        $log->trace = $e->getTraceAsString();
        $log->register_date = $this->_dateTime->format("Y-m-d H:i:s");


        $log->save();

        $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
            "return" => false,
            "message" => "Error de la aplicación id: " . $log->id,
            "status" => ControllerBase::FAILED,
            //TODO###### DEV ONLY ########
            "cause" => $log->message,
            "file" => $log->file,
            "line" => $log->line
            //TODO########################
        ));

    }


    public function log($e,$dataRequest,$type) {

        $log = New Logs();
        $log->params = json_encode($dataRequest);
        $log->platform = isset($dataRequest->platform) ? $dataRequest->platform : "N/A";
        $log->file = empty($e) ?  $dataRequest["file"] : $e->getFile() ;
        $log->line =  empty($e) ?  $dataRequest["line"]  : $e->getLine();
        $log->message =  empty($e) ? $dataRequest["message"] : $e->getMessage();
        $log->trace = empty($e) ? $dataRequest["trace"]  : $e->getTraceAsString();
        $log->register_date = $this->_dateTime->format("Y-m-d H:i:s");
        $log->type = $type;

        $log->save();

        if(isset($e)){
              $this->setJsonResponse(ControllerBase::FAILED, ControllerBase::FAILED_MESSAGE, array(
                "return" => true,
                "message" => "Error de la aplicación id: " . $log->id ,
                "status" => ControllerBase::FAILED,
            ));
        }

    }


    public function BagService($type_service) {

        $bag = BolsaEnvios::findFirst(array(
            "conditions" => "id_servicio_proveedor = ?1 ",
            "bind" => array("1" => $type_service)
        ));

        return $bag->id_bolsa_envio;

        /*
        $service = ServicioProveedor::findFirst($type_service);

        $serviceB = ControllerBase::SERVICEBAG;
        $serviceM = ControllerBase::SERVICETRANS;

        if (in_array($service->id_tipo_envio, $serviceB)) {

            $bag = BolsaEnvios::findFirst(array(
                "conditions" => "id_servicio_proveedor = ?1 ",
                "bind" => array("1" => $type_service)
            ));

            return $bag->id_bolsa_envio;

        }elseif (in_array($service->id_tipo_envio, $serviceM)) {

            /*$bag = ModeloTransaccional::findFirst(array(
                "conditions" => "id_servicio_proveedor = ?1 ",
                "bind" => array("1" => $type_service)
            ));

            return $bag->id_modelo;*/
        /*return $type_service;

    }*/
    }

    /**
     *
     */
    public function sendPush($solicitudes, $sender, $recursoLogistico = "") {

        if ($solicitudes->origin_id != null)
            $location = Location::findFirst($solicitudes->origin_id);
        elseif ($solicitudes->destination_id != null)
            $location = Location::findFirst($solicitudes->destination_id);

        if (isset($location->id_client) and $sender != 1) {
            $client = Cliente::findFirst($location->id_client);
            $this->setUuid($client->gcm_id);
            $this->platformPush($client->plataforma);
        }

        if (isset($recursoLogistico->id_recurso_logistico) && $sender != 3) {
            $this->setUuid($recursoLogistico->gcm_id);
            $this->setTypeDevice(2); //2 = Mensajero, 1 = Cliente android
            $this->platformPush("android");
        }

    }

    public function platformPush($platform, $socket = 0, $validate = true) {
        if (strtolower($platform) == "web") {

            return $this->sendWebPush($socket);
        } else {
            if ($validate) {
                $state = $this->validatePush();
            } else {
                $state = true;
            }
            if ($state == true) {

                if (strtolower($platform) == "ios") {

                    return $this->sendApplePush("prod");

                } else if (strtolower($platform) == "ios-dev") {

                    $cont = 0;

                    Again:
                    $return = $this->sendApplePush("dev");
                    
                    if ($return != true) {

                        $cont++;
                        sleep(60);
                        if ($cont <= 4)
                            goto Again;
                    }


                } else if (strtolower($platform) == "android") {
                    $this->sendAndroidPush();
                }
            } else {
                //TODO no haga nada
            }

        }
    }

    public function validatePush() {

        $return = "";

        $getData = $this->getData();
        $type = $this->getType();

        $shippingId = $getData['shipping_id'];

        $shipping = Request::findFirst($shippingId);

        $state = $shipping->state;

        if ($type == 10) {//no asignado

            if ($state == 10)//no asignada
                $return = true;
            else
                $return = false;

        } elseif ($type == 5 || $type == 13) {//chat - tracking

            if ($state == 1 || $state == 2)//asiganda - recogida
                $return = true;
            else
                $return = false;

        } elseif ($type == 12) {//llegada condcutor

            if ($state == 1)//asignadas
                $return = true;
            else
                $return = false;

        } elseif ($type == 4) {//cancelada

            if ($state == 4)//cancelada
                $return = true;
            else
                $return = false;

        } elseif ($type == 1) {//asignada

            if ($state == 1)//asignada
                $return = true;
            else
                $return = false;

        } elseif ($type == 2) {//recogido

            if ($state == 2)//recogido
                $return = true;
            else
                $return = false;

        } elseif ($type == 3) {//entregado

            if ($state == 3)//entregado
                $return = true;
            else
                $return = false;

        }elseif ($type == 13) {//llegada conductor entregar

            if ($state == 2)//recogido
                $return = true;
            else
                $return = false;

        }elseif ($type == 16) {//nueva orden recibida

            if ($state == 16)//recibido
                $return = true;
            else
                $return = false;

        }elseif ($type == 17) {//orden por despachar

            if ($state == 17)//por despachar
                $return = true;
            else
                $return = false;

        }elseif ($type == 18) {//orden despachada

            if ($state == 18)//despachada
                $return = true;
            else
                $return = false;

        }elseif ($type == 6){
            $return = true;
        } elseif ($type == 22) {//llegada conductor entregar
            $return = true;
        }

        return $return;

    }

    public function sendWebPush($socket) {

        $socket = explode(",", $socket);

        if ($this->getType() == 1 || $this->getType() == 4 || $this->getType() == 12 || $this->getType() == 10) { //asignado - cancelado - llegada del conductor - cancela el conductor

            if ($this->getType() == 1 || $this->getType() == 4)
                $socket = $socket[0];
            elseif ($this->getType() == 12 || $this->getType() == 10)
                $socket = $socket[1];

            $getData = $this->getData();

            $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789" . uniqid());

            if (isset($getData))
                $data = json_encode($this->getData());
            else
                $data = json_encode($this->getBody());

            //exec('/usr/bin/php /var/www/html/api_devel/app/library/WebNotify '.$token.' '.$ip.' > /dev/null & ',$output,$return);// ejecutamos en background la apertura del socket esperando respuesta

            include_once __DIR__ . '/../library/WebNotify.php';

            $pushWeb = new PushWeb();
            $pushWeb->token = $token;
            $pushWeb->data = $data;
            $pushWeb->type = $this->getType();
            $pushWeb->save();
        }
    }

    /**
     *
     */
    public function sendApplePush($enviro) {
        $apple = new ApplePushNotify($enviro);
        $return = $apple->send(
            $this->getUuid(),
            $this->getTitle(),
            $this->getType(),
            $this->getData(),
            array(
                "title" => $this->getTitle(),
                "body" => $this->getBody()
            ),
            $this->getProviderId()
        );

        return $return;
    }

    /**
     *
     */
    public function sendAndroidPush() {
        $google = new GoogleCloudMessage();
        $google->setDevices(array($this->getUuid()));
        $result = $google->send(array(
            "title" => $this->getTitle(),
            "body" => $this->getBody(),
            "data" => $this->getData(),
            "type" => $this->getType(),
            "provider_id" => $this->getProviderId()
        ), $this->getTypeDevice());
    }

    /**
     *
     * Generate new token id hashed with \Phalcon\Crypt
     *
     * @param Integer $time = 10
     * @return
     */
    protected function _getToken($time = 10) {
        $content = array();
        $uniqueid = uniqid();

        $token = new Token();
        $token->expiration_date = strtotime("+10 minute");
        $token->now_date = strtotime("now");
        $token->created_at = date("Y-m-d H:m:s");
        $token->status = 1;

        if ($token->save() == false) {
            return null;
        } else {
            return $token->_id->{'$id'};
        }
    }

    /**
     *
     * Validate if token is available
     *
     * @param String $tk
     * @return Boolean
     *
     */
    protected function _validToken($tk) {
        if (!empty($tk)) {

            $token = Token::findById($tk);

            if ($token == false) {
                return false;
            } else {
                $expiration_date = (int)$token->expiration_date;
                if ($expiration_date > strtotime("now")) {
                    return true;
                } else {
                    return false;
                }
            }

        } else {
            return false;
        }
    }

    /**
     * Check if key is empty or null from post data recieve
     *
     */
    protected function _validKeyProvider($method = "POST") {
        try {

            if ($method == "POST")
                $dataRequest = $this->request->getJsonPost();
            else
                $dataRequest = $this->request->getJsonRawBody();

            if (isset($dataRequest->key)) {

                $key = Key::findFirst(array(
                    "conditions" => "key_hash = ?1",
                    "bind" => array(1 => $dataRequest->key),
                ));


                if ($key) {

                    $proveedor = Service::findFirst(array(
                        "conditions" => "id_key = ?1",
                        "bind" => array(1 => $key->id),
                    ));

                    if ($proveedor) {
                        $this->_idProvider = $proveedor->id;
                    } else {
                        $this->_idProvider = null;
                    }

                    return true;

                } else {

                    $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                        "return" => false,
                        "message" => "Key not found",
                        "status" => ControllerBase::FAILED
                    ));

                    return false;
                }

            } else {

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                    "return" => false,
                    "message" => "Key parameter is empty",
                    "status" => ControllerBase::FAILED
                ));

                return false;

            }

        } catch (Exception $e) {

            $this->logError($e, $dataRequest);
        }
    }

    /**
     * Check if key is empty or null from post data recieve
     *
     */
    protected function _validKeyProviderIatai($_key) {

        if (isset($_key)) {

            $key = Key::findFirst(array(
                "conditions" => "key_hash = ?1",
                "bind" => array(1 => $_key),
            ));

            $proveedor = Proveedor::findFirst(array(
                "conditions" => "id_key = ?1",
                "bind" => array(1 => $key->id_key),
            ));

            if ($proveedor) {
                $this->_idProvider = $proveedor->id_proveedor;
            } else {
                $this->_idProvider = null;
            }

            if ($key) {

                return true;

            } else {

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                    "return" => false,
                    "message" => "Key not found",
                    "status" => ControllerBase::FAILED
                ));

                return false;
            }

        } else {

            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                "return" => false,
                "message" => "Key parameter is empty",
                "status" => ControllerBase::FAILED
            ));

            return false;

        }
    }

    /**
     * Check if key is empty or null from post data recieve
     *
     */
    protected function _validKeyProviderGds($method = "POST") {

        if ($method == "POST")
            $dataRequest = $this->request->getJsonPost();
        else
            $dataRequest = $this->request->getJsonRawBody();

        if (isset($dataRequest->gds_key)) {

            $key = KeyGds::findFirst(array(
                "conditions" => "key_hash_gds = ?1",
                "bind" => array(1 => $dataRequest->gds_key),
            ));

            if ($key) {

                return true;

            } else {

                $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                    "return" => false,
                    "message" => "Key not found",
                    "status" => ControllerBase::FAILED
                ));

                return false;
            }

        } else {

            $this->setJsonResponse(ControllerBase::SUCCESS, ControllerBase::FAILED_MESSAGE, array(
                "return" => false,
                "message" => "Key parameter is empty",
                "status" => ControllerBase::FAILED
            ));

            return false;

        }
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getBody() {
        return $this->body;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setTypeDevice($type) {
        $this->typeDevice = $type;
    }

    public function getTypeDevice() {
        return $this->typeDevice;
    }

    public function setProviderId($id) {
        $this->provider_id = $id;
    }

    public function getProviderId() {
        return $this->_idProvider;
    }

    public function removeAccents($cadena)
    {
        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        //$cadena = utf8_encode($cadena);

        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );
    
        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );
    
        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );
    
        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );
    
        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );
    
        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );
    
        return $cadena;
    }

    public function initWeb3()
    {
        return  $this->web3 = new Web3\Web3('http://127.0.0.1:8545/');
    }
}
