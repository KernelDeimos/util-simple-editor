<?php

namespace DubeDev\Util\SimpleEditor;

/**
 * Stores a list of files to be recognized
 * by an editor instance.
 */
class FileMap {
	function __construct() {
		$this->files = array();
	}

	function add_file($name, $fpath) {
		// Determine file index
		$hash = sha1($fpath);
		// Construct file object
		$fObject = new File($name, $fpath);
		// Add file to hashmap
		$this->files[$hash] = $fObject;
	}

	function get_file($hash) {
		if (array_key_exists($hash, $this->files)) {
			$f = $this->files[$hash];
			return $f;
		}
		return null;
	}
	function list_files() {
		$returnArray = array();

		foreach ($this->files as $hash => $file) {
			$fileDict = array();
			$fileDict['hash'] = $hash;
			$fileDict['name'] = $file->get_name();

			$returnArray[] = $fileDict;
		}

		return $returnArray;
	}
}
