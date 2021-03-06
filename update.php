<?php
$root=$_SERVER['DOCUMENT_ROOT'];
$step=$_REQUEST["step"];
$engine="{$root}/engine";

switch ($step) {
	default:
		$res=array("next"=>"Получение актуальной версии AiKi Engine","error"=>0,"count"=>4);
		break;
	case 1:
		exec("cd {$root} && wget https://github.com/aikianapa/aiki/archive/master.zip && chmod 0777 master.zip");
		$res=array("next"=>"Распаковка архива");
		if (is_file("{$root}/master.zip")) {$res["error"]=false;} else {$res["error"]=true;}
		break;
	case 2:
		exec("cd {$root} && unzip master.zip &&	rm master.zip");
		$res=array("next"=>"Удаление предыдущей версии");
		if (is_dir("{$root}/aik-master")) {$res["error"]=false;} else {$res["error"]=true;}
		break;
	case 3:
		$res=array("next"=>"Обновление системы","error"=>0);
		exec("cd {$root} && rm -rf {$engine}/!(update.php) && rm -rf {$engine}/.*");
		break;
	case 4:
		$res=array("next"=>"Обновление выполнено","error"=>0);
		recurse_copy($root."/aiki-master",$engine);
		exec("cd {$root} && rm -R aiki-master && rm master.zip");
		break;

}
echo json_encode($res);

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst); @chmod($dst,0777);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src.'/'. $file , $dst.'/'.$file);
            } else { 
				copy($src.'/'.$file , $dst.'/'.$file); 
				@chmod($dst.'/'.$file,0766);
			}
        }
    }
    closedir($dir);
}
?>



