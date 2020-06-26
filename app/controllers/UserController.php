<?php

class UserController extends ControllerBase
{
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

                $access_t = $dataRequest->access_type;

                $user = User::findFirst(array(
                    "conditions" => "correo = ?1",
                    "bind" => array(1 => $dataRequest->correo)
                ));

                if (!isset($user->id_user) && $access_t == ControllerBase::ROL_CLIENTE) {

                    $user = new User();

                    $user->tipoDocumento = (string) $dataRequest->tipoDocumento;
                    $user->password = (string) $dataRequest->password;
                    $user->correo = (string) $dataRequest->correo;
                    $user->fecha_nacimiento = (string) $dataRequest->fecha_nacimiento;
                    $user->telefonoMovil = (string) $dataRequest->telefonoMovil;
                    $user->apellidos = (string) $dataRequest->apellidos;
                    $user->nombres = (string) $dataRequest->nombres;
                    $user->numeroIdentificacion = (string) $dataRequest->numeroIdentificacion;
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
}
