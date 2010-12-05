<?php
	error_reporting(E_NONE);
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	if (isset($_GET["Digits"])) {
		switch($_GET['Digits']) {
			case 1:
				$translate_string = "Merry Christmas!";
				$filename = "christmas_trivia.txt";
				break;
			case 2:
				$translate_string = "Happy Hanukkah!";
				$filename = "hanukkah_trivia.txt";
				break;
			case 3:
				$translate_string = "Happy Kwanzaa";
				$filename = "kwanzaa_trivia.txt";
				break;
			case 4:
				$translate_string = "Happy New Year!";
				$filename = "newyears_trivia.txt";
				break;
			case 5:
				$translate_string = "It's a Festivus for the rest of us!";
				$filename = "festivus_trivia.txt";
				break;
			default:
				$translate_string = "Happy Holidays!";
				$filename = "holiday_facts.txt";
				break;
		}
		$available_languages = array("Spanish"=>"es","French"=>"fr","German"=>"de");
		$language_key = array_rand($available_languages);
		$language = urlencode(strtolower($available_languages[$language_key]));
		$message = $translate_string." in ".ucwords($language_key).":";
		$translate_string = urlencode($translate_string);
		$apikey = "AIzaSyAzI62umls2_j1IBL3WYPg4OZxgWqwIWLs";
	$url = "https://www.googleapis.com/language/translate/v2?key=".$apikey."&source=en&target=".$language."&q=".$translate_string;
	$response = json_decode(file_get_contents($url));
	$translation = $response->data->translations[0]->translatedText;
	$facts = file($filename);
	$fact_key = array_rand($facts);
	$fact = $facts[$fact_key];
		?>
		<Response>
			<Say><?php echo $message; ?></Say>
			<Pause />
			<Say language="<?php echo $language; ?>"><?php echo $translation; ?></Say>
			<Pause />
			<Say>Did you know?</Say>
			<Say><?php echo $fact; ?></Say>
			<Pause />
			<Say>Good bye and happy holidays!</Say>
		</Response>
		<?php
	}
	else {
?>
<Response>
<Gather method="GET" numDigits="1">
    <Say>What holiday would you like a greeting for?</Say>
	<Say>For Christmas, press 1</Say>
	<Say>For Hanukkah, press 2</Say>
	<Say>For Kwanzaa, press 3</Say>
	<Say>For New Years, press 4</Say>
	<Say>For Festivus, press 5</Say>
	<Say>For Holiday Greetings, press 6</Say>
</Gather>
</Response>
<?php } ?>