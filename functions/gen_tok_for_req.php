<?php
	// //enable cors
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type");
	$api_data = json_decode(file_get_contents("php://input"));
	if (isset($api_data) && !empty($api_data)&& ($api_data->a === "POKFD43IOJDISf239rdf")) {


		$result;
		$result->status = 0;
        $folder = "../data/users/".$api_data->user->token.".json";
        // $user = json_decode(file_get_contents($folder));
        // if(!empty($user)){
        //     $user->gtfr = generateRandomString(14);
        //     if(file_put_contents($folder, json_encode($user))){
        //         $result->status = 200;
        //         $result->gt = $user->gtfr;
        //     }
        // }else{
        //     $result->status = 199;
        //     $result->req_answer = "Bunaqa foydalanuvchi yo'q";
        // }
		$result->status = 200;

        // $folder = "../data/".$api_data->name."/areas/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
        // $id = generateRandomString(14);
        // $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        // fwrite($myfile, json_encode($api_data->area));
        // fclose($myfile);

		$result->api_data = $api_data;
		print json_encode($result); // show contents
	}else{
	    echo "0 result";
	}
	function generateRandomString($length) {
	    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
?>

