<?php
/***** 關於時間的函式集合 *****/

//取得現在時間
//input:
//	$timezone(PHP所定義的時區字串)
//output:
//	回傳時間字串 格式: 2022-01-14 10:10:01
function get_now($timezone){
	if(function_exists('date_default_timezone_set')){ 
		date_default_timezone_set($timezone);
	}
	return date('Y-m-d H:i:s');
}

//給定日期與加減天數，計算新的日期 *****/
//input: 
//	$date代表日期 格式: 2022-01-14
//	$offset代表加減的天數，型態為整數，正整數代表加，負整數代表減
//output:
//	回傳計算後的日期字串 格式: 2022-01-15
function compute_date($date,$offset){
	
	//取得年、月、日
	$temp=explode("-",$date);
	$year=((int)($temp[0]));
	$month=((int)($temp[1]));
	$day=((int)($temp[2]));
	
	//計算結果
	$result=date("Y-m-d",mktime(0,0,0,$month,($day+$offset),$year));

	//回傳結果
	return $result;
}

/***** 給定小時與分鐘，換算成分鐘 *****/
//input: 
//	$time代表時間(格式如12:05，代表12小時又5分鐘)
//output:
//	回傳總分鐘數(型態為數字)
function switch_to_minute($time){
	
	//轉換為分鐘
	$time_int=explode(":",$time);
	$hour=(int)($time_int[0]);
	$minute=(int)($time_int[1]);
	$time_int=$hour*60+$minute;

	//回傳結果
	return $time_int;
}

//***** 取得日期 *****
//input:
//  $date_time代表日期時間，格式：2022-01-16 18:00
//output:
//  回傳日期，例如：2022-01-16
function get_date($date_time){
	return substr($date_time,0,10);
}

//***** 取得日期 *****
//input:
//  $date_time代表日期時間，格式：2022-01-16 18:00:00
//output:
//  回傳日期,小時,分，例如：2022-01-16 18:00
function get_date_hour_minute($date_time){
	return substr($date_time,0,16);
}

//***** 計算該時段分鐘數 *****
//input: 
//	$beginning_time代表開始時間(格式如12:05，代表12小時又5分鐘)
//	$ending_time代表結束時間(格式如12:05，代表12小時又5分鐘)
//output:
//	回傳總分鐘數(型態為數字)
function compute_interval($beginning_time,$ending_time){
	
	//將開始與結束時間轉換為分鐘
	$beginning=switch_to_minute($beginning_time);
	$ending=switch_to_minute($ending_time);
	
	if($beginning<=$ending){
		return ($ending-$beginning);
	}
	else{
		return 0;
	} 
}

//***** 計算該時間的mktime *****
//input:
//	$time:欲計算之時間(格式可為2022-01-16(僅日期格式),或2022-01-16 08:30:33(完整時間格式))
//output:
//	回傳php之mktime函數所回傳的時間
function convert_to_mktime($time){

	if(strlen($time)==10){
		$date=array();
		$date[0]=substr($time,0,4);
		$date[1]=substr($time,5,2);
		$date[2]=substr($time,8,2);
		return mktime(0,0,0,((int)($date[1])),((int)($date[2])),((int)($date[0])));
	}
	else if(strlen($time)==19){
		$part=array();
		$part[0][0]=substr($time,0,4);
		$part[0][1]=substr($time,5,2);
		$part[0][2]=substr($time,8,2);
		$part[1][0]=substr($time,11,2);
		$part[1][1]=substr($time,14,2);
		$part[1][2]=substr($time,17,2);
	
		return mktime(
			((int)($part[1][0])),
			((int)($part[1][1])),
			((int)($part[1][2])),
			((int)($part[0][1])),
			((int)($part[0][2])),
			((int)($part[0][0]))
		);	
	}
}


//將日期字串轉成數字陣列
//input:
//	$time:yyyy-mm-dd
//output:
//	$array

function convert_date_str_to_int_array($time){
	$tmp=explode('-', $time);
	if(count($tmp)!=3){
		return null;
	}
	else{
		return array(
			'year'=>intval($tmp[0]),
			'month'=>intval($tmp[1]),
			'day'=>intval($tmp[2]),
		);
	}
}


//給定完整時間與時間offset,回傳計算後的完成時間
//input:
//	$time:完整日期時間,格式2022-01-16 18:00:05
//	$offset_seconds:偏移量,可為正負,負數代表過去,正數代表未來,以秒計算
//output:
//	回傳計算後的完整日期時間格式
function compute_time($time,$offset_seconds){

	//將時間轉換成mktime
	$mktime=convert_to_mktime($time);
	
	//計算新的時間
	$mktime=$mktime+((int)($offset_seconds));
	
	//回傳新的日期時間
	return date('Y-m-d H:i:s',$mktime);
}

//將月、日、時、分、秒 不足2位數則補零
function add_zero($num){
	
	//初始化(確保為字串型態)
	$num=$num."";
	
	//依num之不同位數處理
	if(strlen($num)==0){
		return ("00");
	}
	elseif(strlen($num)==1){
		return ("0".$num);
	}
	else{
		return $num;
	}
}

//給定日期(年-月-日)，取得沒有年的日期
function get_month_day($date){
	return substr($date,5,5);
}

//給定年月日，回傳日期字串
function get_date_string($year,$month,$day){
	return $year."-".add_zero($month)."-".add_zero($day);
}

//比較兩個時間早晚
//input:
//	$time_1為第一個時間,格式為2022-01-16 07:29:33
//	$time_2為第二個時間,格式如$time_1
//output:
//	"EARLIER":$time_1時間早於$time_2
//	"EQUAL":$time_1時間與$time_2時間相等
//	"LATER":$time_1時間晚於$time_2
function compare_time($time1,$time2){

	//將time1與time2轉換成mktime
	$mktime1=convert_to_mktime($time1);
	$mktime2=convert_to_mktime($time2);

	//比較兩個mktime
	if($mktime1<$mktime2){
		return "EARLIER";
	}
	elseif($mktime1==$mktime2){
		return "EQUAL";
	}
	else{
		return "LATER";
	}
}

//取得中文的星期幾
function get_week_day_chinese($date){
	$week_day=get_week_day($date);
	if($week_day=="1"){
		return t2("一");
	}
	elseif($week_day=="2"){
		return t2("二");
	}
	elseif($week_day=="3"){
		return t2("三");
	}
	elseif($week_day=="4"){
		return t2("四");
	}
	elseif($week_day=="5"){
		return t2("五");
	}
	elseif($week_day=="6"){
		return t2("六");
	}
	elseif($week_day=="7"){
		return t2("日");
	}
	else{
		return "？";
	}
}

//比較兩個日期早晚
//input:
//	$date_1為第一個日期,格式為2022-01-16
//	$date_2為第二個日期,格式如$date_1
//output:
//	"EARLIER":$date_1時間早於$date_2
//	"EQUAL":$date_1時間與$date_2時間相等
//	"LATER":$date_1時間晚於$date_2
function compare_date($date_1,$date_2){

	return compare_time(
		($date_1." 00:00:00"),
		($date_2." 00:00:00")
	);
}

//給定年、月取得月份最後一天的日期
//例如:給定2020年2月 回傳29
function get_last_day($year,$month){
	if(function_exists('date_default_timezone_set')){ 
		date_default_timezone_set("Asia/Taipei");
	}
	return date("j",mktime(0,0,0,($month+1),0,$year));
}

//給定日期 取得星期幾
//input:
//	$date:日期 2022-01-16
//output:
//	星期一為1,星期二為2,...,星期日為7
function get_week_day($date){
	$unix_time = strtotime($date); 
	$week_day = date('N', $unix_time);
	return $week_day;
}

//測速用
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

//日期相減 
//input:
//  $data_1:日期1 例如2014-06-03
//  $date_2:日期2 例如2014-03-03
//output:
//  相差幾天，0表示同一天
function minus_date($date_1,$date_2){
	return (strtotime($date_1)-strtotime($date_2))/(24*60*60);
}

//時間相減 
//input:
//  $time_1:日期1 例如2014-06-03 15:03:02
//  $time_2:日期2 例如2014-03-03 17:02:01
//output:
//  相差幾秒
function minus_time($time_1,$time_2){
	return strtotime($time_1)-strtotime($time_2);
}

//取得小時分鐘時間
function get_hour_minute($time){
	$result=substr($time,0,5);
	return $result;
}

//取得月份天數
//input:
//	$year:年份,格式為2022
//	$month:月份,格式為1
//output:
//	該月份天數
function get_month_days($year,$month){

	//處理input
	$month=add_zero($month);

	//計算月份天數
	$begin=mktime(0,0,0,$month,1,$year);
	$end=mktime(0,0,0,$month+1,0,$year);
	$diff=$end-$begin;
	$days=($diff/86400)+1;
	
	//回傳結果
	return ((int)($days));
}

//取得現在時刻的microtimestamp(跟所在時區無關)
//精準度到微秒(10^-6)
function get_microtimestamp(){

	//取得現在的microtimestamp
	list($usec, $sec) = explode(" ", microtime());

	//因為浮點數的運算很麻煩，容易會有進位的問題
	//這邊一律轉型為字串後再做處理
	//從小數點後取6位
	$usec=substr($usec,2,6);

	//將秒與微秒連接起來，成為一個16位數的數字
	$result=$sec.$usec;
	
	return $result;
}

//取得現在時刻的millitimestamp(跟所在時區無關)
//精準度到毫秒(10^-3)
function get_millitimestamp(){

	//取得現在的microtimestamp
	list($usec, $sec) = explode(" ", microtime());

	//因為浮點數的運算很麻煩，容易會有進位的問題
	//這邊一律轉型為字串後再做處理
	//從小數點後取3位
	$usec=substr($usec,2,3);

	//將秒與毫秒連接起來，成為一個13位數的數字
	$result=$sec.$usec;
	
	return $result;
}

//驗證輸入的變數是正確的日期格式(YYYY-mm-dd)
function validate_date($date){
	if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)){
		return true;
	}
	else{
		return false;
	}
}

//比較兩個時間早晚
//input:
//	$time_1為第一個日期,格式為00:00:00
//	$time_2為第二個日期,格式如time_1
//output:
//	"EARLIER":time_1時間早於time_2
//	"EQUAL":time_1時間與time_2時間相等
//	"LATER":time_1時間晚於time_2
function compare_hour_minute_second($time_1,$time_2){
	
	//取得各自小時、分鐘、秒數，轉換為秒數
	$hour_1=(int)substr($time_1,0,2);
	$minute_1=(int)substr($time_1,3,2);
	$second_1=(int)substr($time_1,6,2);
	$second_total_1=$hour_1*3600+$minute_1*60+$second_1;
	$hour_2=(int)substr($time_2,0,2);
	$minute_2=(int)substr($time_2,3,2);
	$second_2=(int)substr($time_2,6,2);
	$second_total_2=$hour_2*3600+$minute_2*60+$second_2;
	
	//比較先後
	if($second_total_1>$second_total_2){
		return "LATER";
	}
	else if($second_total_1==$second_total_2){
		return "EQUAL";
	}
	else{
		return "EARLIER";
	}
}
?>