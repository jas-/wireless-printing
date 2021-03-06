<?php

/* prevent direct access */
if (!defined('__SITE')) exit('No direct calls please...');

/**
 * Perform IO tests emulating 'file' command in linux with additional
 * race condition validation
 *
 * LICENSE: This source file is subject to version 3.01 of the GPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.html.  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 *
 * @category   IO
 * @author     jason.gerfen@gmail.com
 * @copyright  2008-2011 Jason Gerfen
 * @license    http://www.gnu.org/licenses/gpl.html  GPL License 3
 * @version    0.3
 */

class io
{
	/*
	 * @name $magic
	 * @var array Nested array used to store file type valiation details
	 *
	 * Each array should indicate the file type. The nested array contains the
	 * location (bytes to read) within file as the key and string at the location for validation
	 * as the value.
	 */
	private $magic = array('pdf'=>array('0'=>'/^%[PDF|FDF]/',
										'5'=>'/^/\b, version %c/',
										'7'=>'/^/\b.%c/'));

	protected static $instance;

	private function __construct($file)
	{
		return;
	}

	public static function instance($file)
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new self($file);
		}
		return self::$instance;
	}

	/*
	 * @name __main
	 * @var $file The specified filename
	 * @return boolean
	 */
	function __main($file)
	{
		if (!$this->__exists($file)) {
			return false;
		}

		$h = $this->__open($file);
		if (!$h) {
			return false;
		}

		if (!$this->__lockr($h)) {
			return false;
		}

		if (!$this->__validate($this->__stat($file), $file)) {
			return false;
		}

		if (!$this->__magic($h, $file)) {
			return false;
		}
		return true;
	}

	/*
	 * @name __exists
	 * @var $file The specified filename
	 * @return boolean
	 */
	function __exists($file)
	{
		return (!empty($file)) ? file_exists($file) : false;
	}

	/*
	 * @name __stat
	 * @var $file The specified filename
	 * @return mixed Returns an array of file details or false
	 */
	function __stat($file)
	{
		return (is_link($file)) ? lstat($file) : (is_file($file)) ? stat($file) : false;
	}

	/*
	 * @name __validate
	 * @var $attr Array of files attributes
	 * @return boolean
	 */
	function __validate($attr, $file)
	{
		$n = $this->__stat($file);
		return ((is_array($attr)) && ($attr['size'] > 0) && ($attr['nlink'] <= 1) && ($attr['dev'] == $n['dev']) && ($attr['ino'] == $n['ino']) && (($attr['atime'] - $n['atime']) <= 10)) ? true : false;
	}

	/*
	 * @name __lockr
	 * @var $handle The currently opened file descriptor
	 * @return boolean
	 */
	function __lockr($handle)
	{
		return (is_resource($handle)) ? flock($handle, LOCK_SH) : false;
	}

	/*
	 * @name __lockw
	 * @var $handle The currently opened file descriptor
	 * @return boolean
	 */
	function __lockw($handle)
	{
		return (is_resource($handle)) ? flock($handle, LOCK_EX) : false;
	}

	/*
	 * @name __open
	 * @var $file The specified filename
	 * @return file descriptor of opened file
	 */
	function __open($file)
	{
		return (file_exists($file)) ? fopen($file, 'rb') : false;
	}

	/*
	 * @name __read
	 * @var $handle The file descriptor
	 * @var $bytes Location of data to be read
	 * @return array
	 */
	function __read($handle, $bytes)
	{
		fseek($handle, $bytes, SEEK_SET);
		return fgets($handle);
	}

	/*
	 * @name __close
	 * @var $handle The file descriptor
	 * @return boolean
	 */
	function __close($handle)
	{
		return (is_resource($handle)) ? fclose($handle) : false;
	}

	/*
	 * @name __unlock
	 * @var $handle The file descriptor
	 * @return boolean
	 */
	function __unlock($handle)
	{
		return (is_resource($handle)) ? flock($handle, LOCK_UN) : false;
	}

	/*
	 * @name __flush
	 * @var $handle The file descriptor
	 * @return boolean
	 */
	function __flush($handle)
	{
		return (is_resource($handle)) ? fflush($handle) : false;
	}

	/*
	 * @name __magic
	 * @var $data An array of file lines for comparision
	 * @var $magic The global array of file comparison options
	 * @return boolean
	 */
	function __magic($handle, $file)
	{
		foreach($this->magic as $type => $value) {
			foreach($value as $bytes => $regex) {
				return (preg_match($regex, $this->__read($handle, $bytes)));
			}
		}
		return false;
	}
}
