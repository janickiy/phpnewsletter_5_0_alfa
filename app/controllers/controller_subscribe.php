<?php

/********************************************
 * PHP Newsletter 5.0.8
 * Copyright (c) 2006-2017 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_subscribe extends Controller
{
	function __construct()
	{
		$this->model = new Model_subscribe();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('subscribe_view.php',$this->model);
	}
}