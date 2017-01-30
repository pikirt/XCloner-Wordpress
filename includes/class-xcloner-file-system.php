<?php
use League\Flysystem\Config;
use League\Flysystem\Filesystem;
use League\Flysystem\Util;
use League\Flysystem\Adapter\Local;

class Xcloner_File_System{
	
	private $excluded_files 			= "";
	private $excluded_files_by_default	= array("administrator/backups", "wp-content/backups");
	private $included_files_handler 	= "backup_files.csv";
	private $temp_dir_handler 			= ".dir";
	public  $filesystem;
	public  $tmp_filesystem;
	public  $storage_filesystem;
	private $xcloner_settings_append;
	private $logger;
	
	private $start_adapter;
	private $tmp_adapter;
	private $storage_adapter;
	
	private $files_counter;
	private $files_size;
	private $last_logged_file;
	private $folders_to_process_per_session = 25;
	private $backup_archive_extensions = array("zip", "tar", "tgz", "tar.gz", "gz");
	private $backup_name_tags = array('[time]', '[hostname]', '[domain]');
	
	public function __construct($hash = "")
	{
		$this->logger = new XCloner_Logger('xcloner_file_system');
		$this->xcloner_requirements = new Xcloner_Requirements();

		$this->xcloner_settings 		= new Xcloner_Settings($hash);
		
		$this->start_adapter = new Local($this->xcloner_settings->get_xcloner_start_path(),LOCK_EX, 'SKIP_LINKS');
		$this->start_filesystem = new Filesystem($this->start_adapter, new Config([
				'disable_asserts' => true,
			]));
					
		$this->tmp_adapter = new Local($this->xcloner_settings->get_xcloner_tmp_path(),LOCK_EX, 'SKIP_LINKS');
		$this->tmp_filesystem = new Filesystem($this->tmp_adapter, new Config([
				'disable_asserts' => true,
			]));
		$adapter = new Local($this->xcloner_settings->get_xcloner_tmp_path(),LOCK_EX|FILE_APPEND, 'SKIP_LINKS');
		$this->tmp_filesystem_append = new Filesystem($adapter, new Config([
				'disable_asserts' => true,
			]));

		$adapter = new Local($this->xcloner_settings->get_xcloner_store_path(),LOCK_EX, 'SKIP_LINKS');
		$this->storage_filesystem = new Filesystem($adapter, new Config([
				'disable_asserts' => true,
			]));	
		
		$this->storage_adapter = new Local($this->xcloner_settings->get_xcloner_store_path(),FILE_APPEND, 'SKIP_LINKS');
		$this->storage_filesystem_append = new Filesystem($this->storage_adapter, new Config([
				'disable_asserts' => true,
			]));
			
		if($value = get_option('xcloner_directories_to_scan_per_request'))
			$this->folders_to_process_per_session = $value;
		//echo $this->folders_to_process_per_session;	
	}
	
	public function set_hash($hash)
	{
		$this->xcloner_settings->set_hash($hash);
	}
	
	public function get_hash($hash)
	{
		$this->xcloner_settings->get_hash();
	}
	
	public function get_tmp_filesystem()
	{
		return $this->tmp_filesystem;
	}
	
	public function get_storage_filesystem()
	{
		return $this->storage_filesystem;
	}
	
	public function get_tmp_filesystem_adapter()
	{
		return $this->tmp_adapter;
	}
	
	public function get_tmp_filesystem_append()
	{
		return $this->tmp_filesystem_append;
	}
	
	public function get_start_adapter()
	{
		return $this->start_adapter;
	}
	
	public function get_start_path_file_info($file)
	{
		//$info= $this->getMetadataFull('start_adapter', $file);
		return $this->start_filesystem->normalizeFileInfo($info);
	}
	
	public function get_storage_path_file_info($file)
	{
		return $this->getMetadataFull('storage_adapter', $file);
	}
	
	public function get_included_files_handler($metadata  = 0)
	{
		$path = $this->included_files_handler;
		if(!$metadata)
			return $path;
		
		$spl_info = $this->getMetadataFull('tmp_adapter', $path);
		return $spl_info;
		
	}
		
	public function get_temp_dir_handler()
	{
		return $this->temp_dir_handler;
	}
	
	public function getMetadataFull($adapter = "storage_adapter" , $path)
    {
        $location = $this->$adapter->applyPathPrefix($path);
        $spl_info = new SplFileInfo($location);
        
        return ($spl_info);
    }

	
	public function start_file_recursion($init = 0)
	{
		if($init)
		{
			$this->logger->info(sprintf(__("Starting the filesystem scanner on root folder %s"), $this->xcloner_settings->get_xcloner_start_path()));
			$this->do_system_init();
		}
		
		if($this->tmp_filesystem->has($this->get_temp_dir_handler())){
		//.dir exists, we presume we have files to iterate	
			$content = $this->tmp_filesystem->read($this->get_temp_dir_handler());
			$files = array_filter(explode("\n", $content));
			$this->tmp_filesystem->delete($this->get_temp_dir_handler());
			
			$counter = 0;
			foreach($files as $file)
			{
				if($counter < $this->folders_to_process_per_session){
					$this->build_files_list($file);
					$counter++;
				}else{
					$this->tmp_filesystem_append->write($this->get_temp_dir_handler(), $file."\n");
				}
			}
		}else{
			$this->build_files_list();
		}
		
		$metadata_dumpfile = $this->get_tmp_filesystem()->getMetadata("index.html");
		$this->store_file($metadata_dumpfile, 'tmp_filesystem');
		$this->files_counter++;
		
		if($this->scan_finished())
		{
			//adding included dump file to the included files list
			if($this->get_tmp_filesystem()->has($this->get_included_files_handler()))
			{
				$metadata_dumpfile = $this->get_tmp_filesystem()->getMetadata($this->get_included_files_handler());
				$this->store_file($metadata_dumpfile, 'tmp_filesystem');
				$this->files_counter++;
			}
		
			//adding a default index.html to the temp xcloner folder
			if(!$this->get_tmp_filesystem()->has("index.html"))
			{
				$this->storage_filesystem->write("index.html","");
			}
		
			return false;
		}	
	
		
		return true;	
	}
	
	private function do_system_init()
	{
		$this->files_counter = 0;
		
		if(!$this->storage_filesystem->has("index.html"))	
			$this->storage_filesystem->write("index.html","");
		
		if(!$this->tmp_filesystem->has("index.html"))	
			$this->tmp_filesystem->write("index.html","");
			
		if($this->tmp_filesystem->has($this->get_included_files_handler()))
			$this->tmp_filesystem->delete($this->get_included_files_handler());
		
		if($this->tmp_filesystem->has($this->get_temp_dir_handler()))	
			$this->tmp_filesystem->delete($this->get_temp_dir_handler());
	}
	
	public function get_scanned_files_num()
	{
		return $this->files_counter;
	}
	
	public function get_scanned_files_total_size()
	{
		return $this->files_size;
	}
	
	public function last_logged_file()
	{
		return $this->last_logged_file;
	}
	
	public function set_excluded_files($excluded_files)
	{
		$this->excluded_files = array_merge($excluded_files, $this->excluded_files_by_default);
		
		return $this;
	}
	
	public function get_excluded_files()
	{
		return $this->excluded_files_by_default;
	}
	
	public function list_directory($path)
	{
		return $this->start_filesystem->listContents($path);
	}
	
	public function build_files_list($folder = "")
	{
		$this->logger->debug(sprintf(("Building the files system list")));
		
		//if we start with the root folder(empty value), we initializa the file system
		if(!$folder){
			
		}
			
		try{
			
			$files = $this->start_filesystem->listContents($folder);
			foreach($files as $file)
			{
				if(!$matching_pattern = $this->is_excluded($file)){
					$this->logger->info(sprintf(__("Adding %s to the filesystem list"), $file['path']));
					$file['visibility'] = $this->start_filesystem->getVisibility($file['path']);
					$this->store_file($file);
					$this->files_counter++;
					if(isset($file['size']))
						$this->files_size += $file['size'];
					
				}else{
					$this->logger->info(sprintf(__("Excluding %s from the filesystem list, matching pattern %s"), $file['path'], $matching_pattern));
					}
			}
			
		}catch(Exception $e){
			
			$this->logger->error($e->getMessage());
		
		}
			
	}
	
	public function estimate_read_write_time()
	{
		$tmp_file = tempnam('./', 'xcloner');
		
		//$this->xcloner_settings->get_xcloner_tmp_path().DS.$tmp_file;
		
		$start_time = microtime();
		
		$data = str_repeat(rand(0,9), 1024*1024); //write 1MB data
		
		try{
			$this->tmp_filesystem->write($tmp_file, $data);
			
			$end_time = microtime() - $start_time;
		
			$return['writing_time'] = $end_time;
		
			$return['reading_time']	= $this->estimate_reading_time($tmp_file);
		
			$this->tmp_filesystem->delete($tmp_file);
		
		}catch(Exception $e){
			
			$this->logger->error($e->getMessage());
			
		}
		
		return $return;
	}
	
	public function backup_storage_cleanup()
	{
		if($this->logger)
			$this->logger->debug(sprintf(("Cleaning the backup storage")));
		
		$_storage_size = 0;
		$_backup_files_list = array();
		
		//rule date limit
		$current_timestamp = strtotime("-".$this->xcloner_settings->get_xcloner_option('xcloner_cleanup_retention_limit_days')." days");
		
		$files = $this->storage_filesystem->listContents();
		
		if(is_array($files))
			foreach($files as $file)
			{
				if(isset($file['extension']) and in_array($file['extension'], $this->backup_archive_extensions))
				{
					$_storage_size += $file['size']; //bytes
					$_backup_files_list[] = $file;
				}
			}
		
		
		$this->sort_by($_backup_files_list, "timestamp","asc");
		
		$_backups_counter = sizeof($_backup_files_list);
				
		foreach($_backup_files_list as $file)
		{
			//processing rule folder capacity
			if($this->xcloner_settings->get_xcloner_option('xcloner_cleanup_capacity_limit') &&
			$_storage_size >= ($set_storage_limit = 1024*1024*$this->xcloner_settings->get_xcloner_option('xcloner_cleanup_capacity_limit')))	//bytes	
			{
				$this->storage_filesystem->delete($file['path']);
				$_storage_size -= $file['size'];
				$this->logger->info("Deleting backup ".$file['path']." matching rule", array("STORAGE SIZE LIMIT", $_storage_size." >= ".$set_storage_limit));
			}
			
			//processing rule days limit
			if($this->xcloner_settings->get_xcloner_option('xcloner_cleanup_retention_limit_days') && $current_timestamp >= $file['timestamp'])
			{
				$this->storage_filesystem->delete($file['path']);
				$this->logger->info("Deleting backup ".$file['path']." matching rule", array("RETENTION LIMIT TIMESTAMP", $file['timestamp']." =< ".$this->xcloner_settings->get_xcloner_option('xcloner_cleanup_retention_limit_days')));
			}
			
			//processing backup countert limit
			if($this->xcloner_settings->get_xcloner_option('xcloner_cleanup_retention_limit_archives') && $_backups_counter >= $this->xcloner_settings->get_xcloner_option('xcloner_cleanup_retention_limit_archives'))
			{
				$this->storage_filesystem->delete($file['path']);
				$_backups_counter--;
				$this->logger->info("Deleting backup ".$file['path']." matching rule", array("BACKUP QUANTITY LIMIT", $_backups_counter." >= ".$this->xcloner_settings->get_xcloner_option('xcloner_cleanup_retention_limit_archives')));
			}
			
				
		}
		
	}
	
	public function estimate_reading_time($tmp_file)
	{
		$this->logger->debug(sprintf(("Estimating file system reading time")));
		
		$start_time = microtime();
		
		$data = $this->tmp_filesystem->read($tmp_file);
		
		$end_time = microtime() - $start_time;
		
		return $end_time;
	
	}
	
	public function process_backup_name($name = "", $max_length=100)
	{
		if(!$name)
			$name = $this->xcloner_settings->get_default_backup_name();
		
		foreach($this->backup_name_tags as $tag)
		{
			if($tag == '[time]')
				$name = str_replace($tag, date("Y-m-d_H-i"),$name);
			elseif($tag == '[hostname]')
				$name = str_replace($tag, gethostname() ,$name);	
			elseif($tag == '[domain]')
			{
				$domain = parse_url(admin_url(), PHP_URL_HOST);
				$name = str_replace($tag, $domain ,$name);	
			}
		}
		
		if($max_length)
			$name = substr($name, 0, $max_length);
			
		return $name;	
	}
	
	private function sort_by( &$array, $field, $direction = 'asc')
	{
	    usort($array, create_function('$a, $b', '
	        $a = $a["' . $field . '"];
	        $b = $b["' . $field . '"];
	
	        if ($a == $b)
	        {
	            return 0;
	        }
	
	        return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
	    '));
	
	    return true;
	}
	
	private function is_excluded($file)
	{
		$this->logger->debug(sprintf(("Checking if %s is excluded"), $file['path']));
		
		if($xcloner_exclude_files_larger_than_mb = $this->xcloner_settings->get_xcloner_option('xcloner_exclude_files_larger_than_mb'))
		{
			if(isset($file['size']) and $file['size'] > $this->calc_to_bytes($xcloner_exclude_files_larger_than_mb))
				return "> ".$xcloner_exclude_files_larger_than_mb."MB";
		}
		
		foreach($this->excluded_files as $excluded_file_pattern)
		{
			if($excluded_file_pattern == "/")
				$needle = "$";
			else
				$needle = "$".$excluded_file_pattern;
				
			if(strstr("$".$file['path'], $needle)){
				return $excluded_file_pattern;
			}
		}
		
		return false;
	}
	
	public function store_file($file, $storage = 'start_filesystem')
	{
		$this->logger->debug(sprintf("Storing %s in the backup list", $file['path']));
		
		if(!isset($file['size']))
			$file['size'] = 0;
		if(!isset($file['visibility']))	
			$file['visibility'] = "private";
			
		$line = '"'.$file['path'].'","'.$file['timestamp'].'","'.$file['size'].'","'.$file['visibility'].'","'.$storage.'"'.PHP_EOL;
		
		$this->last_logged_file = $file['path'];
		
		try{
			$this->tmp_filesystem_append->write($this->get_included_files_handler(), $line);
		
		}catch(Exception $e){
		
			$this->logger->error($e->getMessage());	
		}
		
		if($file['type'] == "dir"){
			try{
				$this->tmp_filesystem_append->write($this->get_temp_dir_handler(), $file['path']."\n");
			}catch(Exception $e){
				$this->logger->error($e->getMessage());	
			}
		}
	}
	
	public function get_fileystem_handler()
	{
		return $this;
	}
	
	public function get_filesystem($system = "")
	{
		if($system == "storage_filesystem_append")
			return $this->storage_filesystem_append;
		elseif($system == "tmp_filesystem_append")
			return $this->tmp_filesystem_append;
		elseif($system == "tmp_filesystem")
			return $this->tmp_filesystem;
		elseif($system == "storage_filesystem")
			return $this->storage_filesystem;
		else
			return $this->start_filesystem;	
	}
	
	public function get_adapter($system)
	{
		if($system == "tmp_filesystem")
			return $this->tmp_adapter;
		elseif($system == "storage_filesystem")
			return $this->storage_adapter;
		else
			return $this->start_adapter;	
	}
	
	private function scan_finished()
	{
		$this->logger->debug(sprintf(("File scan finished")));
		
		if($this->tmp_filesystem_append->has($this->get_temp_dir_handler()) && $this->tmp_filesystem_append->getSize($this->get_temp_dir_handler()))
			return false;
		
		if($this->tmp_filesystem->has($this->get_temp_dir_handler()))
			$this->tmp_filesystem->delete($this->get_temp_dir_handler());
		
		return true;
	}
	
	private function calc_to_bytes($mb_size)
	{
		return $mb_size*(1024*1024);
	}
	
}