<?php

/**
 *
 */
class ServiceDescription
{

    static public function sendPost ($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $serverOutput = curl_exec ($ch);
        curl_close ($ch);

        return $serverOutput;
    }

    public function MethodsDescription($controller){

        $description  = array (
            "Discount" => "Bonos de descuento",
            "Chat" => "Chat entre recurso logistico y cliente",
            "Client" => "Acciones del cliente",
            "Deliveryverification" => "Historial y verificaciones de solicitudes y push",
            "Diligence" => "Servicios de diligencia",
            "Logisticresource" => "Servicios del recurso logistico",
            "Pqrs" => "Peticiones, quejas, reclamos, y servicios",
            "Rating" => "Calificacion del servicio y el recurso logistico",
            "Shipping" => "Servicios de envios, paquetes y pasajeros",
            "Sos" => "Alertas de panico",
            "Virtualreality" => "Realidad virtual",
            "General" => "Servicios varios",
            "Atm" => "Servicios cajero",
            "Payment" => "Servicios Pagos",
            "Subsidiary" => "Servicios de sucursales/restaurantes",
            "Category" => "Servicios de menus de los restaurantes",
            "Productaddition" => "Servicios de productos adicionales",
            "Deliveryorder" => "Servicios de ordenes",
            "Route" => "Servicios para el manejo y listado de rutas (Domesa)",
            "Paymentgateway" => "Servicios pasarela de pagos",
            "Product" => "Servicios paral os productos de los restaurantes"
        );

        if(isset($description[$controller]))
            return $description[$controller];
        else
            return "N/A";

    }

    public function ActionsDescription($controller,$nombre_método){

        $description =array();

        $description['Discount']['post']= "Registrar bono de descuento";

        $description['Chat']['history']= "Historial del chat entre cliente y recurso logistico";
        $description['Chat']['post']= "Envio de mensaje de chat entre cliente y recurso logistico";

        $description['Client']['addresseeinfo']= "Envio de datos personales de un cliente registrado";
        $description['Client']['edit']= "Editar datos de un cliente";
        $description['Client']['forgotpassword']= "Recuperacion de contrasena a traves del correo electronico registrado.";
        $description['Client']['login']= "Login del usuario";
        $description['Client']['register']= "Registro de usuario";
        $description['Client']['getShipping']= "Solicitudes sin finalizar";
        $description['Client']['changepassword']= "Cambiar contraseña";
        $description['Client']['auth']= "Metodo unificado para el login y password";
        $description['Client']['authFacebook']= "Login/registro por red social Facebook cuando es la primera vez";
        $description['Client']['authFacebook']= "Login/registro por red social Facebook cuando es la primera vez";
        $description['Client']['listAddress']= "Listado de direcciones favoritas del cliente";
        $description['Client']['saveAddress']= "Guardar las direcciones favoritas del cliente";

        $description['Deliveryverification']['getHistory']= "Historial de solicitudes para clientes y recursos logisticos";
        $description['Deliveryverification']['getShipping']= "Detalle de la solicitud";
        $description['Deliveryverification']['history']= "Historial de solicitudes para el recurso logistico (Viejo)";
        $description['Deliveryverification']['post']= "Verificacion de recogido, entrega y cancelacion (Viejo)";
        $description['Deliveryverification']['push']= "Verificacion de recibido el push";
        $description['Deliveryverification']['tracking']= "Activar o no el envio de push a las solicitudes";

        $description['Diligence']['diligencespoints']= "Listado de paradas de una diligencia";
        $description['Diligence']['diligencesUpdatePoints']= "Actualizar visita a una parada de la diligencia";
        $description['Diligence']['post']= "Registrar una diligencia";
        $description['Diligence']['quotation']= "Cotizar una diligencia";

        $description['Logisticresource']['arrival']= "Envio de push al cliente indicando la llegada del recurso logistico";
        $description['Logisticresource']['cancelservice']= "Envio de correo alertando al administrador del sistema de una cancelacion";
        $description['Logisticresource']['getLocation']= "Ubicacion actual del recurso logistico";
        $description['Logisticresource']['getNearRequest']= "Solicitudes sin asignar en un radio cercano";
        $description['Logisticresource']['getService']= "Tomar una solicitud disponible";
        $description['Logisticresource']['modifySession']= "Cambiar estado del recurso logistico";
        $description['Logisticresource']['session']= "Inicio de sesion del recurso logistico";
        $description['Logisticresource']['updateLocation']= "Actualizacion de la ubicacion del recurso logistico";
        $description['Logisticresource']['getShipping']= "Solicitudes pendientes por finalizar del mensajero";
        $description['Logisticresource']['edit']= "Edición de datos del recurso logístico";

        $description['Pqrs']['post']= "Registrar PQRS";

        $description['Rating']['post']= "Registrar calificacion de la solicitud y el recurso logistico";

        $description['Shipping']['cancel']= "Cancelacion de solicitud desde el cliente o el recurso logistico";
        $description['Shipping']['codeConfirm']= "Confirmacion del codigo de seguridad de la solicitud";
        $description['Shipping']['end']= "Finalizar la solicitud";
        $description['Shipping'][' history']= "Historial de solicitudes para cliente (viejo)";
        $description['Shipping']['post']= "Registrar cualquier tipo de solicitud excepto diligencias";
        $description['Shipping']['quotation']= "Cotizar cualquier tipo de solicitud excepto diligencias";

        $description['Sos']['post']= "Registrar alertas de panico del cliente y recurso logistico";

        $description['Virtualreality']['getList']= "Listado de los videos de realidad virtual disponibles";

        $description['General']['contract']= "Impresión del contrato entre el cliente y conductor";
        $description['General']['sendMailContact']= "Registro de los diferentes formuarios de contactenos.";
        $description['General']['initialSetup']= "Configuraciones iniciales.";

        $description['Atm']['quotation']= "Cotizador servicios de cajero";
        $description['Atm']['post']= "Registrar solicitudes de cajero";

        $description['Bill']['quotation']= "Cotizador servicios de pagos";
        $description['Bill']['post']= "Registrar solicitudes de pagos";
        $description['Bill']['company']= "Listado de empresas segun el tipo de servicio de pagos";

        $description['Subsidiary']['listDeliveries']= "Lista de restaurantes/farmacias/mercados - Para Cuoco lista los platos de todosl os restaurantes";

        $description['Category']['listmenus']= "Lista de menus";

        $description['Productaddition']['listAdditionalProduct']= "Lista de adicionales";

        $description['Deliveryorder']['post']= "Registrar el pedido del cliente";

        $description['Route']['unfinished']= "Listado de los puntos de la ruta del dia ordenado";

        $description['Paymentgateway']['post']= "Registrar pago por pasarela";
        $description['Paymentgateway']['tokenize']= "Tokenizar tarjeta";

        $description['Product']['post']= "Creación de productos";
        $description['Product']['additionalsToProduct']= "Relacionar los adicionales de cada producto";

        if(isset($description[$controller][$nombre_método]))
            return $description[$controller][$nombre_método];
        else
            return "N/A";

    }

    public function FieldsDescription($controller,$nombre_método,$field){

        $description =array();

        $description['Discount']['post']['amount']= "Costo del envío (aplica solo para web)";
        $description['Discount']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Discount']['post']['shipping_id']= "Id de la solicitud";
        $description['Discount']['post']['bond']= "código de descuento";
        $description['Discount']['post']['platform']= "web,android,ios (Valido solo para web)";
        $description['Discount']['post']['client_id']= "Id del usuario";

        $description['Chat']['history']['key']= "Llave acceso para identificar proveedor";
        $description['Chat']['history']['shipping_id']= "Id de la solicitud";

        $description['Chat']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Chat']['post']['shipping_id']= "Id de la solicitud";
        $description['Chat']['post']['sentby_id']= "Id del usuario que envia el mensaje";
        $description['Chat']['post']['sentby_type']= "Quien envia el mensaje 1:cliente 0:recurso logistico";
        $description['Chat']['post']['message']= "mensaje a enviar";

        $description['Client']['edit']['key']= "Llave acceso para identificar proveedor";
        $description['Client']['edit']['email']= "Correo electronico del usuario";
        $description['Client']['edit']['client_id']= "Id del usuario";
        $description['Client']['edit']['name']= "Nombres y apellidos del usuario";
        $description['Client']['edit']['pass']= "Access token si se registro por redes sociales o contraseña en sha256 si fue por registro normal";
        $description['Client']['edit']['pass_new']= "Solo aplica en caso de que se desee actualizar la contraseña (Se debe enviar en sha256)";
        $description['Client']['edit']['movil_phone']= "Teléfono celular del usuario";
        $description['Client']['edit']['local_phone']= "Teléfono fijo del usuario";
        $description['Client']['edit']['identify']= "Número de identificación";
        $description['Client']['edit']['action']= "Si hace cambio de plataforma se envia 'updateSession' de lo contrario no se envia";

        $description['Client']['forgotpassword']['key']= "Llave acceso para identificar proveedor";
        $description['Client']['forgotpassword']['email']= "Correo electronico del usuario";

        $description['Client']['getShipping']['key']= "Llave acceso para identificar proveedor";
        $description['Client']['getShipping']['clientId']= "Id del usuario";

        $description['Client']['changepassword']['key']= "Llave acceso para identificar proveedor";
        $description['Client']['changepassword']['token']= "Token enviado al correo electronico para cambio der contraseña";
        $description['Client']['changepassword']['pass']= "Nuevo password para asignar al usuario";

        $description['Client']['auth']['key']= "Llave acceso para identificar proveedor";
        $description['Client']['auth']['email']= "Correo electronico del usuario";
        $description['Client']['auth']['pass']= "Access token si se registro por redes sociales o contraseña en sha256 si fue por registro normal";
        $description['Client']['auth']['name']= "Nombres y apellidos del usuario";
        $description['Client']['auth']['platform']= "web,adroid,ios";
        $description['Client']['auth']['uuid']= "identificador unico cuando se ingresa por un celular";
        $description['Client']['auth']['localphone']= "Teléfono fijo del usuario";
        $description['Client']['auth']['cellphone']= "Teléfono celular del usuario";
        $description['Client']['auth']['identify']= "Número de identificación";

        $description['Client']['authFacebook']['key']= "Llave acceso para identificar proveedor";
        $description['Client']['authFacebook']['uuid']= "identificador unico cuando se ingresa por un celular";
        $description['Client']['authFacebook']['pass']= "Access token de la red social facebook";
        $description['Client']['authFacebook']['platform']= "web,adroid,ios";
        $description['Client']['authFacebook']['email']= "Correo electronico del usuario";

        $description['Deliveryverification']['getHistory']['key']= "Llave acceso para identificar proveedor";
        $description['Deliveryverification']['getHistory']['clientId']= "Id del usuario";
        $description['Deliveryverification']['getHistory']['logisticId']= "Id del recurso logístico";
        $description['Deliveryverification']['getHistory']['type']= "tipo de solicitudes a mostrar (1:nomrales, 2:corporativas)";
        $description['Deliveryverification']['getHistory']['shippingId']= "Id de la solicitud";

        $description['Deliveryverification']['getShipping']['key']= "Llave acceso para identificar proveedor";
        $description['Deliveryverification']['getShipping']['shippingId']= "Id de la solicitud";

        $description['Deliveryverification']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Deliveryverification']['post']['description']= "Descripción del envío";
        $description['Deliveryverification']['post']['image']= "Url de la foto de verificacion de recogida/entrega";
        $description['Deliveryverification']['post']['shipping_id']= "Id de la solicitud";
        $description['Deliveryverification']['post']['logisticresource_id']= "Id del recurso logístico";
        $description['Deliveryverification']['post']['status']= "Estado del envío (2:Recogido,3:Entregado,4:Cancelado)";

        $description['Deliveryverification']['tracking']['key']= "Llave acceso para identificar proveedor";
        $description['Deliveryverification']['tracking']['shippingId']= "Id de la solicitud";
        $description['Deliveryverification']['tracking']['action']= "Apagar o inciar el envio de push (start,stop)";

        $description['Deliveryverification']['push']['key']= "Llave acceso para identificar proveedor";
        $description['Deliveryverification']['push']['shipping_id']= "Id de la solicitud";

        $description['Logisticresource']['arrival']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['arrival']['shipping_id']= "Id de la solicitud";

        $description['Logisticresource']['getLocation']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['getLocation']['logisticresource_id']= "Id del recurso logístico";

        $description['Logisticresource']['getNearRequest']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['getNearRequest']['radio']= "radio de busqueda de las solicitudes";
        $description['Logisticresource']['getNearRequest']['quantity']= "Cantidad de solicitudes a mostrar";
        $description['Logisticresource']['getNearRequest']['latitude']= "Latitud del recurso logístico";
        $description['Logisticresource']['getNearRequest']['longitude']= "Longitud del recurso logístico";
        $description['Logisticresource']['getNearRequest']['logisticId']= "Id del recurso logístico";

        $description['Logisticresource']['getService']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['getService']['shipping_id']= "Id de la solicitud";
        $description['Logisticresource']['getService']['logistic_id']= "Id del recurso logístico";
        $description['Logisticresource']['getService']['latitude']= "Latitud del recurso logístico";
        $description['Logisticresource']['getService']['longitude']= "Longitud del recurso logístico";

        $description['Logisticresource']['modifySession']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['modifySession']['remote_id']= "Id del recurso logístico";
        $description['Logisticresource']['modifySession']['conect']= "Estado del recurso (0:No conectado,1:Conectado,2:En modo manual)";

        $description['Logisticresource']['session']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['session']['usr']= "Usuario del recurso logístico";
        $description['Logisticresource']['session']['pass']= "Contraseña del recurso (codificada en sha256)";
        $description['Logisticresource']['session']['uuid']= "identificador unico cuando se ingresa por un celular";

        $description['Logisticresource']['updateLocation']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['updateLocation']['logisticresource_id']= "Id del recurso logístico";
        $description['Logisticresource']['updateLocation']['latitude']= "Latitud del recurso logístico";
        $description['Logisticresource']['updateLocation']['longitude']= "Longitud del recurso logístico";

        $description['Logisticresource']['getShipping']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['getShipping']['logisticId']= "Id del recurso logístico";

        $description['Logisticresource']['edit']['key']= "Llave acceso para identificar proveedor";
        $description['Logisticresource']['edit']['logisticId']= "Id del recurso logístico";
        $description['Logisticresource']['edit']['uuid']= "identificador unico cuando se ingresa por un celular";
        $description['Logisticresource']['edit']['platform']= "web,adroid,ios";

        $description['Pqrs']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Pqrs']['post']['email']= "Correo del usuario";
        $description['Pqrs']['post']['comment']= "Comentario del usuario";
        $description['Pqrs']['post']['clientId']= "Id del usuario";

        $description['Rating']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Rating']['post']['description']= "Descripción de la calificación";
        $description['Rating']['post']['employee_id']= "Id del empleado";
        $description['Rating']['post']['rating']= "Calificación";
        $description['Rating']['post']['shipping_id']= "Id de la solicitud";

        $description['Shipping']['cancel']['key']= "Llave acceso para identificar proveedor";
        $description['Shipping']['cancel']['comment']= "Comentario de la cancelación";
        $description['Shipping']['cancel']['shipping_id']= "Id de la solicitud";
        $description['Shipping']['cancel']['reason_id']= "Motivo de la cancelación (1:Avería, 2:Pasajero no esta,3:Otro)";
        $description['Shipping']['cancel']['logistic_id']= "Id del recurso logístico";

        $description['Shipping']['codeConfirm']['key']= "Llave acceso para identificar proveedor";
        $description['Shipping']['codeConfirm']['shipping_id']= "Id de la solicitud";
        $description['Shipping']['codeConfirm']['code']= "Código de seguridad";

        $description['Shipping']['end']['key']= "Llave acceso para identificar proveedor";
        $description['Shipping']['end']['shipping_id']= "Id de la solicitud";

        $description['Shipping']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['amount_declared']= "Valor declarado del servicio";
        $description['Shipping']['post']['distance']= "Distancia de la solicitud";
        $description['Shipping']['post']['number_pieces']= "Número de piezas";
        $description['Shipping']['post']['origin_client']= "Id del cliente";
        $description['Shipping']['post']['description_text']= "Descripción del envío";
        $description['Shipping']['post']['origin_address']= "Dirección de origen";
        $description['Shipping']['post']['platform']= "web,android,ios";
        $description['Shipping']['post']['origin_latitude']= "Latitud de origen";
        $description['Shipping']['post']['width']= "Ancho del paquete";
        $description['Shipping']['post']['origin_longitude']= "Longitud de origen";
        $description['Shipping']['post']['height']= "Alto del paquete";
        $description['Shipping']['post']['destiny_address']= "Dirección de destino";
        $description['Shipping']['post']['long']= "Largo del paquete";
        $description['Shipping']['post']['destiny_latitude']= "Latitud de destino";
        $description['Shipping']['post']['weight']= "Peso de paquete";
        $description['Shipping']['post']['destiny_longitude']= "Longitud de destino";
        $description['Shipping']['post']['destiny_client']= "Id del cliente destino";
        $description['Shipping']['post']['amount']= "Costo del envío";
        $description['Shipping']['post']['destiny_name']= "Nombre del destinatario";
        $description['Shipping']['post']['type_service']= "Id del servicio (Documentos,paquetes,etc)";
        $description['Shipping']['post']['picture']= "Url de la foto";
        $description['Shipping']['post']['pay']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['content_pack']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['time']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['cellphone_destiny_client']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['email_destiny_client']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['bag_id']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['origin_detail']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['destiny_detail']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['tip']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['polyline']= "Llave acceso para identificar proveedor";
        $description['Shipping']['post']['code_confirm']= "Llave acceso para identificar proveedor";

        $description['Shipping']['quotation']['key']= "Llave acceso para identificar proveedor";

        $description['Subsidiary']['listDeliveries']['key']= "Llave acceso para identificar proveedor";
        $description['Subsidiary']['listDeliveries']['latitude']= "Latitud de la ubicación del cliente";
        $description['Subsidiary']['listDeliveries']['longitude']= "Longitud de la ubicación del cliente";
        $description['Subsidiary']['listDeliveries']['type']= "Tipo en el que esta categorizado el domicilio ej: Restaurantes,Farmacias,etc. (se obtiene del servicio login)";
        $description['Subsidiary']['listDeliveries']['code']= "Código segun tipo de domicilio. (se obtiene del servicio login)";

        $description['Route']['unfinished']['key']= "Llave acceso para identificar proveedor";
        $description['Route']['unfinished']['resource_id']= "Id del recurso logístico";
        $description['Route']['unfinished']['date']= "Fecha para mostrar las solicitudes solo del día";

        $description['Paymentgateway']['tokenize']['key']= "Llave acceso para identificar proveedor";
        $description['Paymentgateway']['tokenize']['default']= "Seleccionar tarjeta como principal. (true - false)";

        $description['Product']['post']['key']= "Llave acceso para identificar proveedor";
        $description['Product']['post']['name']= "Nombre del producto";
        $description['Product']['post']['description']= "Descripción del producto";
        $description['Product']['post']['price']= "Precio del producto (número)";
        $description['Product']['post']['subsidiary_id']= "Id de la sucursal del restaurante (se devuelve en el login)";
        $description['Product']['post']['category_id']= "Id de la categoría a la que pertenece el producto";
        $description['Product']['post']['state']= "Se envia true para dejarlo activo, si no se envia se registra como inactivo";
        $description['Product']['post']['image']= "Imagen del producto";



        if(isset($description[$controller][$nombre_método][$field]))
            return $description[$controller][$nombre_método][$field];
        else
            return "N/A";

    }
}
