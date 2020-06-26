<?php


use \Phalcon\Mvc\Model;

class Preguntas extends Model
{
    /**
     *
     * @var integer
     */
    public $id_pregunta;

    /**
     * Llave foranea
     * @var integer
     */
    public $id_caso;

    /**
     *
     * @var string
     */
    public $respuesta;

    /**
     *
     * @var string
     */
    public $pregunta;

    

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }
}
