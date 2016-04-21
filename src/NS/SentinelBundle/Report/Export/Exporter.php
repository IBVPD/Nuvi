<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/04/16
 * Time: 2:25 PM
 */

namespace NS\SentinelBundle\Report\Export;


use Liuggio\ExcelBundle\Factory;

class Exporter
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Factory
     */
    private $phpExcel;

    /**
     * Exporter constructor.
     * @param \Twig_Environment $twig
     * @param Factory $phpExcel
     */
    public function __construct(\Twig_Environment $twig, Factory $phpExcel)
    {
        $this->twig = $twig;
        $this->phpExcel = $phpExcel;
    }

    /**
     * @param $twigTemplate
     * @param array $params
     * @param string $format
     * @return mixed
     * @throws \PHPExcel_Reader_Exception
     */
    public function export($twigTemplate, array $params, $format = 'xls')
    {
        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);
        $html = $this->twig->render($twigTemplate, $params);
        $excelObj = $this->phpExcel->createPHPExcelObject();
        $htmlReader = new HTMLReader();
        $htmlReader->loadFromString($html,$excelObj);
        $writer = $this->phpExcel->createWriter($excelObj);

        $headers = array(
            'Content-type' => 'text/vnd.ms-excel; charset=utf-8',
            'Pragma'=>'public',
            'Cache-Control'=>'maxage=1',
            'Content-Disposition' => sprintf('attachment;filename=%s-%s.xls',$filename, date('Y_m_d_H_i_s')),
        );

        return $this->phpExcel->createStreamedResponse($writer, 200, $headers);
    }
}
