<?php

$db = new SQLite3("./open_appl_folder/postdb.db");
$db->exec("CREATE TABLE IF NOT EXISTS applications(id INTEGER PRIMARY KEY AUTOINCREMENT not null, name TEXT not null, appl_textarea TEXT not null, priority INTEGER not null, email TEXT not null, pin TEXT UNIQUE not null)");

?>