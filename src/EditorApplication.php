<?php

namespace DubeDev\Util\SimpleEditor;

/**
 * A default implementation for the SimpleEditor backend
 * for quick use and example purposes.
 */
class EditorApplication {

	private $fileMap = null;

	function __construct($editDirectory) {
		$files = glob($editDirectory.'/*');
		$files = array_filter($files, 'is_file');
		$files = array_filter($files, function ($file) {
			if (substr($file, 0, 1) == '.')
				return false;
			return true;
		});

		$map = new FileMap();

		foreach ($files as $fpath) {
			$fname = basename($fpath);

			$map->add_file($fname, $fpath);
		}

		$this->fileMap = $map;
	}

	function post_load_file() {
		$hash = $_POST['hash'];
		$fo = $this->fileMap->get_file($hash);
		if ($fo !== null) {
			$fo->load();
			$text = $fo->get_contents();
			if ($text !== false) {
				$response = array(
					'status' => "okay",
					'contents' => $text,
				);
			} else {
				$response = array(
					'status' => "error",
				);
			}
		} else {
			$response = array(
				'status' => "error",
			);
		}
		ob_clean();
		echo json_encode($response);
	}

	function post_save_file() {
		$hash = $_POST['hash'];
		$fo = $this->fileMap->get_file($hash);
		if ($fo !== null) {
			$fo->set_contents(strval($_POST['contents']));
			$result = $fo->save();
			if ($result !== false) {
				$response = array(
					'status' => "okay",
					'written' => $result
				);
			} else {
				$response = array(
					'status' => "error",
					'result' => $result
				);
			}
		} else {
			$response = array(
				'status' => "error",
			);
		}
		ob_clean();
		echo json_encode($response);
	}

	function run() {
		$here = realpath(dirname(__FILE__));

		// Check for POST message from ajax script
		if (isset($_POST['ddev_editor_operation'])) {
			$cmd = $_POST['ddev_editor_operation'];
			if ($cmd === "load_file") {
				$this->post_load_file();
				return;
			}
			else if ($cmd === "save_file") {
				$this->post_save_file();
				return;
			}
		}

		$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );

		$editorConfig = array();
		$editorConfig['submit'] = $url;

		$editorConfig = json_encode($editorConfig);

		$fileMap = $this->fileMap->list_files();

		// If no return value,
		$editor_tmpl = $here.'/html/editor_full.html.php';
		include($editor_tmpl);
	}
}
