<?php
//这是
//
function GrabImage($url, $dir = '', $filename = '',$x=1)
{
    if (empty($url)) {
        return false;
    }
    $ext = strrchr($url, '.');
    //为空就当前目录
    
//
    //目录+文件
    $dir = "d:/xg" . (empty($filename) ? '/' . time() . rand(1, 1000) . $ext : '/' . $dir.'/');
	
	if($x==1){
		mkdir(iconv('utf-8','gbk',$dir));
	}
	
	$filename=$dir.time() . rand(1, 1000).'.jpg';
	//echo $filename;exit;
    //开始捕捉
    //方法一
    ob_start();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    $img = ob_get_contents();
    ob_end_clean();
    file_put_contents(iconv('utf-8','gbk',$filename), $img);

    //方法二
    /*      $a=file_get_contents($url);
          file_put_contents($filename,$a);*/

    //方法三
    /*    ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
        $fp2 = fopen($filename, "a");
        fwrite($fp2, $img);
        fclose($fp2);*/
    return $filename;
}






set_time_limit(0);
require 'QueryList.class.php';
$start = microtime(true);

for ($i=1; $i<=27; $i++) {
	if($i==1){
		$turl='http://www.ilovgou.com/xg/';
	}else{
		$turl='http://www.ilovgou.com/xg/index_'.$i.'.html';
	}
	
	
	phpQuery::newDocumentFile($turl); 
$artlist = pq(".clearfix li"); 
foreach($artlist as $li){ 
   $aa=pq($li)->find("a")->attr('href');
   $url='http://www.ilovgou.com'.$aa;
   $aa=file_get_contents($url);
   preg_match('#\(1/(.*)\)#',$aa,$match);
   $num=$match[1];
   
   for ($x=1; $x<=$num; $x++) {
    if($x==1){
		   $content_url=$url;
	   }else{
		   $newurl=$url;
		   $aaa=rtrim($newurl,'.html');
		   $content_url=$aaa.'_'.$x.'.html';
	   }
	   $end_content=file_get_contents($content_url);
		   preg_match('#<h1>(.*)</h1>#',$end_content,$title_matchs);
		   $wenjian='';
		$img_title=$title_matchs[1];
		phpQuery::newDocumentFile($content_url); 
		$src=pq("center")->find('img')->attr('src');
		if(stripos($src,"d/file")!== false){
         $src='http://www.ilovgou.com'.$src;
         }
			
		$one_title=preg_replace('#\(.*\)#','',$img_title);
		GrabImage($src, $one_title."(".$num.")", $img_title,$x);
} 
} 

	
	
}





exit;

/*

header('Content-type:text/html;charset=utf-8');

$url = 'http://querylist.cc/explore/';
// 抓取相关数据
// 取值类似jQuery的操作
$reg = array('href'=>array('img','src'),// 解析头像 对应html代码为<img width="48" height="48" class="pfs" src="http://pic.cnblogs.com/face/694143/20141118194530.png" alt="">
    'title'=>array('.titlelnk','text'),// 解析文章名 对应html代码为 <a class="titlelnk" ....简单的jQuery 四级分类实用插件</a>
    'content'=>array('.post_item_summary','text'),// 解析文章简介 对应html代码为  <a href="http://www.cnblogs.com/jr1993/".....   前言最近因需要自 .... 首先html代码： ...
    'content_url'=>array('.titlelnk','href'));// 解析帖子链接 对应html代码为<a class="titlelnk" href="http://www.cnblogs.com/jr1993/p/4716308.html" target="_blank">
// 抓取内容的div
$rang = '.aw-item';
$hj = QueryList::Query($url,$reg,$rang,'curl');*/
/*print_r(json_decode($hj->getJSON(),true));*/

/*function aa($v){
   unset($v['title']);
    echo '<img src='.$v['img'].'>';
    return $v;
}


$url = "http://www.cnbeta.com/";
//元素选择器
$reg = array(
    "title" => array("a","text"),
    "href"   => array("a","href")
);
//块选择器
$rang = ".sub_navi li";
//采集
$hj = QueryList::Query($url,$reg,$rang);
//输入采集结果
$arr=$hj->jsonArr;
//$a=array_map('aa',$arr);
print_r($arr);*/


/*$redis = new Redis();
$redis->connect('127.0.0.1', 6379);


function aa($v=123){

    if($v==123){
        $webUrl="http://www.whdota.com/boke";
        $url = "http://www.whdota.com/boke/index.php/index-show-tid-1.html";
//元素选择器
        $reg = array(
            "title" => array("h3","text"),
            "href"   => array("h3 a","href")
        );
//块选择器
        $rang = ".wz";
//采集
        $hj = QueryList::Query($url,$reg,$rang);
//输入采集结果
        $arr=$hj->jsonArr;
        return $arr;
    }else{
       $url="http://www.whdota.com".$v['href'];
        $reg = array(
            "conent" => array(".news_text","html"),
            //"href"   => array("h3 a","href")
        );
        $rang = ".news_content";
//采集
        $hj = QueryList::Query($url,$reg,$rang);
//输入采集结果
        $arr=$hj->jsonArr;
        return array_merge($v,$arr[0]);
    }
}

$caiji=$redis->get("caijiji");

if(!empty($caiji)){
    $xinxi=json_decode($caiji,true);
}else{
    $xinxi=aa();
    $redis->set("caijiji",json_encode($xinxi));
}

if(!empty($xinxi)){
    $new=array_map('aa',$xinxi);

    print_r($new);

    // 获取存储的数据并输出
  //  echo "Stored string in redis:: " . $redis->get("tutorial-name");

}





/*$webUrl="http://www.whdota.com/boke";

$url = "http://www.whdota.com/boke/index.php/index-show-tid-1.html";
//元素选择器
$reg = array(
    "title" => array("h3","text"),
    "href"   => array("h3 a","href")
);
//块选择器
$rang = ".wz";
//采集
$hj = QueryList::Query($url,$reg,$rang);
//输入采集结果
$arr=$hj->jsonArr;
//$a=array_map('aa',$arr);
if(!empty($arr)){

   // print_r($arr);
   $ar=array_map('aa',$arr);
}*/
//echo sprintf('asss -%s','nihao');exit;
//echo ltrim(strrchr('http://localhost/1.php','/'),'/');exit;



GrabImage('http://www.ilovgou.com/d/file/mt/20170822/f1a3f3dfb422e9c00aecbf40446a8656.jpg');
exit;


function caiji($v = '', $url = "http://www.cnbeta.com")
{

    if ($v == '') {
//元素选择器
        $reg = array(
            "title" => array(".title a", "text"),
            "img" => array(".picno img", "src"),
            'desc' => array('.newsinfo p', 'text'),
            'href' => array('.pic a', 'href')
        );
//块选择器
        $rang = ".hd";
//采集
        $hj = QueryList::Query($url, $reg, $rang);
//输入采集结果
        $arr = $hj->jsonArr;
        return $arr;
    } else {
        $url = $url . $v['href'];
        $img = $v['img'];
        $reg = array(
            "conent" => array(".content p", "html"),
        );
        $rang = ".article_content";
//采集
        $hj = QueryList::Query($url, $reg, $rang);
//输入采集结果
        $arr = $hj->jsonArr;
        GrabImage($img, "aa.jpg");
        if (is_array($arr))
            return array_merge($v, $arr[0]);
        else
            return $v;
    }
}

$xinxi = caiji();
$end = microtime(true) - $start;

echo '<br/>';
echo $end; // 平均 10.091157913208s
?>
