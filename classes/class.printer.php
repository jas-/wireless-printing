<?php

/* prevent direct access */
if (!defined('__SITE')) exit('No direct calls please...');

/**
 * The bulk of the myprint service resides within this class
 *
 * LICENSE: This source file is subject to version 3.01 of the GPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.html.  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 *
 * @category   printer
 * @author     jason.gerfen@gmail.com
 * @copyright  2008-2011 Jason Gerfen
 * @license    http://www.gnu.org/licenses/gpl.html  GPL License 3
 * @version    0.3
 */
class printer
{

	/*
	 * @param array $tools
	 * @abstract This should be setup with accurate path information for each
	 *           system tool required
	 */
	protected static $tools = array('file' => '/usr/bin/file',
									'lpr'  => '/usr/local/bin/lpr');

	/*
	 * @param array $printers
	 * @abstract Here we define the list of available print queues (these must correspond with the
	 *           print queues defined in /etc/cups/printers.conf)
	 */
	protected static $printers = array('kc-1', 'kc-color', 'gr-1', 'fa-1', 'un-1', 'un-color', 'ben-1', 'bencolor', 'sage-1', 'sr-1', 'st-1', 'CMS-5', 'pub-1', 'pub-2');

	/*
	 * @param array $files
	 * @abstract An array of allowed file extensions
	 */
	protected static $files = array('pdf');

	/*
	 * @param array $mime
	 * @abstract An array of allowed mime types
	 */
	protected static $mime = array('application/pdf', 'application/x-pdf', 'application/acrobat', 'applications/vnd.pdf');

	/*
	 * @param array $unids
	 * @abstract An array of prefixes to allow for the username field (ideally this field should be validated against active directory through an ldaps bind)
	 */
	protected static $unids = array('gp', 'u', 'gx', 'gn', '0');

	/*
	 * @function _calculations
	 * @access public
	 *
	 * @abstract Due to the design of PHP which empties all super-global arrays
	 *           without a return code on true conditional of $_FILE < max_upload_size
	 *           we must perform additional checks on the size
	 */
	public function _calculations()
	{
		$max = self::getMax();
		$cur = (!empty($_SERVER['CONTENT_LENGTH'])) ? (int)$_SERVER['CONTENT_LENGTH'] - sizeof($_POST) : self::getMax() - self::getMax();
		return ($cur < $max);
	}

	/*
	 * @function _main
	 * @access public
	 *
	 * @abstract Public access method to handling printing functionality
	 */
	public function _main($post, $files)
	{
		$print1 = true;
		$print2 = true;

		if ((!empty($post)) && (count($files['files'])===0) || ($files['files'][0]['size'] > self::getMax())) {
			$files_err = self::genErr(self::fErr(3));
		}

		if ((empty($post['unid'])) || (empty($post['printer'])) || (count($files['files']) <= 0)) {
			$print1 = false;
		}

		if ((!filter_var($post['unid'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>self::buildRegex(self::$unids, true))))) ||
			(!filter_var($post['printer'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>self::buildRegex(self::$printers, false, true)))))||
			(self::fileChk($files['files'], self::$files, self::$mime, self::getMax()) > 0)) {
			$print2 = false;
		}

		 if ((!$print1)||(!$print2)) {
			$unid_err = (empty($post['unid'])) ? self::genErr('Your uNID or guest pass account is missing') : (!filter_var($post['unid'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>self::buildRegex(self::$unids, 'true'))))) ? self::genErr('Your uNID is invalid') : false;
			$location_err = (empty($post['printer'])) ? self::genErr('You did not select a printer location') : (!filter_var($post['printer'], FILTER_VALIDATE_REGEXP, array('options'=>array('regexp'=>self::buildRegex($printers, false, true))))) ? self::genErr('The printer location is invalid') : false;
			$files_err = (count($files['files']) <= 0) ? self::genErr('You did not upload any files to print') : (self::fileChk($files['files'], self::$files, self::$mime, self::getMax()) !== 0) ? self::genErr(self::fErr(self::fileChk($files['files'], self::$files, self::$mime, self::getMax()))) : false;
			return array($unid_err, $location_err, $files_err);
		}

		if (($print1) && ($print2)) {
			$duplex = ($post['duplex']==='true') ? '-o sides=two-sided-long-edge' : '';
			$success = self::_printer($post['unid'], $post['printer'], $files['files'], $duplex);
			return $success;
		}
	}

	/*
	 * @function _printer
	 * @access private
	 *
	 * @param string $unid
	 * @param string $printers
	 * @param string $files
	 * @param string $duplex
	 *
	 * @return string
	 */
	private function _printer($unid, $printer, $files, $duplex)
	{
		if (count($files['name'])>1) {
			foreach($files['name'] as $key => $value) {
				$cmd = self::$tools['lpr'].' -P '.self::_filter($printer).' -U '.self::_filter($unid).' -C "'.urlencode(self::_filter($files['name'][$key])).'" '.self::_filter($files['tmp_name'][$key]).' '.self::_filter($duplex).' -r ';
				`$cmd`;
			}
		} else {
			$cmd = self::$tools['lpr'].' -P '.self::_filter($printer).' -U '.self::_filter($unid).' '.self::_filter($duplex).' -C '.urlencode(self::_filter($files['name'][0])).' "'.self::_filter($files['tmp_name'][0]).'" -r ';
			`$cmd`;
		}
		return self::genSucc('Your print job ('.$unid.') has been sent to the ('.$printer.') print queue');
	}

	/*
	 * @function getMax
	 * @access private
	 *
	 * @return integer
	 */
	private function getMax($friendly = false)
	{
		return (!$friendly) ? substr(ini_get('upload_max_filesize'), 0, strlen(ini_get('upload_max_filesize')-1)) * 1048576 : substr(ini_get('upload_max_filesize'), 0, strlen(ini_get('upload_max_filesize')));
	}

	/*
	 * @function fileChk
	 * @access private
	 *
	 * @param array $array
	 * @param array $files
	 * @param array $mime
	 * @param integer $asize
	 *
	 * @return string|false
	 */
	private function fileChk($array, $files, $mime, $asize)
	{
		$x = 0;
		foreach($array as $k => $v) {
			foreach($v as $key => $value) {

				$io = io::instance($array['tmp_name'][$key]);

				$array['name'][$key] = filter_var($array['name'][$key], FILTER_SANITIZE_STRING);

				if ((!self::_fcmd($array['tmp_name'][$key])) &&
					(!$io->__main($array['tmp_name'][$key])) &&
					(!self::fileChkName($array['name'][$key], $files)) ||
					(!self::fileChkMime($array['type'][$key], $mime)) ||
					(!self::fileChkSize($array['size'][$key], self::getMax())) ||
					(!self::fileChkErr($array['error'][$key]))) {
					$x = 1;

					$f = (!self::fileChkName($array['name'][$key], $files)) ? 1 : 0;
					if ((!$f)&&(!self::fileChkMime($array['type'][$key], $mime))){ $f=2; }
					if ((!$f)&&(!self::fileChkSize($array['size'][$key], self::getMax()))){ $f=3; }
					if ((!$f)&&(self::fileChkErr($array['error'][$key]))){ $f=4; }
					if ((!$f)&&(!$io->__main($array['tmp_name'][$key]))){ $f=5; }
					if ((!$f)&&(!self::_fcmd($array['tmp_name'][$key]))){ $f=6; }
					break;
				}
			}
		}

		return ($x===1) ? $f : false;
	}

	/*
	 * @function fileChkName
	 * @access private
	 *
	 * @param string $name
	 * @param array $files
	 *
	 * @return boolean
	 */
	private function fileChkName($name, $files)
	{
		return (preg_match(self::buildRegex($files, 'x'), $name)) ? true : false;
	}

	/*
	 * @function fileChkMime
	 * @access private
	 *
	 * @param string $type
	 * @param array $mime
	 *
	 * @return boolean
	 */
	private function fileChkMime($type, $mime)
	{
		return (in_array($type, $mime)) ? true : false;
	}

	/*
	 * @function fileChkSize
	 * @access private
	 *
	 * @param integer $size
	 * @param integer $asize
	 *
	 * @return boolean
	 */
	private function fileChkSize($size, $asize)
	{
		return (($size>0)&&($size<=$asize)) ? true : false;
	}

	/*
	 * @function fileChkErr
	 * @access private
	 *
	 * @param string $err
	 *
	 * @return string|false
	 */
	private function fileChkErr($err)
	{
		return ($err === 0) ? false : $err;
	}

	/*
	 * @function fErr
	 * @access private
	 *
	 * @param integer $c
	 *
	 * @return string
	 */
	public function fErr($c)
	{
		if ((!empty($c))&&(is_int($c))){
			switch($c){
				case 1:
					return 'File name uploaded is invalid, files can only contain spaces, hyphens, underscores and periods and end with .pdf';
					break;
				case 2:
					return 'File mime type is invalid, only PDF formatted documents are supported';
					break;
				case 3:
					return 'File size is invalid, currently only accepts documents up to '.self::getMax(true).'B';
					break;
				case 4:
					return 'An internal error with file was found, maybe a partial upload occured or other encoding problem transpired with original creation of the document you are trying to print';
					break;
				case 5:
					return 'A race condition, attempt to read locked file by another process or invalid file type (as common with renamed files which are not true PDF documents) has occured';
					break;
				case 6:
					return 'You uploaded an invalid PDF document';
					break;
				default:
					return 'An unknown error occured in which no response code was found, if you believe this to be a problem with this software please report it';
					break;
			}
		}else{
			return false;
		}
	}

	/*
	 * @function genErr
	 * @access private
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function genErr($string)
	{
		return sprintf('<p class="err"><img src="images/icons/icon-error.png" />&nbsp;%s</p>', $string);
	}

	/*
	 * @function genSucc
	 * @access private
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function genSucc($string)
	{
		return sprintf('<p class="good"><img src="images/icons/icon-ok.png" />&nbsp;%s</p>', $string);
	}

	/*
	 * @function genErrIco
	 * @access private
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function genErrIco($string)
	{
		return '<img src="images/icons/icon-error.png" />';
	}

	/*
	 * @function buildRegex
	 * @access private
	 *
	 * @param array $array
	 * @param boolean $a
	 * @param boolean $f
	 *
	 * @return string
	 */
	private function buildRegex($array, $a=false, $f=false)
	{
		$fnd = array('/-/', '/ /');
		$rplce = array('\-', '\_');
		$l=0;
		$x = ($a==='x') ? '/^[A-Za-z0-9-_.\s+]+\.([' : '/^([';
		foreach($array as $key => $value) {
			$x .= preg_replace($fnd, $rplce, $value).'|';
			$l = ($l>strlen($value)) ? $l : strlen($value);
		}
		$x .= ($a!=='x') ? ']){1,'.$l.'}$/i' : ']){1,'.$l.'}$/i';
		return (($a)&&($a!=='x')) ? str_replace('|]){1,'.$l.'}$/i', ']){1,2}[\d+]{5,15}$/i', $x) : str_replace('|]){1,'.$l.'}$/i', ']){1,'.$l.'}$/i', $x);
	}

	/*
	 * @function filter
	 * @access private
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function _filter($string)
	{
		return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRING_HIGH);
	}

	/*
	 * @function _fcmd
	 * @access private
	 *
	 * @param string $file
	 *
	 * @return boolean
	 */
	private function _fcmd($file)
	{
		$cmd = self::$tools['file'].' '.$file;
		$r = `$cmd`;
		return (preg_match('/PDF document/', $r));
	}
}