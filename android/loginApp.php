<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {

		$username = $_POST['username'];
		$password = $_POST['password'];

		require_once 'coonect.php';
		session_start();

		$query = "SELECT * FROM user WHERE username='$username' AND role='Driver'";
		$response = mysqli_query($con,$query);

		$result = array();
		$result['login'] = array();

		if (mysqli_num_rows($response)===1) {
			$row = mysqli_fetch_assoc($response);
			$acc_pass = $row['password'];

			if ($password == base64_decode($acc_pass)) {
                $currentPass = base64_decode($acc_pass);
				$index['name'] = $row['fname'];
				$index['telephone'] = $row['telephone'];
				$index['password'] = $currentPass;
				array_push($result['login'],$index);

				$result['success'] = "1";
				echo json_encode($result);
				mysqli_close($con);
			}
			else{
				$result['success'] = "0";
				echo json_encode($result);
				mysqli_close($con);
			}
		}
}
