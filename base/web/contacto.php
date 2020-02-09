<?php
require_once 'common.php';
################################################################################
/**
 * Esta funci�n es para enviar mails de contacto al representante del sitio
 *
 * Adem�s se puede agregar mails para enviar por BCC. Para m�s detalles, ver en
 * la wiki o la clase InnyMailer
 */
function enviarEmail(){

    # Creo la instancia de Smarty, para obtener el HTML del email
    $smartyEmail = new Smarty();

    # Creo la instancia de InnyMailer, con la cual enviar� el email
    $mailer = new InnyMailer();

    # El HTML correspondiente al email lo obtengo de un template
    #
    # De ahora en m�s el cuerpo de un email tendr� que estar en un template.
    # El motivo es sencillo: es m�s c�modo para maquetar y se sigue un m�todo
    # m�s prolijo.
    #
    # ATENCION: el html de los emails es m�s limitado al de una p�gina web com�n.
    # Para m�s info: http://www.email-standards.org/why/
    $mensaje = '
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
        Maecenas ut purus. Aenean arcu. Etiam in purus pretium sapien viverra bibendum.
        Fusce blandit pretium enim. Aenean blandit tortor non justo.';
    $smartyEmail->assign('mensaje',$mensaje);

    # Seteo los par�metros para el env�o de mails
    $nombre = 'Pepe Cibri�n';
    $email = 'pepe@mail.com';
    $asunto = 'El asunto de la consulta de pepe';

    # El m�todo fetch de Smarty permite asignar a una variable el contenido de
    # un template. Tal contenido ser� el cuerpo del email
    $cuerpo = $smartyEmail->fetch('email_contacto.tpl');

    # Por �ltimo env�o el email.
    # Luego redirijo para evitar mantener variables en POST
    try{
        @$mailer->send($nombre,$email,$asunto,$cuerpo);
    }catch(Exception $e){}
    Denko::redirect('contacto.php?send=ok');
}
################################################################################