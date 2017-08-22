<?php

/********************************************
 * PHP Newsletter 5.2.1
 * Copyright (c) 2006-2017 Alexander Yanitsky
 * Website: http://janicky.com
 * E-mail: janickiy@mail.ru
 * Skype: janickiy
 ********************************************/

defined('LETTER') || exit('NewsLetter: access denied.');

class core
{
    protected static $_init = NULL;
    protected static $paths = array();
    protected static $mainConfig = NULL;
    protected static $language   = NULL;
    protected static $licensekey_url = 'http://license.janicky.com/';
    protected static $license_path = 'sys/license_key';
    public static $db  = NULL;
    public static $tpl = NULL;
    public static $path = NULL;
    public static $session = NULL;
    protected static $licensekey = NULL;
    public static $system_error = NULL;
    const KEY = 'Y945$r7=0))!y';
    const IV = '1234567890abcdef';

    /**
     * Check if self::init() has been called
     *
     * @return boolean
     */
    static public function isInit()
    {
        return self::$_init;
    }

    /**
     * Initialization
     *
     * @return boolean
     */
    static public function init($paths)
    {
        if (self::isInit())
            return TRUE;
        self::$paths = $paths;
        self::$path = str_replace("//", "/", "/" . trim(str_replace(chr(92), "/", substr(SYS_ROOT, strlen($_SERVER["DOCUMENT_ROOT"]))), "/") . "/");
        self::_loadEngines();
        self::$_init = TRUE;
    }

    /**
     * Create class $className
     *
     * @param string $className
     *            class name
     * @return mixed
     */
    static public function factory($className)
    {
        return new $className();
    }

    static public function database()
    {
        return self::$db;
    }

    static public function session()
    {
        return self::$session;
    }

    /**
     * AUTOLOAD modules
     */
    static protected function _loadEngines()
    {
        require_once 'folders.php';
        $folders = array(
            self::$paths['engine']
        );
        $autoload = array_reverse(folders::scan($folders, 'php', TRUE));
        foreach ($autoload as $lib) {
            if (is_file($lib))
                require_once $lib;
        }

        self::$licensekey = self::getLicensekey();

        if (self::checkLicense() == false && $_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
            header('Location: ./?t=expired');
            exit();
        }
    }

    /**
     * @return mixed
     * @throws ExceptionSQL
     */
    static public function getLicensekey()
    {
        global $ConfigDB;

        $db = new DB($ConfigDB);
        $query = "SELECT * FROM " . $db->getTableName('licensekey') . "";
        $result = $db->querySQL($query);
        $row = $db->getRow($result);

        return $row['licensekey'];
    }

    /**
     * @return null
     */
    static public function getTemplate()
    {
        return self::$tpl;
    }

    /**
     * @param $tpl
     */
    static public function setTemplate($tpl)
    {
        self::$tpl = SYS_ROOT . self::$paths['templates'] . DIRECTORY_SEPARATOR . $tpl;
    }

    // --------- SETTINGS -------------------------------
    static public function addSetting($set = array())
    {
        self::$mainConfig = (is_array(self::$mainConfig)) ? array_merge(self::$mainConfig, $set) : $set;
    }

    /**
     * @param $index
     * @param $value
     */
    static public function setSetting($index, $value)
    {
        self::$mainConfig[$index] = $value;
    }

    /**
     * @param string $name
     * @return null
     */
    static public function getSetting($name = '')
    {
        // Main config
        return ($name == '') ? self::$mainConfig : self::$mainConfig[$name];
    }
    // --------- SETTINGS -------------------------------

    // --------- language -------------------------------
    static public function addLanguage($lng = array())
    {
        self::$language = $lng;
    }

    /**
     * @param $section
     * @param $item
     * @return string
     */
    static public function getLanguage($section, $item)
    {
        return (isset(self::$language[$section][$item])) ? self::$language[$section][$item] : '';
    }

    /**
     * @param $section
     * @param $item
     * @param $value
     */
    static public function setLanguage($section, $item, $value)
    {
        self::$language[$section][$item] = $value;
    }

    /**
     * @param $engine
     * @param $data
     * @return string
     */
    static public function pathTo($engine, $data)
    {
        return SYS_ROOT . self::$paths[$engine] . DIRECTORY_SEPARATOR . $data;
    }

    /**
     * @param $engine
     * @param $name
     * @return bool
     */
    static public function requireEx($engine, $name)
    {
        $file = SYS_ROOT . self::$paths[$engine] . DIRECTORY_SEPARATOR . $name;
        if (file_exists($file)) {
            require_once $file;
            return true;
        } else
            return false;
    }

    /**
     * @param $engine
     * @param $name
     * @return bool
     */
    static public function includeEx($engine, $name)
    {
        $file = SYS_ROOT . self::$paths[$engine] . DIRECTORY_SEPARATOR . $name;
        if (file_exists($file)) {
            include_once $file;
            return true;
        } else
            return false;
    }

    /**
     * @param $path
     * @return mixed
     */
    static public function getPath($path)
    {
        return self::$paths[$path];
    }

    /**
     * @param null $text
     * @return string
     */
    static public function encodeStr($str = null)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5(self::KEY), $str, 'ctr', self::IV));
    }

    /**
     * @param $text
     * @return string
     */
    static public function decodeStr($str = null)
    {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5(self::KEY), base64_decode($str), 'ctr', self::IV);
    }

    /**
     * @return bool
     */
    static public function checkLicense()
    {
        $result = true;
        $domain = (substr($_SERVER["SERVER_NAME"], 0, 4)) == "www." ? str_replace('www.','', $_SERVER["SERVER_NAME"]) : $_SERVER["SERVER_NAME"];

		if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
			if (file_exists(SYS_ROOT . self::$license_path)) {
				$lisense_info = self::getLicenseInfo();

				if ($lisense_info['domain'] != $domain) {
					self::makeLicensekey();
				}

				if ($lisense_info['license_type'] == 'demo' && $domain == $lisense_info['domain'] &&  $_REQUEST['t'] != 'expired') {
					if (round((strtotime($lisense_info['date_to']) - strtotime(date("Y-m-d H:i:s"))) / 3600 / 24) < 0) {
						return false;
					}
				}
			} else {
				self::makeLicensekey();
			}			
		}       

        return $result;
    }

    /**
     * @param $licensekey
     */
    static public function updateLicensekey($licensekey)
    {
        $lisense_info = self::getLicenseInfo(SYS_ROOT . self::$license_path);

        if (file_exists(SYS_ROOT . self::$license_path) || $lisense_info['licensekey'] != $licensekey) {
           self::makeLicensekey();
        }
    }

    /**
     *
     */
    static public function makeLicensekey()
    {
        $domain = (substr($_SERVER["SERVER_NAME"], 0, 4)) == "www." ? str_replace('www.','', $_SERVER["SERVER_NAME"]) : $_SERVER["SERVER_NAME"];
        $lisense_info = json_decode(self::file_get_contents_curl(self::$licensekey_url . '?t=licensekey&licensekey=' . self::$licensekey . '&domain=' . $domain . ''), true);

        if (!isset($lisense_info['error'])) {
            $arr = array(
                'domain' => (substr($_SERVER["SERVER_NAME"], 0, 4)) == "www." ? str_replace('www.','', $_SERVER["SERVER_NAME"]) : $_SERVER["SERVER_NAME"],
                'license_type' => $lisense_info['license_type'],
                'licensekey'   => self::$licensekey,
                'created'   => $lisense_info['date_created'],
                'date_from' => $lisense_info['date_active_from'],
                'date_to'   => $lisense_info['date_active_to']
            );

            $encodeStr = self::encodeStr(json_encode($arr));

            $f = fopen(SYS_ROOT . self::$license_path, "w");

            if (fwrite($f, $encodeStr) === false) {
                self::$system_error = 'CANNT_CREATE_LICENSEKEY_FILE';
            }

            fclose($f);
        } else {
            self::$system_error = 'ERROR_CHECK_LICENSEKEY';
        }
    }

    /**
     * @return mixed
     */
    static public function getLicenseInfo()
    {
        if (file_exists(SYS_ROOT . self::$license_path)) {
            $handle = fopen(SYS_ROOT . self::$license_path, "r");
            $contents = fread($handle, filesize(SYS_ROOT . self::$license_path));
            fclose($handle);

            return json_decode(self::decodeStr($contents), true);
        } else {
            self::makeLicensekey();
        }
    }

    /**
     * @param $url
     * @return mixed
     */
    static public function file_get_contents_curl($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 0);
        curl_setopt($ch, CURLOPT_REFERER, isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $data = curl_exec($ch);

        curl_close($ch);

        preg_match('/\{([^\}])+\}/U',$data, $out);
        return $out[0];
    }

    /**
     * @return float
     */
    static public function expired_day_count()
    {
        $lisense_info = self::getLicenseInfo(SYS_ROOT . self::$license_path);

        if ($lisense_info['license_type'] == 'demo') {
            return round((strtotime($lisense_info['date_to']) - strtotime(date("Y-m-d H:i:s"))) / 3600 / 24);
        }
    }
}