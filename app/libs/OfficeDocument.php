<?php

/*
 * An object that converts office documents into s plain string.
 * This is a reworked version of:
 * https://stackoverflow.com/questions/19503653/how-to-extract-text-from-word-file-doc-docx-xlsx-pptx-php
 */

class OfficeDocument
{
    private string $m_filepath;
    private string $m_fileText;


    public function __construct(string $filePath, string $fileExtension=null)
    {
        if (file_exists($filePath) === false)
        {
            throw new \Exception("File does not exist: {$filePath}");
        }

        $this->m_filepath = $filePath;

        if ($fileExtension === null)
        {
            $fileArray = pathinfo($this->m_filepath);
            $fileExtension  = $fileArray['extension'];
        }

        switch ($fileExtension)
        {
            case "doc": $this->m_fileText = $this->readDoc($this->m_filepath); break;
            case "docx": $this->m_fileText = $this->readDocx($this->m_filepath); break;
            case "xlsx": $this->m_fileText = $this->xlsxToText($this->m_filepath); break;
            case "pptx": $this->m_fileText = $this->pptxToText($this->m_filepath); break;
            default: { throw new \Exception("Invalid file type"); }
        }
    }

    /**
     * Helper method that transforms a .doc file into a text form.
     * @return type
     */
    private function readDoc()
    {
        $fileHandle = fopen($this->m_filepath, "r");
        $line = @fread($fileHandle, filesize($this->m_filepath));
        $lines = explode(chr(0x0D),$line);
        $outtext = "";

        foreach ($lines as $thisline)
        {
            $pos = strpos($thisline, chr(0x00));

            if (($pos !== FALSE)||(strlen($thisline)==0))
            {
                // do nothing
            }
            else
            {
                $outtext .= $thisline." ";
            }
        }

        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }


    /**
     * Helper method that transoforms a .docx file into a string.
     * @return string
     */
    private function readDocx() : string
    {
        $strippedContent = '';
        $content = '';

        $zip = zip_open($this->m_filepath);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip))
        {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $strippedContent = strip_tags($content);

        return $strippedContent;
    }


    /**
     * Helper method that transforms an xlsx file into a string.
     * @param type $inputFile
     * @return string
     */
    private function xlsxToText($inputFile) : string
    {
        $xml_filename = "xl/sharedStrings.xml"; //content file name
        $zip_handle = new ZipArchive;
        $outputText = "";

        if (true === $zip_handle->open($inputFile))
        {
            if (($xml_index = $zip_handle->locateName($xml_filename)) !== false)
            {
                $xmlDatas = $zip_handle->getFromIndex($xml_index);
                $xmlHandle = DOMDocument::loadXML($xmlDatas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $outputText = strip_tags($xmlHandle->saveXML());
            }
            else
            {
                $outputText .="";
            }

            $zip_handle->close();
        }
        else
        {
            $outputText .= "";
        }

        return $outputText;
    }


    /**
     * Helper method that transoforms a powerpoint pptx file into a string.
     * @param type $inputFile
     * @return string
     */
    private function pptxToText($inputFile) : string
    {
        $zipHandle = new ZipArchive;
        $outputText = "";

        if (true === $zipHandle->open($inputFile))
        {
            $slideNumber = 1; //loop through slide files

            while (($xml_index = $zipHandle->locateName("ppt/slides/slide".$slideNumber.".xml")) !== false)
            {
                $xmlDatas = $zipHandle->getFromIndex($xml_index);
                $xmlHandle = DOMDocument::loadXML($xmlDatas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $outputText .= strip_tags($xmlHandle->saveXML());
                $slideNumber++;
            }

            if ($slideNumber == 1)
            {
                $outputText .="";
            }

            $zipHandle->close();
        }
        else
        {
            $outputText .="";
        }

        return $outputText;
    }


    public function __toString()
    {
        return $this->m_fileText;
    }
}
