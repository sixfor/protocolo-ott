<?php
  error_reporting(E_ALL);

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require_once 'ack/ack.php';

  require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require 'vendor/phpmailer/phpmailer/src/Exception.php';
  require 'vendor/phpmailer/phpmailer/src/SMTP.php';


?>
<!DOCTYPE html>
<html>
  <head>
    <style>
      table {
        font-family: 'Arial';
      }
      th {
        height: 100px;
        background-color: #00b594;
        width: 100%;
      }
      h1 {
        font-size: 22px;
        margin: 0;
        color: white;
        padding: 20px 10px 20px 10px;
      }
      h3 {
        font-size: 16px;
        font-weight: 100;
      }
      h4 {
        font-size: 15px;
      }
      p {
        font-size: 13px;
      }
      a {
        color: #00b594;
      }
      small {
        font-size: 12px;
      }
    </style>
  </head>
  <body>
    <pre>
      <?php
      $mail = new PHPMailer(true);
      try {
        $mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Host = HOST_EMAIL;
        $mail->SMTPAuth = true;
        $mail->Username = SERVER_EMAIL;
        $mail->Password = SERVER_EMAIL_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );
        $mail->setFrom('a3nh46kd2@ott.cl', 'Ventanilla OTT');
        $mail->addAddress('kyllzoldyck@gmail.com');
        $mail->isHTML(true);

        $mail->Subject = 'asdas das ear contraseña - Ventanilla OTT';
        $body = 'Pruebaa asda d';
        $mail->Body = $body;

        if ($mail->send()) {
          echo "Enviado";
        }
        else {
          print_r($mail->ErrorInfo);
        }
      }
      catch (Exception $e) {
        echo 'Message could not be sent.' , $e;
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      }


      ?>
    </pre>
    <table>
      <thead>
        <th><h1>Bienvenido a la Ventanilla Virtual</th>
      </thead>
      <tbody>
        <tr>
          <td><h3>Te damos la bievenida a la Ventanilla Virtual #Nombre.</h3></td>
        </tr>
        <tr>
          <td>
            <p>
              La Oficina de Transferencia Tecnol&oacute;gica le da la bienvenida y le informa que para
              comenzar a utilizar la ventanilla virtual necesitar&aacute; <a href="?email=&?token=">crear su contrase&ntilde;a.</a>
            </p>
          </td>
        </tr>
        <tr>
          <td>
            <p>Haga click en el siguiente enlace para crear su contrase&ntilde;a:</p>
            <a href="?email=&?token=">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.</a>
          </td>
        </tr>
        <tr>
          <td>
            <br>
            <small>Si ha recibido este correo por error, por favor ign&oacute;relo y b&oacute;rrelo de su bandeja de entrada. Lo sentimos.</small>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
