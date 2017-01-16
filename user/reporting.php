<?php
	$Auth->allow("reporting");

	$con = new connexion();
	
	$day=date("d");
	$month=date("m");
	$year=date("Y");
	
	$report_month=date("m",strtotime("-1 month"));
	$report_year=date("Y",strtotime("-1 month"));
		
	$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$report_year."' AND mois='".$report_month."'")->fetch();
	if(isset($id_mois_requete) && !empty($id_mois_requete))
	{
		$id_mois = $id_mois_requete->id;
	}
	else
	{
		$con->query("INSERT INTO mois (id,annee,mois,is_validated) VALUES ('','".$report_year."','".$report_month."','')");
		//echo "mois ".$year."-".$month." created<br>";
		
		$id_mois_requete = $con->query("SELECT id FROM mois WHERE annee='".$report_year."' AND mois='".$report_month."'")->fetch();
		$id_mois = $id_mois_requete->id;
	}
	
	$data = $con->query("SELECT * FROM rapport WHERE id_user=".$_SESSION["Auth"]->id." AND id_mois=".$id_mois)->fetch(PDO::FETCH_BOTH); //PDO::FETCH_BOTH
		
	
	if(!empty($_POST))
	{
		extract($_POST);
		
		if (isset($action_demande) && $action_demande == 'add') 
		{
			$date_demande = date("Y")."-".date("m")."-".date("d")." ".date("H:i:s");
			$cont = $con->query("INSERT INTO demande_reouverture VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,$date_demande,'',$message,'-1']);
			
			$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,35,'Demande de reouverture de session',date("y-m-d"),1]);
				
			echo 'add demande';
		}
		
		if(isset($action) && $action == 'report')
		{
			$day_before_2days = $day-2;
			$date_before_2days = $year."-".$month."-".$day_before_2days." ".date("H:i:s");
			$profils = $con->query("SELECT * FROM demande_reouverture WHERE reponse=0 AND date_activation >= '".$date_before_2days."' AND id_user=".$_SESSION["Auth"]->id)->fetchAll();
			$test=1;
			$is_ok=1;
			if($day>15)
			{
				$is_ok=0;
				if($profils==null)
					echo "Sorry, time expired.<br>";
				else
				{
					$cont = $con->query("UPDATE demande_reouverture SET reponse=2 WHERE id='".$profils[0]->id."'");
					echo "You just used a Modification/Upload permission <br>";
					$is_ok=1;
				}
			}
			
			if($is_ok==1)
			{
				require("/inc/send_mail.php");
				$sujet= "Sujet : L'utilisateur ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom." à Remplit le rapport du mois ".strftime("%B %Y",strtotime("-1 month") );
				$body = "Salam,<br><br>L'utilisateur ".$_SESSION["Auth"]->nom." ".$_SESSION["Auth"]->prenom." à Remplit le rapport du mois ".strftime("%B %Y",strtotime("-1 month") )." le ".date("Y-m-d H:i:s").".<br><br>Cordialement.";
				send_mail("abdelhadimersoul@gmail.com","Mersoul Abdelhadi",$sujet,$body);
				
				$cont = $con->query("INSERT INTO notification VALUES(?,?,?,?,?,?)",['',$_SESSION["Auth"]->id,35,"l'utilisateur à remplit son rapport mensuel.",date("y-m-d"),1]);
				
				if(!empty($data)) //update requete
				{
					$requete = "UPDATE rapport SET is_valider=0 , ";
					$requete.= "YTD_chiffre_affaires=".$YTD_chiffre_affaires.", YTDL_chiffre_affaires=".$YTDL_chiffre_affaires.", YTDB_chiffre_affaires=".$YTDB_chiffre_affaires.", ALY_chiffre_affaires=".$ALY_chiffre_affaires.", ABBP_chiffre_affaires=".$ABBP_chiffre_affaires.", ABR_chiffre_affaires=".$ABR_chiffre_affaires.",";
					$requete.= " YTD_achat_revendu=".$YTD_achat_revendu.", YTDL_achat_revendu=".$YTDL_achat_revendu.", YTDB_achat_revendu=".$YTDB_achat_revendu.", ALY_achat_revendu=".$ALY_achat_revendu.", ABBP_achat_revendu=".$ABBP_achat_revendu.", ABR_achat_revendu=".$ABR_achat_revendu.",";
					$requete.= " YTD_charges_oper=".$YTD_charges_oper.", YTDL_charges_oper=".$YTDL_charges_oper.", YTDB_charges_oper=".$YTDB_charges_oper.", ALY_charges_oper=".$ALY_charges_oper.", ABBP_charges_oper=".$ABBP_charges_oper.", ABR_charges_oper=".$ABR_charges_oper.",";
					$requete.= " YTD_salaires=".$YTD_salaires.", YTDL_salaires=".$YTDL_salaires.", YTDB_salaires=".$YTDB_salaires.", ALY_salaires=".$ALY_salaires.", ABBP_salaires=".$ABBP_salaires.", ABR_salaires=".$ABR_salaires.",";
					$requete.= " YTD_taxes=".$YTD_taxes.", YTDL_taxes=".$YTDL_taxes.", YTDB_taxes=".$YTDB_taxes.", ALY_taxes=".$ALY_taxes.", ABBP_taxes=".$ABBP_taxes.", ABR_taxes=".$ABR_taxes.",";
					$requete.= " YTD_amortissement=".$YTD_amortissement.", YTDL_amortissement=".$YTDL_amortissement.", YTDB_amortissement=".$YTDB_amortissement.", ALY_amortissement=".$ALY_amortissement.", ABBP_amortissement=".$ABBP_amortissement.", ABR_amortissement=".$ABR_amortissement.",";
					$requete.= " YTD_res_finans=".$YTD_res_finans.", YTDL_res_finans=".$YTDL_res_finans.", YTDB_res_finans=".$YTDB_res_finans.", ALY_res_finans=".$ALY_res_finans.", ABBP_res_finans=".$ABBP_res_finans.", ABR_res_finans=".$ABR_res_finans.",";
					$requete.= " YTD_charges_produits=".$YTD_charges_produits.", YTDL_charges_produits=".$YTDL_charges_produits.", YTDB_charges_produits=".$YTDB_charges_produits.", ALY_charges_produits=".$ALY_charges_produits.", ABBP_charges_produits=".$ABBP_charges_produits.", ABR_charges_produits=".$ABR_charges_produits.",";
					$requete.= " YTD_IS=".$YTD_IS.", YTDL_IS=".$YTDL_IS.", YTDB_IS=".$YTDB_IS.", ALY_IS=".$ALY_IS.", ABBP_IS=".$ABBP_IS.", ABR_IS=".$ABR_IS.",";
					$requete.= " date_envoi='".date('Y-m-d H:i:s')."' WHERE id_user=".$_SESSION["Auth"]->id." AND id_mois=".$id_mois."";
				}
				
				else //insert requete
				{
					$requete = "INSERT INTO rapport VALUES('',";
					$requete.= $YTD_chiffre_affaires.",".$YTDL_chiffre_affaires.",".$YTDB_chiffre_affaires.",".$ALY_chiffre_affaires.",".$ABBP_chiffre_affaires.",".$ABR_chiffre_affaires.",";
					$requete.= $YTD_achat_revendu.",".$YTDL_achat_revendu.",".$YTDB_achat_revendu.",".$ALY_achat_revendu.",".$ABBP_achat_revendu.",".$ABR_achat_revendu.",";
					$requete.= $YTD_charges_oper.",".$YTDL_charges_oper.",".$YTDB_charges_oper.",".$ALY_charges_oper.",".$ABBP_charges_oper.",".$ABR_charges_oper.",";
					$requete.= $YTD_salaires.",".$YTDL_salaires.",".$YTDB_salaires.",".$ALY_salaires.",".$ABBP_salaires.",".$ABR_salaires.",";
					$requete.= $YTD_taxes.",".$YTDL_taxes.",".$YTDB_taxes.",".$ALY_taxes.",".$ABBP_taxes.",".$ABR_taxes.",";
					$requete.= $YTD_amortissement.",".$YTDL_amortissement.",".$YTDB_amortissement.",".$ALY_amortissement.",".$ABBP_amortissement.",".$ABR_amortissement.",";
					$requete.= $YTD_res_finans.",".$YTDL_res_finans.",".$YTDB_res_finans.",".$ALY_res_finans.",".$ABBP_res_finans.",".$ABR_res_finans.",";
					$requete.= $YTD_charges_produits.",".$YTDL_charges_produits.",".$YTDB_charges_produits.",".$ALY_charges_produits.",".$ABBP_charges_produits.",".$ABR_charges_produits.",";
					$requete.= $YTD_IS.",".$YTDL_IS.",".$YTDB_IS.",".$ALY_IS.",".$ABBP_IS.",".$ABR_IS.",";
					$requete.= "'".date('Y-m-d H:i:s')."','',".$_SESSION["Auth"]->id.",".$id_mois.")";
					
					//echo $requete."<br>";
				}
				
				$con->query($requete);
			
			}
		
		}
		
		$data = $con->query("SELECT * FROM rapport WHERE id_user=".$_SESSION["Auth"]->id." AND id_mois=".$id_mois)->fetch(PDO::FETCH_BOTH);
		
		
		if(isset($action) && $action == 'xsl')
		{
			require_once "PHPExcel/Classes/PHPExcel.php";

			//$tmpfname = "Reporting cannevas.xlsx";
			
			$tmpfname = $_FILES["fileToUpload"]["tmp_name"];
			$target_file = basename($_FILES["fileToUpload"]["name"]);
			
			$uploadOk = 1;
			
			$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
			if($FileType != "xlsm" && $FileType != "xlsx" && $FileType != "xlt" )		
			{
				echo "Sorry, only MS Excel files are allowed.<br>";
				$uploadOk = 0;
			}
			
			else
			{
				
				$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
				$excelObj = $excelReader->load($tmpfname);
				$worksheet = $excelObj->getSheet(0);// or use ->getActiveSheet() for the last sheet
				$lastRow = $worksheet->getHighestRow();
				$lastCol = $worksheet->getHighestColumn();
				
				$excel_array = $worksheet->toArray(null,true,true,true); //get data as array, excel_array[1][A]
				
				/* echo "<table>";
				echo "<tr><td>";
				echo $excel_array[1]['A'];
				echo "</td><td>";
				echo $excel_array[1]['G'];
				echo "</td><tr>"; */
				/*
				for ($row = 1; $row <= $lastRow; $row++) {
					 echo "<tr><td>";
					 echo $worksheet->getCell('A'.$row)->getValue();
					 echo "</td><td>";
					 echo $worksheet->getCell('G'.$row)->getValue();
					 echo "</td><tr>";
				}
				echo "</table>";*/	
				
				$data[1]=$excel_array[8]['G'];$data[2]=$excel_array[8]['H'];$data[3]=$excel_array[8]['J'];
				$data[4]=$excel_array[8]['L'];$data[5]=$excel_array[8]['M'];$data[6]=$excel_array[8]['N'];
				
				$data[7]=$excel_array[10]['G'];$data[8]=$excel_array[10]['H'];$data[9]=$excel_array[10]['J'];
				$data[10]=$excel_array[10]['L'];$data[11]=$excel_array[10]['M'];$data[12]=$excel_array[10]['N'];
				
				$data[13]=$excel_array[15]['G'];$data[14]=$excel_array[15]['H'];$data[15]=$excel_array[15]['J'];
				$data[16]=$excel_array[15]['L'];$data[17]=$excel_array[15]['M'];$data[18]=$excel_array[15]['N'];
				
				$data[19]=$excel_array[16]['G'];$data[20]=$excel_array[16]['H'];$data[21]=$excel_array[16]['J'];
				$data[22]=$excel_array[16]['L'];$data[23]=$excel_array[16]['M'];$data[24]=$excel_array[16]['N'];
				
				$data[25]=$excel_array[17]['G'];$data[26]=$excel_array[17]['H'];$data[27]=$excel_array[17]['J'];
				$data[28]=$excel_array[17]['L'];$data[29]=$excel_array[17]['M'];$data[30]=$excel_array[17]['N'];
				
				$data[31]=$excel_array[24]['G'];$data[32]=$excel_array[24]['H'];$data[33]=$excel_array[24]['J'];
				$data[34]=$excel_array[24]['L'];$data[35]=$excel_array[24]['M'];$data[36]=$excel_array[24]['N'];
				
				$data[37]=$excel_array[28]['G'];$data[38]=$excel_array[28]['H'];$data[39]=$excel_array[28]['J'];
				$data[40]=$excel_array[28]['L'];$data[41]=$excel_array[28]['M'];$data[42]=$excel_array[28]['N'];
				
				$data[43]=$excel_array[33]['G'];$data[44]=$excel_array[33]['H'];$data[45]=$excel_array[33]['J'];
				$data[46]=$excel_array[33]['L'];$data[47]=$excel_array[33]['M'];$data[48]=$excel_array[33]['N'];
				
				$data[49]=$excel_array[34]['G'];$data[50]=$excel_array[34]['H'];$data[51]=$excel_array[34]['J'];
				$data[52]=$excel_array[34]['L'];$data[53]=$excel_array[34]['M'];$data[54]=$excel_array[34]['N'];
			}
			
		}
		
	}
	
	
?>



<style type="text/css">
.reporting-input 
{
	margin: 5px;
    padding: 0 0px !important;
    width: 95px !important;
    height: 27px;
    color: #404040;
    border: 1px solid;
    border-color: #ccc;
    border-radius: 2px;
}

.reporting-input-var
{
	margin: 5px;
    padding: 0 0px !important;
    width: 60px !important;
    height: 27px;
    color: #404040;
    border: 1px solid;
    border-color: #ccc;
    border-radius: 2px;
}

.reporting-input-var2
{
	margin: 5px;
    padding: 0 0px !important;
	width: 95px !important;
    height: 27px;
    color: #404040;
    border: 1px solid;
    border-color: #ccc;
    border-radius: 2px;
}

table td 
{
	padding: 10px 5px;
}

table thead td 
{
    font-size: 13px;
}
</style>

<script type="text/javascript">
$(function () {
	var variance = function()
	{
		$var1 = ($("input[name='YTD_chiffre_affaires']").val() / $("input[name='YTDL_chiffre_affaires']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_chiffre_affaires']").val() / $("input[name='YTDB_chiffre_affaires']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_chiffre_affaires']").val($var1);	$("input[name='var2_chiffre_affaires']").val($var2);
		
		$var1 = ($("input[name='YTD_achat_revendu']").val() / $("input[name='YTDL_achat_revendu']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_achat_revendu']").val() / $("input[name='YTDB_achat_revendu']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_achat_revendu']").val($var1);			$("input[name='var2_achat_revendu']").val($var2);
		
		$YTD = parseInt ($("input[name='YTD_chiffre_affaires']").val()) +parseInt ( $("input[name='YTD_achat_revendu']").val() );
		$YTDL = parseInt ($("input[name='YTDL_chiffre_affaires']").val()) +parseInt ( $("input[name='YTDL_achat_revendu']").val());
		$("input[name='YTD_marge']").val($YTD);			$("input[name='YTDL_marge']").val($YTDL);
		$var1 = ($("input[name='YTD_marge']").val() / $("input[name='YTDL_marge']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";
		$("input[name='var1_marge']").val($var1);
		$YTDB = parseInt ( $("input[name='YTDB_chiffre_affaires']").val()) + parseInt ( $("input[name='YTDB_achat_revendu']").val());
		$("input[name='YTDB_marge']").val($YTDB);
		$var2 = ($("input[name='YTD_marge']").val() / $("input[name='YTDB_marge']").val() -1 )* 100;
		$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var2_marge']").val($var2);
		$ALY = parseInt ($("input[name='ALY_chiffre_affaires']").val()) +parseInt ( $("input[name='ALY_achat_revendu']").val() );
		$ABBP = parseInt ($("input[name='ABBP_chiffre_affaires']").val()) +parseInt ( $("input[name='ABBP_achat_revendu']").val());
		$ABR = parseInt ($("input[name='ABR_chiffre_affaires']").val()) +parseInt ( $("input[name='ABR_achat_revendu']").val() );
		$("input[name='ALY_marge']").val($ALY);$("input[name='ABBP_marge']").val($ABBP);$("input[name='ABR_marge']").val($ABR);
		
		$YTD = ($("input[name='YTD_marge']").val() / $("input[name='YTD_chiffre_affaires']").val())* 100;
		$YTDL = ($("input[name='YTDL_marge']").val() / $("input[name='YTDL_chiffre_affaires']").val() )* 100;
		$YTDB = ($("input[name='YTDB_marge']").val() / $("input[name='YTDB_chiffre_affaires']").val() )* 100;
		$ALY = ($("input[name='ALY_marge']").val() / $("input[name='ALY_chiffre_affaires']").val())* 100;
		$ABBP = ($("input[name='ABBP_marge']").val() / $("input[name='ABBP_chiffre_affaires']").val() )* 100;
		$ABR = ($("input[name='ABR_marge']").val() / $("input[name='ABR_chiffre_affaires']").val()  )* 100;
		$YTD = Math.round($YTD).toFixed(2)+"%";	$YTDL = Math.round($YTDL).toFixed(2)+"%";	$YTDB = Math.round($YTDB).toFixed(2)+"%";
		$ALY = Math.round($ALY).toFixed(2)+"%";	$ABBP = Math.round($ABBP).toFixed(2)+"%";	$ABR = Math.round($ABR).toFixed(2)+"%";
		$("input[name='YTD_per_sales']").val($YTD);	$("input[name='YTDL_per_sales']").val($YTDL);	$("input[name='YTDB_per_sales']").val($YTDB);
		$("input[name='ALY_per_sales']").val($ALY);	$("input[name='ABBP_per_sales']").val($ABBP);	$("input[name='ABR_per_sales']").val($ABR);
		
		
		
		
		$var1 = ($("input[name='YTD_charges_oper']").val() / $("input[name='YTDL_charges_oper']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_charges_oper']").val() / $("input[name='YTDB_charges_oper']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_charges_oper']").val($var1);			$("input[name='var2_charges_oper']").val($var2);
		
		$var1 = ($("input[name='YTD_salaires']").val() / $("input[name='YTDL_salaires']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_salaires']").val() / $("input[name='YTDB_salaires']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_salaires']").val($var1);			$("input[name='var2_salaires']").val($var2);
		
		$var1 = ($("input[name='YTD_taxes']").val() / $("input[name='YTDL_taxes']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_taxes']").val() / $("input[name='YTDB_taxes']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_taxes']").val($var1);			$("input[name='var2_taxes']").val($var2);
		
		$YTD = parseInt ($("input[name='YTD_charges_oper']").val()) +parseInt ( $("input[name='YTD_salaires']").val()) +parseInt ( $("input[name='YTD_taxes']").val());
		$YTDL = parseInt ($("input[name='YTDL_charges_oper']").val()) +parseInt ( $("input[name='YTDL_salaires']").val()) +parseInt ( $("input[name='YTDL_taxes']").val());
		$("input[name='YTD_charge_expl']").val($YTD);			$("input[name='YTDL_charge_expl']").val($YTDL);
		$var1 = ($("input[name='YTD_charge_expl']").val() / $("input[name='YTDL_charge_expl']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";
		$("input[name='var1_charge_expl']").val($var1);
		$YTDB = parseInt ( $("input[name='YTDB_charges_oper']").val()) + parseInt ( $("input[name='YTDB_salaires']").val()) + parseInt ( $("input[name='YTDB_taxes']").val());
		$("input[name='YTDB_charge_expl']").val($YTDB);
		$var2 = ($("input[name='YTD_charge_expl']").val() / $("input[name='YTDB_charge_expl']").val() -1 )* 100;
		$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var2_charge_expl']").val($var2);
		$ALY = parseInt ($("input[name='ALY_charges_oper']").val()) +parseInt ( $("input[name='ALY_salaires']").val() ) +parseInt ( $("input[name='ALY_taxes']").val() );
		$ABBP = parseInt ($("input[name='ABBP_charges_oper']").val()) +parseInt ( $("input[name='ABBP_salaires']").val()) +parseInt ( $("input[name='ABBP_taxes']").val()) ;
		$ABR = parseInt ($("input[name='ABR_charges_oper']").val()) +parseInt ( $("input[name='ABR_salaires']").val() ) +parseInt ( $("input[name='ABR_taxes']").val() );
		$("input[name='ALY_charge_expl']").val($ALY);$("input[name='ABBP_charge_expl']").val($ABBP);$("input[name='ABR_charge_expl']").val($ABR);
		
		$YTD = parseInt ($("input[name='YTD_marge']").val()) +parseInt ( $("input[name='YTD_charge_expl']").val() );
		$YTDL = parseInt ($("input[name='YTDL_marge']").val()) +parseInt ( $("input[name='YTDL_charge_expl']").val());
		$("input[name='YTD_ebita']").val($YTD);			$("input[name='YTDL_ebita']").val($YTDL);
		$var1 = ($("input[name='YTD_ebita']").val() / $("input[name='YTDL_ebita']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";
		$("input[name='var1_ebita']").val($var1);
		$YTDB = parseInt ( $("input[name='YTDB_marge']").val()) + parseInt ( $("input[name='YTDB_charge_expl']").val());
		$("input[name='YTDB_ebita']").val($YTDB);
		$var2 = ($("input[name='YTD_ebita']").val() / $("input[name='YTDB_ebita']").val() -1 )* 100;
		$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var2_ebita']").val($var2);
		$ALY = parseInt ($("input[name='ALY_marge']").val()) +parseInt ( $("input[name='ALY_charge_expl']").val() );
		$ABBP = parseInt ($("input[name='ABBP_marge']").val()) +parseInt ( $("input[name='ABBP_charge_expl']").val());
		$ABR = parseInt ($("input[name='ABR_marge']").val()) +parseInt ( $("input[name='ABR_charge_expl']").val() );
		$("input[name='ALY_ebita']").val($ALY);$("input[name='ABBP_ebita']").val($ABBP);$("input[name='ABR_ebita']").val($ABR);
		
		
		$YTD = ($("input[name='YTD_ebita']").val() / $("input[name='YTD_chiffre_affaires']").val())* 100;
		$YTDL = ($("input[name='YTDL_ebita']").val() / $("input[name='YTDL_chiffre_affaires']").val() )* 100;
		$YTDB = ($("input[name='YTDB_ebita']").val() / $("input[name='YTDB_chiffre_affaires']").val() )* 100;
		$ALY = ($("input[name='ALY_ebita']").val() / $("input[name='ALY_chiffre_affaires']").val())* 100;
		$ABBP = ($("input[name='ABBP_ebita']").val() / $("input[name='ABBP_chiffre_affaires']").val() )* 100;
		$ABR = ($("input[name='ABR_ebita']").val() / $("input[name='ABR_chiffre_affaires']").val()  )* 100;
		$YTD = Math.round($YTD).toFixed(2)+"%";	$YTDL = Math.round($YTDL).toFixed(2)+"%";	$YTDB = Math.round($YTDB).toFixed(2)+"%";
		$ALY = Math.round($ALY).toFixed(2)+"%";	$ABBP = Math.round($ABBP).toFixed(2)+"%";	$ABR = Math.round($ABR).toFixed(2)+"%";
		$("input[name='YTD_per_sales2']").val($YTD);	$("input[name='YTDL_per_sales2']").val($YTDL);	$("input[name='YTDB_per_sales2']").val($YTDB);
		$("input[name='ALY_per_sales2']").val($ALY);	$("input[name='ABBP_per_sales2']").val($ABBP);	$("input[name='ABR_per_sales2']").val($ABR);
		
		
		
		$var1 = ($("input[name='YTD_amortissement']").val() / $("input[name='YTDL_amortissement']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_amortissement']").val() / $("input[name='YTDB_amortissement']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_amortissement']").val($var1);			$("input[name='var2_amortissement']").val($var2);
		
		$YTD = parseInt ($("input[name='YTD_ebita']").val()) +parseInt ( $("input[name='YTD_amortissement']").val() );
		$YTDL = parseInt ($("input[name='YTDL_ebita']").val()) +parseInt ( $("input[name='YTDL_amortissement']").val());
		$("input[name='YTD_ebit']").val($YTD);			$("input[name='YTDL_ebit']").val($YTDL);
		$var1 = ($("input[name='YTD_ebit']").val() / $("input[name='YTDL_ebit']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";
		$("input[name='var1_ebit']").val($var1);
		$YTDB = parseInt ( $("input[name='YTDB_ebita']").val()) + parseInt ( $("input[name='YTDB_amortissement']").val());
		$("input[name='YTDB_ebit']").val($YTDB);
		$var2 = ($("input[name='YTD_ebit']").val() / $("input[name='YTDB_ebit']").val() -1 )* 100;
		$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var2_ebit']").val($var2);
		$ALY = parseInt ($("input[name='ALY_ebita']").val()) +parseInt ( $("input[name='ALY_amortissement']").val() );
		$ABBP = parseInt ($("input[name='ABBP_ebita']").val()) +parseInt ( $("input[name='ABBP_amortissement']").val());
		$ABR = parseInt ($("input[name='ABR_ebita']").val()) +parseInt ( $("input[name='ABR_amortissement']").val() );
		$("input[name='ALY_ebit']").val($ALY);$("input[name='ABBP_ebit']").val($ABBP);$("input[name='ABR_ebit']").val($ABR);
		
		
		$YTD = ($("input[name='YTD_ebit']").val() / $("input[name='YTD_chiffre_affaires']").val())* 100;
		$YTDL = ($("input[name='YTDL_ebit']").val() / $("input[name='YTDL_chiffre_affaires']").val() )* 100;
		$var1 = ($("input[name='var1_ebit']").val() / $("input[name='var1_chiffre_affaires']").val() )* 100;
		$YTDB = ($("input[name='YTDB_ebit']").val() / $("input[name='YTDB_chiffre_affaires']").val() )* 100;
		$var2 = ($("input[name='var2_ebit']").val() / $("input[name='var2_chiffre_affaires']").val() )* 100;
		$ALY = ($("input[name='ALY_ebit']").val() / $("input[name='ALY_chiffre_affaires']").val())* 100;
		$ABBP = ($("input[name='ABBP_ebit']").val() / $("input[name='ABBP_chiffre_affaires']").val() )* 100;
		$ABR = ($("input[name='ABR_ebit']").val() / $("input[name='ABR_chiffre_affaires']").val()  )* 100;
		$YTD = Math.round($YTD).toFixed(2)+"%";	$YTDL = Math.round($YTDL).toFixed(2)+"%";	$YTDB = Math.round($YTDB).toFixed(2)+"%";
		$ALY = Math.round($ALY).toFixed(2)+"%";	$ABBP = Math.round($ABBP).toFixed(2)+"%";	$ABR = Math.round($ABR).toFixed(2)+"%";
		//$var1 = Math.round($var1).toFixed(2)+"%"; $var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='YTD_per_sales3']").val($YTD);	$("input[name='YTDL_per_sales3']").val($YTDL);	$("input[name='YTDB_per_sales3']").val($YTDB);
		$("input[name='ALY_per_sales3']").val($ALY);	$("input[name='ABBP_per_sales3']").val($ABBP);	$("input[name='ABR_per_sales3']").val($ABR);
		//$("input[name='var1_per_sales3']").val($var1);	$("input[name='var2_per_sales3']").val($var2);
		
		
		$var1 = ($("input[name='YTD_res_finans']").val() / $("input[name='YTDL_res_finans']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_res_finans']").val() / $("input[name='YTDB_res_finans']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_res_finans']").val($var1);			$("input[name='var2_res_finans']").val($var2);
		
		$YTD = parseInt ($("input[name='YTD_ebit']").val()) +parseInt ( $("input[name='YTD_res_finans']").val() );
		$YTDL = parseInt ($("input[name='YTDL_ebit']").val()) +parseInt ( $("input[name='YTDL_res_finans']").val());
		$("input[name='YTD_ebt']").val($YTD);			$("input[name='YTDL_ebt']").val($YTDL);
		$var1 = ($("input[name='YTD_ebt']").val() / $("input[name='YTDL_ebt']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";
		$("input[name='var1_ebt']").val($var1);
		$YTDB = parseInt ( $("input[name='YTDB_ebit']").val()) + parseInt ( $("input[name='YTDB_res_finans']").val());
		$("input[name='YTDB_ebt']").val($YTDB);
		$var2 = ($("input[name='YTD_ebt']").val() / $("input[name='YTDB_ebt']").val() -1 )* 100;
		$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var2_ebt']").val($var2);
		$ALY = parseInt ($("input[name='ALY_ebit']").val()) +parseInt ( $("input[name='ALY_res_finans']").val() );
		$ABBP = parseInt ($("input[name='ABBP_ebit']").val()) +parseInt ( $("input[name='ABBP_res_finans']").val());
		$ABR = parseInt ($("input[name='ABR_ebit']").val()) +parseInt ( $("input[name='ABR_res_finans']").val() );
		$("input[name='ALY_ebt']").val($ALY);$("input[name='ABBP_ebt']").val($ABBP);$("input[name='ABR_ebt']").val($ABR);
		
		$YTD = ($("input[name='YTD_ebt']").val() / $("input[name='YTD_chiffre_affaires']").val())* 100;
		$YTDL = ($("input[name='YTDL_ebt']").val() / $("input[name='YTDL_chiffre_affaires']").val() )* 100;
		$YTDB = ($("input[name='YTDB_ebt']").val() / $("input[name='YTDB_chiffre_affaires']").val() )* 100;
		$ALY = ($("input[name='ALY_ebt']").val() / $("input[name='ALY_chiffre_affaires']").val())* 100;
		$ABBP = ($("input[name='ABBP_ebt']").val() / $("input[name='ABBP_chiffre_affaires']").val() )* 100;
		$ABR = ($("input[name='ABR_ebt']").val() / $("input[name='ABR_chiffre_affaires']").val()  )* 100;
		$YTD = Math.round($YTD).toFixed(2)+"%";	$YTDL = Math.round($YTDL).toFixed(2)+"%";	$YTDB = Math.round($YTDB).toFixed(2)+"%";
		$ALY = Math.round($ALY).toFixed(2)+"%";	$ABBP = Math.round($ABBP).toFixed(2)+"%";	$ABR = Math.round($ABR).toFixed(2)+"%";
		$("input[name='YTD_per_sales4']").val($YTD);	$("input[name='YTDL_per_sales4']").val($YTDL);	$("input[name='YTDB_per_sales4']").val($YTDB);
		$("input[name='ALY_per_sales4']").val($ALY);	$("input[name='ABBP_per_sales4']").val($ABBP);	$("input[name='ABR_per_sales4']").val($ABR);
		
		
		$var1 = ($("input[name='YTD_charges_produits']").val() / $("input[name='YTDL_charges_produits']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_charges_produits']").val() / $("input[name='YTDB_charges_produits']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_charges_produits']").val($var1);			$("input[name='var2_charges_produits']").val($var2);
		
		$var1 = ($("input[name='YTD_IS']").val() / $("input[name='YTDL_IS']").val() -1 )* 100;
		$var2 = ($("input[name='YTD_IS']").val() / $("input[name='YTDB_IS']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";	$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var1_IS']").val($var1);			$("input[name='var2_IS']").val($var2);
		
		
		$YTD = parseInt ($("input[name='YTD_ebt']").val()) +parseInt ( $("input[name='YTD_charges_produits']").val()) +parseInt ( $("input[name='YTD_IS']").val());
		$YTDL = parseInt ($("input[name='YTDL_ebt']").val()) +parseInt ( $("input[name='YTDL_charges_produits']").val()) +parseInt ( $("input[name='YTDL_IS']").val());
		$("input[name='YTD_total']").val($YTD);			$("input[name='YTDL_total']").val($YTDL);
		$var1 = ($("input[name='YTD_total']").val() / $("input[name='YTDL_total']").val() -1 )* 100;
		$var1 = Math.round($var1).toFixed(2)+"%";
		$("input[name='var1_total']").val($var1);
		$YTDB = parseInt ( $("input[name='YTDB_ebt']").val()) + parseInt ( $("input[name='YTDB_charges_produits']").val()) + parseInt ( $("input[name='YTDB_IS']").val());
		$("input[name='YTDB_total']").val($YTDB);
		$var2 = ($("input[name='YTD_total']").val() / $("input[name='YTDB_total']").val() -1 )* 100;
		$var2 = Math.round($var2).toFixed(2)+"%";
		$("input[name='var2_total']").val($var2);
		$ALY = parseInt ($("input[name='ALY_ebt']").val()) +parseInt ( $("input[name='ALY_charges_produits']").val() ) +parseInt ( $("input[name='ALY_IS']").val() );
		$ABBP = parseInt ($("input[name='ABBP_ebt']").val()) +parseInt ( $("input[name='ABBP_charges_produits']").val()) +parseInt ( $("input[name='ABBP_IS']").val()) ;
		$ABR = parseInt ($("input[name='ABR_ebt']").val()) +parseInt ( $("input[name='ABR_charges_produits']").val() ) +parseInt ( $("input[name='ABR_IS']").val() );
		$("input[name='ALY_total']").val($ALY);$("input[name='ABBP_total']").val($ABBP);$("input[name='ABR_total']").val($ABR);
		
	}
	
	$("input").keyup(variance).hover(variance).ready(variance);
});
</script>

<h2> <center>  <u>
Reporting du mois de <?php setlocale(LC_TIME,'fr_FR.utf8','fra'); echo utf8_encode(strftime("%B %Y",strtotime("-1 month") )) ?>
</u> </center>   </h2>

<a href="#" class="add">Demander réouverture</a>
<div class="adduser form" style="left: 35%;width: 40%;margin: 106px auto;">
	<h2>Demander la reouverture de la session</h2>
	<span class="close"></span>
	<form id="form" method="post" action="/user/reporting">
		<p><input type="hidden" name="uid" value=""></p>
		<p><textarea name="message" placeholder="message...." value="" rows="7" cols="75"></textarea></p>
		<p><input type="hidden" name="action_demande" id="action" value="add_demande"></p>
		<p class="submit"><input type="submit" id="btnsubmit" name="submit" value="demander"></p>
	</form>
</div>


<form action="/user/reporting" method="POST">


<table>
	<thead>
		<tr>
			<td>In K MAD</td>
			<td>YTD</td>
			<td>UTD Last Year</td>
			<td>Var.</td>
			
			<td>YTD Budget</td>
			<td>Var.</td>
			
			<td>Actual Last Year</td>
			<td>Annual Budget BP</td>
			<td>Annual Budget Reforcast</td>
		</tr>
		<tr>
			<td></td>
			<td><?php echo $report_month; ?> month</td>
			<td><?php echo $report_month; ?> month</td>
			<td></td>
			
			<td><?php echo $report_month; ?> month</td>
			<td></td>
			
			<td>12 month</td>
			<td>12 month</td>
			<td>12 month</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Chiffre d'affaires</td>
			<td><input type="text" class="reporting-input" name="YTD_chiffre_affaires" value="<?php if(!empty($data)) echo $data[1]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_chiffre_affaires" value="<?php if(!empty($data)) echo $data[2]; ?>" required></td>
			<td><input name="var1_chiffre_affaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_chiffre_affaires" value="<?php if(!empty($data)) echo $data[3]; ?>" required></td>
			<td><input name="var2_chiffre_affaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_chiffre_affaires" value="<?php if(!empty($data)) echo $data[4]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_chiffre_affaires" value="<?php if(!empty($data)) echo $data[5]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_chiffre_affaires" value="<?php if(!empty($data)) echo $data[6]; ?>" required></td>
		</tr>
		<tr>
			<td>Achat revendu de marchandises</td>
			<td><input type="text" class="reporting-input" name="YTD_achat_revendu" value="<?php if(!empty($data)) echo $data[7]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_achat_revendu" value="<?php if(!empty($data)) echo $data[8]; ?>" required></td>
			<td><input name="var1_achat_revendu" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_achat_revendu" value="<?php if(!empty($data)) echo $data[9]; ?>" required></td>
			<td><input name="var2_achat_revendu" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_achat_revendu" value="<?php if(!empty($data)) echo $data[10]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_achat_revendu" value="<?php if(!empty($data)) echo $data[11]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_achat_revendu" value="<?php if(!empty($data)) echo $data[12]; ?>" required></td>
		</tr>
		<tr>
			<td>Marge brute</td>
			<td><input name="YTD_marge" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_marge" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_marge" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_marge" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_marge" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_marge" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_marge" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_marge" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>As a % of sales</td>
			<td><input name="YTD_per_sales" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_per_sales" class="reporting-input-var2" disabled> </td>
			<td></td>
			<td><input name="YTDB_per_sales" class="reporting-input-var2" disabled> </td>
			<td></td>
			<td><input name="ALY_per_sales" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_per_sales" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_per_sales" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>Charges opérationnelles</td>
			<td><input type="text" class="reporting-input" name="YTD_charges_oper" value="<?php if(!empty($data)) echo $data[13]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_charges_oper" value="<?php if(!empty($data)) echo $data[14]; ?>" required></td>
			<td><input name="var1_charges_oper" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_charges_oper" value="<?php if(!empty($data)) echo $data[15]; ?>" required></td>
			<td><input name="var2_charges_oper" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_charges_oper" value="<?php if(!empty($data)) echo $data[16]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_charges_oper" value="<?php if(!empty($data)) echo $data[17]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_charges_oper" value="<?php if(!empty($data)) echo $data[18]; ?>" required></td>
		</tr>
		<tr>
			<td>Salaires et charges sociale</td>
			<td><input type="text" class="reporting-input" name="YTD_salaires" value="<?php if(!empty($data)) echo $data[19]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_salaires" value="<?php if(!empty($data)) echo $data[20]; ?>" required></td>
			<td><input name="var1_salaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_salaires" value="<?php if(!empty($data)) echo $data[21]; ?>" required></td>
			<td><input name="var2_salaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_salaires" value="<?php if(!empty($data)) echo $data[22]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_salaires" value="<?php if(!empty($data)) echo $data[23]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_salaires" value="<?php if(!empty($data)) echo $data[24]; ?>" required></td>
		</tr>
		<tr>
			<td>Taxes</td>
			<td><input type="text" class="reporting-input" name="YTD_taxes" value="<?php if(!empty($data)) echo $data[25]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_taxes" value="<?php if(!empty($data)) echo $data[26]; ?>" required></td>
			<td><input name="var1_taxes" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_taxes" value="<?php if(!empty($data)) echo $data[27]; ?>" required></td>
			<td><input name="var2_taxes" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_taxes" value="<?php if(!empty($data)) echo $data[28]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_taxes" value="<?php if(!empty($data)) echo $data[29]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_taxes" value="<?php if(!empty($data)) echo $data[30]; ?>" required></td>
		</tr>
		<tr>
			<td>Total charges d'exploitation</td>
			<td><input name="YTD_charge_expl" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_charge_expl" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_charge_expl" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_charge_expl" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_charge_expl" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_charge_expl" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_charge_expl" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_charge_expl" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>EBITDA</td>
			<td><input name="YTD_ebita" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_ebita" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_ebita" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_ebita" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_ebita" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_ebita" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_ebita" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_ebita" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>As a % of sales</td>
			<td><input name="YTD_per_sales2" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_per_sales2" class="reporting-input-var2" disabled> </td>
			<td></td>
			<td><input name="YTDB_per_sales2" class="reporting-input-var2" disabled> </td>
			<td></td>
			<td><input name="ALY_per_sales2" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_per_sales2" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_per_sales2" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>Amortissement et provisions</td>
			<td><input type="text" class="reporting-input" name="YTD_amortissement" value="<?php if(!empty($data)) echo $data[31]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_amortissement" value="<?php if(!empty($data)) echo $data[32]; ?>" required></td>
			<td><input name="var1_amortissement" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_amortissement" value="<?php if(!empty($data)) echo $data[33]; ?>" required></td>
			<td><input name="var2_amortissement" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_amortissement" value="<?php if(!empty($data)) echo $data[34]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_amortissement" value="<?php if(!empty($data)) echo $data[35]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_amortissement" value="<?php if(!empty($data)) echo $data[36]; ?>" required></td>
		</tr>
		<tr>
			<td>EBIT</td>
			<td><input name="YTD_ebit" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_ebit" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_ebit" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_ebit" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_ebit" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_ebit" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_ebit" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_ebit" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>As a % of sales</td>
			<td><input name="YTD_per_sales3" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_per_sales3" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_per_sales3" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_per_sales3" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_per_sales3" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_per_sales3" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_per_sales3" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_per_sales3" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>Résultat financier</td>
			<td><input type="text" class="reporting-input" name="YTD_res_finans" value="<?php if(!empty($data)) echo $data[37]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_res_finans" value="<?php if(!empty($data)) echo $data[38]; ?>" required></td>
			<td><input name="var1_res_finans" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_res_finans" value="<?php if(!empty($data)) echo $data[39]; ?>" required></td>
			<td><input name="var2_res_finans" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_res_finans" value="<?php if(!empty($data)) echo $data[40]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_res_finans" value="<?php if(!empty($data)) echo $data[41]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_res_finans" value="<?php if(!empty($data)) echo $data[42]; ?>" required></td>
		</tr>
		<tr>
			<td>EBT</td>
			<td><input name="YTD_ebt" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_ebt" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_ebt" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_ebt" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_ebt" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_ebt" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_ebt" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_ebt" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>As a % of sales</td>
			<td><input name="YTD_per_sales4" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_per_sales4" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_per_sales4" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_per_sales4" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_per_sales4" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_per_sales4" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_per_sales4" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_per_sales4" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>Charges et produits exceptionnel</td>
			<td><input type="text" class="reporting-input" name="YTD_charges_produits" value="<?php if(!empty($data)) echo $data[43]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_charges_produits" value="<?php if(!empty($data)) echo $data[44]; ?>" required></td>
			<td><input name="var1_charges_produits" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_charges_produits" value="<?php if(!empty($data)) echo $data[45]; ?>" required></td>
			<td><input name="var2_charges_produits" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_charges_produits" value="<?php if(!empty($data)) echo $data[46]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_charges_produits" value="<?php if(!empty($data)) echo $data[47]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_charges_produits" value="<?php if(!empty($data)) echo $data[48]; ?>" required></td>
		</tr>
		<tr>
			<td>IS</td>
			<td><input type="text" class="reporting-input" name="YTD_IS" value="<?php if(!empty($data)) echo $data[49]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="YTDL_IS" value="<?php if(!empty($data)) echo $data[50]; ?>" required></td>
			<td><input name="var1_IS" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_IS" value="<?php if(!empty($data)) echo $data[51]; ?>" required></td>
			<td><input name="var2_IS" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_IS" value="<?php if(!empty($data)) echo $data[52]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABBP_IS" value="<?php if(!empty($data)) echo $data[53]; ?>" required></td>
			<td><input type="text" class="reporting-input" name="ABR_IS" value="<?php if(!empty($data)) echo $data[54]; ?>" required></td>
		</tr>
		<tr>
			<td>Résultat Net</td>
			<td><input name="YTD_total" class="reporting-input-var2" disabled> </td>
			<td><input name="YTDL_total" class="reporting-input-var2" disabled> </td>
			<td><input name="var1_total" class="reporting-input-var" disabled> </td>
			<td><input name="YTDB_total" class="reporting-input-var2" disabled> </td>
			<td><input name="var2_total" class="reporting-input-var" disabled> </td>
			<td><input name="ALY_total" class="reporting-input-var2" disabled> </td>
			<td><input name="ABBP_total" class="reporting-input-var2" disabled> </td>
			<td><input name="ABR_total" class="reporting-input-var2" disabled> </td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="action" value="report">
				<input type="submit" value="enregister et envoyer" name="save"/>
			</td>
			<td>
				<input type="reset" value="reset" 
					style="padding: 3px 20px;height: 29px;font-size: 12px;
					font-weight: bold;color: #fff;background: #34495e;
					border: 0px solid;border-radius: 3px;outline: 0;"/>
			</td>
		</tr>
	</tbody>
</table>

</form>

<table style="margin : 0px;">
	<thead>
		<tr>
			<td>Utiliser un fichier Excel du Reporting  <font color="black" > (compatible au canvas du Reporting) </font>  </td>
		</tr>
	</thead>
	<tbody>
		<form action="/user/reporting" method="post" enctype="multipart/form-data">
			<tr>
				<input type="hidden" name="action" value="xsl">
				<td> <input type="file" name="fileToUpload" id="fileToUpload"> </td>
			</tr>
			<tr>
				<td> <input type="submit" value="Upload file" name="submit"> </td>
			</tr>
		</form>
	</tbody>
</table>