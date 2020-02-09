<?php
require_once 'common.php';
################################################################################
/**
 * Esta función es para enviar mails de contacto al representante del sitio
 *
 * Además se puede agregar mails para enviar por BCC. Para más detalles, ver en
 * la wiki o la clase InnyMailer
 */
function enviarEmail(){

    # Creo la instancia de Smarty, para obtener el HTML del email
    $smartyEmail = new Smarty();

    # Creo la instancia de InnyMailer, con la cual enviaré el email
    $mailer = new InnyMailer();

    # El HTML correspondiente al email lo obtengo de un template
    #
    # De ahora en más el cuerpo de un email tendrá que estar en un template.
    # El motivo es sencillo: es más cómodo para maquetar y se sigue un método
    # más prolijo.
    #
    # ATENCION: el html de los emails es más limitado al de una página web común.
    # Para más info: http://www.email-standards.org/why/
    $mensaje = '
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
        Maecenas ut purus. Aenean arcu. Etiam in purus pretium sapien viverra bibendum.
        Fusce blandit pretium enim. Aenean blandit tortor non justo.';
    $smartyEmail->assign('mensaje',$mensaje);

    # Seteo los parámetros para el envío de mails
    $nombre = 'Pepe Cibrián';
    $email = 'pepe@mail.com';
    $asunto = 'El asunto de la consulta de pepe';

    # El método fetch de Smarty permite asignar a una variable el contenido de
    # un template. Tal contenido será el cuerpo del email
    $cuerpo = $smartyEmail->fetch('email_contacto.tpl');

    # Por último envío el email.
    # Luego redirijo para evitar mantener variables en POST
    try{
        @$mailer->send($nombre,$email,$asunto,$cuerpo);
    }catch(Exception $e){}
    Denko::redirect('contacto.php?send=ok');
}
################################################################################