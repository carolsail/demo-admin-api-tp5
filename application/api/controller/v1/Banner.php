<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\repository\Banner as BannerRep;

require_once '../extend/PHPExcel/PHPExcel.php';

class Banner extends BaseController
{
    public function initialize()
    {
        parent::initialize();
    }

    //前置操作
    protected $beforeActionList = [
        'checkSuperScope'
    ];

    public function export()
    {
        list($where) = $this->buildparams();
        $lists = (new BannerRep)->getExportRows($where);

        $this->objPHPExcel = new \PHPExcel();
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $inputFileName = './static/file/test.xls';
        $this->objPHPExcel = $objReader->load($inputFileName);
        $sheet = $this->objPHPExcel->getActiveSheet(0);

        $r = 2;
        foreach($lists as $k=>$list){
            $sheet->setCellValue('A'.$r, $k+1);
            $sheet->setCellValue('B'.$r, $list['name']);
            $sheet->setCellValue('C'.$r, $list['url']);
            $r++;
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');

        $saveFileName = 'test.xls';
        $objWriter->save('./uploads/export/'.$saveFileName);
        $url = request()->root(true) . '/uploads/export/'.$saveFileName;
        return json(['msg'=>'upload success', 'val' => $url], 200);
    }
}
