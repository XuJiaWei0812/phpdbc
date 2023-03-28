<?php
require_once('phpdbc.php');

$db = new Database();

$db->set_table('test_table');

$rows = $db->select();

foreach($rows as $row){
    echo $row['name'].',';
}

unset($db)

?>