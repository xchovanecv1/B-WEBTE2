<?php
// https://github.com/PHPMailer/PHPMailer
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require_once 'Page.php';
$updareArray = array();
//SELECT a.mail as "mail", b.Name as "name", c.URL as "url", d.text as "newsletter", a.type as "typeMail" FROM mailer as a JOIN users as b on a.user = b.ID JOIN pass_gen as c ON b.ID = c.ID LEFT JOIN newsletter as d on a.user=d.id WHERE a.sent = 0 LIMIT 5

if ($result = $mysqli->query("SELECT * FROM `mailer` WHERE NOT sent=1 LIMIT ".SMTP_LIMIT_ONE_RUN.";")) {

    while($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $updareArray[$row['ID']] = $row;
    }
    /* free result set */
    $result->close();
}

foreach($updareArray as $key => $value){
    $mail = new PHPMailer(true);        // Passing `true` enables exceptions
    try {
        //Server settings
        //$mail->SMTPDebug = 2;         // Enable verbose debug output
        $mail->isSMTP();                // Set mailer to use SMTP
        $mail->Host = SMTP_HOST;  // Specify SMTP servers
        $mail->SMTPAuth = true;         // Enable SMTP authentication
        $mail->Username = SMTP_USER;    // SMTP username
        $mail->Password = SMTP_PASSWORD;    // SMTP password
        $mail->SMTPSecure = 'ssl';      // Enable TLS encryption, `ssl` also accepted
        $mail->Port = SMTP_PORT;               // TCP port to connect to
        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom(SMTP_FROM, 'alphaBravo');

       /* echo $i."<br> ";
        echo $updareArray[$i][0] ."<br> ";
        echo $updareArray[$i][1]."<br> ";
        echo $updareArray[$i][2]."<br> ";*/
        $mail->addAddress($value['mail'], 'user');
        //$mail->addAddress("mrchovanec@gmail.com");

        if($value["type"] == '1'){
            if($stmt = $mysqli->prepare("SELECT a.Name as \"name\", b.URL as \"url\" FROM users as a join pass_gen as b on a.ID=b.ID WHERE a.ID = ?")){
                $stmt->bind_param("i",$value["user"]);
                //$route = $_POST['routeID'];
                $data = $updareArray[$i][2];
                $stmt->execute();
                $stmt->bind_result($name, $url);
                $stmt->fetch();

                $mail->isHTML(true);                            // Set email format to HTML

                $activateURL = BASE_URL."activate.php?x=".$url;

                $mail->Subject = 'alphaBravo Registrácia';

                $mail->Body  = "<h3>Vážený ". $name .",</h3>";
                $mail->Body  .= "<p>ďakujeme, že ste sa registrovali na našej stránke. Posledným krokom, ktorý Vás delí od úspešnej registrácie a využití plnej funkcionality našej stránky je kliknutie na nasledujúci link, pomocou ktorého vykonáte aktiváciu svojho účtu. <br><br>";
                $mail->Body  .= "<br>Link: ";
                $mail->Body  .= "<a href=\"".$activateURL."\">Aktivovať účet</a> <br><br>";
                $mail->Body  .= "V prípade, že ste sa neregistrovali, ignorujte tento odkaz.<br>";
                $mail->Body  .= "Váš alphaBravo tím.</p>";

                $mail->AltBody = 'Vážený'. $name .'\n' ;
                $mail->AltBody .= 'ďakujeme, že ste sa registrovali na našej stránke. Posledným krokom, ktorý Vás delí od úspešnej registrácie a využití plnej funkcionality našej stránky je kliknutie na nasledujúci link, pomocou ktorého vykonáte aktiváciu svojho účtu. \n\n' ;
                $mail->AltBody .= $activateURL.'\n' ;
                $mail->AltBody .= 'V prípade, že ste sa neregistrovali, ignorujte tento odkaz. \n' ;
                $mail->AltBody .= 'Váš alphaBravo tím.' ;

                $mail->send();

                $stmt->close();
            }

        }
        elseif ($value["type"] == '2'){
            if($stmt = $mysqli->prepare("SELECT newsletter.text as \"newsletter\" FROM newsletter WHERE newsletter.id = ?")){
                $stmt->bind_param("i",$value["user"]);
                //$route = $_POST['routeID'];
                $data = $updareArray[$i][2];
                $stmt->execute();
                $stmt->bind_result($newsletter);
                $stmt->fetch();

                $mail->isHTML(true);                            // Set email format to HTML
                $mail->Subject = 'alphaBravo NEWSLETTER';

                $mail->Body  = "<h3>Vážený užívateľ</h3>";
                $mail->Body  .= "<p>Administrátor uverejnil nasledujúci správu:<br>";
                $mail->Body  .= $newsletter."<br>";
                $mail->Body  .= "Váš alphaBravo tím.</p>";

                $mail->AltBody = 'Vážený užívateľ\n' ;
                $mail->AltBody .= 'Administrátor uverejnil nasledujúci správu:\n' ;
                $mail->AltBody .= $newsletter.'\n' ;
                $mail->AltBody .= 'Váš alphaBravo tím.' ;

                $mail->send();



                $stmt->close();
            }

        }
        //echo 'Message has been sent';
    } catch (Exception $e) {
        //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}



foreach ($updareArray as $key => $value) {
    if($update = $mysqli->prepare("UPDATE mailer SET sent = 1 WHERE ID = ?")) {
        $update->bind_param("i", $key);

        $update->execute();
        $update->close();
    }
}

?>