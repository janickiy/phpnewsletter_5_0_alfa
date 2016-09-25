<?php

/********************************************
 * PHP Newsletter 5.0.0 alfa
 * Copyright (c) 2006-2016 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

if(empty(Core_Array::getGet('id'))) Pnl::error(core::getLanguage('error', 'unsubscribe'));
if(empty(Core_Array::getGet('token'))) Pnl::error(core::getLanguage('error', 'unsubscribe'));

$token = $data->getToken(Core_Array::getGet('id'));

if($token == $_GET['token']){
	$result = $data->makeUnsubscribe(Core_Array::getGet('id'));
	
	if(!$result) Pnl::error(core::getLanguage('error', 'unsubscribe'));
	
	echo '<!DOCTYPE html>';
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	echo "<title>".core::getLanguage('str', 'title_unsubscribe')."</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<center>".core::getLanguage('msg', 'subscribe_removed')."</center>\n";
	echo "</body>\n";
	echo "</html>";	
}
else Pnl::error(core::getLanguage('error', 'unsubscribe'));
