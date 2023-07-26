<?php
//XML值的處理
function xml_value($text){
	return remove_invisible_chars(remove_emoji(xml_entities($text)));
}

//將XML特殊字元 轉換成entity的寫法
//input: $text為一字串
//output: 將XML特殊字元代換成entity的寫法, 特殊字元轉換如下:
//	& : &amp;
//	< : &lt;
//	> : &gt;
//	" : &quot;
//	' : &apos;
//	\ : \\;
function xml_entities($text){
	$text=str_replace("\\","\\\\",$text);
	return str_replace('&#039;', '&apos;', htmlspecialchars($text, ENT_QUOTES));
}

//去除顏文字
function remove_emoji($string){

	// Match Emoticons
	$regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
	$clear_string = preg_replace($regex_emoticons, '', $string);

	// Match Miscellaneous Symbols and Pictographs
	$regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
	$clear_string = preg_replace($regex_symbols, '', $clear_string);

	// Match Transport And Map Symbols
	$regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
	$clear_string = preg_replace($regex_transport, '', $clear_string);

	// Match Miscellaneous Symbols
	$regex_misc = '/[\x{2300}-\x{26FF}]/u';
	$clear_string = preg_replace($regex_misc, '', $clear_string);

	// Match Dingbats
	$regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
	$clear_string = preg_replace($regex_dingbats, '', $clear_string);

	// Additional Match Emoticons
	$regex_v2_emoticons = '/[\x{1F900}-\x{1F9FF}]/u';
	$clear_string = preg_replace($regex_v2_emoticons, '', $clear_string);

	// Match Miscellaneous Symbols
	$regex_misc_v2 = '/[\x{2B00}-\x{2BFF}]/u';
	$clear_string = preg_replace($regex_misc_v2, '', $clear_string);

	// Match Miscellaneous Symbols
	$regex_misc_v3 = '/[\x{3030}-\x{303F}]/u';
	$clear_string = preg_replace($regex_misc_v3, '', $clear_string);
	$regex_misc_v4 = '/[\x{3290}-\x{329F}]/u';
	$clear_string = preg_replace($regex_misc_v4, '', $clear_string);

	// Additional Match Others
	$regex_others = '/[\x{1F000}-\x{1F2FF}]/u';
	$clear_string = preg_replace($regex_others, '', $clear_string);

	// // Additional Match Others
	// $regex_others_v2 = '/[\x{FE00}-\x{FEFF}]/u';
	// $clear_string = preg_replace($regex_others_v2, '', $clear_string);

	// Additional Match Others
	// $regex_others_v3 = '/[\x{2000}-\x{21FF}]/u';
	$regex_others_v3 = '/[\x{2100}-\x{21FF}]/u';
	$clear_string = preg_replace($regex_others_v3, '', $clear_string);

	// Additional Match Others
	$regex_others_v4 = '/[\x{1FA00}-\x{1FAFF}]/u';
	$clear_string = preg_replace($regex_others_v4, '', $clear_string);

	// Additional Match Others
	$regex_others_v5 = '/[\x{1F700}-\x{1F7FF}]/u';
	$clear_string = preg_replace($regex_others_v5, '', $clear_string);

	// Additional Match Others
	$regex_others_v6 = '/[\x{2930}-\x{293F}]/u';
	$clear_string = preg_replace($regex_others_v6, '', $clear_string);

	// Additional Match Others
	// $regex_others_v7 = '/[\x{0020}-\x{003F}]/u';
	// $clear_string = preg_replace($regex_others_v7, '', $clear_string);
	// $regex_others_v7a = '/[\x{00A0}-\x{00AF}]/u';
	// $clear_string = preg_replace($regex_others_v7a, '', $clear_string);

	// Additional Match Others
	$regex_others_v8 = '/[\x{E0000}-\x{E00FF}]/u';
	$clear_string = preg_replace($regex_others_v8, '', $clear_string);

	return $clear_string;
}

//去除看不到的字元
function remove_invisible_chars($string){	
	
	//此空格非正常的空格,會造成錯誤
	$result=str_replace('','',$string);
	$result=str_replace('','',$result);
	$result=str_replace(array("\r\n","\n"),array("",""),$result);
	return str_replace(' ','',$result);
}

//將字串轉成MySQL的boolean
function string_to_bool($str){
	if($str=="true"){
		return '1';
	}
	else{
		return '0';
	}
}

//產生隨機字串
//input:
//	$length:產生長度為多少的隨機字串
//	$mode:模式
//		NUMBER:每個字元只允許數字
//		LOWERCASE:每個字元只允許數字與小寫字母
//		ALPHABET:每個字元允許數字,小寫字母,大寫字母
function random_string($length,$mode){
	
	//根據模式,指定不同的字元集合
	if($mode=="NUMBER"){
		$characters='0123456789';
	}
	elseif($mode=="LOWERCASE"){
		$characters='0123456789abcdefghijklmnopqrstuvwxyz';
	}
	else{
		$characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}
	
	//形成隨機字串
	$randstring='';
    for ($i=0;$i<((int)($length));$i++) {
        $randstring.=$characters[rand(0,(strlen($characters)-1))];
    }
	
	//回傳結果
    return $randstring;
}
?>