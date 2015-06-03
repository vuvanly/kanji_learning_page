<?php
$q = $_REQUEST["q"];
$servername = "127.0.0.1";
$database = "learn_kanji";
$username = "root";
$password = "";

// $strArr = str_split($q);

// $strArr = preg_split('//', $q, -1, PREG_SPLIT_NO_EMPTY);
// $strArr = join(",", $strArr);



$conn = null;
try{
	$conn = new PDO("mysql:host=$servername; dbname=$database", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
	$strlen = mb_strlen($q); 
	while ($strlen) { 
	    $strArr[] = "'".mb_substr($q,0,1,"UTF-8")."'"; 
	    $q = mb_substr($q,1,$strlen,"UTF-8"); 
	    $strlen = mb_strlen($q); 
	}   
	$length =  sizeof($strArr);
	$strArr = join(",", $strArr);  
		
	if ($length === 0){
		echo "";
	}else{
		$query = "SELECT * FROM kanjis WHERE kanji IN ($strArr) LIMIT  $length";		
		$stmt = $conn->prepare("SELECT * FROM kanjis WHERE kanji IN ($strArr) LIMIT  $length");
		$stmt->execute();		

		$result = $stmt->fetchAll();		
		echo json_encode($result);		
	}
	
						
}catch(PDOException $e){
	// echo "This is Test Fail".$q;
	echo "";	
}

?>