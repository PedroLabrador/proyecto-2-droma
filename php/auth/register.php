<?php
	session_start();
	include_once "../config/config.php";

	function validationDate($year){
		if ($year[3] <= 8)
			return true;
		return false;
	}

    function userExists($username) {
        $pdo = Database::getConnection();

        $query = $pdo->prepare("SELECT COUNT(id) FROM users WHERE username = '$username'");
        $query->bindValue(1, $username);
        try{
            $query->execute();
            $rows = $query->fetchColumn();
            if($rows >= 1){
                return true;
            } else {
                return false;
            }
        }catch (PDOException $e){
            die($e->getMessage());
        }
    }

	function AddUser($username,$password){

		$pdo = Database::getConnection();
		$sql = "INSERT INTO users(username,password) VALUES (:username,:password)";
		$query = $pdo->prepare($sql);
		$result = $query->execute([
			'username'=> $username,
			'password' =>$password,
		]);
	}

	function getIdUser($username){

		$pdo = Database::getConnection();
		$sql ="SELECT id FROM users WHERE username = '$username'";
		$query = $pdo->prepare($sql);
		$query->execute();
		$result =  $query->fetch(PDO::FETCH_ASSOC);
		return $result['id'];
	}

	function AddTeam($user_id,$name,$email,$shortN,$date,$address,$website){

		$pdo = Database::getConnection();
		$sql = "INSERT INTO teams(user_id,teamname,short_name,date,address,email,website) VALUES (:user_id,:teamname,:short_name,:date,:address,:email,:website)";
		$query = $pdo->prepare($sql);
		$result = $query->execute([
			'user_id'=> $user_id,
			'teamname' =>$name,
			'short_name'=>$shortN,
			'date'=>$date,
			'address'=>$address,
			'email'=>$email,
			'website'=>$website,
		]);
	}

	if (isset($_POST["register"])) {

		$name = filter_input(INPUT_POST, 'name',FILTER_SANITIZE_STRING);
		$email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_STRING);
		$shortN = filter_input(INPUT_POST, 'short-name',FILTER_SANITIZE_STRING);
		$password = md5(filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING));
		$username = filter_input(INPUT_POST, 'username',FILTER_SANITIZE_STRING);
		$date = filter_input(INPUT_POST, 'date',FILTER_SANITIZE_STRING);
		$address = filter_input(INPUT_POST, 'address',FILTER_SANITIZE_STRING);
		$website = filter_input(INPUT_POST, 'website',FILTER_SANITIZE_STRING);

		if (strlen($name) > 0 && strlen($shortN) > 0 &&
			strlen($email) > 0 && strlen($username) > 0 &&
			strlen($password) > 0 && validationDate($date)) {

			if(!userExists($username))
				AddUser($username, $password);

			$idUser = getIdUser($username);
			if (strlen($address > 0) && strlen($website)) {
					AddTeam($idUser, $name, $email, $shortN, $date, $address, $website);
			} else {
				if (strlen($address) > 0)
					AddTeam($idUser, $name, $email, $shortN, $date, $address, "no website");
				else if (strlen($website) > 0)
					AddTeam($idUser, $name, $email, $shortN, $date, "no address", $website);
				else
					AddTeam($idUser, $name, $email, $shortN, $date, "no address", "no website");
			}
			$_SESSION['success'] = true;
		} else {
			$_SESSION['failure'] = true;
		}
	}
 ?>

<html>
<head>
	<meta charset='utf-8'>
	<title>Sport tournament system</title>
	<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
	<div class='container'>
		<?php if (isset($_SESSION['success'])): ?>
			<div class="success">
				Registered Successfully!!
			</div>
		<?php unset($_SESSION['success']); endif ?>
		<?php if (isset($_SESSION['failure'])): ?>
			<div class="failure">
				Please enter the correct values :(
			</div>
		<?php unset($_SESSION['failure']); endif ?>
		<form class='' method="post">
			<fieldset class='content'>
				<legend>Register</legend>
				<div class='separator'>
					<label for='name'>Team's name</label>
					<input id='name' type='text' name='name' required>
				</div>
				<div class='separator'>
					<label for='short-name'>Short name</label>
					<input id='short-name' type='text' name='short-name' required>
				</div>
				<div class='separator'>
					<label for='date'>Creation date </label>
					<input id='date' type='date' name='date' required>
				</div>
				<div class="separator">
					<label for='address'>Address of the responsible</label>
					<input id='address' type='text' name='address' placeholder=' Optional' >
				</div>
				<div class="separator">
					<label for='email'>Email address</label>
					<input id='email' type='email' name='email' required>
				</div>
				<div class="separator">
					<label for='website'>Website</label>
					<input id='website' type='text' name='website' placeholder=' Optional' >
				</div>
				<hr>
				<div class="separator">
					<label for='username'>Username</label>
					<input id='username' type='text' name='username' required>
				</div>
				<div class="separator">
					<label for='password'>Password</label>
					<input id='password' type='password' name='password' required>
				</div>
				<div class="separator">
					<input class='button btn-web' type="submit" name="register" value="Register">
				</div>
			</fieldset>
		</form>
		<div class="separator">
			<a class="button btn-web" href="/">Back</a>
		</div>
	</div>
</body>
</html>
