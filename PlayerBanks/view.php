<?php 
	include('php/bank_reader.php');
	$banks = FetchBankDataFromJSON();
	$user = isset($_GET['user']) ? GetUserData($_GET['user']) : false;

	include('php/auth_config.php');
	$auth = array_key_exists($_SERVER['REMOTE_ADDR'], $admin_whitelist) ? $admin_whitelist[$_SERVER['REMOTE_ADDR']] : false;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		<title>Simple Activity Roller</title>
		<link rel="stylesheet" 
			href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
			integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
			crossorigin="anonymous">
	</head>
	<body>
		<div class="container">

			<h4 class="my-4 text-center"><?php echo $user->username; ?>'s Bank</h4>
			<h5 class="mb-3 text-center"><b>Balance:</b> $<?php echo $user->balance; ?></h5>

			<div class='card mb-3'>
				<div class='card-header'>
					<h5 class='card-title mb-0'>Inventory</h5>
				</div>
				<div class='card-body'>
					<div class='flex flex-row flex-wrap'>
						<?php 
							foreach($user->inventory as $item) {
								echo "<span class='mr-4'>";
								echo "<span class='badge badge-light mr-3'>$item->quantity</span>";
								echo $item->item;
								echo "</span>";
							}
						?>
					</div>
				</div>
			</div>

			<a href="index.php" class="btn btn-primary">Return to Bank List</a> 
			<?php 
				if($auth) {
					echo "<a href='manage.php?user=$user->username' class='btn btn-primary'>Manage</a>";
				}
			?>

		</div>
	</body>
</html>