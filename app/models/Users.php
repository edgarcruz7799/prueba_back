<?php


use \Phalcon\Mvc\Model;

class Users extends Model
{

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var string
     */
    public $tipodocumento;
    /**
     *
     * @var string
     */
    public $telefonomovil;
     /**
     *
     * @var string
     */
    public $nombres;

    /**
     *
     * @var string
     */
    public $password;
    /**
     *
     * @var string
     */
    public $correo;

    /**
     *
     * @var string
     */
    public $fecha_nacimiento;

    /**
     *
     * @var integer
     * 
     */
    public $rol;

    /**
     *
     * @var string
     */
    public $apellidos;

    /**
     *
     * @var string
     */
    public $numeroidentificacion;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }
}
