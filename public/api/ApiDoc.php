<?php
/**
 * Created by PhpStorm.
 * User: jhon
 * Date: 22/10/16
 * Time: 04:13 PM
 */

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

if(isset($_POST['cok'])){

    unset($_COOKIE['ApiCookie']);
    unset($_COOKIE['NameCookie']);
    setcookie('ApiCookie', $_COOKIE['ApiCookie'], -1);
    setcookie('NameCookie', $_COOKIE['NameCookie'], -1);

    header ("Location: index.php");
}

if(isset($_COOKIE['ApiCookie'])){
 goto Continuar;
}else{
    header ("Location: index.php");
}

Continuar:

include_once '../../app/controllers/ControllerBase.php';
include_once '../../app/library/OsrmProject.php';
include_once 'Servicedescription.php';

$directorio = '/var/www/html/api_devel/app/controllers';// obtenemos todos los archivos controladores
$ficheros  = scandir($directorio);
//$host = "localhost";
$host = "52.43.247.174";

echo "<b>Bienvenido:</b> ".$_COOKIE['NameCookie']."<br><br>";
?>

<form name="formcok" method="post" action="">
    <input type="hidden" name="cok" id="cok" value="1">
    <input type="submit" value="Cerrar Sesion">
</form>
<html>
<title>Servicios</title>

<style type="text/css">

     p {color: black;}

     A:link {text-decoration:none;color:#0000cc;}

     A:visited {text-decoration:none;color:#ffcc33;}

     A:active {text-decoration:none;color:#ff0000;}

     A:hover {text-decoration:underline;color:#999999;}

     #sons {color: #ff8000; padding-left:25px}


</style>
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script>

    function realizaProceso(nameform,url) {

        $.ajax({
            data:  $("#"+nameform).serialize(),
            url:   url,
            type:  'post',
            beforeSend: function () {
                $("#resultado"+nameform).html("Procesando, espere por favor...");
            },
            success:  function (response) {

                $("#resultado"+nameform).html(JSON.stringify(response));
            },
            error: function(response) {

                $("#resultado"+nameform).html(JSON.stringify(response));
            }
        });

    }

    function habilitar(field,formu) {

        var elElemento = document.forms[formu][field];
        //var elElemento = document.forms.getElementById(field);

        //alert(field)
        if (elElemento.disabled == true) {
            elElemento.disabled = false;
        } else if(elElemento.disabled == false) {
            elElemento.disabled = true;
        }
    }

</script>

<body>
<h1>Servicios Logisticapp</h1>

<?php

for($i=0; $i<count($ficheros); $i++){

    if($i>1){

        if($ficheros[$i] != 'ControllerBase.php' &&
            $ficheros[$i] != 'IndexController.php' && $ficheros[$i] != 'ProveedorController.php'
            && $ficheros[$i] != 'ServiceController.php' && $ficheros[$i] != 'StatusController.php'
            && $ficheros[$i] != 'StoresController.php' && $ficheros[$i] != 'TestController.php'
            && $ficheros[$i] != 'TokenController.php' && $ficheros[$i] != 'Servicedescription.php'){

           //error_reporting(0);

            include_once '../../app/controllers/'.$ficheros[$i];//incluimos cada archivo

            $controller = str_replace("Controller.php", "", $ficheros[$i]);
            $controllerName = str_replace(".php", "", $ficheros[$i]);

            $data = new ServiceDescription();
            $description = $data->MethodsDescription($controller);

            if($description != "N/A"){

                ?>

                <script>

                    function show(cual) {

                        var elElemento = document.getElementById(cual);

                        var hiden = document.getElementsByClassName('controller');
                        i = hiden.length;

                        while(i--) {
                            hiden[i].style.display = 'none';
                        }

                        if (elElemento.style.display == 'block') {
                            elElemento.style.display = 'none';
                        } else if(elElemento.style.display == 'none') {
                            elElemento.style.display = 'block';
                        }
                    }

                    function showSon(cual) {

                        var elElemento = document.getElementById(cual);

                        var hiden = document.getElementsByClassName('controllerMetodo');
                        i = hiden.length;

                        while(i--) {
                            hiden[i].style.display = 'none';
                        }

                        if (elElemento.style.display == 'block') {
                            elElemento.style.display = 'none';
                        } else if(elElemento.style.display == 'none') {
                            elElemento.style.display = 'block';
                        }
                    }

                </script>

                <p id="title"><a href='javascript:void(0);' onclick="show('<?php echo $controller ?>')"
                                 id='<?php echo $controllerName ?>'><?php echo $controller ?></a>

                    <?php

                    echo " ===> $description<br></p>";

            }

            $métodos_clase = get_class_methods(new $controllerName());//obtenemos las funciones de cada clase del controlador

            echo "<div id='$controller' class='controller' style='display: none'>";

            foreach ($métodos_clase as $nombre_método) {

                if($nombre_método == "initialize")
                    break;

                $nombre_método = str_replace("Action", "", $nombre_método);

                $description = $data->ActionsDescription($controller,$nombre_método);

                if($description != "N/A"){

                    ?>
                        <p><a id='sons' href='javascript:void(0);' onclick="showSon('<?php echo "Son".$controller.$nombre_método ?>')"><?= $nombre_método ?></a>

                    <?php

                    echo "===> $description --- Url: http://$host/api_devel/$controller/$nombre_método<br></p>";

                    $result = json_decode(file_get_contents('http://'.$host.'/api_devel/'.$controller.'/'.$nombre_método), true);

                    $total = 0 + count($result['messages']['This parameters are wrong']);//parametros obligatorios

                    $totalOpt = 0 + count($result['optional_fields']);//parametros opcionales

                    echo "<div id='Son$controller$nombre_método' class='controllerMetodo' style='display: none'>
                    <form name='$controller$nombre_método' id='$controller$nombre_método' action='' method='post'>";

                    echo "<table border='1'>
                            <tr>
                                <th>Habilitar</th>
                                <th>Nombre</th>
                                <th>Campo</th>
                                <th>Obligatorio</th>
                                <th>Descripción</th>
                            </tr>";

                    if($total >= $totalOpt){

                        for($y=0; $y < $total; $y++){

                            $name = $result['messages']['This parameters are wrong'][$y];

                            if(isset($result['optiona_fields'][$y])){

                                $nameOpt = $result['optiona_fields'][$y];
                                echo "<tr>";
                                echo "<td><input type='checkbox' onclick=habilitar('$nameOpt','$controller$nombre_método')></td>";
                                echo "<td>".$nameOpt."</td>";
                                echo "<td><input type='text' name='$nameOpt' id='$nameOpt' disabled='disabled'></td>";
                                echo "<td>No</td>";
                                echo "<td>".$data->FieldsDescription($controller,$nombre_método,$nameOpt)."</td>";
                                echo "</tr>";
                            }

                            echo "<tr>";
                            echo "<td></td>";
                            echo "<td>".$name."</td>";
                            echo "<td><input type='text' name='$name' id='$name' required='required'></td>";
                            echo "<td>Si</td>";
                            echo "<td>".$data->FieldsDescription($controller,$nombre_método,$name)."</td>";
                            echo "</tr>";

                        }
                    }

                    if($totalOpt > $total){

                        for($y=0; $y < $totalOpt; $y++){

                            $nameOpt = $result['optional_fields'][$y];

                            if(isset($result['messages']['This parameters are wrong'][$y])){

                                $name = $result['messages']['This parameters are wrong'][$y];
                                echo "<tr>";
                                echo "<td></td>";
                                echo "<td>".$name."</td>";
                                echo "<td><input type='text' name='$name' id='$name' required='required'></td>";
                                echo "<td>Si</td>";
                                echo "<td>".$data->FieldsDescription($controller,$nombre_método,$name)."</td>";
                                echo "</tr>";
                            }

                            echo "<tr>";
                            echo "<td><input type='checkbox' onclick=habilitar('$nameOpt','$controller$nombre_método')></td>";
                            echo "<td>".$nameOpt."</td>";
                            echo "<td><input type='text' name='$nameOpt' id='$nameOpt' disabled='disabled' ></td>";
                            echo "<td>No</td>";
                            echo "<td>".$data->FieldsDescription($controller,$nombre_método,$nameOpt)."</td>";
                            echo "</tr>";

                        }
                    }
                    ?>

                    <tr><td colspan='4' align='center'>
                            <input type='button' onclick="realizaProceso('<?= $controller.$nombre_método ?>','<?= 'http://'.$host.'/api_devel/'.$controller.'/'.$nombre_método ?>');" value='Enviar'>
                        </td></tr>

                    <?php

                    echo "<tr><td id='resultado$controller$nombre_método' colspan='4' align='center'>Sin consulta</td></tr>";

                    echo "</table>";

                    echo "</div></form>";

                }
            }

            echo "</div>";

        }
    }
}

?>
</body>
</html>