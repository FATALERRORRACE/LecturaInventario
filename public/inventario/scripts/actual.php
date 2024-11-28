<?php
$path = explode("/", $_SERVER['HTTP_REFERER']);
$rut_act = end($path);
unset($path);
?>