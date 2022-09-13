<?php
$day = date('d.m.20y');
$year = (int) date('20y');
$month = (int) date('m');
$year_start = 2019;
$year_for_request = $year;

$changed_year = substr($_POST['studyyear'], 0, 4);
$year_for_request = $changed_year;
$pair_order = $_POST['pair_order'];
$pair_order = str_replace("\r", '', str_replace("\n", '', $pair_order));

if ($_POST['choosedisc'] == 'default_disc' || $_POST['choosetype'] == '-1'){
    echo '<script language="javascript">';
    echo 'alert("Тип пары, а также наименование дисциплины обязательны для выбора! Повторите попытку...")';
    echo '</script>';
}
else{
    $request = "\"" . substr($pair_order, 11) .
    "$" . substr($pair_order, 1, 10) .
    "$" . $_POST['choosedisc'] .
    "$" . $_POST['choosetype'] .
    "$" . substr($pair_order, 0, 1) .
    "$" . $_POST['chooseprep'] .
    "$" . $_POST['aud'] . "\"";
    shell_exec("cd /D D:\php_localhost");
    shell_exec("db_add.exe {$year_for_request} {$request}");
}
$week = str_replace("\r\n", "", $_POST["hiddenweek"]);
$new_url = "/changeDefault.php?hiddenweek={$week}&studyyear={$year_for_request}";
header("Location: ".$new_url);
//header("Refresh:0; url=changeDefault.php");