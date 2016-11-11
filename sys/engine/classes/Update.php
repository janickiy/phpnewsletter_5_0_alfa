<?php

/********************************************
 * PHP Newsletter 5.0.3
 * Copyright (c) 2006-2016 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

class Update
{
	private $language;
	private $url = 'http://license.janicky.com/';
	private $currenversion;

	public function __construct($language, $currenversion, $ip)
	{
		$this->language = $language;
		$this->currenversion = $currenversion;
	}

	public function checkNewVersion()
	{
		$newversion = $this->getVersion();
		
		preg_match("/(\d+)\.(\d+)\.(\d+)/", $this->currenversion, $out1);
		preg_match("/(\d+)\.(\d+)\.(\d+)/", $newversion, $out2);
		
		$v1 = ($out1[1] * 10000 + $out1[2] * 100 + $out1[3]);
		$v2 = ($out2[1] * 10000 + $out2[2] * 100 + $out2[3]);
		
		if($v2 > $v1)
			return true;
		else
			return false;
	}

	public function getUrlInfo()
	{
		return $this->url . '?id=1&version=' . $this->currenversion . '&lang=' . $this->language . '&ip=' . $this->getIP();
	}

	public function getDataNewVersion($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 0); 
		curl_setopt($ch, CURLOPT_REFERER, isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);

		$data = curl_exec($ch);
		curl_close($ch);

		return json_decode($data, true);
	}

	public function checkTree()
	{
		$newversion = $this->getVersion();

		preg_match("/(\d+)\.(\d+)\.(\d+)/", $this->currenversion, $out1);
		preg_match("/(\d+)\.(\d+)\.(\d+)/", $newversion, $out2);

		if($out1[1] < $out1[2])
			return false;
		else
			return true;
	}
	
	public function getVersion()
	{
		$out = $this->getDataNewVersion($this->getUrlInfo());
		return $out['version'];
	}

	public function getDownloadLink()
	{
		$out = $this->getDataNewVersion($this->getUrlInfo());
		return $out['download'];
	}
	
	public function getCreated()
	{
		$out = $this->getDataNewVersion($this->getUrlInfo());
		return $out['created'];
	}

	public function getUpdate()
	{
		$out = $this->getDataNewVersion($this->getUrlInfo());
		return $out['update'];
	}

	public function getUpgradeVersion()
	{
		$out = $this->getDataNewVersion($this->getUrlInfo());
		return $out['upgrade_version'];
	}

	public function getMessage()
	{
		$out = $this->getDataNewVersion($this->getUrlInfo());
		return $out['message'];
	}
	
	public function getIP() {
        if (getenv("HTTP_CLIENT_IP") and strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        elseif (getenv("REMOTE_ADDR") and strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        elseif (! empty($_SERVER['REMOTE_ADDR']) and strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";

        return ($ip);
    }
}