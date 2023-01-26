<!-- https://api.telegram.org/bot5797488424:AAHmoHpsogzqBcNkfhiL3JSLFOQJhRiRI7o/setWebhook?url=https://tsnqb.uz/test/telegram_bot/index.php -->
<?php 
	include './bot-master/Telegram.php';

	$telegram = new Telegram('5797488424:AAHmoHpsogzqBcNkfhiL3JSLFOQJhRiRI7o');

	unlink('./error_log');
	// $chat_id = $telegram->ChatId();
	// $content = array('chat_id'=>$chat_id, 'text'=>'Assalomu alaykum, chat_id: '.$chat_id);
	// $telegram->sendMessage($content);

	$result = $telegram->getData();
	$text = $result['message'] ['text'];
	$chat_id = $result['message'] ['chat']['id'];
	$content = array();

	$found_tt = false;
	$keyb = '';

	$selectedSeason = '2021-2022 Birinchi semestr';
	$week_file_name = '';
	setWeekName($selectedSeason, date('W'));

	$res_text = '';
	if(empty($week_file_name)){
		//shu hafta topa olmasa
		$res_text = 'Bu hafta dars jadvali qo‘yilmagan!';
	}else{
		switch ($text) {
			case '/start': startCommand(); break;
			case 'Talaba': setRole('student'); $res_text = "Guruhingiz nomini yozing \n\nMasalan, <u>62-22</u>"; break;
			case 'O‘qituvchi': setRole('teacher'); $res_text = "Ismingizni yozing \n\nMasalan, <u>Obidov Bekzod Olimjon o‘g‘li</u>"; break;
			default:
				$user = getUser();
				switch ($user->role) {
					case 'student':
						if(!empty($user->group_name)){
							switch ($text) {
								case 'Bugun':
									setWeekName($selectedSeason, date('W'));
									showTTforDay($user->group_name, date('w'), date('d.m.Y'), $selectedSeason, $week_file_name);
									$found_tt = true;
									break;
								case 'Ertaga':
									if(intval(date('w')) === 0){setWeekName($selectedSeason, intval(date('W')) + 1);}
									showTTforDay($user->group_name, (intval(date('w')) + 1), (date('d.m.Y', strtotime("+ 1 day"))), $selectedSeason, $week_file_name);
									$found_tt = true;
									break;
								case 'Shu hafta':
									setWeekName($selectedSeason, date('W'));
									$dayOfWeek = (date('w')) - 1;
									if($dayOfWeek === -1){$dayOfWeek = 6;}
									$beginingOfWeek = date('d.m.Y', strtotime(date('d.m.Y'). ' - '. $dayOfWeek .' days'));

									for($i = 1; $i<=6; $i++){
										showTTforDay($user->group_name, $i, (date('d.m.Y', strtotime($beginingOfWeek."+ ".($i - 1)." day"))), $selectedSeason, $week_file_name);
									}
									$found_tt = true;
									break;
								case 'Kelasi hafta':
									setWeekName($selectedSeason, intval(date('W')) + 1);
									$dayOfWeek = 8 - (date('w'));
									if($dayOfWeek === 8){$dayOfWeek = 1;}
									$beginingOfWeek = date('d.m.Y', strtotime(date('d.m.Y'). ' + '. $dayOfWeek .' days'));

									for($i = 1; $i<=6; $i++){
										showTTforDay($user->group_name, $i, (date('d.m.Y', strtotime($beginingOfWeek."+ ".($i - 1)." day"))), $selectedSeason, $week_file_name);
									}
									$found_tt = true;
								
								default:
									startCommand();
									break;
							}
							break;
						}
						if((strlen($text) > 3) && !empty(checkGroupAvailable($text, $selectedSeason, $week_file_name))){
							$res_text = "Vaqt turini tanlang";
							askForTimeType();
						}else{
							if((strlen($text) > 3) && (count(findSimilarGroups($text, $selectedSeason, $week_file_name)) !== 0)){
								$res_text = "O‘xshash guruhlar topildi";
							}else{
								$res_text = $text . " guruhi mavjud emas \n\nGuruh nomini <a href='https://www.ferpi.uz/'>https://www.ferpi.uz/</a> sayti orqali tekshiring";
							}
						}
						break;
					case 'teacher':
						if(!empty($user->teacher)){
							switch ($text) {
								case 'Bugun':
									setWeekName($selectedSeason, date('W'));
									showTeacherTTforDay($user->teacher, date('w'), date('d.m.Y'), $selectedSeason, $week_file_name);
									$found_tt = true;
									break;
								case 'Ertaga':
									if(intval(date('w')) === 0){setWeekName($selectedSeason, intval(date('W')) + 1);}
									showTeacherTTforDay($user->teacher, (intval(date('w')) + 1), (date('d.m.Y', strtotime("+ 1 day"))), $selectedSeason, $week_file_name);
									$found_tt = true;
									break;
								case 'Shu hafta':
									setWeekName($selectedSeason, date('W'));
									$dayOfWeek = (date('w')) - 1;
									if($dayOfWeek === -1){$dayOfWeek = 6;}
									$beginingOfWeek = date('d.m.Y', strtotime(date('d.m.Y'). ' - '. $dayOfWeek .' days'));

									for($i = 1; $i<=6; $i++){
										showTeacherTTforDay($user->teacher, $i, (date('d.m.Y', strtotime($beginingOfWeek."+ ".($i - 1)." day"))), $selectedSeason, $week_file_name);
									}
									$found_tt = true;
									break;
								case 'Kelasi hafta':
									setWeekName($selectedSeason, intval(date('W')) + 1);
									$dayOfWeek = 8 - (date('w'));
									if($dayOfWeek === 8){$dayOfWeek = 1;}
									$beginingOfWeek = date('d.m.Y', strtotime(date('d.m.Y'). ' + '. $dayOfWeek .' days'));

									for($i = 1; $i<=6; $i++){
										showTeacherTTforDay($user->teacher, $i, (date('d.m.Y', strtotime($beginingOfWeek."+ ".($i - 1)." day"))), $selectedSeason, $week_file_name);
									}
									$found_tt = true;
								
								default:
									startCommand();
									break;
							}
							break;
						}
						if((strlen($text) > 4) && (count($found_option = findSimilarTeacher($text, $selectedSeason, $week_file_name)) !== 0)){
							if(count($found_option) === 1){
								$res_text = "Vaqt turini tanlang";
								askForTimeType();

							}else{
								$res_text = "O‘xshash Ismlar topildi";
							}
						}else{
							$res_text = "O'qituvchi topilmadi";
						}
						break;
					
					default:
						// $res_text = 'topilmadi, role: '. $role;
						startCommand();
						break;
				}
				break;
		}		
	}
	if(empty($found_tt)){
		$content = array('chat_id' => $chat_id, 'text' => $res_text, 'reply_markup' => $keyb, 'parse_mode' => 'HTML');
		$telegram->sendMessage($content);		
	}


	function setWeekName($selectedSeason, $week_number){
		global $week_file_name;

        $folder = "../data/timetables/".$selectedSeason."/gen_time_tables/";
		$filesNames=scandir($folder);
		foreach ($filesNames as $key => $value) {
            if($value !== '.' && $value !== '..' && $value !== 'selected.json' && (substr($value,count($value) - 6) === ".json")){
                if (file_exists($folder.$value)) {
                    $file = json_decode(file_get_contents($folder.$value));
					unset($file->timeTable);
                    unset($file->notFoundTeaRoom);
                    if(intval($file->weekNumber) === intval($week_number)){$week_file_name = $value; break;}  
                }
            }
		}
	}
	function sortByPair($a, $b) {return $a->pair - $b->pair;}
	// function filterOtherLecture($a) { return $a->pair !== 2; }
	function showTeacherTTforDay($teacher, $dayOfWeek, $date, $selectedSeason, $week_file_name){
		global $res_text, $telegram, $chat_id, $keyb, $user;

		$found_lesson = false;
		// $date=date_create("2022-11-11");
		// $dayOfWeek = date_format($date,"w");
		// $dayOfWeek = date('w');
		$res_text = '';
		$res_text .= "📅 ". nameDayOfWeekUz($dayOfWeek) .", " . $date . " \n\n";

		$folder = '../data/timetables/'.$selectedSeason.'/gen_time_tables/'.$week_file_name;
		$timetable = json_decode(file_get_contents($folder));

		// usort($timetable->timeTable->$group_name, "sortByPair");

		foreach ($timetable->timeTable as $group_name => $group_value) {
			foreach ($timetable->timeTable->$group_name as $key => $value) {
				$type = $value->lessons->type;
				if((intval($value->day) === intval($dayOfWeek)) && $value->teacherUid === $teacher->uid){
					$res_text .= "⏱" . $value->startTime. " – " .$value->endTime. "⏱\n";
					$res_text .= '<b>' . $value->lessons->subjectName . "</b>\n";
					addTypeAGroups($value);
					$res_text .= 'Qayerda: <b>' . $value->room->building . '-' . $value->room->roomNumber . "</b>\n";
					$res_text .= 'Kim: <b>' . $value->lessons->teacher->$group_name->$type->name . "</b>\n\n";
					$found_lesson = true;
					if($type === 'lecture'){
						foreach($value->lessons->groups as $key => $group_name_tmp){
							$timetable->timeTable->$group_name_tmp = array_filter($timetable->timeTable->$group_name_tmp , function($a) use ($value) {
								return ($a->day !== $value->day) || ($a->pair !== $value->pair) || ($a->fullTime !== $value->fullTime) || ($a->numerator !== $value->numerator) || ($a->denominator !== $value->denominator);   
							});						
						}

					}
				}
			}
		}

		if(!$found_lesson){
			$res_text .= "Dars yo‘q"; 
		}
		$res_text .= "\n\n";
		$content = array('chat_id' => $chat_id, 'text' => $res_text, 'reply_markup' => $keyb, 'parse_mode' => 'HTML');
		$telegram->sendMessage($content);
	}
	function showTTforDay($group_name, $dayOfWeek, $date, $selectedSeason, $week_file_name){
		global $res_text, $telegram, $chat_id, $keyb;

		$found_lesson = false;
		// $date=date_create("2022-11-11");
		// $dayOfWeek = date_format($date,"w");
		// $dayOfWeek = date('w');
		$res_text = '';
		$res_text .= "📅 ". nameDayOfWeekUz($dayOfWeek) .", " . $date . " \n\n";

		$folder = '../data/timetables/'.$selectedSeason.'/gen_time_tables/'.$week_file_name;
		$timetable = json_decode(file_get_contents($folder));

		usort($timetable->timeTable->$group_name, "sortByPair");

		foreach ($timetable->timeTable->$group_name as $key => $value) {
			$type = $value->lessons->type;
			if(intval($value->day) === intval($dayOfWeek)){
				$res_text .= "⏱" . $value->startTime. " – " .$value->endTime. "⏱\n";
				$res_text .= '<b>' . $value->lessons->subjectName . "</b>\n";
				addTypeAGroups($value);
				$res_text .= 'Qayerda: <b>' . $value->room->building . '-' . $value->room->roomNumber . "</b>\n";
				$res_text .= 'Kim: <b>' . $value->lessons->teacher->$group_name->$type->name . "</b>\n\n";
				$found_lesson = true;
			}
		}

		if(!$found_lesson){
			$res_text .= "Dars yo‘q"; 
		}
		$res_text .= "\n\n";
		$content = array('chat_id' => $chat_id, 'text' => $res_text, 'reply_markup' => $keyb, 'parse_mode' => 'HTML');
		$telegram->sendMessage($content);
	}
	function addHalfLessons($numerator, $denominator){
		global $res_text;
		if($numerator){
			$res_text .= " surat";
		}else if($denominator){
			$res_text .= " maxraj";
		}
	}
	function addTypeAGroups($value){
		$lessons = $value->lessons;
		global $res_text;
		switch ($lessons->type) {
			case 'lecture':
				$res_text .= 'Leksiya';
				if(!$value->fullTime){addHalfLessons($value->numerator, $value->denominator);}
				$res_text .= "\nGuruhlar: <b>" . implode(", ", $lessons->groups) . "</b>\n";
				break;
			case 'practice':
				$res_text .= 'Praktika';
				if(!$value->fullTime){addHalfLessons($value->numerator, $value->denominator);}
				$res_text .= "\nGuruh: <b>" . $lessons->group . "</b>\n";
				break;
			case 'lab':
				$res_text .= 'Laboratoriya';
				if(!$value->fullTime){addHalfLessons($value->numerator, $value->denominator);}
				$res_text .= "\nGuruh: <b>" .$lessons->group . "</b>\n";
				break;
			default:
				return '';
				break;
		}
	}
	function nameDayOfWeekUz($dayOfWeek){
		switch ($dayOfWeek) {
			case '0':
				return 'Yakshanba';
				break;
			case '1':
				return 'Dushanba';
				break;
			case '2':
				return 'Seshanba';
				break;
			case '3':
				return 'Chorshanba';
				break;
			case '4':
				return 'Payshanba';
				break;
			case '5':
				return 'Juma';
				break;
			case '6':
				return 'Shanba';
				break;
			
			default:
				return '';
				break;
		}
	}
	function askForTimeType(){
		global $keyb, $telegram; 
		$option = array( 
		    //First row
		    array($telegram->buildKeyboardButton("Bugun"), $telegram->buildKeyboardButton("Ertaga")), 
		    //Second row 
		    array($telegram->buildKeyboardButton("Shu hafta"), $telegram->buildKeyboardButton("Kelasi hafta"))
		);
		$keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);
	}
	function findTTByGroup($text){}

	function findSimilarTeacher($text, $selectedSeason, $week_file_name){
		global $keyb, $telegram, $chat_id, $user; 
		$new_week_file_name = str_replace('.json', '', $week_file_name);
		$folder = '../data/timetables/'.$selectedSeason.'/gen_time_tables/'.$new_week_file_name.'/teachers/';
		$option = array();
		$teacher_found = '';
		$filesNames=scandir($folder);
		// $result->teachers;
		foreach ($filesNames as $key => $value) {
			if (file_exists($folder.$value)) {
                if($value !== "." && $value !== ".."){
                    $teacher = json_decode(file_get_contents($folder.$value));
                    $fullName = $teacher->last_name . ' ' . $teacher->first_name . ' ' . $teacher->patrynomic;  
                    if (!empty(findSimilar($fullName, $text, 1))) {
                    	$teacher_found = $teacher;
						array_push($option,  array($telegram->buildKeyboardButton($fullName)));
					}
                }
			}
		}

		$keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);
		if(count($option) === 1){
			$folder = './user_data/';
	        $file_name = $chat_id.".json";
			$user_data = json_decode(file_get_contents($folder.$file_name));
			$user_data->teacher = $teacher_found;
			$user = $user_data;
			file_put_contents($folder.$file_name, json_encode($user_data));
		}
		return $option;
	}
	function findSimilarGroups($text, $selectedSeason, $week_file_name){
		global $keyb, $telegram; 
		$folder = '../data/timetables/'.$selectedSeason.'/gen_time_tables/'.$week_file_name;
		$timetable = json_decode(file_get_contents($folder));
		$option = array();

		foreach ($timetable->timeTable as $group_name => $value) {
			if (!empty(findSimilar($group_name, $text, 1))) {
				array_push($option,  array($telegram->buildKeyboardButton($group_name)));
			}
		}
		$keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);

		return $option;
	}
	function findSimilar($firstSentence, $secondSentence, $degreeSimilarity){
		$firstSentence = strtolower(preg_replace('/\s+/', '', $firstSentence));
		$secondSentence = strtolower(preg_replace('/\s+/', '', $secondSentence));

	    $findSimilar = 0;

	    for($f=0; $f<strlen($firstSentence); $f++){
	      $findSimilar = 0;
	      $countSecond = 0;

	      for($count = $f; ($count < (strlen($secondSentence) + $f)); $count++){
	            if($firstSentence[$count] === $secondSentence[$countSecond]){
	              $findSimilar++;
	            }
	            $countSecond++;
	      }
	      if($findSimilar + $degreeSimilarity >= strlen($secondSentence)){
	      	return true;
	      }
	    }
	    return false;
	}
	function checkGroupAvailable($text, $selectedSeason, $week_file_name){
		global $chat_id;

		$found_group_name = '';
		$folder = '../data/timetables/'.$selectedSeason.'/gen_time_tables/'.$week_file_name;
		$timetable = json_decode(file_get_contents($folder));
		foreach ($timetable->timeTable as $group_name => $value) {
			if (strpos(strtolower(preg_replace('/\s+/', '', $group_name)), strtolower(preg_replace('/\s+/', '', $text))) !== false) {
				$found_group_name = $group_name;
				break;
			}
		}
		if(!empty($found_group_name)){
			$folder = './user_data/';
	        $file_name = $chat_id.".json";
			$user = json_decode(file_get_contents($folder.$file_name));
			$user->group_name = $found_group_name;
			file_put_contents($folder.$file_name, json_encode($user));
		}
		return $found_group_name;
	}
	function getUser(){
		global $chat_id;
		$folder = './user_data/';
        $file_name = $chat_id.".json";
		$user = json_decode(file_get_contents($folder.$file_name));
		return $user;
	}
	function startCommand(){
		global $telegram, $res_text, $keyb, $chat_id;
		$res_text = "Assalomu alaykum! \n\nJadvalni ko'rish uchun o'zingizni tanishtirishingiz kerak.";
		$option = array( 
		    //First row
		    array($telegram->buildKeyboardButton("Talaba")), 
		    //Second row 
		    array($telegram->buildKeyboardButton("O‘qituvchi")) 
		);
		$keyb = $telegram->buildKeyBoard($option, $onetime=true, $resize=true, $selective=true);
		$folder = './user_data/';
        $file_name = $chat_id.".json";
        unlink($folder.$file_name);
	}
	function setRole($role){
		global $chat_id;
		$folder = './user_data/';
        $file_name = $chat_id.".json";
		$user = (object) ['role' => $role];
        $myfile = fopen($folder.$file_name, "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($user));
        fclose($myfile);
	}
?>