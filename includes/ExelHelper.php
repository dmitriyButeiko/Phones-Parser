<?php 

	require_once "PHPExcel/PHPExcel.php";;


	class ExelHelper
	{
		private $ea;
		private $currentColumn = 2;
		
		public function __construct()
		{
			$ea = new \PHPExcel();
			$this->ea = $ea;
		}
		
		public function createColumnsNames()
		{
			$ews = $this->ea->getSheet(0);
			$ews->setCellValue('A1', "Порядковый номер");
			$this->ea->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			//$this->ea->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(true);
			$ews->setCellValue('B1', 'Дата полученного письма');
			$this->ea->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			//$this->ea->getActiveSheet()->getColumnDimensionByColumn('B')->setAutoSize(true);
			$ews->setCellValue('C1', 'Адрес отправителя');
			$this->ea->getActiveSheet()->getColumnDimension('D')->setWidth(30);
			//$this->ea->getActiveSheet()->getColumnDimensionByColumn('C')->setAutoSize(true);
			$ews->setCellValue('D1', 'Адрес сайта');
			//$this->ea->getActiveSheet()->getColumnDimensionByColumn('D')->setAutoSize(true);
			$ews->setCellValue('E1', 'Телефоны');
			$this->ea->getActiveSheet()->getColumnDimension('E')->setWidth(70);
			//$this->ea->getActiveSheet()->getColumnDimensionByColumn('E')->setAutoSize(true);
		}
		
		public function addRow($data)
		{
			$ews = $this->ea->getSheet(0);
			$ews->setCellValue('A' . $this->currentColumn, $data["counter"]);
			$ews->setCellValue('B' . $this->currentColumn, $data["arrivedDate"]);
			$ews->setCellValue('C' . $this->currentColumn, $data["arrivedFrom"]);
			$ews->setCellValue('D' . $this->currentColumn, $data["siteAddress"]);
			$phonesString = "";
			foreach($data["phones"] as $singlePhone)
			{
				$phonesString .= $singlePhone . "; ";
			}
			$ews->setCellValue('E' . $this->currentColumn, $phonesString);
			$this->currentColumn++;
			//$this->ea->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(true);
		}
		
		
		public function save($fileName)
		{
			$writer = \PHPExcel_IOFactory::createWriter($this->ea, 'Excel2007'); 
			$writer->setIncludeCharts(true);
			$writer->save($fileName);
		}
	}

?>