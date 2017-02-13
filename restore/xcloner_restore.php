<?php

define("DS", DIRECTORY_SEPARATOR);

//require_once(dirname( __FILE__ )  . DS.'vendor'.DS.'autoload.php');
//require_once(dirname( __FILE__ )  . DS.'../wordpress/wp-content/plugins/xcloner/vendor'.DS.'autoload.php');
require_once(__DIR__.DS."vendor.phar");

use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use League\Flysystem\Util;
use League\Flysystem\Adapter\Local;

use splitbrain\PHPArchive\Tar;
use splitbrain\PHPArchive\Archive;
use splitbrain\PHPArchive\FileInfo;


//do not modify below
$xcloner_restore = new Xcloner_Restore();

try{
	$return = $xcloner_restore->init();
	$xcloner_restore->send_response(200, $return);
}catch(Exception $e){
	$xcloner_restore->send_response(417, $e->getMessage());
}

class Xcloner_Restore
{
	private $backup_archive_extensions = array("zip", "tar", "tgz", "tar.gz", "gz", "csv");
	private $process_files_limit = 50;
	private $adapter;
	private $filesystem;
	
	
	public function __construct()
	{
		$dir = dirname(__FILE__);
		
		$this->adapter = new Local($dir ,LOCK_EX, 'SKIP_LINKS');
		$this->filesystem = new Filesystem($this->adapter, new Config([
				'disable_asserts' => true,
			]));
	}
	
	public function init()
	{
		if(isset($_POST['action']) and $_POST['action'])
		{
			$method = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
			
			//$method = "list_backup_archives";
			
			$method .= "_action";
			
			if(method_exists($this, $method))
			{
				return call_user_func(array($this, $method));
				
			}else{
				throw new Exception($method ." does not exists");
				}
		}
		
		return $this->check_system();
	}
	
	public function write_file_action()
	{
		if(isset($_POST['file']))
		{
			$target_file = filter_input(INPUT_POST, 'file', FILTER_SANITIZE_STRING);
			
			if(!$_POST['start'])
				$fp = fopen($target_file, "wb+");
			else
				$fp = fopen($target_file, "ab+");	
			
			if(!$fp)
				throw new Exception("Unable to open $target_file file for writing");
			
			fseek($fp, $_POST['start']);
			
			if(!$bytes_written = fwrite($fp, $_POST['blob']))
				throw new Exception("Unable to write data to file $target_file");
			
			fclose($fp);
		}
		
		return $bytes_written;
		
	}
	
	public function list_backup_archives_action()
	{
		
		$list = $this->filesystem->listContents();
		
		$backup_files = array();
		$parents = array();
		
		foreach($list as $file_info)
		{
			$data = array();
			
			if(isset($file_info['extension']) and $file_info['extension'] == "csv")
			{
				$lines = explode(PHP_EOL, $this->filesystem->read($file_info['path']));
				foreach($lines as $line)
					if($line)
					{
						$data = str_getcsv($line);
						if(is_array($data)){
							$parents[$data[0]] = $file_info['path'];
							$file_info['childs'][] = $data;
							$file_info['size'] += $data[2];
						}
					}
						
			}
			
			if($file_info['type'] == 'file' and isset($file_info['extension']) and in_array($file_info['extension'], $this->backup_archive_extensions))
				$backup_files[$file_info['path']] = $file_info;
		}
		
		$new_list = array();
		
		foreach($backup_files as $key=>$file_info)
		{
			if(isset($parents[$file_info['path']]))
				$backup_files[$key]['parent'] = $parents[$file_info['path']];
			else
				$new_list[$key] = $file_info;
		}
		
		
		$return['files'] = $new_list;
		
		$this->send_response(200, $return);
		
	}
	
	public function restore_backup_to_path_action()
	{
		$backup_file = filter_input(INPUT_POST, 'backup_file', FILTER_SANITIZE_STRING);
		$remote_path = filter_input(INPUT_POST, 'remote_path', FILTER_SANITIZE_STRING);
		$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
		$return['part'] = (int)filter_input(INPUT_POST, 'part', FILTER_SANITIZE_NUMBER_INT);
		$return['processed'] = (int)filter_input(INPUT_POST, 'processed', FILTER_SANITIZE_NUMBER_INT);
				
		$this->target_adapter = new Local($remote_path ,LOCK_EX, 'SKIP_LINKS');
		$this->target_adapter = new Filesystem($this->target_adapter, new Config([
				'disable_asserts' => true,
			]));
		
		$return['finished'] = 1;
		$return['total_size'] = $this->get_backup_size($backup_file);
		
		$backup_archive = new Tar();
		if($this->is_multipart($backup_file))
		{
			if(!$return['part'])
				$return['processed'] += $this->filesystem->getSize($backup_file);
				
			$backup_parts = $this->get_multipart_files($backup_file);
			$backup_file = $backup_parts[$return['part']];	
		}	
		
		$backup_archive->open($backup_file, $start);
		
		/*$res = $backup_archive->contents();
		foreach($res as $file)
			$return['files'][] = $file->getPath();
		*/
		$data = $backup_archive->extract($remote_path, '','','', $this->process_files_limit);
		
		if(isset($data['start']))
		//if(isset($data['start']) and $data['start'] <= $this->filesystem->getSize($backup_file))
		{
			$return['finished'] = 0;
			$return['start'] = $data['start'];
		}else{
			
			$return['processed'] += $start;
			
			if($this->is_multipart($backup_file))
			{
				$return['start'] = 0;
				
				++$return['part'];
			
				if($return['part'] < sizeof($backup_parts))	
					$return['finished'] = 0;
			}
		}
		
		$return['backup_file'] = $backup_file;
		
		$this->send_response(200, $return);
	}
	
	public function get_current_directory_action()
	{
		$return['dir'] = (dirname(__FILE__))."/tmp";
		$this->send_response(200, $return);
	}
	
	public function check_system()
	{
		//check if i can write
		$tmp_file = md5(time());
		if(!file_put_contents($tmp_file, "++"))
			throw new Exception("Could not write to new host");
		
		if(!unlink($tmp_file))
			throw new Exception("Could not delete temporary file from new host");
		
		$max_upload      = $this->return_bytes((ini_get('upload_max_filesize')));
		$max_post        = $this->return_bytes((ini_get('post_max_size')));

		$return['max_upload_size'] = min($max_upload, $max_post); // bytes
		$return['status']		= true;
		
		$this->send_response(200, $return);
	}
	
	private function return_bytes($val) {
	    $val = trim($val);
	    $last = strtolower($val[strlen($val)-1]);
	    switch($last) {
	        // The 'G' modifier is available since PHP 5.1.0
	        case 'g':
	            $val *= 1024;
	        case 'm':
	            $val *= 1024;
	        case 'k':
	            $val *= 1024;
	    }
	
	    return $val;
	}
	
	public function is_multipart($backup_name)
	{
		if(stristr($backup_name, "-multipart.csv"))
			return true;
		
		return false;	
	}
	
	public function get_backup_size($backup_name)
	{
		$backup_size = $this->filesystem->getSize($backup_name);
		if($this->is_multipart($backup_name))
		{
			$backup_parts = $this->get_multipart_files($backup_name);
			foreach($backup_parts as $part_file)
				$backup_size += $this->filesystem->getSize($part_file);
		}
		
		return $backup_size;
	}
	
	public function get_multipart_files($backup_name)
	{
		$files = array();
		
		if($this->is_multipart($backup_name))
		{
			$lines = explode(PHP_EOL, $this->filesystem->read($backup_name));
			foreach($lines as $line)
			{
				if($line)
				{
					$data = str_getcsv($line);
					$files[] = $data[0];
				}
			}
		}
		
		return $files;
	}
		
	public function send_response($status = 200, $response)
	{
		header("Access-Control-Allow-Origin: *");
		header("HTTP/1.1 200");
		header('Content-Type: application/json');
		$return['status'] = $status;
		$return['statusText'] = $response;
		
		echo json_encode($return);
		exit;
	}
}

