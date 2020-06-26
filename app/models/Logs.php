<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Logs extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $params;

    /**
     *
     * @var string
     */
    public $platform;

    /**
     *
     * @var string
     */
    public $file;


     /**
     *
     * @var string
     */
    public $line;

     /**
     *
     * @var string
     */
    public $message;


    /**
     *
     * @var string
     */
    public $trace;

    /**
     *
     * @var string
     */
    public $register_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }




}
