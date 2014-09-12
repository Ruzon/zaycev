<?

ini_set("display_errors", 1);
set_time_limit(10000);
//header("Content-type:text/html; charset=utf-8");
error_reporting(E_ERROR);


//$i = $_REQUEST['page'];
echo "i".$i."<p>";

$links = get_links_on_page('http://zaycev.net/m3_top/2.html');
echo " links";	
//print_r($links);
//print_r($_SERVER);
//echo "</pre>";	
$j==0;
foreach($links as $link) {
	$j++; if($j >7) break;
	$file = str_replace('/','_',$link);
//echo "<p>link ".$link."<p> file ".$file;	
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/zaycev/pages/'.$file)) {
		$c = file_get_contents('http://zaycev.net'.$link);
		$data = get_song($c);
//echo "<pre> data  ";	
//print_r($data);
//echo "</pre>";	
//echo "<p> c ".$c;	
		$name = iconv("utf-8","windows-1251",$data['name'].'.mp3');
//echo "<p>name ".$name;
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/zaycev/song/'.$name)) {
			if(copy($data['link'], $_SERVER['DOCUMENT_ROOT'].'/zaycev/song/'.$name)) {
            	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/zaycev/pages/'.$file, $c);
			}
		}
	}
}


function get_links_on_page($url) {
	$content = str_replace('class="audio-track-list__items  "','id="audio-track-list__items"',file_get_contents($url));
	$dom = new DOMDocument();
	$dom->loadHTML('<meta http-equiv="content-type" content="text/html; charset=windows-1251">' . $content);
//echo "<pre>";	
//print_r($dom);
//echo "</pre>";	

	$links = array();
	$table = $dom->getElementById("audio-track-list__items")->getElementsByTagName('tr');
	foreach($table as $tr_key => $tr) {

//	echo " tablestring ".$table;	
echo "<pre>";
print_r($table);
echo "</pre>";	
echo "<p>tr_key ".$tr_key;
//echo "tr ".$tr;
//echo "<p>tr ".$tr;	
		if($tr_key > 0) $links[] = $tr->getElementsByTagName('a')->item(0)->getAttribute('href');
	}
	return $links;
}

function get_song($content) {
	//$host = 'http://zaycev.net';
	//$content = file_get_contents($url);
	$dom = new DOMDocument();
	$dom->loadHTML('<meta http-equiv="content-type" content="text/html; charset=windows-1251">' . $content);

	$link = $dom->getElementById("pages-download-button")->getAttribute('href');
	$name = $dom->getElementById("pages-download-link")->nodeValue;

	return array('name' => $name, 'link' => $link);
}

?>
