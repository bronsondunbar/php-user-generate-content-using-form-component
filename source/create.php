<?php

require "includes/PHPMailerAutoload.php";

$captcha = "";
$captchaError = "";
$yourEmail = "your@email.com";

$userName = "";
$userEmail = "";
$pageTitle = "";
$pageTags = "";
$pageContent = "";

$rootFolder = "/";
$userPage = "";
$pageMessage = "";
$emailLink = "";

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

	    $newPath = $newFolder . "-0" . ++$int;

	  }

	  mkdir($newPath);

	  $uploadFolder = $newPath . "/";
	  $userPage = $currentURL . $uploadFolder . $pageTitleFormatted . ".php";

	  $createFile = $uploadFolder . $pageTitleFormatted . ".php";
	  $fh = fopen($createFile, "w") or die($responseData["pageError"] = "<i class='fa fa-times' aria-hidden='true'></i> Page could not be created.");

	  ob_start();
include 'includes/template.php';
$include = ob_get_clean();
$include = str_replace("pageTitle", $pageTitle, $include);
$include = str_replace("pageTags", $pageTags, $include);
$include = str_replace("pageContent", $pageContent, $include);
$include = str_replace("$", "", $include);
$content = <<<EOF
{$include}
EOF;

	  fwrite($fh, $include);

	  $emailLink = $_POST["link"];

	  if ($emailLink == "Yes") {

	    $sendEmail = new PHPMailer;

	    $sendEmail->SMTPDebug = 3;

	    $sendEmail->setFrom($yourEmail, "Content link");
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

	  ob_start();
include 'includes/template.php';
$include = ob_get_clean();
$include = str_replace("pageTitle", $pageTitle, $include);
$include = str_replace("pageTags", $pageTags, $include);
$include = str_replace("pageContent", $pageContent, $include);
$include = str_replace("$", "", $include);
$content = <<<EOF
{$include}
EOF;

	  fwrite($fh, $include);

	  $emailLink = $_POST["link"];

	  if ($emailLink == "Yes") {

	    $sendEmail = new PHPMailer;

	    $sendEmail->SMTPDebug = 3;

	    $sendEmail->setFrom($yourEmail, "Content link");
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