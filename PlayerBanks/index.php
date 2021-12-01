<?php 
	include('php/bank_reader.php');
	$banks = FetchBankDataFromJSON();

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

			<h4 class="my-4 text-center">Player Banks</h4>

			<table class="table table-bordered mx-auto">
				<thead>
					<tr>
						<th>Username</th>
						<th>Balance</th>
						<th># of Items</th>
						<th>
							<?php 
								if($auth) 
								{
									echo "<a href='manage.php' class='btn btn-primary'>Add New Bank</a>";
								}
							?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach($banks as $bank) {
							echo "<tr>";
							echo "<td>$bank->username</td>";
							echo "<td>$$bank->balance</td>";
							echo "<td>".CountItems($bank->inventory)."</td>";
							echo "<td><a href='view.php?user=$bank->username' class='btn btn-primary'>View Bank</a>";
							if($auth) 
								echo " <a href='manage.php?user=$bank->username' class='btn btn-primary'>Manage Bank</a>";
							echo "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>