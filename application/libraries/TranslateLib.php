<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
// translation library

class TranslateLib{

	private $CI;
	private $fileData = array();
	private $createLog = true;
	private $userId = 2;
	private $logFile = '';
	private $logMessage = array();
	private $arrDicWords = array();

	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model(array('dictionary_model','files_model'));

		if($this->CI->session->userdata('cib_userID') != '' && $this->CI->session->userdata('cib_userID')  >0)
		{
			$this->userId = $this->CI->session->userdata('cib_userID');
		}
		$this->logFile = 'log_'.date('Y_m_d').".txt";
	}

	public function processFile($fileData)
	{
		$return = false;
		$fileId = $fileData['file_id'];
		$fileType = $fileData['file_type'];
		$dictioaries =  $fileData['dic_applied'];
		$fileName = $fileData['file_name'];
		if($fileType == 'doc')
			$filePath = './assets/uploads/documents/';
		else
			$filePath = './assets/uploads/excel/';


		if($fileName =='' || !file_exists($filePath.$fileName))
		{
			return false;
		}

		$this->addLog("==============================================================");
		$this->addLog("File Info:".print_r($fileData,true));
		$this->addLog("---------------------------------------------------------------");
		$this->addLog("DateTime Info:".date("Y-m-d H:i:s"));
		$this->addLog("---------------------------------------------------------------");

		//update file status
		echo "<br/> FIle Id : $fileId<br/> ";
		$this->updateStatus($fileId,'processing');

		

		//unset dictionary variable
		unset($this->arrDicWords);
		//load updated dictionary ids with translation words
		$this->loadDictionry($dictioaries);
		switch($fileType)
		{
			case 'doc':
						$ret = $this->processWordFile($filePath.$fileName);
						//if true then update status to done and create log
						if($ret)
						{
							$this->updateStatus($fileId,'completed');
							$return = true;
						}
						else
						{
							$this->updateStatus($fileId,'failed');
							$return = false;
						}
						break;
		}

		$this->addLog("==============================================================");
		$this->writeLogFile();
		return $return;
	}

	private function addLog($message='')
	{
		if($message != '')
		{
			$this->logMessage[] = $message ."\n";
		}
	}

	private function writeLogFile()
	{
		if(count($this->logMessage) >0)
		{
			$this->CI->load->helper('file');
			@write_file(APPPATH.'logs/'.$this->logFile, implode(" ",$this->logMessage), 'a');
		}
	}

	private function loadDictionry($dictioaries)
	{
		$arrDic = explode(",",$dictioaries);
		if(count($arrDic) > 0)
		{
			foreach($arrDic as $dicId)
			{
				$items = array();
				$res = $this->CI->dictionary_model->getWords($dicId);
				if(count($res) > 0)
				{
					foreach($res as $d)
					{
						$items[$d['from_lang']] = $d['to_lang'];
					}
				}
				//echo "<pre>$dicId <br/> >>>> ";print_r($items);die;
				$this->arrDicWords[$dicId] = $items; 
			}
		}
	}


	private function updateStatus($fileId,$status)
	{
		$data = array();
		$data['file_status'] = $status;
		$data['last_modified_by'] = $this->userId;
		$data['last_modified_on'] = date("Y-m-d H:i:s");
		$this->CI->files_model->updateStatus($fileId,$data);
		$this->addLog("Status Updated to:".$status." By UserId:".$this->userId);
	}

	private function processWordFile($file)
	{
		$zip = new ZipArchive();
		$inputFilename = $file;
		if ($zip->open($inputFilename, ZipArchive::CREATE)!==TRUE) {
		    $this->addLog("Cannot open $filename :( "); 
		}
		else
		{	
			// Fetch the document.xml file from the word subdirectory in the archive.
			$xml = $zip->getFromName('word/document.xml');
			// Replace the tokens.
			$totalReplacements = 0;
			foreach($this->arrDicWords as $dicId => $arrWords)
			{
				//echo "<pre>$dicId <br/> >>>> ";print_r($arrWords);//die;
				$xml = str_replace(array_keys($arrWords), $arrWords, $xml,$cnt);
				$totalReplacements += $cnt;

			}
			//echo'<hr/>'. $xml;die;

			if ($zip->addFromString('word/document.xml', $xml)) 
			{
			 	$this->addLog('File written! Number of replacements: '.$totalReplacements);
			 	return true;
			}
			else
		 	{ 
		 		$this->addLog('File not written.  Go back and add write permissions to this folder!l'); 
		 		return true;
		 	}
			$zip->close();
		}
	}
}
?>