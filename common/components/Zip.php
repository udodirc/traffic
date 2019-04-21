<?php
namespace common\components;

/**
 * Zip class
 */
 
class Zip
{
	private $file;
	private $file_path;
	private $extract_to;
	private $zip;
	private $openFile;
	
	function __construct($filePath, $file, $extractTo) 
	{
       $this->zip = new \ZipArchive;
       $this->file_path = $filePath;
       $this->file = $file;
       $this->extract_to = $extractTo;
	}
	
	public function openZipFile($file)
	{	
		$this->openFile = $this->zip->open($this->file_path);
	}
	
	public function extractZipTo()
	{
		$result = false;
		$open = $this->openZipFile($this->file_path);
		
		if($this->openFile)
		{
			$extract = $this->zip->extractTo($this->extract_to);
 
			//if unzipped succesfully then show the success message
			if($extract)
			{
				$result = true;
			}
		}
		
		$this->zip->close(); 
		
		return $result;
	}
}
?>
