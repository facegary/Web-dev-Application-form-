<!DOCTYPE html>
<html lang=ru>
<head>
	<link href=./open_appl_folder/css/bootstrap.css rel=stylesheet />
	<link href=./open_appl_folder/css/styles.css rel=stylesheet />
	<!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<?php
if(($_SERVER["REQUEST_METHOD"] == "POST")&&($_POST['submit'] == "Отправить"))
{
	function inputDecode($data)
	{
		$data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}

	$name = inputDecode($_POST['name']);
	$appl_textarea = inputDecode($_POST['appl_textarea']);
	$email = inputDecode($_POST['email']);
	$priority = inputDecode($_POST['priority']);
	$pin = inputDecode($_POST['pin']);

	//-------------Создание DB и заполнение данных
	include "./open_appl_folder/createdb.php";
	if ($db->querySingle("SELECT pin FROM applications where pin = $pin") == false)
	{
		$db->exec("INSERT INTO `applications` (`name`, `appl_textarea`, `priority`, `email`, `pin`)
		VALUES ('$name', '$appl_textarea', '$priority', '$email', '$pin')");

		$result = $db->query("SELECT id, pin FROM applications where pin = $pin")->fetchArray(SQLITE3_ASSOC);
		setcookie("id", $result['id']);
		setcookie("pin", $result['pin']);

		$result = $db->query("SELECT id, pin FROM applications where pin = $pin")->fetchArray(SQLITE3_ASSOC);

		$tCookie = json_encode($result['id']);
		//-----------Список бывших заявок
		setcookie("allAppls[$tCookie]", $result['pin']);

		$pin = 0;

		//Переход на миниформу
		header('Location: ./open_appl_folder/test.php');

	}
}
else
{
	$name = "";
	$appl_textarea = "";
	$email = "";
	$priority = "3";
	$pin = 0;
}

?>

<body>
	<div id=appldiv>
		<div id=appls>
		<label id=lab_appls_s for="appls_s" hidden=true>Ваши ранние заявки:</label>
		        	<select id="appls_s" name="appls_s" hidden="true">
		        		<?php
			        	if(isset($_COOKIE['allAppls']))
						{
			        		include "./open_appl_folder/createdb.php";
							if (isset($_COOKIE['allAppls'])) {
					    		foreach ($_COOKIE['allAppls'] as $cookieName => $cookieValue) {
					    			if($db->querySingle("SELECT pin FROM applications where pin = $pin") != false) {
						    			$result = $db->query("SELECT name FROM applications where pin = $cookieValue")->fetchArray(SQLITE3_ASSOC);
								        $cookieName = htmlspecialchars($cookieName);
								        $cookieValue = htmlspecialchars($cookieValue);
								        $optionValue = htmlspecialchars($result['name']);
								        echo "<option value=$cookieName>$optionValue</option>";
								    }
							    }
							}
						}
						?>
					</select>
		</div>
		<section id=applmain >
			<h3>Заполните заявку</h3>
			<form id="appl_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
				<ul id="application">
				<li>
					<br><label for = "text">ФИО: </label>
				    <br><input type="text" name="name" id="name" value="<?=$name?>" class="applareas" pattern="[А-Я]{1}[а-я]*?\s[А-Я]{1}[а-я]+[\-]?[А-Я]?[а-я]*?\s[А-Я]{1}[а-я]*" maxlength="255" required>
				</li>
				<span id="msgbox" class="messagebox"></span>
				<li>
					<br><laber for="count_char_textarea"> Текст заявки: </label>
		            <textarea id="appl_textarea" name="appl_textarea" rows="6" onchange="countChar()" onkeyup="countChar()" maxlength="4096" style="width:90%;" required><?=$appl_textarea?></textarea>
		            <input type="text" id="count_char" value="4096" readonly="readonly" style="width: 90%;">
	        	</li>
	        	<li>
		        	<label for="priority">Приоритет:</label>
		        	<br><select name="priority" value="<?=$priority?>" id="priority" >
		        	<option value="1">1</option>
					<option value="2">2</option>
					<option value="3" selected="">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					</select>
	        	</li>
	        	<li>
	        		<br><label for="email">Ваш email:</label>
					<br><input type="email" id="email" name="email" value="<?=$email?>" maxlength="255" class="applareas" required>
	        	</li>
	        	<li>
	        		<br><label for="pin"> Придумайте пин-код: </span>
	        		<br><input name="pin" id="pin" value="<?=$pin?>" class="applareas" maxlength="4" pattern="[\d]{4}" required>
	        		<br><span id="pin_alert" class="messagebox2"></span>
	        	</li>
	        	</ul>
				<br><input type="submit" name="submit" id="submit" disabled="" value="Отправить">
			</form>
		</section>
	</div>
	<?php include "./open_appl_folder/js/js.html"; ?>
</body>