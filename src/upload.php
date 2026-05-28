<?php

$app = require __DIR__ . '/init.php';

// accept=".jpg,.jpeg,.bmp,.png,.gif"
// accept=".mkv,.mp4,.mov,.avi,.mpg,.mpeg,.mts,.wmv,.ts,.flv,.webm,.3gp"
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	if(!extension_loaded('fileinfo')) die('fileinfo required'); // You're fucked if you don't have that
	if(!($_FILES['vidup']['name'] ?? '')) {
		header('Set-Cookie: uperr=' . rawurlencode('<font color="red"><b>Error: Video file is not present</b></font>'));
		header('location: /upload.php');
		ob_flush(); flush(); die();
	}

	if($_FILES['vidup']['size'] > 1024 * 1024 * 111) {
		header('Set-Cookie: uperr=' . rawurlencode('<font color="red"><b>Error: Filesize exceeds 111 MiB</b></font>'));
		header('location: /upload.php');
		ob_flush(); flush(); die();
	}

	if(!(trim($_POST['title'] ?? ''))) {
		header('Set-Cookie: uperr=' . rawurlencode('<font color="red"><b>Error: Title is not present</b></font>'));
		header('location: /upload.php');
		ob_flush(); flush(); die();
	}

	if($_FILES['vidup']['error'] ?? 1 !== 0) {
		header('Set-Cookie: uperr=' . rawurlencode('<font color="red"><b>Error: Unknown uploading error happened. Code=' . $_FILES['vidup']['error'] . '</b></font>'));
		header('location: /upload.php');
		ob_flush(); flush(); die();
	}

	$finfo = new finfo(FILEINFO_MIME_TYPE); // file type checker

	$MIMEType = $finfo->file($_FILES['vidup']['tmp_name']);

	if(!(
		$MIMEType === 'video/x-matroska' ||
		$MIMEType === 'video/mp4' ||
		$MIMEType === 'video/quicktime' ||
		$MIMEType === 'video/x-msvideo' ||
		$MIMEType === 'video/mpeg' ||
		$MIMEType === 'video/MP2T' ||
		$MIMEType === 'video/x-ms-wmv' ||
		$MIMEType === 'video/mp2t' ||
		$MIMEType === 'video/x-flv' ||
		$MIMEType === 'video/webm' ||
		$MIMEType === 'video/3gpp'
	)) {
		header('Set-Cookie: uperr=' . rawurlencode('<font color="red"><b>Error: Incorrect Video MIME-Type</b></font>'));
		header('location: /upload.php');
		 ob_flush(); flush(); die();
	}

	$randstr = bin2hex(random_bytes(24));

	// make subfolders for performance optimization
	$frag = substr($ransstr, 0, 2);
	mkdir($app['env']['VIDEODIR'] . $frag);

	// move video
	move_uploaded_file($_FILES['vidup']['tmp_name'], $app['env']['VIDEODIR'] . $frag . '/' . $randstr . '.mp4');

	//------- check thumbnail mime

	$pMIMEType = $finfo->file($_FILES['thumbup']['tmp_name']);

	if(!(
		$pMIMEType === 'image/jpeg' ||
		$pMIMEType === 'image/jpg' ||
		$pMIMEType === 'image/bmp' ||
		$pMIMEType === 'image/png' ||
		$pMIMEType === 'image/gif'
	)) {
		header('Set-Cookie: uperr=' . rawurlencode('<font color="red"><b>Error: Incorrect Image MIME-Type</b></font>'));
		header('location: /upload.php');
		ob_flush(); flush(); die();
	}
}

?>

<?php
// crude mechanism to deliever error message
$msg = '';
if($_COOKIE['uperr'] ?? '') {
	$msg = $_COOKIE['uperr'];
	header('Set-Cookie: uperr=; Max-Age=0');
}
?>
<?= $app['layout']['head'] ?>
<?= $app['layout']['main'] ?>

<style>
	.c {
		margin-right: 210px;
	}
	label {
		display: block;
		position: relative;
		width: 200px;
		left: 0px;
		right: 0px;
		top: 0px;
		bottom: 1lh;
		font-size: 1.5em;
	}
	.obje {
		display: block;
		position: relative;
		left: 0px;
		margin-left: 210px;
		right: 0px;
		top: -1.5lh;
		width: 100%;
	}
	sup {
		color: red;
	}
</style>
<?php echo $msg . '<br>'; ?>
<!-- empty action so it would post to same page -->
<form action="" method="POST" enctype="multipart/form-data">
	<div class="c">
		<label>Uploading a video</label>
		<div class="obje">
			This is a setup wizard-like, step-by-step process for video uploading.
			I designed this in order to be very intuitive for low-intelligence persons,
			or non-technically proficient persons. This is also highly compatible with even old machines.<br><br>

			You simply fufill the required objectives in order to upload a video-file to this platform:
			<ul>
				<li>Video title<sup>*</sup></li>
				<li>Video upload (to obtain the video file)<sup>*</sup></li>
				<li>Thumbnail upload</li>
				<li>Video description</li>
			</ul>
			<span style="font-size:.9em"><sup>*</sup> means required input.</span><br><br>
			Then press the button to upload your video!
		</div>
	</div>
	<div class="c">
		<label>Video title<sup>*</sup></label>
		<input required class="obje" name="title" required>
	</div>
	<div class="c">
		<label>Video upload<sup>*</sup></label>
		<input type="file" class="obje" accept=".mkv,.mp4,.mov,.avi,.mpg,.mpeg,.mts,.wmv,.ts,.flv,.webm,.3gp,.mp4v,.mpg4" name="vidup" required>
	</div>
	<div class="c">
		<label>Thumbnail upload</label>
		<input type="file" class="obje" accept=".jpg,.jpeg,.bmp,.png,.gif" name="thumbup">
	</div>
	<div class="c">
		<label>Video description<br>
			<span style="font-size:0.5em">(Supports BBCode)</span></label>
		<!-- margin is to account for the extra line, even though line is smaller, 1.5lh is still correct -->
		<!-- due to the linebreak (which what makes the space) still being the same 1.5em -->
		<textarea rows="25" class="obje" style="margin-top:-1.5lh;margin-bottom:.75lh;" name="desc" placeholder="I love this platform!" class="obje"></textarea>
	</div>
	<div class="c">
		<label>Submit</label>
		<button class="h3d-button obje" style="font-size: 1.33em">Upload your video!</button>
	</div>
	<div class="c">
		<label>Progress</label>
		<div class="obje">
			<progress id="progress" value="0" min="0" max="100" style="width:100%;height:32px"></progress><br>
			<div style="font-size:1.33em"><span id="percentage">0%</span> <span id="bytes">0/0</span></div>
		</div>
	</div>
</form>
<?= $app['layout']['footers'][0] ?>
<?= $app['layout']['footers'][1] ?>
<?= $app['layout']['footers'][2] ?>
