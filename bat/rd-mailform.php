<?php

//$recipients = 'test@demolink.com';
//$recipients = '#';

try {
    require './phpmailer/PHPMailerAutoload.php';
/*
    preg_match_all("/([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)/", $recipients, $addresses, PREG_OFFSET_CAPTURE);

    if (!count($addresses[0])) {
        die('MF001');
    }

    if (preg_match('/^(127\.|192\.168\.)/', $_SERVER['REMOTE_ADDR'])) {
        die('MF002');
    }
*/
    $template = file_get_contents('rd-mailform.tpl');

    if (isset($_POST['form-type'])) {
        switch ($_POST['form-type']){
            case 'contact':
                $subject = 'A message from your site visitor';
                break;
            case 'subscribe':
                $subject = 'Subscribe request';
                break;
            case 'order':
                $subject = 'Order request';
                break;
            default:
                $subject = 'A message from your site visitor';
                break;
        }
    }else{
        die('MF004');
    }

    if (isset($_POST['email'])) {
        $template = str_replace(
            ["<!-- #{FromState} -->", "<!-- #{FromEmail} -->"],
            ["Email:", $_POST['email']],
            $template);
    }else{
        die('MF003');
    }

    if (isset($_POST['message'])) {
        $body = $_POST['message'];
    }


/*
    if (isset($_POST['message'])) {
        $template = str_replace(
            ["<!-- #{MessageState} -->", "<!-- #{MessageDescription} -->"],
            ["Message:", $_POST['message']],
            $template);
    }

    preg_match("/(<!-- #{BeginInfo} -->)(.|\n)+(<!-- #{EndInfo} -->)/", $template, $tmp, PREG_OFFSET_CAPTURE);
    foreach ($_POST as $key => $value) {
        if ($key != "email" && $key != "message" && $key != "form-type" && !empty($value)){
            $info = str_replace(
                ["<!-- #{BeginInfo} -->", "<!-- #{InfoState} -->", "<!-- #{InfoDescription} -->"],
                ["", ucfirst($key) . ':', $value],
                $tmp[0][0]);

            $template = str_replace("<!-- #{EndInfo} -->", $info, $template);
        }
    }
*/
    $template = str_replace(
        ["<!-- #{Subject} -->", "<!-- #{SiteName} -->"],
        [$subject, $_SERVER['SERVER_NAME']],
        $template);

/*
    $mail = new PHPMailer();
    $mail->From = $_SERVER['SERVER_ADDR'];
    $mail->FromName = $_SERVER['SERVER_NAME'];

    foreach ($addresses[0] as $key => $value) {
        $mail->addAddress($value[0]);
    }

    $mail->CharSet = 'utf-8';
    $mail->Subject = $subject;
    $mail->MsgHTML($template);

    if (isset($_FILES['attachment'])) {
        foreach ($_FILES['attachment']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $mail->AddAttachment($_FILES['attachment']['tmp_name'][$key], $_FILES['Attachment']['name'][$key]);
            }
        }
    }

    $mail->send();
*/

    $mail             = new PHPMailer();

    $mail->ContentType = "text/html";
    $mail->charset = "utf-8";
    //$mail->Charset = "EUC-KR";
    $mail->Encoding = "base64";
    $mail->isSMTP();
    $mail->isHTML(true);
    $mail->SMTPAuth = false;
    //$mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPDebug = 0;
    //$mail->SMTPDebug  = 2; 

    $mail->Host       = "mail.edamic.com"; // sets the SMTP server
    $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
    $mail->Username   = "bluebell@edamic.com"; // SMTP account username
    $mail->Password   = "edamic12345";        // SMTP account password

    $mail->SetFrom('bluebell@edamic.com', 'First Last');
    //$mail->Subject    = $subject;//"PHPMailer Test Subject via smtp, basic with authentication";
    $subject = "우물안의 중생이여 이 메일을 열어보아라..";
    $mail->Subject = "=?UTF-8?B?".base64_encode($subject)."?=";

    //$mail->Body    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    //$body = "가가가나나우우우우우웅응응";
    //$mail->Body    =  iconv('utf-8', 'euc-kr', $body);  
    //$body = "우물안의 중생이여 이 메일을 열어보아라..";
    //$mail->Body = iconv('utf-8', 'euc-kr', $body);  
    $mail->Body    =  $body;  
    //$mail->Body    =  iconv("UTF-8", "EUC-KR", $body); 
    //$mail->MsgHTML($template);
 
    $address = "bluebell@edamic.com";
    $mail->AddAddress($address, "John Doe");

    $mail->send();

        die('MF000');
    } catch (phpmailerException $e) {
        die('MF254');
    } catch (Exception $e) {
        die('MF255');
    }

?>