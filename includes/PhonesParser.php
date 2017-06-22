<?php

require_once "pdf_to_text.php";
require_once "TextAtAnyCost-master/doc.php";
require_once "ExelHelper.php";	
	
	
/**
 *
 */
class PhonesParser
{
    /**
     *
     */
    public function __construct($emailText = "")
    {
		$this->emailText = $emailText;
		$this->phonesPatterns = array(
			"/\+?((\d)( )?(-)?){10,}/i",
			"/\+?(\d+)?( )?\(\d+\)(( )?(-)?( )?\d+)+/i"
		);
		$this->attachmentsPattern = "/Attachments(-)?(\d+)?\/(.)+\.(.)+/i";
    }

    /**
     * @var void
     */
    private $emailText;	
	private $attachmentsPattern;
	private $currentEmail;
    private $phonesPatterns;

    /**
     * returns array of phones
     */
    public function getPhonesFromTextIntoArray()
    {
		$phonesArray = array();
		
		foreach($this->phonesPatterns as $singlePattern)
		{
			preg_match_all($singlePattern, $this->emailText, $matches);
			foreach($matches[0] as $singleMatch)
			{
				$phonesArray[] = $singleMatch;
			}
		}
	
		return $phonesArray;
    }

    /**
     * this function returns email "От" from parser
     *
     */
    public function getFromEmail()
    {
        $fromEmail = "";
		
		$begin = strpos($this->emailText, "От:");
		$length = strpos($this->emailText, "Дата:") - $begin;
		$betweenFromAndData = substr($this->emailText, $begin, $length);
		$fromEmail = $this->retrieveEmailFromString($betweenFromAndData);
		$this->currentEmail = $fromEmail;
		
		return $fromEmail;
    }
	
	public function setEmailText($emailText)
	{
		$this->emailText = $emailText;
	}
	
	private function getFileExtension($fileName)
	{
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		return $ext;
	}
	
	public function parseEmailData()
	{
		$exelHelper = new ExelHelper();
		$exelHelper->createColumnsNames();
		$currentWorkedDirectory = getcwd();
		$files = array_diff(scandir($currentWorkedDirectory."/emails"), array('.', '..'));
		$counter = 1;
		$senders = array();
		
		foreach($files as $singleFile)
		{
			if(strpos($singleFile, ".txt"))
			{
				$fileContents = file_get_contents($currentWorkedDirectory . "/emails/" . $singleFile);
				$this->setEmailText($fileContents);
				$fromEmail = $this->getFromEmail();
				$arrivedDate = $this->getArrivingDateFromEmail();
				$siteName = $this->getSiteName();
				$phonesArray = $this->getPhonesFromTextIntoArray();
				
				if(count($phonesArray) == 0)
				{
					$attachments = $this->getAttachmentsPaths();
					foreach($attachments as $singleAttachment)
					{
						$extension = $this->getFileExtension($singleAttachment);
						if($extension == "doc" || $extension == "docs")
						{
							$docText = doc2text("emails/Attachments/Реквизиты.doc");
							var_dump($docTexts);
							exit;
						}
						if($extension == "pdf")
						{
							
						}
						if($extension == "pdf")
						{
							
						}
					}
				}
					
				$exelHelper->addRow(array(
					"counter" => $counter,
					"arrivedDate" => $arrivedDate,
					"arrivedFrom" => $fromEmail,
					"siteAddress" => $siteName,
					"phones" => $phonesArray
				));
				
				$counter++;
			}
		}
		
		$exelHelper->save("CreatedTable.xlsx");
	}
	
	public function getAttachmentsPaths()
	{
		$attachments = array();
		preg_match_all($this->attachmentsPattern, $this->emailText, $attachments);
		
		return $attachments[0];
	}
	
	
	public function getSiteName()
	{
		$email = $this->currentEmail;
		$explodedEmail = explode("@", $email);
		return $explodedEmail[1];
	}
	
	/*
	*
	*/
	public function getArrivingDateFromEmail()
	{
		$arrivingDate = "";
		
		$begin = strpos($this->emailText, "Дата:");
		$length = strpos($this->emailText, "Кому:") - $begin;
		$betweenDataAndTo = substr($this->emailText, $begin, $length);		
		$arrivingDate = $this->retrieveDataFromString($betweenDataAndTo);
		
		return $arrivingDate;
	}
	
	/*
	*
	*/	
	public function retrieveDataFromString($emailString)
	{
		$pattern = '/\d+\.\d+\.\d+/i';
		preg_match_all($pattern, $emailString, $matches);
		$data = $matches[0][0];
		
		return $data;
	}

	public function getTextFromPdf($fileName)
	{
		$pdfText = pdf2text($fileName);
		return $pdfText;
	}
	
	public function getTextFromDoc($fileName)
	{
		$docText = doc2text($fileName);
		return $docText;
	}
	
	public function getTextFromXls()
	{
		
	}
	
	private function retrieveEmailFromString($string)
	{
		$pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
		preg_match_all($pattern, $string, $matches);
		$email = $matches[0][0];
		
		return $email;
	}
}
