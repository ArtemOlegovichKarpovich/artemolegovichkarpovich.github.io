<?php
$day = date('d.m.20y');
$year = (int) date('20y');
$month = (int) date('m');
$year_start = 2019;
$year_for_request = $year;

echo "<html>";
echo "<head>";
echo "<link rel='stylesheet' href='styles.css'>";
echo "<link rel='stylesheet' href='my_styles.css'>";
echo "<script src=\"scripts.js\"></script>";
echo "<script src=\"https://code.jquery.com/jquery-3.6.0.min.js\" integrity=\"sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=\" crossorigin=\"anonymous\"></script>";
echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>";
echo "</head>";
echo "<body>";
echo "<form class=\"filter-from-ver-1\" id=\"headForm\" style=\"margin-bottom: 1px\">";
/*echo "    <label>";
echo "        Форма обучения";
echo "        <select id=\"fo\" style=\"background-color:#E0E1B2;\">";
echo "            <option value=\"do\">Дневная</option>";
echo "        </select>";
echo "    </label>";
echo "    <label>";
 */
echo "        Учебный год";
echo "        <select id=\"studyyear\" style=\"background-color:#E0E1B2;\">";
$buf = $year_start;
while($buf <= $year + 1){
	if ($buf == $year){
		$prev = $buf - 1;
		$second = $buf;
		if ($month < 9){
			echo "<option value=\"{$prev}\" selected=\"\">{$prev}/{$second}</option>";
			$year_for_request = $prev;
		}
		else{
			echo "<option value=\"{$prev}\">{$prev}/{$second}</option>";
			$second_second = $second + 1;
			echo "<option value=\"{$second}\" selected=\"\">{$second}/{$second_second}</option>";
			$year_for_request = $second;
			++$buf;
		}
	}
	else {
		$prev = $buf - 1;
		$second = $buf;
		echo "<option value=\"{$prev}\">{$prev}/{$second}</option>";
	}
	++$buf;
}
shell_exec("D:\php_localhost\weeks_finder.py {$year_for_request}");
$n = shell_exec("D:\php_localhost\db_connection.py {$year_for_request}");
echo "                    </select>";
echo "    </label>";
echo "    <label>";
echo "        Факультет";
echo "        <select id=\"facult\" style=\"background-color:#E0E1B2;\">";
echo "                            <option value=\"1\">Математики и информационных технологий</option>";
echo "                                            </select>";
echo "    </label>";
echo "    <label>";
echo "        Отображаемая неделя";
echo "        <a href=\"javascript:void(0)\" onclick=\"actWeekChange(-1);\">";
echo "            <img src=\"/images/sotr/calendar_prev_month.png\" style=\"vertical-align: middle; cursor: pointer; width: 18px; height: 18px;\">";
echo "        </a>";
echo "        <select id=\"week\" style=\"background-color:#E0E1B2;\">";
$weeks_file = fopen("D:\php_localhost\weeks.txt", 'r');
$weeks = [];
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
for($i = 0; $i < count($weeks); ++$i){

	$str = array_slice($weeks, 0, 10);

	if ($i != $index_of_current_week){
        echo "<option value=\"{$str}\">{$weeks[$i]}</option>";
    }
	else{
        echo "<option value=\"{$str}\" selected=\"\">{$weeks[$i]}</option>";
    }
}
echo "</select>";

echo "        <a href=\"javascript:void(0)\" onclick=\"actWeekChange(1);\">";
echo "            <img src=\"/images/sotr/calendar_next_month.png\" style=\"vertical-align: middle; cursor: pointer; width: 18px; height: 18px;\">";
echo "        </a>";
echo "    </label>";
echo "    <label>";
$current_week = $index_of_current_week + 1;
echo "        <span id=\"numweek\">№{$current_week}</span>";
echo "    </label>";
//echo "    <label>";
//echo "        Курс";
//echo "        <select id=\"kurs\" style=\"background-color:#E0E1B2;\">";
//echo "                            <option value=\"1\" selected=\"\">1</option>";
//echo "                            <option value=\"2\">2</option>";
//echo "                            <option value=\"3\">3</option>";
//echo "                            <option value=\"4\">4</option>";
//echo "                            <option value=\"5\">5</option>";
//echo "                            <option value=\"6\">6</option>";
//echo "                    </select>";
//echo "    </label>";
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
echo "</form";



$time_of_pairs = ['08.00', '09.35', '11.30', '13.05', '14.40', '16.35', '18.10', '19.45'];
$days = [1 => 'Понедельник',
         2 => 'Вторник',
         3 => 'Среда',
         4 => 'Четверг',
         5 => 'Пятница',
         6 => 'Суббота',
         7 => 'Воскресенье'];
$months =  [1 => 'Января',
            2 => 'Февраля',
            3 => 'Марта',
            4 => 'Апреля',
            5 => 'Мая',
            6 => 'Июня',
            7 => 'Июля',
            8 => 'Августа',
            9 => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'];

$grops_file = fopen("D:\php_localhost\groups_name.txt", 'r');
$groups = [];
while(!feof($grops_file)){
	$groups[] = /*mb_convert_encoding(*/fgets($grops_file)/*, 'utf-8')*/;
}
fclose($grops_file);

$grops_file = fopen("D:\php_localhost\groups_coursename.txt", 'r');
$groups_courses = [];
while(!feof($grops_file)){
	$groups_courses[] = /*mb_convert_encoding(*/fgets($grops_file)/*, 'utf-8')*/;
}
fclose($grops_file);


echo "<div id=\"d1\">";
echo "    <table class=\"schedule pattern\" style=\"table-layout: fixed; width: 970px; max-height: 504px; overflow-y: scroll;\">";
echo "        <thead style=\" max-height:200px; display:table-header-group;\">";
echo "        <tr class=\"group_title\">";
echo "            <td rowspan=\"2\" colspan=\"2\" style=\"border-bottom-width: 2px; border-right-width: 2px; width:60px;\">&nbsp;";
echo "            </td>";
for($i = 0; $i < count($groups); ++$i){
    echo "<td class=\"group_title\" colspan=\"2\" style=\"font-size:11pt;\" title=\"{$groups_courses[$i]}\">{$groups[$i]}</td>";
}

echo "<td rowspan=\"2\" style=\"width:10px;\" id=\"qss\"></td></tr>";
echo "<tr class=\"subgroup_title\">";

$width_cell = 970/count($groups);
for($id = 6571, $i=0; $i < count($groups); ++$i, ++$id){
	for ($j = 1; $j <= 2; ++$j){
        echo "<td id=\"{$id}\" title=\"{$groups_courses[$i]}. Подгруппа {$j}\" class=\"subgroup_title\" style=\"border-bottom-width: 2px; font-size:11pt; width:{$width_cell}px\">{$groups[$i]}_{$j} (<span style=\"color: red\" title=\"Не соответствует норме часов(24-38)\">62</span>)</td>";
    }
}

echo "</tr></thead><tbody style=\"max-height: 504px; overflow-y: auto; display: block; width: 970px;\">";
$i = 1;
for($day = 1; $day < 8; ++$day){
	for($pair = 1, $buf = 6571; $pair < 9; ++$pair){
		echo "<tr class=\"day{$day}ofweek pair{$pair}ofday\">";
		if ($pair == 1){
			echo "<td class=\"dayofweek\" rowspan=\"8\" style=\"border-bottom-width: 2px; width:12px; padding:none;\"><img src=\"/_eluni/r90/Понедельник\"><img src=\"/_eluni/r90/30 май 2022\"></td>";
		 }
		echo "<td title=\"\" style=\" width:12px; border-right-width: 2px; font-size:9pt; padding:none;\"><b>{$pair}</b><br><span id=\"time\" pair=\"{$pair}\">{$time_of_pairs[$pair - 1]}</span></td>";
        for($count = 1, $group=-1; $count < (count($groups))*2 + 1; ++$count){
            $subgroup = 2 - ($i % 2);
            $group += ($subgroup%2);

			echo "<td class=\"pairs\" day=\"{$day}\" pair=\"{$pair}\" subgroup=\"{$buf}\" style=\"width:{$width_cell}px; font-size:11pt;  word-break:break-all; undefined \" title=\"\"><a href=\"#modal{$i}\"></a>";
			echo "<div class=\"modal\" id=\"modal{$i}\">";
            echo "<div class=\"div_add_pair\" style=\"height:412px;\">";
            echo "        <form>";
			echo "<figure style=\"margin-left: 3px; margin-top: 3px;\">";
            echo "        Добавить пару";
            echo "    </figure>";
            echo "    <div style=\"width: 330px; margin-left: 10px; float:left;\" align=\"left\">";
            echo "            <label style=\"font-weight: bold;\">Группа:</label>";
            echo "            <label style=\"font-weight: normal;\">{$groups[$group]}_{$subgroup}</label>";
            echo "            <label style=\"font-weight: bold;\">Триместр:</label>";
            echo "            <select style=\"width:50px; background: #C0F6C0;\" disabled>";
            echo "                <option value=\"1\">2</option>";
            echo "            </select>";
            echo "            <br>";
            echo "            <br>";
            echo "            <label style=\"font-weight: bold;\">День:</label>";
            echo "            <label style=\"font-weight: normal; margin-left: 0px;\">{$days[$day]} (20 июль 2022)</label>";
            echo "            <br><br><br>";
            echo "            <input type=\"checkbox\" id=\"zamenaCheck{$i}\"  onclick=\"isZamena({$i});\"/>";
            echo "            <label style=\"font-weight: bold; margin-left: 0px;\">Замена:</label>";
            echo "            <select id=\"zamenaSelect{$i}\" style=\"width:155px; background: #C0F6C0;\" disabled>";
            echo "                <option value=\"1\"></option>";
            echo "            </select>";
            echo "            <br><br>";
            echo "            <label style=\"font-weight: bold;\">Примечание:</label>";
            echo "            <input type=\"text\" style=\"width:147px; background: #C0F6C0;\">";
            echo "            <br>";
            echo "    </div>";
            echo "    <div style=\"margin-left: 330px;\" align=\"left\">";
            echo "            <label>Номер пары</label>";
            echo "            <select style=\"width:50px;background: #C0F6C0; background: #C0F6C0;\" disabled>";
            echo "                <option value=\"1\">{$pair}</option>";
            echo "            </select>";
            echo "            <br>";
            echo "            <label>Тип пары</label>";
            echo "            <br>";
            echo "            <select style=\"width:300px; background: #C0F6C0;\">";
            echo "                <option value=\"1\">Выберите тип пары</option>";
            echo "                <option value=\"2\">Математика ЦТ</option>";
            echo "            </select>";
            echo "            <br>";
            echo "            <label>Дисциплина</label>";
            echo "            <br>";
            echo "            <select style=\"width:300px; background: #C0F6C0;\">";
            echo "                <option value=\"1\">Выберите дисциплину</option>";
            echo "            </select>";
            echo "            <br>";
            echo "            <label>Преподаватель</label>";
            echo "            <br>";
            echo "            <select style=\"width:300px; background: #C0F6C0;\">";
            echo "                <option value=\"1\">Выберите преподавателя</option>";
            echo "            </select>";
            echo "            <input type=\"button\" value=\"зп\" title=\"Закрепить преподавателя\">";
            echo "            <br>";
            echo "            <label style=\"margin-left: 18px;\">Аудитория</label>";
            echo "            <br>";
            echo "            <select style=\"width:150px; background: #C0F6C0; margin-left: 15px;\">";
            echo "                <option value=\"1\">Главный корпус</option>";
            echo "            </select>";
            echo "            <input type=\"text\" style=\"width:145px; background: #C0F6C0;\">";
            echo "            <input type=\"button\" value=\"за\" title=\"Закрепить аудиторию\">";
            echo "            <br>";
            echo "            <label style=\"margin-left: 18px;\">Тип занятия (on-/offline)</label>";
            echo "            <select style=\"width:70px; background: #C0F6C0;\">";
            echo "                <option value=\"1\">-</option>";
            echo "            </select>";
            echo "            <br>";
            echo "    </div>";
            echo "    <hr>";
            echo "    <div style=\"margin-left: 50px;\">";
            echo "        <input type=\"submit\" value=\"Применить\" style=\"color: #298fb7; background: white; background-color: white; border-color: #ecefe7d1; font-weight:bold; border-width: 2px; font-family: Helvetica; font-size:14px;\">";
            echo "    </div>";
            echo "    <div style=\"margin-left: 330px;\">";
            echo "        <a style=\"width:10px;\" href=\"#\"><input type=\"button\" value=\"Отмена\" style=\"margin-left: 0px; color: #298fb7; background: white; background-color: white; border-color: #ecefe7d1; font-weight:bold; border-width: 2px; font-family: Helvetica; font-size:14px;\"></a>";
            echo "    </div>";
            echo "</div>";
            echo "        </form>";
            echo "</div>";
			++$buf;
			++$i;
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
