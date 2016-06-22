<?php
/**
 * Full page editor for DDev SimpleEditor.
 * Generates a complete HTML document to
 * implement SimpleEditor.
 */
?><!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>

		<!-- jQuery from CDN -->
		<script
		src="https://code.jquery.com/jquery-3.0.0.min.js"
		integrity="sha256-JmvOoLtYsmqlsWxa7mDSLMwa6dZ9rrIdtrrVYRnDRH0="
		crossorigin="anonymous"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>


		<!-- Vendor code -->
		<style><?php
			echo file_get_contents($here.'/html/vendor/linedtextarea/jquery-linedtextarea.css');
		?></style>
		<script><?php
			echo file_get_contents($here.'/html/vendor/linedtextarea/jquery-linedtextarea.js');
		?></script>

		<script type="text/javascript">
			$(document).ready(function () {
				$('#edit-area').linedtextarea();

				function SimpleEditor(config) {
					var self = this;

					self.config = config;
					self.hash = null;

					self._bind_handlers();
				}

				SimpleEditor.prototype._bind_handlers = function() {
					var self = this;

					// Bind form handlers
					$('#js-editor-save').on('click', function() {
						if (self.hash === null) {
							alert('Error: No file loaded!');
							return;
						}

						var contents = $("#edit-area").val();

						var data = {
							'ddev_editor_operation': 'save_file',
							'hash': self.hash,
							'contents': contents
						};

						var jqxhr = $.post(self.config.submit, data, function (resp) {
							if (resp.status == "okay") {
								alert('Written '+resp.written+' bytes!');
							} else {
								alert('Error saving!');
							}
						}, 'json').fail(function () {
							alert("Failed to save!");
						});
					});
					// Bind button handlers
					$('.js-file-load').on('click', function() {
						console.log("File clicked");
						var fileButton = $(this);

						var hash = $(this).data('hash');
						var data = {
							'ddev_editor_operation': 'load_file',
							'hash': hash
						};

						var jqxhr = $.post(self.config.submit, data, function (resp) {
							if (resp.status == "okay") {
								self.hash = hash;
								self.set_contents(resp['contents']);
								self.set_filename(fileButton.html());
							} else {
								alert('Failed to load!');
							}
						}, 'json').fail(function () {
							alert("Failed to load!");
						});
					});
				};

				SimpleEditor.prototype.set_contents = function(text) {
					var self = this;
					$("#edit-area").val(text);
				};

				SimpleEditor.prototype.set_filename = function(text) {
					var self = this;
					$("#js-editor-filename").html(text);
				};

				var configElement = $("#editor-config");
				var config = JSON.parse(configElement.text());

				var app = new SimpleEditor(config);
			});
		</script>
		<script
			id="editor-config"
			type="application/json"
		><?php
			echo $editorConfig;
		?></script>

		<style type="text/css">
			.col-nop {
				padding-left: 0;
				padding-right: 0;
			}
			.contents-row {
				position: absolute;
				height: 100%;
				width: 100%;
			}
			.menu-col {
				position: relative;
				height: 100%;
			}
			.editor-col {
				position: relative;
				height: 100%;
			}

			.fluid-header-box {
				position: relative;
				height: 100%;
				z-index: 100;
			}
			.fluid-header-box .head {
				position: relative;

				font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
				font-size: 22pt;

				z-index: 102;
				background-color: #196966;
				color: #FFF;
				height: 32pt;
				line-height: 32pt;
				padding: 0 10pt;
			}
			.fluid-header-box .stuff {
				position: relative;
				z-index: 101;
				height: 100%;
				margin-top: -32pt;
				padding-top: 32pt;
			}

			#edit-area {
				position: relative;
				left: 0;
				width: 100%;
				height: 100%;

				resize: none;

				border: none;
				/*border-left: 4px solid #45B3AF;*/
			}
			.linededit-fixer .linedwrap {
				height: 100%;
			}
			.linededit-fixer .linedtextarea {
				height: 100%;
			}
			.linededit-fixer .lines {
				height: 100% !important;
			}
			.linededit-fixer .linedwrap {
				border: none;
				border-left: 4px solid #45B3AF;
			}

			.fluid-header-box.for-menu {
				/*-webkit-box-shadow:inset -15px 0 15px -15px rgba(0,0,0,0.75);
				box-shadow:inset -15px 0 15px -15px rgba(0,0,0,0.75);*/
			}
			.fluid-header-box.for-menu {
				overflow-y: scroll;
			}
			.editor-col {
				z-index: 200;
				-webkit-box-shadow: 0 0 15px 0 rgba(0,0,0,0.4);
				box-shadow: 0 0 15px 0 rgba(0,0,0,0.4);
			}
			.fluid-header-box.for-editor .head {
				background-color: #45B3AF;
			}

			.file {
				height: 20pt;
				line-height: 20pt;
				padding: 0 10pt;

				background-color: #E0F4F6;
				border-bottom: 1px solid #C0E5E4;

				cursor: pointer;
			}
			.file:hover {
				background-color: #C0E5E4;
			}

			.button {
				display: inline-block;

				position: relative;

				height: 32pt;
				line-height: 32pt;
				padding: 0 10pt;

				font-size: 18pt;

				cursor: pointer;

				vertical-align: top;
			}
			.button .glyphicon {
				position: relative;
				height: 32pt;
				line-height: 32pt;
				margin: 0;
				padding: 0;

				vertical-align: top;

			}
			.button:hover {
				background-color: rgba(255,255,255,0.4);
			}

			.filename {
				display: inline-block;
				position: relative;
				height: 32pt;
				line-height: 32pt;
				padding: 0 10pt;

				font-size: 14pt;

				vertical-align: top;
			}

			.warning {
				float: right;

				display: inline-block;
				position: relative;
				margin-top: 3pt;
				height: 32pt;
				line-height: 13pt;
				padding: 0 10pt;
				font-size: 10pt;

				vertical-align: top;

				overflow: hidden;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid main-container">
			<div class="row contents-row">
				<div class="col col-nop col-sm-2 menu-col">
					<div class="fluid-header-box for-menu">
						<div class="head">
							Files
						</div>
						<div class="stuff">
							<div class="files-list">
								<?php foreach ($fileMap as $file) { ?>
								<div class="file js-file-load" data-hash="<?php echo $file['hash']; ?>">
									<?php echo $file['name']; ?>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col col-nop col-sm-10 editor-col">
					<div class="fluid-header-box for-editor">
						<div class="head">
							<div class="button button-save" id="js-editor-save">
								<span class="glyphicon glyphicon-floppy-save"></span>
								Save
							</div>
							<div class="filename" id="js-editor-filename">
								No File Loaded
							</div>
							<div class="warning">
								<strong>Warning:</strong><br />
								Margin may number extra lines
							</div>
							<!--
							<div class="button button-save">
								<span class="glyphicon glyphicon-calendar"></span>
								Date
							</div>
							-->
						</div>
						<div class="stuff">
							<span class="linededit-fixer">
								<textarea id="edit-area"></textarea>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
