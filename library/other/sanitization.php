<?php
include_once "string.php";

//消毒總函式
//input:
//	$data(待消毒字串)
//	$mode:模式
//		DEFAULT:預設模式
//		XML_VALUE:代表放在XML中間的值
//output: 消毒後字串
function sanitize($data,$mode="DEFAULT"){

	//將php執行開始符號、結束符號刪除
	$data=preg_replace('/[<][?](.*)[?][>]/','',$data);
	$data=str_replace("<?","",$data);
	$data=str_replace("?>","",$data);
	$data=preg_replace('/[&][l][t][;][?](.*)[?][&][g][t][;]/','',$data);
	$data=str_replace("&lt;?","",$data);
	$data=str_replace("?&gt;","",$data);
	
	//XSS消毒 - <script>
	$data=preg_replace('/[<](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[>](.*)[<][\/](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[\/][Ss][Cc][Rr][Ii][Pp][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[&][g][t][;](.*)[&][l][t][;][\/](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](\s*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[\/][Ss][Cc][Rr][Ii][Pp][Tt](\s*)[&][g][t][;]/','',$data);
	
	//XSS消毒 - <iframe>
	$data=preg_replace('/[<](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[>](.*)[<][\/](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](\s*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[\/][Ii][Ff][Rr][Aa][Mm][Ee](\s*)[>]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[&][g][t][;](.*)[&][l][t][;][\/](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](\s*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[\/][Ii][Ff][Rr][Aa][Mm][Ee](\s*)[&][g][t][;]/','',$data);
	
	//XSS消毒 - <object>
	$data=preg_replace('/[<](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[>](.*)[<][\/](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[\/][Oo][Bb][Jj][Ee][Cc][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[&][g][t][;](.*)[&][l][t][;][\/](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](\s*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[\/][Oo][Bb][Jj][Ee][Cc][Tt](\s*)[&][g][t][;]/','',$data);
	
	//HTML entities
	$data=xml_value($data);
	
	//允許html換行
	$data=preg_replace('/[&][l][t][;][b][r](\s*)[&][g][t][;]/','*****NEW_LINE*****',$data);
	$data=preg_replace('/[&][l][t][;][b][r](\s*)[\/][&][g][t][;]/','*****NEW_LINE*****',$data);
	$data=preg_replace('/[&][l][t][;][\/][b][r](\s*)[&][g][t][;]/','*****NEW_LINE*****',$data);
	
	//允許粗體字
	$data=preg_replace('/[&][l][t][;][b](\s*)[&][g][t][;]/','*****BOLD_BEGINNING*****',$data);
	$data=preg_replace('/[&][l][t][;][\/][b](\s*)[&][g][t][;]/','*****BOLD_ENDING*****',$data);
	
	//完全去除&lt;與&gt;
	$data=str_replace('&lt;','',$data);
	$data=str_replace('&gt;','',$data);
	
	//還原換行
	if($mode=="XML_VALUE"){
		$data=str_replace('*****NEW_LINE*****','&lt;br&gt;',$data);
		$data=str_replace('*****NEW_LINE*****','&lt;br&gt;',$data);
		$data=str_replace('*****BOLD_BEGINNING*****','&lt;b&gt;',$data);
		$data=str_replace('*****BOLD_ENDING*****','&lt;/b&gt;',$data);
	}
	else{
		$data=str_replace('*****NEW_LINE*****','<br>',$data);
		$data=str_replace('*****NEW_LINE*****','<br>',$data);
		$data=str_replace('*****BOLD_BEGINNING*****','<b>',$data);
		$data=str_replace('*****BOLD_ENDING*****','</b>',$data);
	}
	
	//SQLI消毒採用prepared variable的方法
	//因此在這裡不寫消毒函式
	
	//回傳消毒後結果
	return $data;
}

//消毒總函式
//input:
//	$data(待消毒字串)
//output: 消毒後字串
function sanitize_xml($data){

	//將php執行開始符號、結束符號刪除
	$data=preg_replace('/[<][?](.*)[?][>]/','',$data);
	$data=str_replace("<?","",$data);
	$data=str_replace("?>","",$data);
	$data=preg_replace('/[&][l][t][;][?](.*)[?][&][g][t][;]/','',$data);
	$data=str_replace("&lt;?","",$data);
	$data=str_replace("?&gt;","",$data);
	
	//XSS消毒 - <script>
	$data=preg_replace('/[<](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[>](.*)[<][\/](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[\/][Ss][Cc][Rr][Ii][Pp][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[&][g][t][;](.*)[&][l][t][;][\/](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](\s*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ss][Cc][Rr][Ii][Pp][Tt](.*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[\/][Ss][Cc][Rr][Ii][Pp][Tt](\s*)[&][g][t][;]/','',$data);
	
	//XSS消毒 - <iframe>
	$data=preg_replace('/[<](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[>](.*)[<][\/](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](\s*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[\/][Ii][Ff][Rr][Aa][Mm][Ee](\s*)[>]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[&][g][t][;](.*)[&][l][t][;][\/](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](\s*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Ii][Ff][Rr][Aa][Mm][Ee](.*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[\/][Ii][Ff][Rr][Aa][Mm][Ee](\s*)[&][g][t][;]/','',$data);
	
	//XSS消毒 - <object>
	$data=preg_replace('/[<](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[>](.*)[<][\/](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[>]/','',$data);
	$data=preg_replace('/[<](\s*)[\/][Oo][Bb][Jj][Ee][Cc][Tt](\s*)[>]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[&][g][t][;](.*)[&][l][t][;][\/](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](\s*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[Oo][Bb][Jj][Ee][Cc][Tt](.*)[&][g][t][;]/','',$data);
	$data=preg_replace('/[&][l][t][;](\s*)[\/][Oo][Bb][Jj][Ee][Cc][Tt](\s*)[&][g][t][;]/','',$data);
	
	//允許html換行
	$data=preg_replace('/[<][b][r](\s*)[>]/','*****NEW_LINE*****',$data);
	$data=preg_replace('/[<][b][r](\s*)[\/][>]/','*****NEW_LINE*****',$data);
	$data=preg_replace('/[&][l][t][;][b][r](\s*)[&][g][t][;]/','*****NEW_LINE*****',$data);
	$data=preg_replace('/[&][l][t][;][b][r](\s*)[\/][&][g][t][;]/','*****NEW_LINE*****',$data);
	
	//完全去除&lt;與&gt; 與看不到的字元
	$data=str_replace('&lt;','',$data);
	$data=str_replace('&gt;','',$data);
	$data=str_replace('','',$data);
	$data=str_replace('','',$data);
	
	$data=str_replace('*****NEW_LINE*****','&lt;br&gt;',$data);
	$data=str_replace('*****NEW_LINE*****','&lt;br&gt;',$data);
	
	//SQLI消毒採用prepared variable的方法
	//因此在這裡不寫消毒函式
	
	//回傳消毒後結果
	return $data;
}
?>