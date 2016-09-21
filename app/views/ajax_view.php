<?php

/********************************************
 * PHP Newsletter 5.0.0 alfa
 * Copyright (c) 2006-2016 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

set_time_limit(0);

session_start();

// authorization
Auth::authorization();

session_write_close();

$autInfo = Auth::getAutInfo($_SESSION['id']);

switch (Core_Array::getGet('action'))
{
	case 'alert_update':
		$update = new Update();
		$update->currenversion = VERSION;

		if ($update->checkNewVersion()){
			$update_warning = str_replace('%SCRIPTNAME%', core::getLanguage('str', 'script_name'), core::getLanguage('str', 'update_warning'));
			$update_warning = str_replace('%VERSION%', $update->getVersion(), $update_warning);
			$update_warning = str_replace('%CREATED%', $update->getCreated(), $update_warning);
			$update_warning = str_replace('%DOWNLOADLINK%', $update->getDownloadLink(), $update_warning);
			$content = array("msg" => $update_warning);

			Pnl::showJSONContent(json_encode($content));
		}

	break;

	case 'countsend':
		$totalmails = 0;
		$successmails = 0;
		$unsuccessfulmails = 0;

		if (Core_Array::getGet('id_log')){
			$id_log = (int)Core_Array::getGet('id_log');

			$totalmails = $data->getTotalMails();
			$successmails = $data->getSuccessMails($id_log);
			$unsuccessfulmails = $data->getUnsuccessfulMails($id_log);
		}

		$sleep = core::getSetting('sleep') == 0 ? 0.5 : core::getSetting('sleep');
		$timesec = intval(($totalmails - ($successmails + $unsuccessfulmails)) * $sleep);

		$datetime = new DateTime();
		$datetime->setTime(0, 0, $timesec);

		$content = array();
		$content['status'] = 1;
		$content['total'] = $totalmails;
		$content['success'] = $successmails;
		$content['unsuccessful'] = $unsuccessfulmails;
		$content['time'] = $datetime->format('H:i:s');

		Pnl::showJSONContent(json_encode($content));

	break;

	case 'daemonstat':

		$content = array("status" => $data->getMailingStatus());

		Pnl::showJSONContent(json_encode($content));

	break;

	case 'logonline':

		foreach($data->getCurrentUserLog(10) as $row){
			$rows[] = array(
				"id_user" => $row['id_user'],
				"email"   => $row['email'],
				"status"  => $row['success'],
				"id_log" => $row['id_log'],
			);

			$content = '{"item":'.json_encode($rows).'}';
		}

		Pnl::showJSONContent($content);

	break;

	case 'start_update':

		$path = SYS_ROOT . $cmspaths['tmp'] . 'update.zip';
		$content = array();

		if (Core_Array::getRequest('p') == 'start' && Auth::checkLicenseKey()){
			$result = $data->DownloadUpdate($path, $update->getUpdate());
		}

		if (Core_Array::getRequest('p') == 'update_files' && Auth::checkLicenseKey()){
			if (is_file($path)){
				$arc = new Unzipper($path);

				$content['status'] = $arc::$status;
				$content['result'] = $arc::$result;
			}
		}

		if (Core_Array::getRequest('p') == 'update_bd' && Auth::checkLicenseKey()){
			$current_version_code = Pnl::get_current_version_code($currentversion);
			$version_code_detect = $data->version_code_detect();

			if ($version_code_detect < $current_version_code){
				if ($version_code_detect == 50000){
					$path_update = 'tmp/update_5_0_' . core::getSetting('language') . '.sql';
				}

				if (is_file($path_update)){
					if ($data->updateDB($path_update, $ConfigDB)){
						$content['status'] = core::getLanguage('msg', 'update_completed');
						$content['result'] = 'yes';
					}
					else{
						$content['status'] = core::getLanguage('error', 'failed_to_update');
						$content['result'] = 'no';
					}
				}
			}
			else{
				$content['status'] = core::getLanguage('msg', 'update_completed');
				$content['result'] = 'yes';
			}
		}

		Pnl::showJSONContent(json_encode($content));

	break;

	case 'sendtest':
		$subject = trim(Core_Array::getRequest('name'));
		$body = trim(Core_Array::getRequest('body'));
		$prior = Core_Array::getRequest('prior');
		$email = trim(Core_Array::getRequest('email'));

		$error = array();

		if (empty($subject)) $error[] = core::getLanguage('error', 'empty_subject');
		if (empty($body)) $error[] = core::getLanguage('error', 'empty_content');
		if (empty($email)) $error[] = core::getLanguage('error', 'empty_email');
		if (!empty($email) && check_email($email)) $error[] = core::getLanguage('error', 'wrong_email');

		if (count($error) == 0){
			if ($data->sendTestEmail($email, $subject, $body, $prior)){
				$result_send = 'success';
				$msg = core::getLanguage('msg', 'letter_was_sent');
			}
			else{
				$result_send = 'error';
				$msg = core::getLanguage('error', 'letter_wasnt_sent');
			}
		}
		else{
			$result_send = 'errors';
			$msg = implode(",", $error);
		}

		$content = array();
		$content['result'] = $result_send;
		$content['msg'] = $msg;

		Pnl::showJSONContent(json_encode($content));

	break;

	case 'send':

		$result = 0;

		if ($_REQUEST['activate']){
			if ($data->updateProcess('start')) $result = $data->SendEmails($_REQUEST['activate']);
		}

		$content = array("completed" => "yes");

		Pnl::showJSONContent(json_encode($content));

	break;

	case 'showlogs':

		$number = isset($_REQUEST['number']) && is_numeric($_REQUEST['number']) ? $_REQUEST['number'] : exit();
		$offset = isset($_REQUEST['offset']) && is_numeric($_REQUEST['offset']) ? $_REQUEST['offset'] : exit();
		$id_log = isset($_REQUEST['id_log']) && is_numeric($_REQUEST['id_log']) ? $_REQUEST['id_log'] : exit();
		$strtmp = !empty($_REQUEST['strtmp']) ? $_REQUEST['strtmp'] : exit();

		$arr = $data->getDetaillog($offset, $number, $_REQUEST['id_log'], $strtmp);

		if (is_array($arr)){
			foreach($arr as $row){
				$catname = $row['id_cat'] == 0 ? core::getLanguage('str', 'general') : $row['catname'];
				$status = $row['success'] == 'yes' ? core::getLanguage('str', 'send_status_yes') : core::getLanguage('str', 'send_status_no');
				$read = $row['readmail'] == 'yes' ? core::getLanguage('str', 'yes') : core::getLanguage('str', 'no');

				$rows[] = array(
					"id" => $row['id_cat'],
					"name" => $row['name'],
					"email" => $row['email'],
					"catname" => $catname,
					"time" => $row['time'],
					"status" => $status,
					"read" => $read,
					"errormsg" => $row['errormsg'],
				);
			}

			if (isset($rows)) {
				$content = '{"item":' . json_encode($rows) . '}';
				Pnl::showJSONContent($content);
			}
		}

	break;

	case 'process':
		if ($data->updateProcess($_REQUEST['status'])){
			$content = array("status" => $_REQUEST['status']);
		}
		else{
			$content = array("status" => "no");
		}

		Pnl::showJSONContent(json_encode($content));

	break;

	case 'remove_attach':

		if (is_numeric($_REQUEST['id'])){
			if ($data->removeAttach(Core_Array::getGet('id'))){
				$content = array("result" => "yes");
			}
			else{
				$content = array("result" => "no");
			}

			Pnl::showJSONContent(json_encode($content));
		}

	break;
}