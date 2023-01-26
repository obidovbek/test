<?php
		$selectedSeason = '2021-2022 Birinchi semestr';
		$week_file_name = '1668408556.json';
		// $res_text = '';
		// $res_text .= "📅 " . ", " . $date . " \n\n";

		$folder = '../data/timetables/'.$selectedSeason.'/gen_time_tables/'.$week_file_name;
		$timetable = json_decode(file_get_contents($folder));

		// usort($timetable->timeTable->$group_name, "sortByPair");

		foreach ($timetable->timeTable as $group_name => $group_value) {
			foreach ($timetable->timeTable->$group_name as $key => $value) {
				$type = $value->lessons->type;
				if((intval($value->day) === 1) && $value->teacherUid === "bZXewccHeX"){
					// echo $value->lessons->teacher->$group_name->$type->name . "</br>";

					if($type === 'lecture'){
						// print_r($value->lessons->groups);
						// echo '</br>';
						foreach($value->lessons->groups as $key => $group_name_tmp){
							echo $group_name_tmp . ", ";
							// $timetable->timeTable->$group_name_tmp = array_filter($timetable->timeTable->$group_name_tmp , function($a) use ($value) {
							// 	return ($a->day !== $value->day) || ($a->pair !== $value->pair) || ($a->fullTime !== $value->fullTime) || ($a->numerator !== $value->numerator) || ($a->denominator !== $value->denominator);   
							// });						
						}
						echo '</br>';
					}

				}
			}
		}

?>