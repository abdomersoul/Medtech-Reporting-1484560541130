<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title>TESTING</title>
</head>
<style type="text/css">
    body {
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-size:12px;
        margin:0px;
        padding:0px;
    }
</style>
<html>
<body>
<?php
    $table = '<table border="0" cellpadding="0" cellspacing="0"><tr><td>';
    $table .= '<tr><td rowspan="2" style="background-color:#000099;color:#FFFFFF;">test</td>';
    $table .= '<td colspan="2" style="background-color:#FFFF33">TEst</td>';
    $table .= '<td colspan="2" style="background-color:#FFFF33">teST</td>';
    $table .= '<td rowspan="2" style="background-color:#000099;">Test<br>test</td>';
    $table .= '</tr><tr><td style="background-color:#FFFCCC">Test</td>';
    $table .= '<td style="background-color:#FFFCCC">test</td>';
    $table .= '<td style="background-color:#FFFCCC">test</td>';
    $table .= '<td style="background-color:#FFFCCC">test</td>';
    $table .= '</tr><tr></td></tr></table>';

    header("Content-type: application/x-msdownload"); 
    header('Content-Disposition: attachment; filename="filename.xls"');
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $table;
?>
</body>
</html>