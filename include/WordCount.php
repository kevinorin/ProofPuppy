<?php

class WordCounter {

	private $filename;

	public function WordCounter( $filename )
	{
		$this->filename = $filename;
	}

	public function countWords() 
	{
		$pathinfo = pathinfo($this->filename);

		switch ($pathinfo['extension'])
		{

			case 'doc':
				$content = $this->readDoc();
				if (!$content) { return false; }
				break;

			case 'docx':
				$content = $this->readDocx();
				if (!$content) { return false; }
				break;

			case 'ppt':
				$content = $this->readPpt();
				if ($content=='') { return false; }
				break;

			case 'pptx':
				$content = $this->readPptx();
				if ($content=='') { return false; }
				break;


			default:
				return false;
				break;
		}

		return str_word_count($content);
		
	}

	public function readDocx()
	{ 
		$filename = $this->filename;

		$striped_content = ''; 
		$content = ''; 
		if(!$this->filename || !file_exists($this->filename))
		{
			return false; 
		}
		$zip = zip_open($this->filename); 
		if (!$zip || is_numeric($zip)) {
			return false; 
		}
		
		while ($zip_entry = zip_read($zip)) 
		{ 
			if (zip_entry_open($zip, $zip_entry) == FALSE)
			{ 
				continue; 
			}
		
			if (zip_entry_name($zip_entry) != "word/document.xml") 
			{
				continue; 
			}
	
			$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)); 
		
			zip_entry_close($zip_entry); 
		} // end while zip_close($zip); 

		# if php zip module is not present, you can get the content that you need with the following command
		# highly discouraged to use though... good that we managed to enable it on dreamhost

		#$content = shell_exec("unzip -p $this->filename word/document.xml");


		$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content); 
		$content = str_replace('</w:r></w:p>', "\r\n", $content); 
		
		$striped_content = strip_tags($content); 
		return $striped_content;
	}

	function readDoc() 
	{
		# check if antiword is present
		$which_antiword = shell_exec('which antiword');
		if ($which_antiword != "")
		{
			$outtext = shell_exec("antiword $this->filename");
			if ($outtext != "$this->filename is not a Word Document." and $outtext != null)
			{
				return $outtext;
			}
		}

		$fileHandle = fopen($this->filename, "r");

		$line = fread($fileHandle, filesize($this->filename));
  
		$lines = explode(chr(0x0D),$line);

		$outtext = "";
		foreach($lines as $thisline)
		{
			$pos = strpos($thisline, chr(0x00));
			if (($pos !== FALSE)||(strlen($thisline)==0))
			{
		
			} else {
				$outtext .= $thisline." ";
			}
		}
		$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);

		return $outtext;

	}

	function readPpt()
	{
		// This approach uses detection of the string "chr(0f).Hex_value.chr(0x00).chr(0x00).chr(0x00)" to find text strings, which are then terminated by another NUL chr(0x00). [1] Get text between delimiters [2]
		$fileHandle = fopen($this->filename, "r");
		$line = @fread($fileHandle, filesize($this->filename));
		$lines = explode(chr(0x0f),$line);
		$outtext = '';

		foreach($lines as $thisline) 
		{
			#print $thisline . "\n";
			if (strpos($thisline, chr(0x00).chr(0x00).chr(0x00)) == 1) 
			{

				$text_line = substr($thisline, 4);
				$end_pos   = strpos($text_line, chr(0x00));
				$text_line = substr($text_line, 0, $end_pos);
				$text_line = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$text_line);
				if (strlen($text_line) > 1) 
				{

					$outtext .= substr($text_line, 0, $end_pos)."\n";
				}
			}
		}

		return $outtext;
	}

	function readPptx()
	{
		$zip_handle = new ZipArchive;
		$output_text = "";
		if(true === $zip_handle->open($this->filename))
		{
			$slide_number = 1; //loop through slide files
			while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false)
			{
				$xml_datas = $zip_handle->getFromIndex($xml_index);
				$xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
				$output_text .= strip_tags($xml_handle->saveXML());
				$slide_number++;
			}
			if($slide_number == 1)
			{
				$output_text .="";
			}

			/*
			# looping for notes
			$slide_number = 1; //loop through note files
			while(($xml_index = $zip_handle->locateName("ppt/notesSlides/notesSlide".$slide_number.".xml")) !== false)
			{
				$xml_datas = $zip_handle->getFromIndex($xml_index);
				$xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
				$output_text .= strip_tags($xml_handle->saveXML());
				$slide_number++;
			}
			if($slide_number == 1)
			{
				$output_text .="";
			}
			*/
			$zip_handle->close();
		}
		else
		{
			$output_text .="";
		}
		return $output_text;
	}

}
