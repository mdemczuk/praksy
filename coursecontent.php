<?php

	session_start();

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
	}
	else {
		$admin_logged = false;
	}

	if($admin_logged && isset($_POST['edit_lesson'])) {
		$edit_lesson_mode = true;
	}
	elseif($admin_logged && isset($_POST['edit_main'])) {
		$edit_main_mode = true;
	}
	else {
		$edit_lesson_mode = false;
		$edit_main_mode = false;
	}
	
	if(isset($_GET['courseid'])) {
		$_SESSION['courseid'] = $_GET['courseid'];
	}
	$id = $_SESSION['courseid'];
	if(!isset($_SESSION["loggedin$id"]) && !$admin_logged) {				# checking if the person is already logged in for this course
		header("Location:course.php?courseid=$id");
		exit();
	}

	if(isset($_SESSION['password_change_message'])){
		$message = $_SESSION['password_change_message'];
		echo "$message <br/>";
		unset($_SESSION['password_change_message']);
	}

	require_once "connect.php";

	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);
	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$id = $_SESSION['courseid'];
		include_once('functions.php');
		$sql = "SELECT id, course_id, name, lesson_number, AES_DECRYPT(content, '$key') AS content FROM lessons WHERE course_id=$id";

		if($result = @$connection->query($sql)) {
			$num_lessons = $result->num_rows;

			$names = array();
			$ids = array();
			$lesson_numbers = array();
			$content = array();
			if($num_lessons > 0) {
				for($x = 0; $x < $num_lessons; $x++) {					# fetch data from all rows
					$lesson = $result->fetch_assoc();
					array_push($names, $lesson['name']);
					array_push($ids, $lesson['id']);
					array_push($lesson_numbers, $lesson['lesson_number']);
					array_push($content, $lesson['content']);
				}
			} 
		}

		$sql_main = "SELECT AES_DECRYPT(main, '$key') AS main FROM courses WHERE id=$id";

		if($result_main = @$connection->query($sql_main)) {
			if($result_main->num_rows > 0) {
				$main_page = $result_main->fetch_assoc();
				$main = $main_page['main'];
			} 
		}

		$connection->close();
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4Learning</title>
	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/sample.js"></script>
	<style>
		#page-title {
			float: left;
		}
		#options {
			float: right;
			text-align:  right;
			padding: 20px;
		}
		#all_lessons {
			float: left;
			width: 160px;
			background-color: gray;
		}
		.lesson {
			border-style: solid;
			padding: 10px;
		}
		#lesson_content {
			padding: 20px;
			float: left;
		}
	</style>

</head>

<body>
	<div class="container">
		<a href="coursecontent.php" style="color: black; text-decoration: none;">
			<div id="page-title">
				<h2><?php echo $_SESSION['course_title']; ?></h2>
			</div>
		</a>

		<div id="options">
			<?php
				if($admin_logged && isset($_GET['num'])) { ?>
					<form method="post">
						<?php
							$lesson_num = $_GET['num'];
							$_SESSION['lessonid'] = $ids[($lesson_num - 1)];
						?>
						<input type="submit" value="Edit lesson content" name="edit_lesson" />
					</form>
					<br />
					<form action="deletelesson.php" method="post">
						<input type="submit" value="Delete this lesson" name="delete_lesson" />
					</form>
					<?php
				}
				elseif($admin_logged && !isset($_GET['num'])) { ?>
					<form method="post">
						<input type="submit" value="Edit main page" name="edit_main" />
					</form>
					<br />
					<form action="delete.php?courseid=<?php echo $id; ?>" method="post">
						<input type="submit" value="Delete this course" name="delete_course" />
					</form>
					<?php
				}
				else {
					echo "<a href='passchange.php'>Change password</a>";
				}
			?>
		</div>
		<div style="clear: both;"></div>

		<div id="all_lessons">
			<?php  
				for($x = 0; $x < $num_lessons; $x++) {
					echo "<a href='coursecontent.php?num={$lesson_numbers[$x]}' style='color: black; text-decoration: none;''>";
					echo "<div class='lesson'>".$names[$x]."</div></a>";	
				}
			if($admin_logged) { ?>
				<a href="addlesson.php" style='color: black; text-decoration: none;'>
					<div class="lesson">
						[Add lesson]
					</div>
				</a>
				<?php
			} ?>
		</div>

		<div id="lesson_content">

			<?php

				if(isset($_SESSION['lesson_message'])){
					$lesson_message = $_SESSION['lesson_message'];
					echo "$lesson_message <br />";
					unset($_SESSION['lesson_message']);
				}
				if(isset($_SESSION['del_lesson_error'])){
					$del_lesson_error = $_SESSION['del_lesson_error'];
					echo "$del_lesson_error <br />";
					unset($_SESSION['del_lesson_error']);
				}

				if(isset($_GET['num'])) {
					$lesson_num = $_GET['num'];
					if($edit_lesson_mode) { ?>
						
						<form action="editlesson.php" method="post">
							<div style="text-align: center;">
								<?php
									$_SESSION['lessonid'] = $ids[($lesson_num - 1)];
									$_SESSION['lesson_num'] = $lesson_num;
									$lesson_name = $names[($lesson_num - 1)];
									echo "<textarea type='text' name='lesson_name' cols=100 rows=2 required>".$lesson_name."</textarea>";
								?>
							</div>
							<br /> <br />
							<div style="text-align: center;">
								<?php
									$lesson_content = $content[($lesson_num - 1)];
									echo "<textarea name='content' id='content'>".$lesson_content."</textarea>";
								?>
							</div>
							<div style="text-align: center;">
								<br/><input type="submit" value="Save changes" name="save" />
							</div>
						</form>
												
						<script>

							CKEDITOR.replace( 'content', {
								/* Ensure that htmlwriter plugin, which is required for this sample, is loaded. */
								extraPlugins: 'htmlwriter',

								/* Style sheet for the contents */
								contentsCss: 'body {color:#000; background-color#:FFF;}',

								/* Simple HTML5 doctype */
								docType: '<!DOCTYPE HTML>',

								/* Allowed content rules which beside limiting allowed HTML
					 			* will also take care of transforming styles to attributes
					 			* (currently only for img - see transformation rules defined below).
								* Read more: http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter */
								allowedContent:
									'h1 h2 h3 p pre[align]; ' +
									'blockquote code kbd samp var del ins cite q b i u strike ul ol li hr table tbody tr td th caption; ' +
									'img[!src,alt,align,width,height]; font[!face]; font[!family]; font[!color]; font[!size]; font{!background-color}; a[!href]; a[!name]',

								/* Core styles. */
								coreStyles_bold: { element: 'b' },
								coreStyles_italic: { element: 'i' },
								coreStyles_underline: { element: 'u' },
								coreStyles_strike: { element: 'strike' },

								/* Font face. */
								// Define the way font elements will be applied to the document.
								// The "font" element will be used.
								font_style: {
									element: 'font',
									attributes: { 'face': '#(family)' }
								},

								/* Font sizes. */
								fontSize_sizes: 'xx-small/1;x-small/2;small/3;medium/4;large/5;x-large/6;xx-large/7',
								fontSize_style: {
									element: 'font',
									attributes: { 'size': '#(size)' }
								},

								/* Font colors. */

								colorButton_foreStyle: {
									element: 'font',
									attributes: { 'color': '#(color)' }
								},

								colorButton_backStyle: {
									element: 'font',
									styles: { 'background-color': '#(color)' }
								},

								/* Styles combo. */
								stylesSet: [
									{ name: 'Computer Code', element: 'code' },
									{ name: 'Keyboard Phrase', element: 'kbd' },
									{ name: 'Sample Text', element: 'samp' },
									{ name: 'Variable', element: 'var' },
									{ name: 'Deleted Text', element: 'del' },
									{ name: 'Inserted Text', element: 'ins' },
									{ name: 'Cited Work', element: 'cite' },
									{ name: 'Inline Quotation', element: 'q' }
								],

								on: {
									pluginsLoaded: configureTransformations,
									loaded: configureHtmlWriter
								}
							});

							/* Add missing content transformations. */
							function configureTransformations( evt ) {
								var editor = evt.editor;

								editor.dataProcessor.htmlFilter.addRules( {
									attributes: {
										style: function( value, element ) {
											// Return #RGB for background and border colors
											return CKEDITOR.tools.convertRgbToHex( value );
										}
									}
								} );

								// Default automatic content transformations do not yet take care of
								// align attributes on blocks, so we need to add our own transformation rules.
								function alignToAttribute( element ) {
									if ( element.styles[ 'text-align' ] ) {
										element.attributes.align = element.styles[ 'text-align' ];
										delete element.styles[ 'text-align' ];
									}
								}
								editor.filter.addTransformations( [
									[ { element: 'p',	right: alignToAttribute } ],
									[ { element: 'h1',	right: alignToAttribute } ],
									[ { element: 'h2',	right: alignToAttribute } ],
									[ { element: 'h3',	right: alignToAttribute } ],
									[ { element: 'pre',	right: alignToAttribute } ]
								] );
							}		

							/* Adjust the behavior of htmlWriter to make it output HTML like FCKeditor. */
							function configureHtmlWriter( evt ) {
								var editor = evt.editor,
									dataProcessor = editor.dataProcessor;

								// Out self closing tags the HTML4 way, like <br>.
								dataProcessor.writer.selfClosingEnd = '>';

								// Make output formatting behave similar to FCKeditor.
								var dtd = CKEDITOR.dtd;
								for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
									dataProcessor.writer.setRules( e, {
										indent: true,
										breakBeforeOpen: true,
										breakAfterOpen: false,
										breakBeforeClose: !dtd[ e ][ '#' ],
										breakAfterClose: true
									});
								}
							}	

						</script>

						<?php 
					}
					else {
						$lesson_content = $content[($lesson_num - 1)];
						echo $lesson_content;
					}
				}
				elseif($edit_main_mode) { ?>
					<form action="editlesson.php" method="post">
						<div style="text-align: center;">
							<?php
								echo "<textarea name='main' id='main'>".$main."</textarea>";
							?>
						</div>
						<div style="text-align: center;">
							<br/><input type="submit" value="Save changes" name="save_main" />
						</div>
					</form>
					
					<script>

						CKEDITOR.replace( 'main', {
							/* Ensure that htmlwriter plugin, which is required for this sample, is loaded. */
							extraPlugins: 'htmlwriter',

							/* Style sheet for the contents */
							contentsCss: 'body {color:#000; background-color#:FFF;}',

							/* Simple HTML5 doctype */
							docType: '<!DOCTYPE HTML>',

							/* Allowed content rules which beside limiting allowed HTML
					 		* will also take care of transforming styles to attributes
					 		* (currently only for img - see transformation rules defined below).
							* Read more: http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter */
							allowedContent:
								'h1 h2 h3 p pre[align]; ' +
								'blockquote code kbd samp var del ins cite q b i u strike ul ol li hr table tbody tr td th caption; ' +
								'img[!src,alt,align,width,height]; font[!face]; font[!family]; font[!color]; font[!size]; font{!background-color}; a[!href]; a[!name]',

							/* Core styles. */
							coreStyles_bold: { element: 'b' },
							coreStyles_italic: { element: 'i' },
							coreStyles_underline: { element: 'u' },
							coreStyles_strike: { element: 'strike' },

							/* Font face. */
							// Define the way font elements will be applied to the document.
							// The "font" element will be used.
							font_style: {
								element: 'font',
								attributes: { 'face': '#(family)' }
							},

							/* Font sizes. */
							fontSize_sizes: 'xx-small/1;x-small/2;small/3;medium/4;large/5;x-large/6;xx-large/7',
							fontSize_style: {
								element: 'font',
								attributes: { 'size': '#(size)' }
							},

							/* Font colors. */

							colorButton_foreStyle: {
								element: 'font',
								attributes: { 'color': '#(color)' }
							},

							colorButton_backStyle: {
								element: 'font',
								styles: { 'background-color': '#(color)' }
							},

							/* Styles combo. */
							stylesSet: [
								{ name: 'Computer Code', element: 'code' },
								{ name: 'Keyboard Phrase', element: 'kbd' },
								{ name: 'Sample Text', element: 'samp' },
								{ name: 'Variable', element: 'var' },
								{ name: 'Deleted Text', element: 'del' },
								{ name: 'Inserted Text', element: 'ins' },
								{ name: 'Cited Work', element: 'cite' },
								{ name: 'Inline Quotation', element: 'q' }
							],

							on: {
								pluginsLoaded: configureTransformations,
								loaded: configureHtmlWriter
							}
						});

						/* Add missing content transformations. */
						function configureTransformations( evt ) {
							var editor = evt.editor;

							editor.dataProcessor.htmlFilter.addRules( {
								attributes: {
									style: function( value, element ) {
										// Return #RGB for background and border colors
										return CKEDITOR.tools.convertRgbToHex( value );
									}
								}
							} );

							// Default automatic content transformations do not yet take care of
							// align attributes on blocks, so we need to add our own transformation rules.
							function alignToAttribute( element ) {
								if ( element.styles[ 'text-align' ] ) {
									element.attributes.align = element.styles[ 'text-align' ];
									delete element.styles[ 'text-align' ];
								}
							}
							editor.filter.addTransformations( [
								[ { element: 'p',	right: alignToAttribute } ],
								[ { element: 'h1',	right: alignToAttribute } ],
								[ { element: 'h2',	right: alignToAttribute } ],
								[ { element: 'h3',	right: alignToAttribute } ],
								[ { element: 'pre',	right: alignToAttribute } ]
							] );
						}		

						/* Adjust the behavior of htmlWriter to make it output HTML like FCKeditor. */
						function configureHtmlWriter( evt ) {
							var editor = evt.editor,
								dataProcessor = editor.dataProcessor;

							// Out self closing tags the HTML4 way, like <br>.
							dataProcessor.writer.selfClosingEnd = '>';

							// Make output formatting behave similar to FCKeditor.
							var dtd = CKEDITOR.dtd;
							for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
								dataProcessor.writer.setRules( e, {
									indent: true,
									breakBeforeOpen: true,
									breakAfterOpen: false,
									breakBeforeClose: !dtd[ e ][ '#' ],
									breakAfterClose: true
								});
							}
						}	

					</script>

					<?php
					}
				else {
					echo $main;
				}
			?>

		</div>
		<div style="clear: both;"></div>
	</div>

	<br />
	<p><a href="index.php">Home page</a></p>
	<p><a href="logout.php">Log out</a></p>
	<br /><br />

</body>
</html>