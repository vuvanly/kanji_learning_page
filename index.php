<?php
	$servername = "127.0.0.1";
	$database = "learn_kanji";
	$username = "root";
	$password = "";
	
	$conn = null;
	try{
		$conn = new PDO("mysql:host=$servername; dbname=$database", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

					
		
	}catch(PDOException $e){
		echo "Connection failed:".$e->getMessage();
	}

	function getKanji() {
		$conn = $GLOBALS['conn'];
		

		$stmt = $conn->prepare("SELECT * FROM kanjis WHERE type='N3' ORDER BY rand() LIMIT 1");
		$stmt->execute();		

		$result = $stmt->fetch();
		return $result;
	};	

	function getListKanji() {
		$conn = $GLOBALS['conn'];
		

		$stmt = $conn->prepare("SELECT * FROM kanjis WHERE type='N3' ORDER BY rand()");
		$stmt->execute();		

		$result = $stmt->fetchAll();
		return $result;
	};	

	function getCauThanh($cauthanh){
		$conn = $GLOBALS['conn'];
		$strArr = explode("", $cauthanh);
		$length = strlen($cauthanh);
		$stmt = $conn->prepare("SELECT * FROM kanjis WHERE kanji IN $strArr LIMIT  $length");
		$stmt->execute();		

		$result = $stmt->fetchAll();
		return $result;
	}		
?>
<style type="text/css">
	.kanji{
		font-size: 200;
		font-weight: bold;
		color: blue;
		width: 100%;
		/*text-align: center;*/
	}
	.han_viet{
		font-size: 30;
		color: red;
		width: 100%;
		/*text-align: center;*/
	}
	.on_reading, .cauthanh{
		font-size: 25;		
		color: blue;
		width: 100%;
		/*text-align: center;*/
	}
	.nghia{
		font-size: 20;
		color: red;
		width: 200;		
		/*text-align: center;*/
	}
	.explain{
		font-size: 20;
		color: black;
		width: 200;		
		/*text-align: center;*/
	}
</style>
<div class="kanji">
	
</div>
<div class="han_viet"></div>
<div class="on_reading"></div>
<div class="nghia"></div>
<div class="cauthanh"></div>
<div class="explain"></div>


<script type="text/javascript">
	var kanji = document.getElementsByClassName("kanji")[0];
	var hanViet = document.getElementsByClassName("han_viet")[0];
	var onReading = document.getElementsByClassName("on_reading")[0];
	var nghia = document.getElementsByClassName("nghia")[0];
	var explain = document.getElementsByClassName("explain")[0];
	var cauThanh = document.getElementsByClassName("cauthanh")[0];
	var listAllKanjis = <?php echo json_encode(getListKanji()); ?>;		
	var randKanji = listAllKanjis[Math.floor(Math.random() * listAllKanjis.length)];		
	
	kanji.innerText = randKanji.kanji;
	hanViet.innerText = randKanji.han_viet.toUpperCase();
	onReading.innerText = randKanji.am_on;
	nghia.innerText = randKanji.nghia;
	explain.innerText = randKanji.explain_meaning;

	var xmlhttp = new XMLHttpRequest();

	function buildCauThanhString(arrayInput){
		var str = "";
		arrayInput = JSON.parse(arrayInput);		
		var i;
		for (i = 0; i < arrayInput.length; i++) {
			
		    str += " " + arrayInput[i].kanji + "(" + arrayInput[i].han_viet.toUpperCase() + ")";
		}				
		return str;
	}	

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var result = xmlhttp.responseText;
            cauThanh.innerHTML = buildCauThanhString(result);           
        }
    }
    
    xmlhttp.open("GET", "cauthanh.php?q=" + randKanji.cau_thanh, true);
    xmlhttp.send();
	setInterval(function(){
		var randKanji = listAllKanjis[Math.floor(Math.random() * listAllKanjis.length)];							
		kanji.innerText = randKanji.kanji;
		hanViet.innerText = randKanji.han_viet.toUpperCase();
		onReading.innerText = randKanji.am_on;
		nghia.innerText = randKanji.nghia;
		explain.innerText = randKanji.explain_meaning;
		
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	        	var result = xmlhttp.responseText;
	            cauThanh.innerHTML = buildCauThanhString(result);	            
	        }
	    };
	    
	    xmlhttp.open("GET", "cauthanh.php?q=" + randKanji.cau_thanh, true);
	    xmlhttp.send();

	}, 10000);	

	
</script>