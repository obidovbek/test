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

        check_time_table();
        check_set_teachers();
        check_set_rooms();

        if(empty($result->exists_time_table) && empty($result->exists_teacher) && empty($result->exists_room)){
            save_to_db();
        }else{
            $result->status = 198;
        }
        $result->api_data = $api_data;
    }
    function save_to_db(){
        global $api_data, $result;

        $data = $api_data->data;
        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/";
        $selected = json_decode(file_get_contents($folder."selected.json"));

        $time_table = json_decode(file_get_contents($folder.$selected.".json"));
        $groups = $data->groups;
        foreach ($groups as $key_group => $group) {
            array_push($time_table->timeTable->$group, $data->newTimeTable);
        }


        $folder_teachers = $folder.$selected."/teachers/";
        $file_name_teachers = $data->teacherData->file_name;
        $teacher = json_decode(file_get_contents($folder_teachers.$file_name_teachers));
        if(empty($teacher->availab)){$teacher->availab = array();}
        array_push($teacher->availab, $data->teacherAvailab);

        $folder_room = $folder.$selected."/rooms/";
        $file_name_room = $data->roomData->file_name;
        $room = json_decode(file_get_contents($folder_room.$file_name_room));
        if(empty($room->availab)){$room->availab = array();}
        array_push($room->availab, $data->roomAvailab);

        if(file_put_contents($folder.$selected.".json", json_encode($time_table)) && 
            file_put_contents($folder_teachers.$file_name_teachers, json_encode($teacher)) && 
            file_put_contents($folder_room.$file_name_room, json_encode($room)) 
        ){
            $result->status = 200;
        }else{
            $result->status = 199;
        }
    }
    function check_time_table(){
        global $api_data, $result;
        $exists = false;
        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/";
        $selected = json_decode(file_get_contents($folder."selected.json"));
        $time_table = json_decode(file_get_contents($folder.$selected.".json"));
        
        $data = $api_data->data;
        $groups = $data->groups;

        foreach ($groups as $key_group => $group) {
            foreach ($time_table->timeTable->$group as $key => $value) {
                if(($value->day === $data->newTimeTable->day && 
                    $value->pair === $data->newTimeTable->pair) &&
                    (($data->newTimeTable->fullTime && ($value->fullTime || $value->numerator || $value->denominator)) ||
                    (empty($data->newTimeTable->fullTime) && (($value->numerator && $data->newTimeTable->numerator) || 
                    ($value->denominator && $data->newTimeTable->denominator))) ||
                    ($value->fullTime)
                    )
                ){
                    $exists = true;
                }
            }
        }
        $result->exists_time_table = $exists;
    }
    function check_set_teachers(){
        global $api_data, $result;
        $exists = false;

        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/";

        $selected = json_decode(file_get_contents($folder."selected.json"));
        $folder = $folder.$selected."/teachers/";

        $data = $api_data->data;
        $file_name = $data->teacherData->file_name;
        $teacher = json_decode(file_get_contents($folder.$file_name));

		foreach ($teacher->availab as $key => $value) {
            if(($value->day === $data->teacherAvailab->day && 
                $value->pair === $data->teacherAvailab->pair) &&
                (($data->teacherAvailab->fullTime && ($value->fullTime || $value->numerator || $value->denominator)) ||
                (empty($data->teacherAvailab->fullTime) && (($value->numerator && $data->teacherAvailab->numerator) || 
                ($value->denominator && $data->teacherAvailab->denominator))) ||
                ($value->fullTime)
                )
            ){
                $exists = true;
            }
        }
        $result->exists_teacher = $exists;

    }
    function check_set_rooms(){
        global $api_data, $result;
        $exists = false;
        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/";

        $selected = json_decode(file_get_contents($folder."selected.json"));
        $folder = $folder.$selected."/rooms/";

        $data = $api_data->data;
        $file_name = $data->roomData->file_name;
        $room = json_decode(file_get_contents($folder.$file_name));

        foreach ($room->availab as $key => $value) {
            if(($value->day === $data->roomAvailab->day && 
                $value->pair === $data->roomAvailab->pair) &&
                (($data->roomAvailab->fullTime && ($value->fullTime || $value->numerator || $value->denominator)) ||
                (empty($data->roomAvailab->fullTime) && (($value->numerator && $data->roomAvailab->numerator) || 
                ($value->denominator && $data->roomAvailab->denominator))) ||
                ($value->fullTime)
                )
            ){
                $exists = true;
            }
        }
        $result->exists_room = $exists;
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