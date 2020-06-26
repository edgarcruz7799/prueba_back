<?php

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

if(isset($_POST['usuario'])){

    //print_r($_POST);
    $dbconn = pg_connect("host=logisticappdev.cqf9id21srjq.us-west-2.rds.amazonaws.com port=5432 dbname=postgresbk user=root password=rqFzSDdf4HAq");

    if (!$dbconn) {
        echo "Ocurrió un error1.\n";
    }else{

        $user = $_POST['usuario'];
        $pass = hash('sha256', $_POST['clave']);
        $result = pg_query($dbconn, "SELECT * FROM user_api WHERE email='$user' and password = '$pass' ");
        if (!$result) {
            echo "Ocurrio un error2.\n";
        }else{

            $rows = pg_num_rows($result);

            if($rows == 1){

                while ($row = pg_fetch_row($result)) {

                    $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
                    setcookie("ApiCookie", $token);
                    setcookie("NameCookie", $row[3]);
                    header ("Location: ApiDoc.php");
                }

            }else{
                echo "credenciales invalidas";
            }
        }
    }

    pg_close ($dbconn);
}

?>
<html>
<title>Servicios API</title>
<body>
<div align="center">
    <h1>Servicios API</h1>
    <form action="" method="post">
        Usuario: <input type="text" name="usuario" id="usuario" required="required">
        <br><br>
        Clave: <input type="password" name="clave" id="clave" required="required">
        <br>
        <br>
        <input type="submit" value="Entrar">
    </form>
</div>
</body>
</html>
