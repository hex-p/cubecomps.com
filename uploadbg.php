<?
/*
 * See comments at lib_upload.php
 */
define("MAX_FILE_SIZE",100); // Kb

if(!isset($_SESSION)) session_start();
require_once "lib_ref_admin.php";

$IE = (preg_match("/msie/i",$_SERVER["HTTP_USER_AGENT"]) || preg_match("/internet explorer/i",$_SERVER["HTTP_USER_AGENT"]));

if ($IE)
{
?>
<HTML>
<HEAD>
<style type="text/css">
	body {background-color:#6b7b71;font-family:arial,sans-serif;font-size:12px;color:#FFFFCC;}
</style>
</HEAD>
<BODY>
<?
}

function _error($msg)
{
	global $IE;
	if ($IE)
	{
		echo $msg."\r\n";
		echo "<script>self.focus();</script>\r\n";
		echo "</BODY></HTML>";
	}
	else
		echo $msg;
	die();
}

$test = preg_match("~^test\\.~i",$_SERVER["HTTP_HOST"]);

if ($_FILES["file"]["error"] > 0) _error ("Error: " . $_FILES["file"]["error"]);
if ($_FILES["file"]["type"] != "image/jpeg" && $_FILES["file"]["type"] != "image/pjpeg") _error ("Error: not a JPEG image (".$_FILES["file"]["type"].")");
$size = $_FILES["file"]["size"] / 1024;
if ($size > MAX_FILE_SIZE) _error ("Error: file exceeds ".MAX_FILE_SIZE." Kb");
$img = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
if (!$img) _error ("Error: not a real JPEG image in its inside!");
imagedestroy($img);
//
if (!move_uploaded_file ($_FILES["file"]["tmp_name"], DIR_UPLOADS_ABS.($test?"test_":"")."bg_" . $_SESSION["c_id"] . ".jpg"))
	_error("Can't copy file!!!");
else if (!$IE)
	die("OK");
else
{
?>

<script>
opener.location.reload();
window.close();
</script>

<?
}
?>
</BODY>
</HTML>
