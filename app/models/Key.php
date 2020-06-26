<?php

use Phalcon\Mvc\Model;

/**
 * Types of Products
 */
class Key extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $key_hash;

    /**
     * @var string
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
       
    }

}