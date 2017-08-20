<?php

require "includes/PHPMailerAutoload.php";

/* Global */

$captcha = "";
$captchaError = "";

$userName = "";
$userEmail = "";
$pageTitle = "";
$pageTags = "";
$pageContent = "";

$rootFolder = "/";
$userPage = "";
$pageMessage = "";
$emailLink = "";

/* Get values from form */

if(isset($_POST["g-recaptcha-response"])){

	$captcha=$_POST["g-recaptcha-response"];

}

if (empty($_POST["name"])) {

	$responseData["nameError"] = "<i class='fa fa-times' aria-hidden='true'></i> Please type your name.";

} else {

	$userName = trim($_POST["name"]);
	$userName = ucwords($userName);

}

if (empty($_POST["email"])) {

	$responseData["emailError"] = "<i class='fa fa-times' aria-hidden='true'></i> Please type your email.";

} else {

	$userEmail = trim($_POST["email"]);

}

if (empty($_POST["title"])) {

	$responseData["titleError"] = "<i class='fa fa-times' aria-hidden='true'></i> Please type a title for your page.";

} else {

	$pageTitle = trim($_POST["title"]);

}

if (empty($_POST["tags"])) {

	$responseData["tagsError"] = "<i class='fa fa-times' aria-hidden='true'></i> Please type the tags for your page.";

} else {

	$pageTags = trim($_POST["tags"]);

}

if (empty($_POST["content"])) {

	$responseData["contentError"] = "<i class='fa fa-times' aria-hidden='true'></i> Please type some content for your page.";

} else {

	$pageContent = trim($_POST["content"]);

}

$secret = "PRIVATE_KEY";
$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$ip);
$responseKeys = json_decode($response,true);

if(intval($responseKeys["success"]) !== 1) {

	$responseData["captchaError"] = "<i class='fa fa-times' aria-hidden='true'></i> Please verify that you are human.";

} else {

	$folderName = $userName;
	$folderName = strtolower($folderName);
	$folderName = str_replace(" ", "-", $folderName);
	$folderName = preg_replace('/[^A-Za-z0-9\-]/', '', $folderName);

	$pageTitleFormatted = $pageTitle;
	$pageTitleFormatted = strtolower($pageTitleFormatted);
	$pageTitleFormatted = str_replace(" ", "-", $pageTitleFormatted);
	$pageTitleFormatted = preg_replace('/[^A-Za-z0-9\-]/', '', $pageTitleFormatted);

	$currentURL = "http://" . $_SERVER['HTTP_HOST'] . $rootFolder;
	$folderPath = $folderName;

	if (file_exists($folderPath)) {

	  $newFolder = $folderPath;
	  $int = 0;
	  $newPath = $newFolder;

	  while (is_dir($newPath)) {

	    $newPath = $newFolder . "_0" . ++$int;

	  }

	  mkdir($newPath);

	  $uploadFolder = $newPath . "/";
	  $userPage = $currentURL . $uploadFolder . $pageTitleFormatted . ".php";

	  $createFile = $uploadFolder . $pageTitleFormatted . ".php";
	  $fh = fopen($createFile, "w") or die($responseData["pageError"] = "<i class='fa fa-times' aria-hidden='true'></i> Page could not be created.");

	  $userContent =  "<!DOCTYPE html> \n" .
	                  "<html lang='en'> \n" .
	                  "<head> \n" .
	                  "<meta charset='utf-8'> \n" .
	                  "<meta http-equiv='X-UA-Compatible' content='IE=edge'> \n" .
	                  "<meta name='viewport' content='width=device-width, initial-scale=1'> \n" .
	                  "<link rel='apple-touch-icon' sizes='57x57' href='assets/ico/apple-icon-57x57.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='60x60' href='assets/ico/apple-icon-60x60.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='72x72' href='assets/ico/apple-icon-72x72.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='76x76' href='assets/ico/apple-icon-76x76.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='114x114' href='assets/ico/apple-icon-114x114.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='120x120' href='assets/ico/apple-icon-120x120.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='144x144' href='assets/ico/apple-icon-144x144.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='152x152' href='assets/ico/apple-icon-152x152.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='180x180' href='assets/ico/apple-icon-180x180.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='192x192'  href='/android-icon-192x192.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='32x32' href='assets/ico/favicon-32x32.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='96x96' href='assets/ico/favicon-96x96.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='16x16' href='assets/ico/favicon-16x16.png'> \n" .
	                  "<link rel='manifest' href='/manifest.json'> \n" .
	                  "<meta name='msapplication-TileColor' content='#ffffff'> \n" .
	                  "<meta name='msapplication-TileImage' content='/ms-icon-144x144.png'> \n" .
	                  "<meta name='theme-color' content='#ffffff'> \n" .
	                  "<title>" . $pageTitle . "</title> \n" .
	                  "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'> \n" .
	                  "<link rel='stylesheet' href='../css/style.css'> \n" .
	                  "</head> \n" .
	                  "<body> \n" .
	                  "<div class='container'> \n" .
	                  "<header> \n" .
	                  "<h1>" . $pageTitle . "</h1> \n" .
	                  "<span>" . $pageTags . "</span> \n" .
	                  "</header> \n" .
	                  $pageContent . "\n" .
	                  "</div> \n" .
	                  "</body> \n" .
	                  "</html> \n";

	  fwrite($fh, $userContent);

	  $emailLink = $_POST["link"];

	  if ($emailLink == "Yes") {

	    $sendEmail = new PHPMailer;

	    $sendEmail->SMTPDebug = 3;

	    $sendEmail->setFrom("bronson@bronsondunbar.com", "Content link");
	    $sendEmail->addAddress($userEmail, "Content link");
	    $sendEmail->Subject = "Content link";
	    $sendEmail->Body = $userPage;
	    $sendEmail->AltBody = "To view the message, please use an HTML compatible email viewer!";
	    $sendEmail->IsHTML(true);

	    if(!$sendEmail->send()) {

	    	$responseData["emailSentError"] = "<i class='fa fa-check' aria-hidden='true'></i> Page has been created. Could not send link to email address. <br />" . $mail->ErrorInfo;

	    } else {
	      
	      $responseData["pageSuccess"] = "<i class='fa fa-check' aria-hidden='true'></i> Page has been created. <a href='" . $userPage . "' target='_blank'>Click here</a> to view or follow the link in the email.";

	    }

	  } else {

	    $responseData["pageSuccess"] = "<i class='fa fa-check' aria-hidden='true'></i> Page has been created. <a href='" . $userPage . "' target='_blank'>Click here</a> to view.";

	  }

	} else {

	  mkdir($folderPath);

	  $uploadFolder = $folderPath . "/";
	  $userPage = $currentURL . $uploadFolder . $pageTitleFormatted . ".php";

	  $createFile = $uploadFolder . $pageTitleFormatted . ".php";
	  $fh = fopen($createFile, "w") or die($responseData["pageError"] = "<i class='fa fa-times' aria-hidden='true'></i> Page could not be created.");

	  $userContent =  "<!DOCTYPE html> \n" .
	                  "<html lang='en'> \n" .
	                  "<head> \n" .
	                  "<meta charset='utf-8'> \n" .
	                  "<meta http-equiv='X-UA-Compatible' content='IE=edge'> \n" .
	                  "<meta name='viewport' content='width=device-width, initial-scale=1'> \n" .
	                  "<link rel='apple-touch-icon' sizes='57x57' href='assets/ico/apple-icon-57x57.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='60x60' href='assets/ico/apple-icon-60x60.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='72x72' href='assets/ico/apple-icon-72x72.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='76x76' href='assets/ico/apple-icon-76x76.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='114x114' href='assets/ico/apple-icon-114x114.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='120x120' href='assets/ico/apple-icon-120x120.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='144x144' href='assets/ico/apple-icon-144x144.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='152x152' href='assets/ico/apple-icon-152x152.png'> \n" .
	                  "<link rel='apple-touch-icon' sizes='180x180' href='assets/ico/apple-icon-180x180.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='192x192'  href='/android-icon-192x192.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='32x32' href='assets/ico/favicon-32x32.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='96x96' href='assets/ico/favicon-96x96.png'> \n" .
	                  "<link rel='icon' type='image/png' sizes='16x16' href='assets/ico/favicon-16x16.png'> \n" .
	                  "<link rel='manifest' href='/manifest.json'> \n" .
	                  "<meta name='msapplication-TileColor' content='#ffffff'> \n" .
	                  "<meta name='msapplication-TileImage' content='/ms-icon-144x144.png'> \n" .
	                  "<meta name='theme-color' content='#ffffff'> \n" .
	                  "<title>" . $pageTitle . "</title> \n" .
	                  "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'> \n" .
	                  "<link rel='stylesheet' href='../css/style.css'> \n" .
	                  "</head> \n" .
	                  "<body> \n" .
	                  "<div class='container'> \n" .
	                  "<header> \n" .
	                  "<h1>" . $pageTitle . "</h1> \n" .
	                  "<span>" . $pageTags . "</span> \n" .
	                  "</header> \n" .
	                  $pageContent . "\n" .
	                  "</div> \n" .
	                  "</body> \n" .
	                  "</html> \n";

	  fwrite($fh, $userContent);

	  $emailLink = $_POST["link"];

	  if ($emailLink == "Yes") {

	    $sendEmail = new PHPMailer;

	    $sendEmail->SMTPDebug = 3;

	    $sendEmail->setFrom("bronson@bronsondunbar.com", "Content link");
	    $sendEmail->addAddress($userEmail, "Content link");
	    $sendEmail->Subject = "Content link";
	    $sendEmail->Body = $userPage;
	    $sendEmail->AltBody = "To view the message, please use an HTML compatible email viewer!";
	    $sendEmail->IsHTML(true);

	    if(!$sendEmail->send()) {

	    	$responseData["emailSentError"] = "<i class='fa fa-check' aria-hidden='true'></i> Page has been created. Could not send link to email address. <br />" . $mail->ErrorInfo;

	    } else {
	      
	      $responseData["pageSuccess"] = "<i class='fa fa-check' aria-hidden='true'></i> Page has been created. <a href='" . $userPage . "' target='_blank'>Click here</a> to view or follow the link in the email.";

	    }

	  } else {

	    $responseData["pageSuccess"] = "<i class='fa fa-check' aria-hidden='true'></i> Page has been created. <a href='" . $userPage . "' target='_blank'>Click here</a> to view.";

	  }

	}

}

echo json_encode($responseData);

?>