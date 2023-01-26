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

            remove__from_lesson_availab();
            remove_from_room_availab();
            remove_from_teacher_availab();
            if($result->lesson_status && $result->room_status && $result->teacher_status){
                $folder = "./data/timetables/".$api_data->name."/gen_time_tables/".$data->timeTableIdx.".json";
                $room_folder = "./data/timetables/".$api_data->name."/gen_time_tables/".$data->timeTableIdx."/rooms/".$data->room->file_name;
                $teacher_folder = "./data/timetables/".$api_data->name."/gen_time_tables/".$data->timeTableIdx."/teachers/".$data->teacherUid.".json";
                if(file_put_contents($folder, json_encode($result->lesson_data)) && 
                    file_put_contents($room_folder, json_encode($result->room_data)) &&
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


        $folder = "./data/timetables/".$api_data->name."/gen_time_tables/".$data->timeTableIdx."/teachers/".$data->teacherUid.".json";
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


        $folder = "./data/timetables/".$api_data->name."/gen_time_tables/".$data->timeTableIdx."/rooms/".$data->room->file_name;
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
    function remove__from_lesson_availab(){
        global $api_data, $result;

        $data = $api_data->data;
        $groups = $data->groups;

        $folder = "./data/timetables/".$api_data->name."/gen_time_tables/".$data->timeTableIdx.".json";

        $time_table = json_decode(file_get_contents($folder));
        
        $count = 0;
        foreach($data->groups as $x => $group){
            $index = -1;
            foreach($time_table->timeTable->$group as $idx => $val) {
                if($val->day === $data->day && $val->pair === $data->pair && $val->title === $data->title){
                    $index = $idx;
                }
            }
            if((!empty($index) || $index === 0) && $index >= 0){
                array_splice($time_table->timeTable->$group, $index, 1);
                $count++;

            }else{
                $result->status = 199;
            }
            $result->count = $count;
        }
        if(count($data->groups) === $count){
            $result->lesson_status = true;
            $result->lesson_data = $time_table;
        //     if(file_put_contents($folder, json_encode($time_table))){
        //         $result->status = 200;
        //     }
        }
        // $result->time_table = $time_table->timeTable;
        // $result->api_data = $api_data;

    }
    // function remove_lesson($var){
    //     global $api_data;
    //     $data = $api_data->data;

    //     return($var->day === $data->day && $var->pair === $data->pair && $var->title === $data->title);
    // }
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
        // $folder = "./data/".$api_data->name."/curriculum/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->curriculum as $key_course => $value_course) {
        //     $folder = "./data/".$api_data->name."/curriculum/".$key_course;
        //     if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        //     foreach ($value_course as $key_curriculum => $value_curriculum) {
        //         $folder = "./data/".$api_data->name."/curriculum/".$key_course.'/';

        //         $id = generateRandomString(14);
        //         $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //         fwrite($myfile, json_encode($value_curriculum));
        //         fclose($myfile);

        //     }
        // }
        // $folder = "./data/".$api_data->name."/streams/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->streams as $key_course => $value_course) {
        //     // $id = $key;
        //     $folder = "./data/".$api_data->name."/streams/".$key_course;
        //     if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        //     foreach ($value_course as $key_stream => $value_stream) {
        //         $folder = "./data/".$api_data->name."/streams/".$key_course.'/'.$key_stream.'/';
        //         if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        //         foreach ($value_stream as $key_stream_item => $value_stream_item) {
        //             $id = generateRandomString(14);
        //             $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //             fwrite($myfile, json_encode($value_stream_item));
        //             fclose($myfile);
        //         }

        //     }
        // }

        // $folder = "./data/".$api_data->name."/teachers/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->teachers as $key => $value) {
        //     $id = $key;
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }
        
        // $folder = "./data/".$api_data->name."/areas/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->areas as $key => $value) {
        //     $id = generateRandomString(14);
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }        
        // $folder = "./data/".$api_data->name."/groups/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->groups as $key => $value) {
        //     $id = generateRandomString(14);
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }
        // $folder = "./data/".$api_data->name."/rooms/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->rooms as $key => $value) {
        //     $id = generateRandomString(14);
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }
?>