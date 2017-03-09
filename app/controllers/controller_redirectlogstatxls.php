<?php

/********************************************
 * PHP Newsletter 5.1.0
 * Copyright (c) 2006-2017 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_redirectlogstatxls extends Controller
{
    function __construct()
    {
        $this->model = new Model_redirectlogstatxls();
        $this->view = new View();
    }

    function action_index()
    {
        $this->view->generate('redirectlogstatxls_view.php', $this->model);
    }
}