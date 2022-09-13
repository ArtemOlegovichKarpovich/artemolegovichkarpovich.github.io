<?php
$day = date('d.m.20y');
$year = (int) date('20y');
//$year = 2022;
$month = (int) date('m');
//$month = 9;
$year_start = 2019;
$year_for_request = $year;

echo "<html>";
echo "<head>";
echo "<meta name=\"google\" value=\"notranslate\">";
echo "<link rel='stylesheet' href='styles.css'>";
echo "<link rel='stylesheet' href='my_styles.css'>";
echo "<script src=\"scripts.js\"></script>";
echo "<script src=\"https://code.jquery.com/jquery-3.6.0.min.js\" integrity=\"sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=\" crossorigin=\"anonymous\"></script>";
echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>";
echo "</head>";
echo "<body>";
echo "<form class=\"filter-from-ver-1\" id=\"headForm1\" style=\"margin-bottom: 1px\" method=\"post\" action=\"changeDefault.php\">";
echo "        Учебный год";
echo "        <select id=\"studyyearMAIN\" style=\"background-color:#E0E1B2;\" name=\"studyyearMAIN\" onchange=\"this.form.submit();\">";
$buf = $year_start;
while($buf <= $year + 1){
	if ($buf == $year){
		$prev = $buf - 1;
		$second = $buf;
		if ($month < 9){
			echo "<option value=\"{$prev}/{$second}\" selected=\"\">{$prev}/{$second}</option>";
			$year_for_request = $prev;
		}
		else{
			echo "<option value=\"{$prev}/{$second}\">{$prev}/{$second}</option>";
			$second_second = $second + 1;
			echo "<option value=\"{$second}/{$second_second}\" selected=\"\">{$second}/{$second_second}</option>";
			$year_for_request = $second;
			++$buf;
		}
	}
	else {
		$prev = $buf - 1;
		$second = $buf;
		echo "<option value=\"{$prev}/{$second}\">{$prev}/{$second}</option>";
	}
	++$buf;
}
shell_exec("cd /D D:\php_localhost");
shell_exec("weeks_finder.exe {$year_for_request}");
$weeks_file = fopen("weeks.txt", 'r');
$weeks = [];
$dates = [];
$index_of_current_week = 0;
while(!feof($weeks_file)){
    $str = fgets($weeks_file);
	$exist = gettype(stripos($str, 't'));
	$str = str_replace('\n', '', $str);
	if ($exist == "integer"){
		$str = str_replace('t', '', $str);
		$index_of_current_week = count($weeks);
    }
	$weeks[] = $str;
}
fclose($weeks_file);
for ($i = 0, $start = date_create(substr($weeks[$index_of_current_week], 0, 10)); $i < 7; ++$i){
    $dates[] = date_format($start, 'd.m.20y');
    date_modify($start, "1 day");
}
$dates_file = fopen("dates.txt", "w");
for ($i = 0; $i < count($dates); ++$i){
    fwrite($dates_file, $dates[$i] . "\r\n");
}
fclose($dates_file);
shell_exec("db_connection.exe {$year_for_request}");
echo "                    </select>";
echo "    </label></form>";
echo "    <br><br>";
echo "<form class=\"filter-from-ver-1\" id=\"headForm2\" style=\"margin-bottom: 1px\" method=\"post\" action=\"changeDefault.php\">";
echo "<label>";
echo "        Отображаемая неделя";
echo "        <a href=\"javascript:void(0)\" onclick=\"actWeekChange(-1);\">";
echo "            <img src=\"/left.png\" style=\"vertical-align: middle; cursor: pointer; width: 18px; height: 18px;\">";
echo "        </a>";
echo "        <select id=\"week\" name=\"week\" style=\"background-color:#E0E1B2;\" onchange=\"this.form.submit();\">";

for($i = 0; $i < count($weeks); ++$i){

	$str = array_slice($weeks, 0, 10);

	if ($i != $index_of_current_week){
        echo "<option value=\"{$weeks[$i]}\">{$weeks[$i]}</option>";
    }
	else{
        echo "<option value=\"{$weeks[$i]}\" selected=\"\">{$weeks[$i]}</option>";
    }
}
echo "</select>";

echo "        <a href=\"javascript:void(0)\" onclick=\"actWeekChange(1);\">";
echo "            <img src=\"/right.png\" style=\"vertical-align: middle; cursor: pointer; width: 18px; height: 18px;\">";
echo "        </a>";
echo "    </label>";
echo "    <label>";
$current_week = $index_of_current_week + 1;
echo "        <span id=\"numweek\">№{$current_week}</span>";
echo "    </label>";
echo "    <label style=\"padding: 0 0 0 0px;\">";
echo "        <input type=\"button\" style=\"margin: 0 0 0 0px; background-color: #DAD89C; border: #DAD89C\" value=\"Excel\" onclick=\"viewExcel()\">";
echo "    </label>";
echo "    <!--    -->";
echo "    <label id=\"showWeekGr\" style=\"display: none;\">";
echo "        <a href=\"javascript:void(0)\" onclick=\"basePair();\">П.гр.</a>";
echo "    </label>";
echo "    <label id=\"showStepBefore\" style=\"padding: 0px; background: rgb(224, 225, 178); display: none;\">";
echo "        <a href=\"javascript:void(0)\" onclick=\"stepBefor();\">";
echo "            <img src=\"/images/catalog/undo.png\" style=\"vertical-align: middle; cursor: pointer; width: 24px; height: 24px;\" title=\"Отмена последнего действия</br> (максимум 5)</br>(только для снятия/добавления/изменения пары)\">";
echo "        </a>";
echo "    </label>";
echo "</form>";



$time_of_pairs = ['08.00', '09.35', '11.30', '13.05', '14.40', '16.35', '18.10', '19.45'];
$days = [1 => 'Понедельник',
         2 => 'Вторник',
         3 => 'Среда',
         4 => 'Четверг',
         5 => 'Пятница',
         6 => 'Суббота',
         7 => 'Воскресенье'];
$short_days = [1 => 'Пн',
         2 => 'Вт',
         3 => 'Ср',
         4 => 'Чт',
         5 => 'Пт',
         6 => 'Сб',
         7 => 'Вс'];
$months =  ['01' => 'Января',
            '02' => 'Февраля',
            '03' => 'Марта',
            '04' => 'Апреля',
            '05' => 'Мая',
            '06' => 'Июня',
            '07' => 'Июля',
            '08' => 'Августа',
            '09' => 'Сентября',
            '10' => 'Октября',
            '11' => 'Ноября',
            '12' => 'Декабря'];
$short_months =  ['01' => 'Янв',
            '02' => 'Фев',
            '03' => 'Мар',
            '04' => 'Апр',
            '05' => 'Мая',
            '06' => 'Июн',
            '07' => 'Июл',
            '08' => 'Авг',
            '09' => 'Сен',
            '10' => 'Окт',
            '11' => 'Ноя',
            '12' => 'Дек'];

$types_full = ['Лекция',
          'Лабораторное занятие',
          'Практическое занятие',
          'Смешанное занятие',
          'ЗПРП',
          'Зачёт',
          'Экзамен',
          'Другое'
         ];
$types_short = [0 => 'lec',
                1 => 'lab',
                2 => 'pra',
                3 => 'sme',
                4 => 'zpr',
                5 => 'zac',
                6 => 'ekz',
                7 => 'dru'];
$types_match = ['Лекция' => 'lec',
               'Лабораторное занятие' => 'lab',
               'Практическое занятие' => 'pra',
               'Смешанное занятие' => 'sme',
               'ЗПРП' => 'zpr',
               'Зачёт' => 'zac',
               'Экзамен' => 'ekz',
               'Другое' => 'dru'];
$types_match2 = ['Лекция' => 0,
               'Лабораторное занятие' => 1,
               'Практическое занятие' => 2,
               'Смешанное занятие' => 3,
               'Занятие под руководством преподавателя' => 4,
               'Зачёт' => 5,
               'Экзамен' => 6,
               'Другое' => 7];


$grops_file = fopen("groups_name.txt", 'r');
$groups = [];
while(!feof($grops_file)){
	$groups[] = fgets($grops_file);
}
fclose($grops_file);

$grops_file = fopen("groups_coursename.txt", 'r');
$groups_courses = [];
while(!feof($grops_file)){
	$groups_courses[] = fgets($grops_file);
}
fclose($grops_file);

$disciplines_file = fopen("disciplines.txt", 'r');
$disciplines_names_full = [];
$disciplines_names_short = [];
$disciplines_hours = [];
while(!feof($disciplines_file)){
	$get_str_str = fgets($disciplines_file);
    $get_str_array = explode('?', $get_str_str);
    $course_index = (int) $get_str_array[0];
    $current_course = substr($groups_courses[$course_index], 0, -2);
    //$disciplines_names_full[] = [$course_index => []];
    //$disciplines_names_short[] = [$course_index => []];
    $disciplines_names_full[$course_index] = [];
    $disciplines_names_short[$course_index] = [];
    for ($i = 1; $i < count($get_str_array); ++$i){
        $get_str_str_str = explode('$', $get_str_array[$i]);
        $disciplines_hours[$current_course . '&' . $get_str_str_str[0]] = $get_str_str_str[2];
        $disciplines_names_full[$course_index][] = $get_str_str_str[0];
        $disciplines_names_short[$course_index][] = $get_str_str_str[1];
    }
}
fclose($disciplines_file);

$teachers_file = fopen("teachers.txt", 'r');
$teachers_names = [];
$teachers_positions = [];
while(!feof($teachers_file)){
	$arr = explode('&', fgets($teachers_file));
    $teachers_names[] = $arr[0];
    $teachers_positions[$arr[0]] = $arr[1];
}
fclose($teachers_file);

//shell_exec("cd /D C:\Users\User\source\repos\PHPWebProject1\PHPWebProject1");
//shell_exec("db_connection.py {$year_for_request}");
$timetable_file = fopen("timetable.txt", 'r');
$timetable = [];
$timetable_file_strings = [];
while(!feof($timetable_file)){
	$timetable_file_strings[] = fgets($timetable_file);
}
fclose($timetable_file);

$id_lenght = (int) $timetable_file_strings[count($timetable_file_strings) - 1];
$id_last = 0;
$limit = count($timetable_file_strings) - 1;
for ($j = 0; $j < $limit; ++$j){
    $id_first = $id_last + 1;
    $id_last = (int)substr($timetable_file_strings[$j], 0, $id_lenght);
    while($id_first < $id_last){
        $timetable[$id_first] = "%%%%%";
        ++$id_first;
    }
    $timetable[$id_last] = $timetable_file_strings[$j];
}
$id_first = $id_last + 1;
$last_cell = 8*7*count($groups_courses)*2;
while($id_first <= $last_cell){
    $timetable[$id_first] = "%%%%%";
    ++$id_first;
}

echo "<div id=\"d1\">";
echo "    <table class=\"schedule pattern\" style=\"table-layout: fixed; width: 1300px; max-height: /*504*/700px; overflow-y: scroll;\">";
echo "        <thead style=\" max-height:/*200*/700px; display:table-header-group;\">";
echo "        <tr class=\"group_title\">";
echo "            <td rowspan=\"2\" colspan=\"2\" style=\"border-bottom-width: 2px; border-right-width: 2px; width:60px;\">&nbsp;";
echo "            </td>";
for($i = 0; $i < count($groups); ++$i){
    echo "<td class=\"group_title\" colspan=\"2\" style=\"font-size:11pt;\" title=\"{$groups_courses[$i]}\">{$groups[$i]}</td>";
}

echo "<td rowspan=\"2\" style=\"width:10px;\" id=\"qss\"></td></tr>";
echo "<tr class=\"subgroup_title\">";

$hours_file = fopen("hours.txt", 'r');
$hours = [];
while(!feof($hours_file)){
	$hours[] = (int) fgets($hours_file);
}
fclose($hours_file);

$width_cell = 1300/count($groups);
$count_groups = count($groups) * 2;
for($id = 6571, $i=0, $c = 1; $i < count($groups); ++$i, ++$id){
	for ($j = 1; $j <= 2; ++$j){
        $hour = $hours[$c % $count_groups];
        if ($hour < 2 || $hour > 6){
            echo "<td id=\"{$id}\" title=\"{$groups_courses[$i]}. Подгруппа {$j}\" class=\"subgroup_title\" style=\"border-bottom-width: 2px; font-size:11pt; width:{$width_cell}px\">{$groups[$i]}_{$j} (<span style=\"color: red\" title=\"Не соответствует норме часов(2-6)\">{$hour}</span>)</td>";
        }
        else{
            echo "<td id=\"{$id}\" title=\"{$groups_courses[$i]}. Подгруппа {$j}\" class=\"subgroup_title\" style=\"border-bottom-width: 2px; font-size:11pt; width:{$width_cell}px\">{$groups[$i]}_{$j} (<span style=\"color: #515447\" title=\"Соответствует норме часов(2-6)\">{$hour}</span>)</td>";
        }
        ++$c;
    }
}

echo "</tr></thead><tbody style=\"max-height: /*504*/600px; overflow-y: auto; display: block; width: /*970*/1300px;\">";
$i = 1;
for($day = 1; $day < 8; ++$day){
	for($pair = 1, $buf = 6571; $pair < 9; ++$pair){
		echo "<tr class=\"day{$day}ofweek pair{$pair}ofday\">";
		if ($pair == 1){
            $local_day = substr($dates[$day - 1], 0, 2);
            $local_month = $short_months[substr($dates[$day - 1], 3, 2)];
            $local_year = substr($dates[$day - 1], 6, 4);
			echo "<td class=\"dayofweek\" rowspan=\"8\" style=\"border-bottom-width: 2px; width:12px; padding:none;\"><p class=\"vertical\">{$local_day} {$local_month} {$local_year} {$short_days[$day]}</p></td>";
        }
		echo "<td style=\" width:12px; border-right-width: 2px; font-size:9pt; padding:none;\"><b>{$pair}</b><br><span id=\"time\" pair=\"{$pair}\">{$time_of_pairs[$pair - 1]}</span></td>";
        if ($groups[0]){
            for($count = 1, $group=-1; $count < (count($groups))*2 + 1; ++$count){
                $subgroup = 2 - ($i % 2);
                $group += ($subgroup%2);

                //echo "<td class=\"pairs\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; undefined \" title=\"\"><a href=\"#modal{$i}\"></a>";
                //echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; undefined \" title=\"\"><span class=\"CellComment\">Here is a comment</span><a href=\"#modal{$i}\"></a>";

                if ($timetable[$i] != "%%%%%"){
                    $show_str = explode("%", substr($timetable[$i], $id_lenght));
                    $order_max = (explode("%", $disciplines_hours[str_replace("\r\n", "", $groups_courses[$group]) . "&" . $show_str[4]] . "%0")[$types_match2[$show_str[3]]])/2;
                    $order_fact = $show_str[5];
                    if($show_str[1] != "no_fio" && $show_str[2] != "no_aud"){
                        if ($order_fact <= $order_max){
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                        else{
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; background-color: silver; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                    }
                    else if($show_str[1] == "no_fio" && $show_str[2] != "no_aud"){
                        if ($order_fact <= $order_fact){
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; border: 2px solid blue; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                        else{
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; background-color: silver; border: 2px solid blue; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                    }
                    else if($show_str[1] == "no_fio" && $show_str[2] == "no_aud"){
                        if ($order_fact <= $order_fact){
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; border: 2px solid rebeccapurple; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                        else{
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; background-color: silver; border: 2px solid rebeccapurple; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                    }
                    else if($show_str[1] != "no_fio" && $show_str[2] == "no_aud"){
                        if ($order_fact <= $order_fact){
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; border: 2px solid deepskyblue; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                        else{
                            echo "<td class=\"CellWithComment\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; background-color: silver; border: 2px solid deepskyblue; undefined \" title=\"\"><span class=\"CellComment\">{$show_str[3]}<br>{$show_str[4]}<br>Порядок: {$order_fact} из {$order_max}</span><a href=\"#modal{$i}\">{$show_str[0]}</a>";
                        }
                    }
                    echo "<div class=\"modal\" id=\"modal{$i}\">";
                    echo "<div class=\"div_add_pair\" style=\"height:412px;\">";
                    echo "<figure style=\"margin-left: 3px; margin-top: 3px;\">";
                    echo "        Отменить пару";
                    echo "    </figure>";
                    echo "<form id=\"deletepair{$i}\" method=\"post\" action=\"delete_pair.php\">";
                    echo "    <div style=\"width: 330px; margin-left: 10px; float:left;\" align=\"left\">";
                    echo "            <label style=\"font-weight: bold;\">Группа:</label>";
                    echo "            <label style=\"font-weight: normal;\">{$groups[$group]}_{$subgroup}</label>";
                    echo "            <label style=\"font-weight: bold;\">Триместр:</label>";
                    echo "            <select id=\"trimestr{$i}\" name=\"trimestr\" style=\"width:50px; background: #C0F6C0;\">";
                    echo "                <option value=\"2\">2</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <br>";
                    echo "            <label style=\"font-weight: bold;\">Учебный год:</label>";
                    echo "            <select id=\"studyyear{$i}\" name=\"studyyear\" style=\"width:140px; background: #C0F6C0;\">";
                    echo "<option value=\"{$year_for_request}\">{$year_for_request}/{$year}</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <br>";
                    echo "            <label style=\"font-weight: bold;\">День:</label>";
                    $local_day = substr($dates[$day - 1], 0, 2);
                    $local_month = $months[substr($dates[$day - 1], 3, 2)];
                    $local_year = substr($dates[$day - 1], 6, 4);
                    echo "            <label style=\"font-weight: normal; margin-left: 0px;\">{$days[$day]} ({$local_day} {$local_month} {$local_year})</label>";
                    echo "            <br><br><br>";
                    //echo "            <input type=\"checkbox\" id=\"zamenaCheck{$i}\"  onclick=\"isZamena({$i});\"/>";
                    //echo "            <label style=\"font-weight: bold; margin-left: 0px;\">Замена:</label>";
                    //echo "            <select id=\"zamenaSelect{$i}\" style=\"width:155px; background: #C0F6C0;\" disabled>";
                    //echo "                <option value=\"1\"></option>";
                    //echo "            </select>";
                    //echo "            <br><br>";
                    //echo "            <label style=\"font-weight: bold;\">Примечание:</label>";
                    //echo "            <input type=\"text\" style=\"width:147px; background: #C0F6C0;\">";
                    //echo "            <br>";
                    echo "    </div>";
                    echo "    <div style=\"margin-left: 330px;\" align=\"left\">";
                    echo "            <label>Номер пары</label>";
                    echo "            <select style=\"width:50px;background: #C0F6C0; background: #C0F6C0;\" name=\"pair_order\" id=\"pair_order{$i}\">";
                    //echo "                <option value=\"{$pair}{$dates[$day - 1]}{$i}\">{$pair}</option>";
                    echo "                <option value=\"{$pair}{$dates[$day - 1]}{$groups[$group]}_{$subgroup}\">{$pair}</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <label>Тип пары</label>";
                    echo "            <br>";
                    echo "            <select style=\"width:300px; background: #C0F6C0;\" name=\"choosetype\" id=\"choosetype{$i}\" >";
                    echo "<option value=\"{$types_match[$show_str[3]]}\">{$show_str[3]}</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <label>Дисциплина</label>";
                    echo "            <br>";
                    echo "<select style=\"width:300px; background: #C0F6C0;\" name=\"choosedisc\" id=\"choosedisc{$i}\">";
                    echo "<option value=\"{$show_str[4]}\">{$show_str[4]}</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <label>Преподаватель</label>";
                    echo "            <br>";
                    echo "<select style=\"width:300px; background: #C0F6C0;\" name=\"chooseprep\" id=\"chooseprep{$i}\">";
                    echo "<option value=\"{$show_str[6]}\">{$show_str[6]}</option>";
                    echo "            </select>";
                    echo "            <input type=\"button\" value=\"зп\" title=\"Закрепить преподавателя\">";
                    echo "            <br>";
                    //echo "            <label style=\"margin-left: 18px;\">Аудитория</label>";
                    echo "            <br>";
                    /*echo "            <select style=\"width:150px; background: #C0F6C0; margin-left: 15px;\">";
                    echo "                <option value=\"1\">Главный корпус</option>";
                    echo "            </select>";*/
                    //echo "            <input type=\"text\" style=\"width:145px; background: #C0F6C0;\" id=\"aud{$i}\" name=\"aud\">";
                    //echo "            <input type=\"button\" value=\"за\" title=\"Закрепить аудиторию\">";
                    //echo "            <br>";
                    //echo "            <label style=\"margin-left: 18px;\">Тип занятия (on-/offline)</label>";
                    //echo "            <select style=\"width:70px; background: #C0F6C0;\" name=\"onoff\" id=\"onoff{$i}\">";
                    //echo "                <option value=\"1\">-</option>";
                    //echo "            </select>";
                    //echo "            <br>";
                    echo "    </div>";
                    echo "    <hr>";
                    echo "    <div style=\"margin-left: 50px;\">";
                    echo "        <input type=\"hidden\" id=\"hiddenweek{$i}\" name=\"hiddenweek\" value=\"{$weeks[$index_of_current_week]}\">";
                    echo "        <input type=\"submit\" value=\"Отменить пару\" style=\"color: #298fb7; background: white; background-color: white; border-color: #ecefe7d1; font-weight:bold; border-width: 2px; font-family: Helvetica; font-size:14px;\">";
                    echo "    </div>";
                    echo "    <div style=\"margin-left: 330px;\">";
                    echo "        <a style=\"width:10px;\" href=\"#\"><input type=\"button\" value=\"Назад\" style=\"margin-left: 0px; color: #298fb7; background: white; background-color: white; border-color: #ecefe7d1; font-weight:bold; border-width: 2px; font-family: Helvetica; font-size:14px;\"></a>";
                    echo "    </div>";
                    echo "</div>";
                    echo "        </form>";
                    echo "</div>";
                }
                else{
                    echo "<td day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; undefined \" title=\"\"><a href=\"#modal{$i}\"></a>";
                    echo "<div class=\"modal\" id=\"modal{$i}\">";
                    echo "<div class=\"div_add_pair\" style=\"height:412px;\">";
                    echo "<figure style=\"margin-left: 3px; margin-top: 3px;\">";
                    echo "        Добавить пару";
                    echo "    </figure>";
                    echo "<form id=\"addpair{$i}\" method=\"post\" action=\"added_pair.php\">";
                    echo "    <div style=\"width: 330px; margin-left: 10px; float:left;\" align=\"left\">";
                    echo "            <label style=\"font-weight: bold;\">Группа:</label>";
                    echo "            <label style=\"font-weight: normal;\">{$groups[$group]}_{$subgroup}</label>";
                    echo "            <label style=\"font-weight: bold;\">Триместр:</label>";
                    echo "            <select id=\"trimestr{$i}\" name=\"trimestr\" style=\"width:50px; background: #C0F6C0;\">";
                    echo "                <option value=\"2\">2</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <br>";
                    echo "            <label style=\"font-weight: bold;\">Учебный год:</label>";
                    echo "            <select id=\"studyyear{$i}\" name=\"studyyear\" style=\"width:140px; background: #C0F6C0;\">";
                    echo "<option value=\"{$year_for_request}\">{$year_for_request}/{$year}</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <br>";
                    echo "            <label style=\"font-weight: bold;\">День:</label>";
                    $local_day = substr($dates[$day - 1], 0, 2);
                    $local_month = $months[substr($dates[$day - 1], 3, 2)];
                    $local_year = substr($dates[$day - 1], 6, 4);
                    echo "            <label style=\"font-weight: normal; margin-left: 0px;\">{$days[$day]} ({$local_day} {$local_month} {$local_year})</label>";
                    echo "            <br><br><br>";
                    echo "            <input type=\"checkbox\" id=\"zamenaCheck{$i}\"  onclick=\"isZamena({$i});\"/>";
                    echo "            <label style=\"font-weight: bold; margin-left: 0px;\">Замена:</label>";
                    echo "            <select style=\"width:155px; background: #C0F6C0;\" name=\"zamenaSelect\" id=\"zamenaSelect{$i}\" disabled>";
                    for($pr = 0; $pr < count($teachers_names); ++$pr){
                        echo "<option value=\"{$teachers_names[$pr]}\">{$teachers_names[$pr]}</option>";
                    }
                    echo "            </select>";
                    echo "            <br><br>";
                    echo "            <label style=\"font-weight: bold;\">Примечание:</label>";
                    echo "            <input type=\"text\" style=\"width:147px; background: #C0F6C0;\">";
                    echo "            <br>";
                    echo "    </div>";
                    echo "    <div style=\"margin-left: 330px;\" align=\"left\">";
                    echo "            <label>Номер пары</label>";
                    echo "            <select style=\"width:50px;background: #C0F6C0; background: #C0F6C0;\" name=\"pair_order\" id=\"pair_order{$i}\">";
                    //echo "                <option value=\"{$pair}{$dates[$day - 1]}{$i}\">{$pair}</option>";
                    echo "                <option value=\"{$pair}{$dates[$day - 1]}{$groups[$group]}_{$subgroup}\">{$pair}</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <label>Тип пары</label>";
                    echo "            <br>";
                    echo "            <select style=\"width:300px; background: #C0F6C0;\" name=\"choosetype\" id=\"choosetype{$i}\" >";
                    echo "                <option value=\"-1\">Выберите тип пары</option>";
                    for($m = 0; $m < count($types_full); ++$m){
                        echo "<option value=\"{$types_short[$m]}\">{$types_full[$m]}</option>";
                    }
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <label>Дисциплина</label>";
                    echo "            <br>";
                    echo "<select style=\"width:300px; background: #C0F6C0;\" name=\"choosedisc\" id=\"choosedisc{$i}\">";
                    echo "<option value=\"default_disc\">Выберите дисциплину</option>";
                    $arr = $disciplines_names_full[$group];
                    if ($arr) {
                        for($disc = 0; $disc < count($arr); ++$disc){
                            //echo "<option value=\"{$disc}\">{$arr[$disc]}</option>";
                            echo "<option value=\"{$arr[$disc]}\">{$arr[$disc]}</option>";
                        }
                    }
                    echo "            </select>";
                    echo "            <br>";
                    echo "            <label>Преподаватель</label>";
                    echo "            <br>";
                    echo "<select style=\"width:300px; background: #C0F6C0;\" name=\"chooseprep\" id=\"chooseprep{$i}\">";
                    echo "<option value=\"default_prep\">Выберите преподавателя</option>";
                    for($pr = 0; $pr < count($teachers_names); ++$pr){
                        //echo "<option value=\"{$pr}\">{$teachers_names[$pr]}</option>";
                        echo "<option value=\"{$teachers_names[$pr]}\">{$teachers_names[$pr]}</option>";
                    }
                    echo "            </select>";
                    echo "            <input type=\"button\" value=\"зп\" title=\"Закрепить преподавателя\">";
                    echo "            <br>";
                    echo "            <label style=\"margin-left: 18px;\">Аудитория</label>";
                    echo "            <br>";
                    /*echo "            <select style=\"width:150px; background: #C0F6C0; margin-left: 15px;\">";
                    echo "                <option value=\"1\">Главный корпус</option>";
                    echo "            </select>";*/
                    echo "            <input type=\"text\" style=\"width:145px; background: #C0F6C0;\" id=\"aud{$i}\" name=\"aud\">";
                    echo "            <input type=\"button\" value=\"за\" title=\"Закрепить аудиторию\">";
                    echo "            <br>";
                    echo "            <label style=\"margin-left: 18px;\">Тип занятия (on-/offline)</label>";
                    echo "            <select style=\"width:70px; background: #C0F6C0;\" name=\"onoff\" id=\"onoff{$i}\">";
                    echo "                <option value=\"1\">-</option>";
                    echo "            </select>";
                    echo "            <br>";
                    echo "    </div>";
                    echo "    <hr>";
                    echo "    <div style=\"margin-left: 50px;\">";
                    echo "        <input type=\"hidden\" id=\"hiddenweek{$i}\" name=\"hiddenweek\" value=\"{$weeks[$index_of_current_week]}\">";
                    echo "        <input type=\"submit\" value=\"Применить\" style=\"color: #298fb7; background: white; background-color: white; border-color: #ecefe7d1; font-weight:bold; border-width: 2px; font-family: Helvetica; font-size:14px;\">";
                    echo "    </div>";
                    echo "    <div style=\"margin-left: 330px;\">";
                    echo "        <a style=\"width:10px;\" href=\"#\"><input type=\"button\" value=\"Отмена\" style=\"margin-left: 0px; color: #298fb7; background: white; background-color: white; border-color: #ecefe7d1; font-weight:bold; border-width: 2px; font-family: Helvetica; font-size:14px;\"></a>";
                    echo "    </div>";
                    echo "</div>";
                    echo "        </form>";
                    echo "</div>";
                }
                ++$buf;
                ++$i;
            }
        }
    }
}
echo "        </tbody>";
echo "        <tfoot>";
echo "        </tfoot>";
echo "    </table>";
echo "</div";
echo "<div class=\"main-content-border main-content-top-fix\" style=\"top: 129px;\"></div>";
echo "<div class=\"main-content-border\"></div";
echo "</body>";
echo "</html>";
?>
