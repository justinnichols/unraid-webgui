<?PHP
/* Copyright 2005-2021, Lime Technology
 * Copyright 2015-2021, Bergware International
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */
?>
<?
$docroot = $docroot ?? $_SERVER['DOCUMENT_ROOT'] ?: '/usr/local/emhttp';
// add translations
$_SERVER['REQUEST_URI'] = '';
require_once "$docroot/webGui/include/Translations.php";

$name = $_POST['name'];
switch ($name) {
case 'crontab':
  $pid = file_exists("/boot/config/plugins/{$_POST['plugin']}/{$_POST['job']}.cron");
  break;
case 'preclear_disk':
  $pid = exec("ps -o pid,command --ppid 1|awk -F/ ".escapeshellarg("/$name .*{$_POST['device']}$/{print $1;exit}"));
  break;
case is_numeric($name):
  $pid = exec("lsof -i:$name -Pn|awk '/\(LISTEN\)/{print $2;exit}'");
  break;
case 'pid':
  $pid = file_exists("/var/run/{$_POST['plugin']}.pid");
  break;
default:
  $pid = exec("pidof -s -x ".escapeshellarg($name));
  break;
}
if (isset($_POST['update'])) {$span = ""; $_span = "";}
else {$span = "<span id='progress' class='status'>"; $_span = "</span>";}

echo $pid ? "{$span}"._('Status').":<span class='green'>"._('Running')."</span>{$_span}" : "{$span}"._('Status').":<span class='orange'>"._('Stopped')."</span>{$_span}";
?>