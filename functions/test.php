<?php
	// //enable cors
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials: true');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type");
	// $api_data = json_decode(file_get_contents("php://input"));
	// if (isset($api_data) && !empty($api_data)&& ($api_data->a === "POKFD43IOJDISf239rdf")) {
		$result;
		$result->status = 0;

        $folder = "./data/timetables/2021-2022 Birinchi semestr/curriculum/";
		$filesNames=scandir($folder);
		$result->streams = array();
		foreach ($filesNames as $key => $value_course) {
            if($value_course !== "." && $value_course !== ".."){
                $result->curriculum[$value_course] = array();
                $folder_course = $folder.$value_course.'/';
                if (is_dir($folder_course)) {
    
                    $filesNames_course=scandir($folder_course);
                    
                    foreach ($filesNames_course as $key => $value_curriculum) {
                        if($value_curriculum !== "." && $value_curriculum !== ".."){
                            $folder_curriculum = $folder_course.$value_curriculum;
                            $file = json_decode(file_get_contents($folder_curriculum));
                            $file->file_name = $value_curriculum;
                            if(!empty($file))array_push($result->curriculum[$value_course],$file);
                        }
                    }
                }
            }
		}
           
        $api_data = $api_data->index_data;
        $folder = "./data/timetables/2021-2022 Birinchi semestr/rooms/";
		$filesNames=scandir($folder);
		$result->rooms=array();
		foreach ($filesNames as $key => $value) {
			if (file_exists($folder.$value)) {
                if($value !== "." && $value !== ".."){
                    if($key < 170){
                        $file = json_decode(file_get_contents($folder.$value));
                        $file->file_name = $value;
                        if(!empty($file))array_push($result->rooms,$file);
                    }

                }
			}
		}

        $folder = "./data/timetables/2021-2022 Birinchi semestr/groups/";
		$filesNames=scandir($folder);
		$result->groups=array();
		foreach ($filesNames as $key => $value) {
			if (file_exists($folder.$value)) {
                if($value !== "." && $value !== ".."){
                    $file = json_decode(file_get_contents($folder.$value));
                    $file->file_name = $value;
                    if(!empty($file))array_push($result->groups,$file);
                }

			}
		}

        $folder = "./data/timetables/2021-2022 Birinchi semestr/subjects/";
		$filesNames=scandir($folder);
		$result->subjects=array();
		foreach ($filesNames as $key => $value) {
			if (file_exists($folder.$value)) {
                if($value !== "." && $value !== ".."){
                    $file = json_decode(file_get_contents($folder.$value));
                    $file->file_name = $value;
                    if(!empty($file))array_push($result->subjects,$file);
                }

			}
		}

        $folder = "./data/timetables/2021-2022 Birinchi semestr/areas/";
		$filesNames=scandir($folder);
		$result->areas=array();
		foreach ($filesNames as $key => $value) {
			if (file_exists($folder.$value)) {
                if($value !== "." && $value !== ".."){
                    $file = json_decode(file_get_contents($folder.$value));
                    $file->file_name = $value;
                    if(!empty($file))array_push($result->areas,$file);
                }

			}
		}

        $folder = "./data/timetables/2021-2022 Birinchi semestr/teachers/";
		$filesNames=scandir($folder);
		$result->teachers;
		foreach ($filesNames as $key => $value) {
			if (file_exists($folder.$value)) {
                if($value !== "." && $value !== ".."){
                    if($key < 30){
                        $file = json_decode(file_get_contents($folder.$value));
                        $file->file_name = $value;
                        if(!empty($file)){
                            $uid = $file->uid;
                            $result->teachers->$uid = $file;
                        }
                    }

                }
			}
		}
        $result->test = array();

        $folder = "./data/timetables/2021-2022 Birinchi semestr/streams/";
		$filesNames=scandir($folder);
		$result->streams = array();
		foreach ($filesNames as $key => $value_course) {
            if($value_course !== "." && $value_course !== ".."){
                $result->streams[$value_course] = array();
                $folder_course = $folder.$value_course.'/';
                if (is_dir($folder_course)) {
    
                    $filesNames_course=scandir($folder_course);
                    $key_stream = 0; 
                    foreach ($filesNames_course as $key => $value_stream) {
                        if($value_stream !== "." && $value_stream !== ".."){
                            $result->streams[$value_course][$key_stream] = array();
                            $folder_stream = $folder_course.$value_stream.'/';
                            
                            if (is_dir($folder_stream)) {
                                $filesNames_stream=scandir($folder_stream);
                                foreach ($filesNames_stream as $key_stream_item => $value_stream_item) {
                                    if($value_stream_item !== "." && $value_stream_item !== ".."){
                                        $file = json_decode(file_get_contents($folder_stream.$value_stream_item));
                                        // $additional_data = json_decode(file_get_contents($folder_stream."data.json"));
                                        // $file->additional_data = $additional_data;
                                        $file->stream_folder = $value_stream;
                                        if(!empty($file))array_push($result->streams[$value_course][$key_stream],$file);
                                    }
                                }
                                $key_stream++;
                            }
                        }

                    }
    
                    
                }
            }

		}
        
        // $myfile = fopen("./data/timetables/2021-2022 Birinchi semestr/tmp_data.json", "w") or die("Unable to open file!");
        // fwrite($myfile, json_encode($result));
        // fclose($myfile);
		// $result2;
        // $result2->status = 200;
        // print json_encode($result2);
		$result->status = 200;
		print json_encode($result); // show contents



?>

