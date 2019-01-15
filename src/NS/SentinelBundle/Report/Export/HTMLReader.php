<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 13/04/16
 * Time: 4:59 PM
 */

namespace NS\SentinelBundle\Report\Export;

use PHPExcel_Reader_Exception;

class HTMLReader extends \PHPExcel_Reader_HTML
{
    public function loadFromString($xmlString, \PHPExcel $objPHPExcel)
    {
        // Create new PHPExcel
        while ($objPHPExcel->getSheetCount() <= $this->sheetIndex) {
            $objPHPExcel->createSheet();
        }
        $sheet = $objPHPExcel->setActiveSheetIndex($this->sheetIndex);

        // Create a new DOM object
        $dom = new \DOMDocument();
        // Reload the HTML file into the DOM object
        $loaded = $dom->loadHTML($this->securityScan($xmlString));
        if ($loaded === FALSE) {
            throw new PHPExcel_Reader_Exception('Failed to load input as a DOM Document');
        }

        // Discard white space
        $dom->preserveWhiteSpace = false;

        $row = 0;
        $column = 'A';
        $content = '';
        $this->processDomElement($dom, $sheet, $row, $column, $content);

        // Return
        return $objPHPExcel;
    }
}
