<?php
define('CURRENTDIR', dirname(__FILE__));
chdir(CURRENTDIR);
require_once("./flxziparchive.php");

$za = new FlxZipArchive();

$res = $za->open('homebackup_' . date("Y-m-d_H-i-s") . '.zip', ZipArchive::CREATE);

// zip all files
$files = file('filedef.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($res === true)
{
	foreach ($files as $file)
	{
		if (substr($file, 0, 1) == '/')
		{
			$za->addDir($_SERVER['DOCUMENT_ROOT'] . $file, substr($file, 1));
		}
		else
		{
			$za->addFile($_SERVER['DOCUMENT_ROOT'] . '/' . $file, $file);
		}
	}
	
	// backup sql
	//$User = "root";
	//$Password = "apmsetup";
	//$DatabaseName = "wordpress";
	//echo shell_exec("mysqldump -uroot -papmsetup wordpress");// > blog.sql");
	//echo shell_exec("cmd >> blog.sql");
	//$Results = shell_exec("mysqldump --allow-keywords --opt -u$User -p$Password $DatabaseName > $DatabaseName.sql");
	
	//$za->addFile('blog' . '.sql');
	
	// Backup Database
	$files = file('sqldef.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	
	$User = $files[0];
	$Password = $files[1];
	$Database[0] = $files[2];
	//$Database[1] = $files[3];
	
	for ($dbNum = 0; $dbNum < 1; $dbNum++)
	{
		$sqlFile[$dbNum] = CURRENTDIR . '/' . $Database[$dbNum] . '.sql';
		
		if (file_exists($sqlFile[$dbNum]))
		{
			unlink($sqlFile[$dbNum]);
		}
		
		//echo shell_exec("mysqldump --allow-keywords --opt -u$User -p$Password $Database");
		$Results = shell_exec('mysqldump --allow-keywords --opt -u' .
			$User . ' -p' . $Password . ' ' . $Database[$dbNum] .
			' > ' . $Database[$dbNum] . '.sql');
		
		$za->addFile($sqlFile[$dbNum], basename($sqlFile[$dbNum]));
	}
	
	$za->close();
	
	for ($dbNum = 0; $dbNum < 1; $dbNum++)
	{
		if (file_exists($sqlFile[$dbNum]))
		{
			unlink($sqlFile[$dbNum]);
		}
	}
}



/*$za = new FlxZipArchive();

$the_folder = $_SERVER['DOCUMENT_ROOT']."/blog";
$zip_file_name = $_SERVER['DOCUMENT_ROOT']."/test.zip";

$res = $za->open($zip_file_name, ZipArchive::CREATE);

if($res === TRUE) {
	$za->addDir($the_folder, basename($the_folder));
	$za->close();
}
else
{
	echo "error";
}
*/
// mysqldump -uroot -papmsetup wordpress > blog.sql

// echo shell_exec("hash mysqldump 2>&1");

// echo shell_exec("mysqldump -uroot -papmsetup wordpress > blog.sql");

/*$User = "root";
$Password = "apmsetup";
$DatabaseName = "wordpress";
$File = "blog.sql"
//echo shell_exec("mysqldump -uroot -papmsetup wordpress > blog.sql");
//$Results = shell_exec("mysqldump --allow-keywords --opt -u$User -p$Password $DatabaseName > $File");*/

?>