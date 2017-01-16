<!doctype>
<html>
<head>
</head>
<body>
<?php

require_once "PHPExcel/Classes/PHPExcel.php";

		$tmpfname = "Reporting cannevas.xlsx";
		
		/*
		//read a excel file from url
		$url = "http://spreadsheetpage.com/downloads/xl/worksheet%20functions.xlsx";
		$filecontent = file_get_contents($url);
		$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
		file_put_contents($tmpfname,$filecontent);
		*/
		
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$worksheet = $excelObj->getSheet(0);// or use ->getActiveSheet() for the last sheet
		$lastRow = $worksheet->getHighestRow();
		$lastCol = $worksheet->getHighestColumn();
		
		//excel_array = $worksheet->toArray(null,true,true,true); get data as array, excel_array[1][A]
		
		echo "<table>";
		for ($row = 1; $row <= $lastRow; $row++) {
			 echo "<tr><td>";
			 echo $worksheet->getCell('A'.$row)->getValue();
			 echo "</td><td>";
			 echo $worksheet->getCell('G'.$row)->getValue();
			 echo "</td><tr>";
		}
		echo "</table>";	
?>

</body>
</html>