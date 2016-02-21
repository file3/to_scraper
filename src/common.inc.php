<?php
error_reporting(E_ALL);
ini_set("display_errors", "0");
ini_set("display_startup_errors", "0");
ini_set("log_errors", "0");

define("LANGUAGE", "en");
define("CHARSET", "UTF-8");
define("KEYWORDS", "Traffic Optimiser,Sainsbury");
define("INPUT", "http://www.sainsburys.co.uk/shop/gb/groceries/fruit-veg/ripe---ready#langId=44&storeId=10151&catalogId=10122&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=30&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0");


function ostr(&$var, $def="")
{
	return trim(isset($var) ? $var : $def);
}
function oint(&$var, $def=0)
{
	return (int)(isset($var) ? $var : $def);
}

/**
 * \brief Detect whether called from CLI
 * \return Is called from CLI
 */
function is_cli()
{
	if (defined('STDIN'))
		return true;
	elseif (php_sapi_name() === 'cli')
		return true;
/*	elseif (array_key_exists('SHELL', $_ENV))
		return true;
	elseif (empty($_SERVER['REMOTE_ADDR']) && (!isset($_SERVER['HTTP_USER_AGENT'])) && (count($_SERVER['argv']) > 0))
		return true;
	elseif (array_key_exists('REQUEST_METHOD', $_SERVER))
		return true;
*/	else
		return false;
}

/**
 * \brief Translation function e.g. with database backend - currently dummy
 * \param word - Word to translate
 * \param lang - Language, default none
 * \return Translated word
 */
function __($word, $lang=false)
{
	return $word;
}
?>
