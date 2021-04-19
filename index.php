<?php

        require_once("connect.php");
        CONST BOT = '1786149369:AAF-BIJTHs6qGxSf_jUXaQABo_LOlRi3emE';
        CONST FILENAME = "Spravochnik.xls";

         function sendDocument($id, $firma, $tel) {

             $txt = "Firma nomi: $firma                              Telefon: $tel";
		    // Create CURL object
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".BOT."/sendDocument?caption=".$txt."&chat_id=" . $id);
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
		    curl_close($ch);
     }

        if(isset($_POST['json'])) {

              $a =  $_POST['json'];
            $a = json_decode($a,true);

            $sql = "UPDATE Excelbot SET firma='{$a[0]['orgName']}', tel='{$a[0]['phoneNumber']}' WHERE id=1";

            if (mysqli_query($conn, $sql)) {
			  	unlink('Spravochnik.xls');

	        	$out = fopen("Spravochnik.xls", 'a');
	        	$actualdata = [];
	        	array_push($actualdata, ['Tr'=> "T/r", 'drugs'=> 'Dorilar']);
	        	for ($i=0; $i < count($a[0]['drugs']); $i++) {
	        		$s = ['Tr'=> $i+1,'drugs'=> $a[0]['drugs'][$i]['name']];
	        		array_push($actualdata, $s);
	        	}

	        	array_push($actualdata, ['Tr'=> 'Jami','drugs'=> count($actualdata)]);
	        	for ($k=0; $k <count($actualdata) ; $k++) {
	        		 fputcsv($out, $actualdata[$k],"\t");

	        	}


	 			fclose($out);




	 			$sql1 = "SELECT chatId FROM chatids";
				$result1 = mysqli_query($conn, $sql1) or die("bolmadi");

				if (mysqli_num_rows($result1) > 0) {
				  // output data of each row
				  while($row1 = mysqli_fetch_assoc($result1)) {
				    sendDocument($row1['chatId'], $a[0]['orgName'], $a[0]['phoneNumber']);

				  }
				} else {
					echo "xato";
				}



			} else {
			  echo "Error updating record: " . mysqli_error($conn);
			}





        }else {
        	echo "json bering";
        }






?>
