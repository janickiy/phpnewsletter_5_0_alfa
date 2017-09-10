<?php

/********************************************
 * PHP Newsletter 5.2.2
 * Copyright (c) 2006-2017 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_subscribers extends Controller
{
	function __construct()
	{
		$this->model = new Model_subscribers();
		$this->view = new View();
	}
	
	public function action_index()
	{
		$this->view->generate('subscribers_view.php', $this->model);
	}
}