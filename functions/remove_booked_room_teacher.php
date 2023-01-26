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
        $data = $api_data->data;

        if(check_gtfr()){

            remove_from_room_availab();
            remove_from_teacher_availab();
            if($result->room_status && $result->teacher_status){
                $room_folder = "./data/timetables/".$api_data->name."/rooms/".$data->room->file_name;
                $teacher_folder = "./data/timetables/".$api_data->name."/teachers/".$data->teacherUid.".json";
                if(file_put_contents($room_folder, json_encode($result->room_data)) &&
                    file_put_contents($teacher_folder, json_encode($result->teacher_data))
                ){
                    $result->status = 200;
                }
            }else{
                $result->status = 199;
            }
        }

		print json_encode($result); // show contents
	}else{
	    echo "0 result";
	}
    function remove_from_teacher_availab(){
        global $api_data, $result;
        $data = $api_data->data;


        $folder = "./data/timetables/".$api_data->name."/teachers/".$data->teacherUid.".json";
        $teacher = json_decode(file_get_contents($folder));

        $index = -1;
        foreach($teacher->availab as $idx => $val) {
            if($val->day === $data->day && $val->pair === $data->pair && (($val->fullTime && $data->fullTime) || (!$val->fullTime && $val->numerator === $data->numerator && $val->denominator === $data->denominator))){
                    $index = $idx;
            }
        }
        if((!empty($index) || $index === 0) && $index >= 0){
            array_splice($teacher->availab, $index, 1);
            $result->teacher_status = true;
            $result->teacher_data = $teacher;

        }else{
            $result->status = 199;
        }

        // $result->teacher = $teacher;
    }
    function remove_from_room_availab(){
        global $api_data, $result;
        $data = $api_data->data;


        $folder = "./data/timetables/".$api_data->name."/rooms/".$data->room->file_name;
        $room = json_decode(file_get_contents($folder));

        $index = -1;
        foreach($room->availab as $idx => $val) {
            if($val->day === $data->day && $val->pair === $data->pair && (($val->fullTime && $data->fullTime) || (!$val->fullTime && $val->numerator === $data->numerator && $val->denominator === $data->denominator))){
                    $index = $idx;
            }
        }
        if((!empty($index) || $index === 0) && $index >= 0){
            array_splice($room->availab, $index, 1);
            $result->room_status = true;
            $result->room_data = $room;
        }else{
            $result->status = 199;
        }

        // $result->room = $room;
        // $result->file_name = $data->room->file_name;
    }

    function check_gtfr(){
        global $api_data;
        $folder = "./data/users/".$api_data->user->token.".json";
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