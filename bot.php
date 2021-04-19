<?php
CONST BOT = '1786149369:AAF-BIJTHs6qGxSf_jUXaQABo_LOlRi3emE';
CONST FILENAME = "Spravochnik.xls";

require_once("connect.php");
        $firma = "";
        $tel = "";
        $sana = "";
        $sql = "SELECT  firma, tel, sana FROM Excelbot";
    	$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
		  // output data of each row
		  while($row = mysqli_fetch_assoc($result)) {
		    $firma = $row['firma'];
		    $tel = $row['tel'];
		    $sana = $row['sana'];
		  }
		}


		$path = "https://api.telegram.org/bot1786149369:AAF-BIJTHs6qGxSf_jUXaQABo_LOlRi3emE";
		$update = json_decode(file_get_contents("php://input"), TRUE);

		$chatId = $update["message"]["chat"]["id"];
		$message = $update["message"]["text"];


		$sql1 = "SELECT chatId FROM chatids where chatId = '{$chatId}'";
		$result1 = mysqli_query($conn, $sql1);

		if (mysqli_num_rows($result1) > 0) {
		   $txt = "Firma nomi: $firma                              Telefon: $tel";
		    // Create CURL object
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".BOT."/sendDocument?caption=".$txt."&chat_id=" . $chatId);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_POST, 1);

		    // Create CURLFile
		    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), FILENAME);
		    $cFile = new CURLFile(FILENAME, $finfo);

		    // Add CURLFile to CURL request
		    curl_setopt($ch, CURLOPT_POSTFIELDS, [
		        "document" => $cFile
		    ]);

		    // Call
		    $result = curl_exec($ch);

		    // Show result and close curl
		    var_dump($result);
		    curl_close($ch);
			
		}else {
			if (strpos($message, "/start") === 0) {
				file_get_contents($path."/sendmessage?chat_id=".$chatId."&Kalit so'zni kiriting!");
			}
			
			if($message == "123456") {
				$sql = "INSERT INTO chatids (chatId)
				VALUES ('{$chatId}')";
	            mysqli_query($conn, $sql);


			             $txt = "Firma nomi: $firma                              Telefon: $tel";
		    // Create CURL object
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".BOT."/sendDocument?caption=".$txt."&chat_id=" . $chatId);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_POST, 1);

		    // Create CURLFile
		    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), FILENAME);
		    $cFile = new CURLFile(FILENAME, $finfo);

		    // Add CURLFile to CURL request
		    curl_setopt($ch, CURLOPT_POSTFIELDS, [
		        "document" => $cFile
		    ]);

		    // Call
		    $result = curl_exec($ch);

		    // Show result and close curl
		    var_dump($result);
		    curl_close($ch);
			}else {
				file_get_contents($path."/sendmessage?chat_id=".$chatId."&Kalit so'zi xato!");
			}
			
			  
		}


    
    


?>
