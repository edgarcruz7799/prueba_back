
<?php


use \Phalcon\Mvc\Model;

class Solicitudespqrs extends Model
{

   /**
     *
     
     */
    

    /**
     *
     
     * Lllve foranea
     */
    

    /**
     *
     
     */
    

    /**
     *
     
     */
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        
    }
    // public function searchPqrsWithQuestions($id_caso, $id_user)
    // {
    //     $sql = "SELECT preguntas.id_caso, preguntas.pregunta, preguntas.respuesta FROM pqrs INNER JOIN preguntas on pqrs.id_caso = preguntas.id_caso WHERE pqrs.id_caso = $id_caso AND pqrs.id_user = $id_user";
    //     $prepare = $this->getDi()->getShared("db")->prepare($sql);
    //     $prepare->execute();
    //     return $prepare;
    // }
}
