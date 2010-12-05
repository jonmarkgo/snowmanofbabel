<?php
	error_reporting(E_ALL);
	function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );
    
    return strtr($string, $table);
}
	$incoming_txt = strtolower(trim($_GET["Body"]));
	header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	if ($incoming_txt == "?" || $incoming_txt == "help") {
		$message = "Greeting Options (TXT one in for a translation and fact): Christmas, Hanukkah, Kwanzaa, New Years, Festivus, or Holiday";
	}
	else {
		switch($incoming_txt) {
			case "xmas": case "x-mas": case "christmas": case "chrismas":
			case "yuletide": case "yule":
				$translate_string = "Merry Christmas!";
				$filename = "christmas_trivia.txt";
				break;
			case "hanukkah": case "chanukah": case "hanukah": case "hannukah":
			case "chanuka": case "chanukkah": case "hanuka": case "channukah":
			case "chanukka": case "hanukka": case "hannuka": case "hannukkah":
			case "channuka": case "xanuka": case "hannukka": case "channukkah":
			case "channukka": case "chanqua":
				$translate_string = "Happy Hanukkah!";
				$filename = "hanukkah_trivia.txt";
				break;
			case "kwanzaa": case "kwanza":
				$translate_string = "Happy Kwanzaa";
				$filename = "kwanzaa_trivia.txt";
				break;
			case "new year": case "newyear": case "new years": case "newyears":
				$translate_string = "Happy New Year!";
				$filename = "newyears_trivia.txt";
				break;
			case "festivus":
				$translate_string = "It's a Festivus for the rest of us!";
				$filename = "festivus_trivia.txt";
				break;
			default:
				$translate_string = "Happy Holidays!";
				$filename = "holiday_facts.txt";
				break;
		}
		$translate_string = urlencode($translate_string);
		$available_languages = parse_ini_file("languages.ini");
		
		$language_key = array_rand($available_languages);
		$language = urlencode(strtolower($available_languages[$language_key]));
		$apikey = "AIzaSyAzI62umls2_j1IBL3WYPg4OZxgWqwIWLs";
		$url = "https://www.googleapis.com/language/translate/v2?key=".$apikey."&source=en&target=".$language."&q=".$translate_string;
		$response = json_decode(file_get_contents($url));
		$translation = $response->data->translations[0]->translatedText;
		$message = ucwords(strtolower($language_key)).": ".normalize($translation);
		$facts = file($filename);
		$fact_key = array_rand($facts);
		$fact = $facts[$fact_key];
		$message .= " Fact: ". $fact;
    }
	$message .= " - ? for Help";
?>
<Response>
    <Sms><?php echo $message; ?></Sms>
</Response>