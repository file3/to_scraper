<?php
require("common.inc.php");

/**
 * \brief Extended file_get_contents(), use cURL or wget
 * \param url    - URL to get
 * \param iswget - Is using wget shell command or cURL otherwise
 * \return Content data
 */
function file_get_contents_ex($url, $iswget=false)
{
	$agent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:11.0) Gecko/20100101 Firefox/11.0";

	if ($iswget && function_exists("shell_exec")) {
		$fn = tempnam("/tmp", "togd");
		$cmd = "wget -q '$url' -O $fn --header='$agent'";
		exec($cmd);
		$rv = file_get_contents($fn);
		unlink($fn);
	} else {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_COOKIE, 'SESSION_COOKIEACCEPT=true');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$rv = curl_exec($ch);
		curl_close($ch);
	}
	return $rv;
}

/**
 * \brief Pretty printing of JSON, source: http://stackoverflow.com/questions/6054033/pretty-printing-json-with-php
 * \param json - Input JSON
 * \return Formatted JSON
 */
function json_pretty_print( $json )
{
	$result = '';
	$level = 0;
	$in_quotes = false;
	$in_escape = false;
	$ends_line_level = NULL;
	$json_length = strlen( $json );

	for( $i = 0; $i < $json_length; $i++ ) {
		$char = $json[$i];
		$new_line_level = NULL;
		$post = "";
		if( $ends_line_level !== NULL ) {
			$new_line_level = $ends_line_level;
			$ends_line_level = NULL;
		}
		if ( $in_escape ) {
			$in_escape = false;
		} else if( $char === '"' ) {
			$in_quotes = !$in_quotes;
		} else if( ! $in_quotes ) {
			switch( $char ) {
				case '}': case ']':
					$level--;
					$ends_line_level = NULL;
					$new_line_level = $level;
					break;

				case '{': case '[':
					$level++;
				case ',':
					$ends_line_level = $level;
					break;

				case ':':
					$post = " ";
					break;

				case " ": case "\t": case "\n": case "\r":
					$char = "";
					$ends_line_level = $new_line_level;
					$new_line_level = NULL;
					break;
			}
		} else if ( $char === '\\' ) {
			$in_escape = true;
		}
		if( $new_line_level !== NULL ) {
			$result .= "\n".str_repeat( "\t", $new_line_level );
		}
		$result .= $char.$post;
	}

	return $result;
}

class GetData
{
	public function __construct()
	{
		$this->res = array();
	}

	public function load()
	{
		$res = array();

		$data = file_get_contents_ex(INPUT);
		$data = trim(str_replace(array("\r", "\n"), array(" ", " "), $data));
//		echo htmlspecialchars($data);

		preg_match_all('/<div class="productNameAndPromotions">.*?<a href="([^"]*)" >(.*?)<img src.*?£([0-9.]*)/', $data, $match);
//		unset($match[0]);
//		print_r($match);

		$res["results"] = array();
		$res["total"] = 0;
		if (is_array($match[1])) {
			foreach($match[1] as $k=>$v)
			{
				$dat = file_get_contents_ex($match[1][$k]);
				$dat = trim(str_replace(array("\r", "\n"), array(" ", " "), $dat));

				preg_match('/Description<\/h3>(.*?)<h3/', $dat, $mat);

				$res["results"][] = array(
										"title"=>trim($match[2][$k]),
										"size"=>number_format((strlen($dat)/1024), 2)."kb",
										"unit_price"=>(double)($match[3][$k]),
										"description"=>(isset($mat[1]) ? preg_replace("/[ \t]*\n/", "\n", trim(strip_tags(str_replace(array("<br>", "<p>"), array("\n", "\n"), $mat[1])))) : "")
									);
				$res["total"] += (double)($match[3][$k]);
			}
		}
//		print_r($res);

		$this->res = $res;
	}

	public function view()
	{
		if (!is_cli()) {
//			header("Content-Type: text/plain");
			header("Content-type: application/json; charset=utf-8");
		}

		if (version_compare(phpversion(), '5.4.0', '<'))
			echo json_pretty_print(json_encode($this->res));
		else
			echo json_encode($this->res, JSON_PRETTY_PRINT);
	}
}
?>
