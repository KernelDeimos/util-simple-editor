<?php

namespace DubeDev\Util\SimpleEditor;

/**
 * This class stores the location of a file and
 * any additional editor-specific properties.
 * It also performs the load an save operations
 * so that the editor can handle each file with
 * specific parameters in the future (i.e. line
 * endings and encoding).
 */
class File
{
	private $path = null;
	private $name = "";
	private $data = "SimpleEditor: No data set\n";

	private $saveEOL;
	private $workEOL;

	function __construct($name, $path) {
		$this->name = $name;
		$this->path = $path;

		// Default to OS' EOL
		$this->workEOL = "\n";
		$this->saveEOL = PHP_EOL;
	}

	function get_name() {
		return $this->name;
	}

	function load() {
		$data = file_get_contents($this->path);

		$contents = $this->change_eol($this->workEOL);

		$this->data = $data;
	}
	function save() {
		$data = $this->change_eol($this->data, $this->saveEOL);
		return file_put_contents($this->path, $data);
	}

	function get_contents() {
		return $this->data;
	}

	function set_contents($contents) {
		$contents = $this->change_eol($contents, $this->workEOL);
		$this->data = $contents;
	}

	function change_eol($text, $newEOL) {
		$text = str_replace("\r\n", $newEOL, $text);
		$text = str_replace("\r", $newEOL, $text);
		$text = str_replace("\n", $newEOL, $text);
		return $text;
	}
}
