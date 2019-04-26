<?php

namespace NS\SentinelBundle\Report\Export;

use Liuggio\ExcelBundle\Factory;
use PHPExcel_Reader_Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Twig_Environment;

class Exporter
{
    /** @var Twig_Environment */
    private $twig;

    /** @var Factory */
    private $phpExcel;

    public function __construct(Twig_Environment $twig, Factory $phpExcel)
    {
        $this->twig     = $twig;
        $this->phpExcel = $phpExcel;
    }

    public function export(string $twigTemplate, array $params, string $format = 'xls'): StreamedResponse
    {
        $filename   = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);
        $html       = $this->twig->render($twigTemplate, $params);
        $excelObj   = $this->phpExcel->createPHPExcelObject();
        $htmlReader = new HTMLReader();
        $htmlReader->loadFromString($html, $excelObj);
        $writer = $this->phpExcel->createWriter($excelObj);

        $headers = [
            'Content-type' => 'text/vnd.ms-excel; charset=utf-8',
            'Pragma' => 'public',
            'Cache-Control' => 'maxage=1',
            'Content-Disposition' => sprintf('attachment;filename=%s-%s.xls', $filename, date('Y_m_d_H_i_s')),
        ];

        return $this->phpExcel->createStreamedResponse($writer, 200, $headers);
    }
}
