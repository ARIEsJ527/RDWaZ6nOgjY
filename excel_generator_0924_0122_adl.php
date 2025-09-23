<?php
// 代码生成时间: 2025-09-24 01:22:29
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelGeneratorController extends Controller
{
    /**
     * 生成Excel表格并下载
     *
     * @param array $data 表格数据
     * @return void
     */
    public function generateAction(array $data): void
    {
        try {
            // 创建一个新的Spreadsheet对象
            $spreadsheet = new Spreadsheet();

            // 创建一个工作表
            $worksheet = $spreadsheet->getActiveSheet();

            // 设置工作表的标题
            $worksheet->setTitle('Excel数据');

            // 填充数据到工作表
            $this->fillWorksheet($worksheet, $data);

            // 创建一个Xlsx写入器
            $writer = new Xlsx($spreadsheet);

            // 创建响应对象
            $response = new Response();
            $response->setContentType('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->setHeader('Content-Disposition', 'attachment; filename="export.xlsx"');

            // 将Excel数据写入响应流
            $writer->save('php://output');
            $response->send();

        } catch (Exception $e) {
            // 错误处理
            $this->flash->error('生成Excel表格失败: ' . $e->getMessage());
            $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }
    }

    /**
     * 填充数据到工作表
     *
     * @param PhpOffice\PhpSpreadsheet\Worksheet $worksheet 工作表对象
     * @param array $data 表格数据
     * @return void
     */
    protected function fillWorksheet($worksheet, array $data): void
    {
        // 设置表头
        $columns = ['姓名', '年龄', '城市'];
        $worksheet->fromArray($columns, null, 'A1');

        // 填充数据
        $row = 2;
        foreach ($data as $item) {
            $worksheet->setCellValue('A' . $row, $item['name']);
            $worksheet->setCellValue('B' . $row, $item['age']);
            $worksheet->setCellValue('C' . $row, $item['city']);
            $row++;
        }
    }
}
