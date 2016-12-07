<?php
/**
 * @package     Joomla.Tutorials
 * @subpackage  Module
 * @copyright   (C) 2015 gordoney
 * @license     License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.

$error = false;

if ($_POST['captcha']) {

	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$_POST['captchaSecretKey']."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$response = json_decode($response, true);
	if ($response['success']==false) {
		$error = true;
	}
}
if ((!$error)||(!$_POST['captcha'])) {

	define('_JEXEC', 1);

	define('JPATH_BASE', dirname(__FILE__) . '/../..' );

	define('DS', DIRECTORY_SEPARATOR);
	require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
	require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');
    
    
    $filePaths = array();

	JFactory::getApplication('site')->initialise();

	$mailer = JFactory::getMailer();

	$config = JFactory::getConfig();
	$sender = $config->get('mailfrom');
	$mailer->setSender($sender);
	$mailer->addRecipient($_POST['recipient']);

	for ($i=0; $i < $_POST['quantityFields']; $i++) {
		$body  .=  $_POST['namefield'.$i].' '.$_POST['field'.$i];
		$body  .=   "\r\n";
	}
    
	if((isset($_POST['imageId'])) && ($_POST['imageId'] != '') && ($_POST['imageId'] != 0)) {
        $body .= $_POST['imageIdFieldName'].' '.$_POST['imageId'];
		$body  .=   "\r\n";
    }
	
    if((isset($_POST['pageLink'])) && ($_POST['pageLink'] != '')) {
        $body .= $_POST['pageLinkFieldName'].' '.$_POST['pageLink'];
		$body  .=   "\r\n";
    }
    
	if(is_uploaded_file($_FILES["file"]["tmp_name"][0])) {
        for ($i = 0; $i < count($_FILES["file"]["tmp_name"]); $i++) {
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'].'/images/'.$_FILES["file"]["name"][$i]);
            
            $mailer->addAttachment($_SERVER['DOCUMENT_ROOT'].'/images/'.$_FILES["file"]["name"][$i]);
            
            array_push($filePaths, $_SERVER['DOCUMENT_ROOT'].'/images/'.$_FILES["file"]["name"][$i]);
        }
	}

	$mailer->setSubject($_POST['mailSubject']);
	$mailer->setBody($body);
    
	if ($mailer->Send()){
        foreach ($filePaths as $filePath) {
            unlink($filePath);
        }
    
		echo $status= 'success';
	}

}


