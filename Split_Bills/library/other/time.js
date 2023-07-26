//***** 取得現在時間 *****
//input:
//	utc_offset:
//		1.值為"LOCAL"或"local", 取得client端現在時間字串
//		2.值為"+8","-1"之類的數字, 取得與UTC時間差距該數字時區的現在時間
//output: 回傳時間字串(年月日時分秒) 例如: 2011-11-08 14:05:31 
function get_now(utc_offset){
	
	//若utc_offset為空字串 則預設為台北時間
	if(utc_offset=="" || typeof(utc_offset)=="undefined"){
		utc_offset="+8";
	}
	
	//取得local的時間
	var local_time=new Date(); 
	
	//判別回傳client端現在的時間 或是回傳指定時區的時間
	if(utc_offset=="LOCAL" || utc_offset=="local"){
		var result_time=local_time;
	}
	else{
		
		//取得UTC時間 以毫秒表示
		var utc=local_time.getTime()+(local_time.getTimezoneOffset()*60000); 
	
		//轉換成指定時區的時間
		var result_time=new Date(utc+(3600000*utc_offset)); 
	}
	
	//取得年,月,日,時,分,秒
	var year=result_time.getFullYear();
	var month= (result_time.getMonth()+1);
	var day=result_time.getDate();
	var hour=result_time.getHours();
	var minute=result_time.getMinutes();
	var second=result_time.getSeconds()
	
	//形成結果
	var result=
			year+"-"+add_zero(month)+"-"+add_zero(day)+" "+
			add_zero(hour)+":"+add_zero(minute)+":"+add_zero(second);

	//回傳字串
	return result;
}

//***** 比較時間 *****
//time1大於或等於則回傳true time1較小則回傳false(time1 time2格式皆為12:05)
function compare_time(time1,time2){
	time1=switch_to_minute(time1);
	time2=switch_to_minute(time2);
	return (time1>=time2);
}

//***** 給定日期與加減天數，計算新的日期 *****
//input: 
//	date代表日期(格式如2011-08-12)
//	offset代表加減的天數，型態為整數，正整數代表加，負整數代表減
//output:
//	回傳計算後的日期
function compute_date(date,offset){
	
	//分解date
	var temp=new Array();
	temp=date.split("-");;
	
	//取得年、月、日
	var year=parseInt(temp[0],10);
	var month=parseInt(temp[1],10);
	var day=parseInt(temp[2],10);
	
	//計算結果
	var offset_date=new Date(year,(month-1),(day+parseInt(offset,10)));

	//取得計算結果的年、月、日
	var offset_year=offset_date.getFullYear();
	var offset_month= (offset_date.getMonth()+1);
	var offset_day=offset_date.getDate();
	
	//形成結果
	var result=offset_year+"-"+add_zero(offset_month)+"-"+add_zero(offset_day);
	
	//回傳結果
	return result;
}

//***** 給定兩個日期，計算兩日期差距的天數 *****
//input: 
//	date1,date2代表日期(格式如2011-08-12)
//output:
//	回傳計算後的天數
//		若為正數代表date1的日期在date2之後
//		若為負數則代表date1的日期在date2之前
//		Ex1: date1: 2011-08-12, date2: 2011-08-13, return -1
//		Ex2: date1: 2011-08-13, date2: 2011-08-12, return 1
function compute_date_inverse(date1, date2){

	var date1 = new Date(date1);
	var date2 = new Date(date2);
	var timeDiff = date1.getTime() - date2.getTime();
	var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 

	return diffDays;
}

//***** 給定時間,轉換成javascript的date物件 *****
//input: 
//	time代表時間間(格式如2011-02-12, 或2011-08-12 08:01:30)
//output:
//	回傳javascript日期物件
function convert_to_js_date(time){

	//判別time是否只有日期
	if(time.length==10){
		var date=time.split("-");
		var js_date=new Date(date[0],(parseInt(date[1],10)-1),date[2]);
	}
	else{
		var part=time.split(" ");
		var date=part[0].split("-");
		var temp=part[1].split(":");
		var js_date=new Date(
			date[0],
			(parseInt(date[1],10)-1),
			date[2],
			temp[0],
			temp[1],
			temp[2]
		);
	}
	
	//回傳結果
	return js_date;
}

//***** 給定小時與分鐘，換算成分鐘 *****
//input: 
//	time代表時間(格式如12:05，代表12小時又5分鐘)
//output:
//	回傳總分鐘數(型態為數字)
function switch_to_minute(time){
	
	//轉換為分鐘
	var time_int=new Array();
	time_int=time.split(":");
	var hour=parseInt(time_int[0],10);
	var minute=parseInt(time_int[1],10);
	var result=hour*60+minute;

	//回傳結果
	return result;
}

//***** 給定總分鐘數，換算成小時與分鐘 *****
//input:
//	minute:分鐘數(型態為int)
//	split_str:小時與分鐘中間的分隔符號(型態為字串,允許空字串)
//output:
//	回傳小時與分鐘的字串
function switch_to_time(minute,split_str){
	
	var hour_int=parseInt((minute/60),10);
	var minute_int=(minute%60);
	var hour_str=add_zero(hour_int);
	var minute_str=add_zero(minute_int);
	var result_str=hour_str+split_str+minute_str;
	return result_str;
}

//計算從第1天00:00開始的分鐘數
function minute_from_beginning(day,time){
	
	//轉成分鐘數
	day=(parseInt(day,10)-1)*(60*24);
	time=switch_to_minute(time);
	
	//回傳結果
	return (day+time);
}

//***** 取得日期 *****
//input:
//  date_time代表日期時間，格式：2012-01-03 18:00
//output:
//  回傳日期，例如：2012-01-03
function get_date(date_time){
	return date_time.substr(0,10);
}

//***** 取得月份 *****
//input:
//  date_time代表日期時間，格式：2012-01-03 18:00，或僅日期2012-03-12
//output:
//  回傳月份（不包含前置的0）
function get_month(date_time){
	return parseInt(date_time.substr(5,2),10);
}

//***** 取得年份 *****
//input:
//  date_time代表日期時間，格式：2012-01-03 18:00，或僅日期2012-03-12
//output:
//  回傳年份（不包含前置的0）
function get_year(date_time){
	return parseInt(date_time.substr(0,4),10);
}

//取得小時分鐘
function get_hour_minute(time){
	return time.substr(0,5);
}

//***** 取得季 *****
//input:
//  date_time代表日期時間，格式：2012-01-03 18:00，或僅日期2012-03-12
//output:
//  回傳1,2,3,4,代表第幾季
function get_season(date_time){
	var temp_month=get_month(date_time);
	if(temp_month<=3){
		return 1
	}
	else if(temp_month<=6){
		return 2
	}
	else if(temp_month<=9){
		return 3
	}
	else if(temp_month<=12){
		return 4
	}
}

//***** 取得月份日期數 *****
//input:
//  date_time代表日期時間，格式：2012-01-03 18:00，或僅日期2012-03-12
//output:
//  回傳28表示該越有28天
function get_days_in_month(date_time){
	var temp_year=get_year(date_time);
	var temp_month=get_month(date_time);
	var days=new Date(temp_year,temp_month,0).getDate();
	return days
}

//***** 計算該時段分鐘數 *****
//input: 
//	beginning_time代表開始時間(格式如12:05，代表12小時又5分鐘)
//	ending_time代表結束時間(格式如12:05，代表12小時又5分鐘)
//output:
//	回傳總分鐘數(型態為數字)
function compute_interval(beginning_time,ending_time){
	
	//將開始與結束時間轉換為分鐘
	var beginning=switch_to_minute(beginning_time);
	var ending=switch_to_minute(ending_time);
	
	if(beginning<=ending){
		return (ending-beginning);
	}
	else{
		return 0;
	} 
}

//***** 計算時間 *****
//input:
//	beginning_time:開始時間(格式: 12:05)
//	interval:時段(正為正負整數)
//output:
//	回傳結束時間(最小值00:00,最大值23:59)
function compute_ending_time(beginning_time,interval){
	beginning_time=switch_to_minute(beginning_time);
	interval=parseInt(interval,10);
	var hour=parseInt((beginning_time+interval)/60,10);
	var minute=parseInt((beginning_time+interval)%60,10);
	return (add_zero(hour)+":"+add_zero(minute));
}

//***** 將月、日、時、分、秒 不足2位數則補零 *****
function add_zero(num){
	
	//初始化(確保為字串型態)
	num=num+"";
	
	//依num之不同位數處理
	if(num.length==0){
		return ("00");
	}
	else if(num.length==1){
		return ("0"+num);
	}
	else{
		return num;
	}
}

//***** 給定完整時間字串 轉換成javascript的Date物件 *****
function js_date(time){

	var a=time.split(" ");
	var d=a[0].split("-");
	var t=a[1].split(":");
	var date = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);
	return date;
}

//給定日期 取得月,日
//input:date為日期(格式如2012-03-12)
//回傳:03-12
function month_day(date){
	return date.substr(5,5);
}

//給定日期 取得月,日
//input:date為日期(格式如2012-03-12)
//回傳:03/12
function month_day_slash(date){
	return date.substr(5,2)+"/"+date.substr(8,2);
}

//日期格式轉換
//input:date為日期(格式如2012-03-12)
//回傳:2012/03/12
function convert_format(date){
	return date.replace("-","/");
}

//給定日期 取得星期幾
//input:date為日期(格式如2012-03-12)
//回傳:星期幾(週一為1、週二為2、...、週日為7)
function get_day(date){
	var temp_date=convert_to_js_date(date);
	var temp=temp_date.getDay();
	if(temp==0 || temp=="0"){
		return 7;
	}
	else{
		return temp;
	}
}

//給定日期 取得星期幾
//input:date為日期(格式如2012-03-12)、prefix為星期或週
//回傳:星期幾或週幾
function get_day_chinese(date,prefix){
	var temp=get_day(date);
	if(temp==1){
		temp=t("一");
	}
	else if(temp==2){
		temp=t("二");
	}
	else if(temp==3){
		temp=t("三");
	}
	else if(temp==4){
		temp=t("四");
	}
	else if(temp==5){
		temp=t("五");
	}
	else if(temp==6){
		temp=t("六");
	}
	else if(temp==7){
		temp=t("日");
	}
	else{
		temp="？";
	}
	return prefix+temp;
}

//給定月份 取得月份中文
//input:月份,格式:1,2,3,...,12
//回傳:一月、二月...十二月
function get_month_chinese(month){
	if(month==1){
		month=t("一月");
	}
	else if(month==2){
		month=t("二月");
	}
	else if(month==3){
		month=t("三月");
	}
	else if(month==4){
		month=t("四月");
	}
	else if(month==5){
		month=t("五月");
	}
	else if(month==6){
		month=t("六月");
	}
	else if(month==7){
		month=t("七月");
	}
	else if(month==8){
		month=t("八月");
	}
	else if(month==9){
		month=t("九月");
	}
	else if(month==10){
		month=t("十月");
	}
	else if(month==11){
		month=t("十一月");
	}
	else if(month==12){
		month=t("十二月");
	}
	else{
		month="？";
	}
	return month;
}

//給定日期 取得星期幾
//input:date為日期(格式如2012-03-12)、prefix為星期或週
//回傳:星期幾或週幾
function get_day_english(date,prefix){
	var temp=get_day(date);
	if(temp==1){
		temp="Mon";
	}
	else if(temp==2){
		temp="Tue";
	}
	else if(temp==3){
		temp="Wed";
	}
	else if(temp==4){
		temp="Thu";
	}
	else if(temp==5){
		temp="Fri";
	}
	else if(temp==6){
		temp="Sat";
	}
	else if(temp==7){
		temp="Sun";
	}
	else{
		temp="？";
	}
	return prefix+temp;
}

//給定月份 取得月份英文
//input:月份,格式:1,2,3,...,12
//回傳:Jan,Feb..
function get_month_english(month){
	if(month==1){
		month="Jan";
	}
	else if(month==2){
		month="Feb";
	}
	else if(month==3){
		month="Mar";
	}
	else if(month==4){
		month="Apr";
	}
	else if(month==5){
		month="May";
	}
	else if(month==6){
		month="Jun";
	}
	else if(month==7){
		month="Jul";
	}
	else if(month==8){
		month="Aug";
	}
	else if(month==9){
		month="Sep";
	}
	else if(month==10){
		month="Oct";
	}
	else if(month==11){
		month="Nov";
	}
	else if(month==12){
		month="Dec";
	}
	else{
		month="？";
	}
	return month;
}

//給定日期 取得星期幾(日文)
//input:date為日期(格式如2012-03-12)、prefix為星期或週
//回傳:星期幾或週幾
function get_day_japan(date,prefix){
	var temp=get_day(date);
	if(temp==1){
		temp="月";
	}
	else if(temp==2){
		temp="火";
	}
	else if(temp==3){
		temp="水";
	}
	else if(temp==4){
		temp="木";
	}
	else if(temp==5){
		temp="金";
	}
	else if(temp==6){
		temp="土";
	}
	else if(temp==7){
		temp="日";
	}
	else{
		temp="？";
	}
	return prefix+temp;
}

//***** 給定兩個日期字串，比較大小 *****
//input:
//	date1:第一個日期,格式2012-03-12
//	date2:第二個日期,格式2012-03-12
//output:
//	回傳字串定義如下:
//		EARLIER:代表第一個日期較早
//		EQUAL:代表兩個日期相同
//		LATER:代表第一個日期較晚
function compare_date(date1,date2){
	
	//先轉換成JS Date物件
	var js_date1=convert_to_js_date(date1);
	var js_date2=convert_to_js_date(date2);
	
	//判別大小
	if(js_date1.valueOf()<js_date2.valueOf()){
		return "EARLIER";
	}
	else if(js_date1.valueOf()==js_date2.valueOf()){
		return "EQUAL";
	}
	else{
		return "LATER";
	}
}

//當日期需要做排序時，可採用此function
function compare_date_func(date1,date2){
	var result=compare_date(date1,date2);
	if(result=="EARLIER"){
		return -1;
	}
	else if(result=="EQUAL"){
		return 0;
	}
	else{
		return 1;
	}
}

//將日期時間轉換成日期字串格式
function convert_date_to_string(date){

	//初始化結果
	var result="";

	//取得年,月,日
	var year=date.getFullYear();
	var month=date.getMonth()+1;
	var day=date.getDate();

	//取得日期字串
	result+=year+"-"+add_zero(month)+"-"+add_zero(day);

	//回傳結果
	return result;
}

//***** 給定時間與間隔毫秒，回傳計算之後的時間 *****
//input:
//	beginning_time:開始時間,格式2012-03-12 02:05:11
//	interval:秒,可接受負數,負數代表回到過去
//output:
//	回傳計算後的時間,格式2012-03-12 02:05:11
function compute_time(beginning_time,interval){
	
	//先轉換成JS Date物件
	var beginning_js_date=convert_to_js_date(beginning_time);
	var result_js_date=new Date(beginning_js_date.getTime()+interval*1000);
	
	//取得年,月,日,時,分,秒
	var year=result_js_date.getFullYear();
	var month= (result_js_date.getMonth()+1);
	var day=result_js_date.getDate();
	var hour=result_js_date.getHours();
	var minute=result_js_date.getMinutes();
	var second=result_js_date.getSeconds();
	
	//形成結果
	var result=
			year+"-"+add_zero(month)+"-"+add_zero(day)+" "+
			add_zero(hour)+":"+add_zero(minute)+":"+add_zero(second);

	//回傳字串
	return result;
}