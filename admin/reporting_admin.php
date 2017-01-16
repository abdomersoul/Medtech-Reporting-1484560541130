<?php
	$Auth->allow("reporting_admin");

	$con = new connexion();
	
	$day=date("d");
	$month=date("m");
	$year=date("Y");
	
	
	
	if(!empty($_GET))
	{
		extract($_GET);
		
		$data = $con->query("SELECT * FROM rapport WHERE id=".$id_rapport)->fetch(PDO::FETCH_BOTH); //PDO::FETCH_BOTH
		$date_envoi=$data[55];
		$report_month=date("m",strtotime("-1 month",strtotime($date_envoi)));
	}
	else
	{
		header("Location : /admin/etat_envoi");
	}
	
?>


<?php


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
		$YTDB = ($("input[name='YTDB_ebit']").val() / $("input[name='YTDB_chiffre_affaires']").val() )* 100;
		$ALY = ($("input[name='ALY_ebit']").val() / $("input[name='ALY_chiffre_affaires']").val())* 100;
		$ABBP = ($("input[name='ABBP_ebit']").val() / $("input[name='ABBP_chiffre_affaires']").val() )* 100;
		$ABR = ($("input[name='ABR_ebit']").val() / $("input[name='ABR_chiffre_affaires']").val()  )* 100;
		$YTD = Math.round($YTD).toFixed(2)+"%";	$YTDL = Math.round($YTDL).toFixed(2)+"%";	$YTDB = Math.round($YTDB).toFixed(2)+"%";
		$ALY = Math.round($ALY).toFixed(2)+"%";	$ABBP = Math.round($ABBP).toFixed(2)+"%";	$ABR = Math.round($ABR).toFixed(2)+"%";
		$("input[name='YTD_per_sales3']").val($YTD);	$("input[name='YTDL_per_sales3']").val($YTDL);	$("input[name='YTDB_per_sales3']").val($YTDB);
		$("input[name='ALY_per_sales3']").val($ALY);	$("input[name='ABBP_per_sales3']").val($ABBP);	$("input[name='ABR_per_sales3']").val($ABR);
		
		
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
Reporting du mois de <?php setlocale(LC_TIME,'fr_FR.utf8','fra'); echo utf8_encode(strftime("%B %Y",strtotime("-1 month",strtotime($date_envoi)) ))?>
</u> </center>   </h2>

<form action="/admin/generate_file" method="POST">


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
			<td><input type="text" class="reporting-input" name="YTD_chiffre_affaires" value="<?php if(!empty($data)) echo $data[1]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_chiffre_affaires" value="<?php if(!empty($data)) echo $data[2]; ?>" disabled></td>
			<td><input name="var1_chiffre_affaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_chiffre_affaires" value="<?php if(!empty($data)) echo $data[3]; ?>" disabled></td>
			<td><input name="var2_chiffre_affaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_chiffre_affaires" value="<?php if(!empty($data)) echo $data[4]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_chiffre_affaires" value="<?php if(!empty($data)) echo $data[5]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_chiffre_affaires" value="<?php if(!empty($data)) echo $data[6]; ?>" disabled></td>
		</tr>
		<tr>
			<td>Achat revendu de marchandises</td>
			<td><input type="text" class="reporting-input" name="YTD_achat_revendu" value="<?php if(!empty($data)) echo $data[7]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_achat_revendu" value="<?php if(!empty($data)) echo $data[8]; ?>" disabled></td>
			<td><input name="var1_achat_revendu" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_achat_revendu" value="<?php if(!empty($data)) echo $data[9]; ?>" disabled></td>
			<td><input name="var2_achat_revendu" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_achat_revendu" value="<?php if(!empty($data)) echo $data[10]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_achat_revendu" value="<?php if(!empty($data)) echo $data[11]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_achat_revendu" value="<?php if(!empty($data)) echo $data[12]; ?>" disabled></td>
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
			<td><input type="text" class="reporting-input" name="YTD_charges_oper" value="<?php if(!empty($data)) echo $data[13]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_charges_oper" value="<?php if(!empty($data)) echo $data[14]; ?>" disabled></td>
			<td><input name="var1_charges_oper" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_charges_oper" value="<?php if(!empty($data)) echo $data[15]; ?>" disabled></td>
			<td><input name="var2_charges_oper" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_charges_oper" value="<?php if(!empty($data)) echo $data[16]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_charges_oper" value="<?php if(!empty($data)) echo $data[17]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_charges_oper" value="<?php if(!empty($data)) echo $data[18]; ?>" disabled></td>
		</tr>
		<tr>
			<td>Salaires et charges sociale</td>
			<td><input type="text" class="reporting-input" name="YTD_salaires" value="<?php if(!empty($data)) echo $data[19]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_salaires" value="<?php if(!empty($data)) echo $data[20]; ?>" disabled></td>
			<td><input name="var1_salaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_salaires" value="<?php if(!empty($data)) echo $data[21]; ?>" disabled></td>
			<td><input name="var2_salaires" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_salaires" value="<?php if(!empty($data)) echo $data[22]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_salaires" value="<?php if(!empty($data)) echo $data[23]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_salaires" value="<?php if(!empty($data)) echo $data[24]; ?>" disabled></td>
		</tr>
		<tr>
			<td>Taxes</td>
			<td><input type="text" class="reporting-input" name="YTD_taxes" value="<?php if(!empty($data)) echo $data[25]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_taxes" value="<?php if(!empty($data)) echo $data[26]; ?>" disabled></td>
			<td><input name="var1_taxes" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_taxes" value="<?php if(!empty($data)) echo $data[27]; ?>" disabled></td>
			<td><input name="var2_taxes" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_taxes" value="<?php if(!empty($data)) echo $data[28]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_taxes" value="<?php if(!empty($data)) echo $data[29]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_taxes" value="<?php if(!empty($data)) echo $data[30]; ?>" disabled></td>
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
			<td><input type="text" class="reporting-input" name="YTD_amortissement" value="<?php if(!empty($data)) echo $data[31]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_amortissement" value="<?php if(!empty($data)) echo $data[32]; ?>" disabled></td>
			<td><input name="var1_amortissement" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_amortissement" value="<?php if(!empty($data)) echo $data[33]; ?>" disabled></td>
			<td><input name="var2_amortissement" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_amortissement" value="<?php if(!empty($data)) echo $data[34]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_amortissement" value="<?php if(!empty($data)) echo $data[35]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_amortissement" value="<?php if(!empty($data)) echo $data[36]; ?>" disabled></td>
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
			<td><input type="text" class="reporting-input" name="YTD_res_finans" value="<?php if(!empty($data)) echo $data[37]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_res_finans" value="<?php if(!empty($data)) echo $data[38]; ?>" disabled></td>
			<td><input name="var1_res_finans" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_res_finans" value="<?php if(!empty($data)) echo $data[39]; ?>" disabled></td>
			<td><input name="var2_res_finans" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_res_finans" value="<?php if(!empty($data)) echo $data[40]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_res_finans" value="<?php if(!empty($data)) echo $data[41]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_res_finans" value="<?php if(!empty($data)) echo $data[42]; ?>" disabled></td>
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
			<td><input type="text" class="reporting-input" name="YTD_charges_produits" value="<?php if(!empty($data)) echo $data[43]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_charges_produits" value="<?php if(!empty($data)) echo $data[44]; ?>" disabled></td>
			<td><input name="var1_charges_produits" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_charges_produits" value="<?php if(!empty($data)) echo $data[45]; ?>" disabled></td>
			<td><input name="var2_charges_produits" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_charges_produits" value="<?php if(!empty($data)) echo $data[46]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_charges_produits" value="<?php if(!empty($data)) echo $data[47]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_charges_produits" value="<?php if(!empty($data)) echo $data[48]; ?>" disabled></td>
		</tr>
		<tr>
			<td>IS</td>
			<td><input type="text" class="reporting-input" name="YTD_IS" value="<?php if(!empty($data)) echo $data[49]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="YTDL_IS" value="<?php if(!empty($data)) echo $data[50]; ?>" disabled></td>
			<td><input name="var1_IS" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="YTDB_IS" value="<?php if(!empty($data)) echo $data[51]; ?>" disabled></td>
			<td><input name="var2_IS" class="reporting-input-var" disabled> </td>
			<td><input type="text" class="reporting-input" name="ALY_IS" value="<?php if(!empty($data)) echo $data[52]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABBP_IS" value="<?php if(!empty($data)) echo $data[53]; ?>" disabled></td>
			<td><input type="text" class="reporting-input" name="ABR_IS" value="<?php if(!empty($data)) echo $data[54]; ?>" disabled></td>
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
	</tbody>
</table>
<input type="hidden" name="rapport_id" value="<?php echo $id_rapport; ?>"/>
<center><input type="submit" name="generate" value="Générer le rapport"/></center><br>

</form>