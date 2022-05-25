<!DOCTYPE html>
<html lang=ru>
<head>
	<link href=./css/bootstrap.css rel=stylesheet />
	<link href=./css/styles.css rel=stylesheet />
	<!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<?php

	$id = $_COOKIE['id'];
	$pin = $_COOKIE['pin'];

	$db = new SQLite3("postdb.db");
	$result = $db->query("SELECT * FROM applications where id = $id")->fetchArray(SQLITE3_ASSOC);

	$name = $result['name'];
	$appl_textarea = $result['appl_textarea'];
	$priority = $result['priority'];
	$email = $result['email'];


?>
<body>
	<section>
		<ul>
			<h3>Ваша заявка отправлена</h3>
			<li>
				<span>Ваше имя: <?=$name?></span>
			</li>
			<li>
				<span>Ваша заявка:</span>
				<br><textarea style="width: 90%;" rows="6" readonly="readonly"><?=$appl_textarea?></textarea>
			</li>
			<li>
				<span>Приоритет: <?=$priority?></span>
			</li>
			<li>
				<span>Ваш email: <?=$email?></span>
			</li>
		</ul>
	</section>
</body>