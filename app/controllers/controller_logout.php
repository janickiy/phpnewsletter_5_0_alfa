<?php

/********************************************
 * PHP Newsletter 5.0.3
 * Copyright (c) 2006-2016 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_logout extends Controller
{
    function action_index()
    {
        $this->view->generate('logout_view.php');
    }
}