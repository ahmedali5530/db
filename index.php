<?php
use DB\Db;

include_once 'src/db/Db.php';

$db = new DB('localhost', 'root', 'root', 'skool', 'sk_');

$users = $db->table('users')->get_row();

echo '<pre>';
print_r($users);
echo '</pre>';