<?php

namespace DubeDev\Util\SimpleEditor;

class EditorSubmitHandler
{
	function __construct($fileMap, $postData) {
		$this->fileMap = $fileMap;
		$this->data = $postData;
	}

	function submit() {
		// Hash of file to edit
		$hash = $postData['hash'];

		$contents = $postData['contents'];

		$file = $this->fileMap->get_file();
		$file->set_contents($contents);
	}
}
