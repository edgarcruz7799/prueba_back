<?php

use \Phalcon\Mvc\Model;

class Client extends Model
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
    public $name;
    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $document_client;

    /**
     *
     * @var integer
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $update_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

    public function searchCity(){


        $sql = "
               SELECT DISTINCT city.city , city.id  FROM city ORDER BY id LIMIT 1121";
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();
        return $prepare;
    }

}
