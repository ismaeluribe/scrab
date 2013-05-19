<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Correos</title>
    </head>
    <body>
        <form action="correos.php" method="POST">
            <input type="text" placeholder="Correo para mandar" size="20" name="email" />
            <input type="submit" value="Enviar" />
        </form>
        <?php
        if (isset($_POST['email'])) {
            require ("php/commons/class.phpmailer.php");
            $mail = new PHPMailer();
            $mail->SMTPAuth = true;
            $mail->Username = 'proyectoDawIsNi@gmail.com';
            $mail->Password = 'B6<V!ne4';
            $mail->Mailer = "smtp";
            $mail->From = "proyectoDawIsNi@gmail.com";
            $mail->FromName = "Scrab";
            $mail->AddAddress($_POST['email']);
            $mail->Subject = "Descubre Scrab, la nueva red social";
            $mail->Body = "<h1>Descubre la nueva red Social Scrab</h1><br/>Mensaje generado dinamicamente con phpmailer";
            $mail->IsHTML(true);
            $mail->IsSMTP(true);
            $mail->Host = 'ssl://smtp.gmail.com';
            $mail->Port = 465;
            if (!$mail->Send()) {
                echo 'Error: ' . $mail->ErrorInfo;
            } else {
                //Mail enviado
                echo '<div id="correcto">El correo se ha enviado correctamente.</div>';
            }
        }
        ?>
    </body>
</html>