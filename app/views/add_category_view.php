<?php

/********************************************
 * PHP Newsletter 5.0.1 beta
 * Copyright (c) 2006-2016 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

session_start();

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo($_SESSION['id']);

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

if (Core_Array::getRequest('action')){
	$name = trim(htmlspecialchars(Core_Array::getRequest('name')));

	if (empty($name)) $alert_error = core::getLanguage('error', 'empty_category_name');
	if (!empty($name) && $data->checkExistCatName($name)) $alert_error = core::getLanguage('error', 'cat_name_exist');
	
	if (!isset($alert_error)){
		$fields = array();
		$fields['id_cat'] = null;
		$fields['name'] = $_POST['name'];	
	
		if ($data->addNewCategory($fields)){
			header("Location: ./?t=category");
			exit();
		}
		else $alert_error = core::getLanguage('error', 'no_category_added') ;
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'add_category'));
$tpl->assign('TITLE', core::getLanguage('title', 'add_category'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'add_category'));

//error alert
if (isset($alert_error)) {
	$tpl->assign('ERROR_ALERT', $alert_error);
}

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$tpl->assign('RETURN_BACK', core::getLanguage('str', 'return_back'));
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('RETURN_BACK_LINK', './?t=category');
$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));
$tpl->assign('NAME', Core_Array::getRequest('name'));
$tpl->assign('BUTTON', core::getLanguage('button', 'add'));

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();