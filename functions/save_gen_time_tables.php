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
        if (!file_exists($folder)) { mkdir($folder, 0777, true); }
        $time = time();
        $result->selected = $time;

        $myfile = fopen($folder.$time.".json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($api_data->gen_time_table));
        fclose($myfile);

        $myfile = fopen($folder."selected.json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($time));
        fclose($myfile);

        save_teachers();
        save_rooms();
        save_not_found_tea_room();

        $result->status = 200;
    }
    function save_teachers(){
        global $api_data, $result;
        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/".$result->selected."/teachers/";
        if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		foreach ($api_data->teachers as $key => $value) {
            foreach ($value->availab as $key_a => $ava) { $ava->roomData->availab = array(); }
            $id = $key;
            $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
            fwrite($myfile, json_encode($value));
            fclose($myfile);
        }
        $result->status = 200;

    }
    function save_rooms(){
        global $api_data, $result;
        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/".$result->selected."/rooms/";
        if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		foreach ($api_data->rooms as $key => $value) {
            foreach ($value->availab as $key_a => $ava) { $ava->teacherData->availab = array(); }
            $id = $value->file_name;
            $myfile = fopen($folder.$id, "w") or die("Unable to open file!");
            fwrite($myfile, json_encode($value));
            fclose($myfile);
        }
    }
    function save_not_found_tea_room(){
        global $api_data, $result;
        $folder = "../data/timetables/".$api_data->name."/gen_time_tables/".$result->selected."/";
        if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        $myfile = fopen($folder."not_found_tea_room.json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($api_data->gen_time_table->notFoundTeaRoom));
        fclose($myfile);
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
        // $folder = "../data/".$api_data->name."/curriculum/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->curriculum as $key_course => $value_course) {
        //     $folder = "../data/".$api_data->name."/curriculum/".$key_course;
        //     if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        //     foreach ($value_course as $key_curriculum => $value_curriculum) {
        //         $folder = "../data/".$api_data->name."/curriculum/".$key_course.'/';

        //         $id = generateRandomString(14);
        //         $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //         fwrite($myfile, json_encode($value_curriculum));
        //         fclose($myfile);

        //     }
        // }
        // $folder = "../data/".$api_data->name."/streams/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->streams as $key_course => $value_course) {
        //     // $id = $key;
        //     $folder = "../data/".$api_data->name."/streams/".$key_course;
        //     if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        //     foreach ($value_course as $key_stream => $value_stream) {
        //         $folder = "../data/".$api_data->name."/streams/".$key_course.'/'.$key_stream.'/';
        //         if (!file_exists($folder)) { mkdir($folder, 0777, true); }

        //         foreach ($value_stream as $key_stream_item => $value_stream_item) {
        //             $id = generateRandomString(14);
        //             $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //             fwrite($myfile, json_encode($value_stream_item));
        //             fclose($myfile);
        //         }

        //     }
        // }

        // $folder = "../data/".$api_data->name."/teachers/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->teachers as $key => $value) {
        //     $id = $key;
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }
        
        // $folder = "../data/".$api_data->name."/areas/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->areas as $key => $value) {
        //     $id = generateRandomString(14);
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }        
        // $folder = "../data/".$api_data->name."/groups/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->groups as $key => $value) {
        //     $id = generateRandomString(14);
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }
        // $folder = "../data/".$api_data->name."/rooms/";
        // if (!file_exists($folder)) { mkdir($folder, 0777, true); }
		// foreach ($api_data->rooms as $key => $value) {
        //     $id = generateRandomString(14);
        //     $myfile = fopen($folder.$id.".json", "w") or die("Unable to open file!");
        //     fwrite($myfile, json_encode($value));
        //     fclose($myfile);
        // }
?>