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

        if(check_gtfr()){

            do_action();

        }

		print json_encode($result); // show contents
	}else{
	    echo "0 result";
	}
    function do_action(){
        global $api_data, $result;

        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/";
		$filesNames=scandir($folder);
        $time_tables=array();
        $found = false;
		foreach ($filesNames as $key => $value) {
            if($value !== '.' && $value !== '..' && $value !== 'selected.json' && (substr($value,count($value) - 6) === ".json")){
                if (file_exists($folder.$value)) {
                    $file = json_decode(file_get_contents($folder.$value));
                    $file->time = substr($value,0, count($value) - 6);
                    unset($file->timeTable);
                    unset($file->notFoundTeaRoom);
                    if(!empty($file))array_push($time_tables,$file);
                    if(intval($api_data->weekNumber) === intval($file->weekNumber)){ $found = true; }
                }
            }
		}
        if(!$found){
            $file = json_decode(file_get_contents($folder.$api_data->time.'.json'));
            $file->weekNumber = intval($api_data->weekNumber);
            if(file_put_contents($folder.$api_data->time.'.json', json_encode($file))){
                $result->status = 200;
            }else{
                $result->status = 199;
            }
        }else{
            $result->status = 198;
        }
    }
    function check_gtfr(){
        global $api_data;
        $folder = "../data/users/".$api_data->user->token.".json";
        $user = json_decode(file_get_contents($folder));
        if(!empty($user) && $user->gtfr === $api_data->gt){
            return true;
        }else{
            return false;
        }
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