<?php
	
	
	class Download_file
	{
		function download($id_file)
		{
			$con = new connexion();
			
			$res = $con->query("SELECT path FROM files WHERE id='".$id_file."'")->fetch();
			
			$file = getcwd()."/".$res->path;
			
			echo $file;
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
	}
?>