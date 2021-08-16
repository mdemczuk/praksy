<?php
	session_start();
	$id = $_SESSION['courseid'];

	# checking if admin is logged in
	if(isset($_SESSION['admin']) && ($_SESSION['admin']==true)) {
		$admin_logged = true;
		if(isset($_SESSION['add_lesson_error'])){
			$add_lesson_error = $_SESSION['add_lesson_error'];
			echo "$add_lesson_error <br />";
			unset($_SESSION['add_lesson_error']);
		}
	}
	else {
		$admin_logged = false;
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_pswd, $db_name);		# connecting to database

	if($connection->connect_errno!=0) {	
		echo "Error: ".$connection->connect_errno;
	}
	else {
		$sql = "SELECT * FROM lessons WHERE course_id = $id ORDER BY lesson_number DESC LIMIT 1";
		$result = mysqli_query($connection, $sql);
		if($result) {
			if($result->num_rows == 0) {
				$last_lesson_num = 0;
			}
			else {
				$last_lesson = $result->fetch_assoc();
				$last_lesson_num = $last_lesson['lesson_number'];
			}
		}

		if(isset($_POST['save'])) {
			# retrieving data from form
			$lesson_name = $_POST['lesson_name'];
			$editor_data = $_POST['content'];
			$new_lesson_num = $last_lesson_num + 1;

			# query to add new lesson
			$sql = "INSERT INTO lessons (course_id, name, lesson_number, content) VALUES (".$id.", '".$lesson_name."', ".$new_lesson_num.", '".$editor_data."')";

			$result = mysqli_query($connection, $sql);
			if($result) {
				$_SESSION['lesson_message'] = '<span style="color:green"><b>Succesfully changed lesson content!</b></span>';
				header("Location: coursecontent.php?num=$new_lesson_num");
			}
			else {
				$_SESSION['add_lesson_error'] = '<span style="color:red">'."Something went wrong, please try again.".'</span>';
			}
			
		}
	}

	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Ideas4learning</title>
	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/sample.js"></script>
</head>

<body>
	
	<form method="post">
		<div style="text-align: center;">
			<textarea type='text' name='lesson_name' cols=100 rows=2 required></textarea>
		</div>
		<br /> <br />
		<div style="text-align: center;">
			<textarea name='content' id='content'></textarea>
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

	<p><a href="index.php">Home page</a></p>

</body>
</html>