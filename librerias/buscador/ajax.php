<?php
include_once 'lib/ez_sql_core.php';
include_once 'lib/ez_sql_mysql.php';

$db = new ezSQL_mysql('root', '1p2', 'imp', 'localhost');

$productos = $db->get_results("SELECT * FROM vw_participantes WHERE ficha LIKE '%" . $_POST['query'] . "%'");

echo json_encode($productos);