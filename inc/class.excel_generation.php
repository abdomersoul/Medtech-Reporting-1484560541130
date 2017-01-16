<?php

	class excel_generation
	{
		function generate($rapport_id)
		{
			require_once "/PHPExcel/Classes/PHPExcel.php";
			$con = new connexion();
			$data = $con->query("SELECT * FROM rapport WHERE id=".$rapport_id)->fetch();
			
			$tmpfname = "canneva.xlsx";
			$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
			$excelObj = $excelReader->load($tmpfname);

			$worksheet = $excelObj->getSheet(0);// or use ->getActiveSheet() for the last sheet
			$worksheet->setTitle('Data');
			$lastRow = $worksheet->getHighestRow();
			$lastCol = $worksheet->getHighestColumn();

			//$worksheet->fromArray($data, ' ', 'A2');
			
			$worksheet->setCellValue('g8', $data->YTD_chiffre_affaires);
			$worksheet->setCellValue('h8', $data->YTDL_chiffre_affaires);
			$worksheet->setCellValue('j8', $data->YTDB_chiffre_affaires);
			$worksheet->setCellValue('l8', $data->ALY_chiffre_affaires);
			$worksheet->setCellValue('m8', $data->ABBP_chiffre_affaires);
			$worksheet->setCellValue('n8', $data->ABR_chiffre_affaires);
			
			$worksheet->setCellValue('g10', $data->YTD_achat_revendu);$worksheet->setCellValue('h10', $data->YTDL_achat_revendu);
			$worksheet->setCellValue('j10', $data->YTDB_achat_revendu);$worksheet->setCellValue('l10', $data->ALY_achat_revendu);
			$worksheet->setCellValue('m10', $data->ABBP_achat_revendu);$worksheet->setCellValue('n10', $data->ABR_achat_revendu);
			
			$worksheet->setCellValue('g15', $data->YTD_charges_oper);$worksheet->setCellValue('h15', $data->YTDL_charges_oper);
			$worksheet->setCellValue('j15', $data->YTDB_charges_oper);$worksheet->setCellValue('l15', $data->ALY_charges_oper);
			$worksheet->setCellValue('m15', $data->ABBP_charges_oper);$worksheet->setCellValue('n15', $data->ABR_charges_oper);
			
			$worksheet->setCellValue('g16', $data->YTD_salaires);$worksheet->setCellValue('h16', $data->YTDL_salaires);
			$worksheet->setCellValue('j16', $data->YTDB_salaires);$worksheet->setCellValue('l16', $data->ALY_salaires);
			$worksheet->setCellValue('m16', $data->ABBP_salaires);$worksheet->setCellValue('n16', $data->ABR_salaires);
			
			$worksheet->setCellValue('g17', $data->YTD_taxes);$worksheet->setCellValue('h17', $data->YTDL_taxes);
			$worksheet->setCellValue('j17', $data->YTDB_taxes);$worksheet->setCellValue('l17', $data->ALY_taxes);
			$worksheet->setCellValue('m17', $data->ABBP_taxes);$worksheet->setCellValue('n17', $data->ABR_taxes);
			
			$worksheet->setCellValue('g24', $data->YTD_amortissement);$worksheet->setCellValue('h24', $data->YTDL_amortissement);
			$worksheet->setCellValue('j24', $data->YTDB_amortissement);$worksheet->setCellValue('l24', $data->ALY_amortissement);
			$worksheet->setCellValue('m24', $data->ABBP_amortissement);$worksheet->setCellValue('n24', $data->ABR_amortissement);
			
			$worksheet->setCellValue('g28', $data->YTD_res_finans);$worksheet->setCellValue('h28', $data->YTDL_res_finans);
			$worksheet->setCellValue('j28', $data->YTDB_res_finans);$worksheet->setCellValue('l28', $data->ALY_res_finans);
			$worksheet->setCellValue('m28', $data->ABBP_res_finans);$worksheet->setCellValue('n28', $data->ABR_res_finans);
			
			$worksheet->setCellValue('g33', $data->YTD_charges_produits);$worksheet->setCellValue('h33', $data->YTDL_charges_produits);
			$worksheet->setCellValue('j33', $data->YTDB_charges_produits);$worksheet->setCellValue('l33', $data->ALY_charges_produits);
			$worksheet->setCellValue('m33', $data->ABBP_charges_produits);$worksheet->setCellValue('n33', $data->ABR_charges_produits);
			
			$worksheet->setCellValue('g34', $data->YTD_IS);$worksheet->setCellValue('h34', $data->YTDL_IS);
			$worksheet->setCellValue('j34', $data->YTDB_IS);$worksheet->setCellValue('l34', $data->ALY_IS);
			$worksheet->setCellValue('m34', $data->ABBP_IS);$worksheet->setCellValue('n34', $data->ABR_IS);

			
			$writer = \PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
			$writer->setIncludeCharts(true);
			//$writer->save('output.xlsx');
			
			$filename="rapport_id_".$rapport_id.".xlsx";
			
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: application/vnd.ms-excel");
			
			$writer->save('php://output');
			
			exit;
		}
	}
		
?>