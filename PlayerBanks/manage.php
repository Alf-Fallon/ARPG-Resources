<?php 
	include('php/bank_reader.php');
	include('php/item_reader.php');
	$banks = FetchBankDataFromJSON();
	$items = FetchItemDataFromJSON();
	$user = isset($_GET['user']) ? GetUserData($_GET['user']) : false;

	include('php/auth_config.php');
	$auth = array_key_exists($_SERVER['REMOTE_ADDR'], $admin_whitelist) ? $admin_whitelist[$_SERVER['REMOTE_ADDR']] : false;
	if(!$auth) {
		header('Location: index.php');
	}
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="container">

			<span class="text-muted">Hello <?php echo $auth; ?>!</span>

			<h4 class="my-4 text-center">Manage <?php echo $user->username; ?>'s Bank</h4>

			<form class="mx-auto" style="max-width: 500px;" method="post">

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="username-addon">Username</span>
					</div>
					<input type="text" class="form-control"  name="username" value="<?php if($user) echo $user->username; ?>" placeholder="Username" aria-label="Username" aria-describedby="username-addon">
				</div>

				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="balance-addon">Balance</span>
					</div>
					<input type="number" class="form-control"  name="balance" value="<?php if($user) echo $user->balance; else echo 0; ?>" min=0 aria-label="Username" aria-describedby="username-addon">
				</div>

				<table id="inventory" class="table table-bordered table-sm">
					<thead>
						<tr>
							<th>Item</th>
							<th>Quantity</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($user)
							foreach($user->inventory as $index => $item)
							{
								echo "<tr>";
								echo "<td><select class='custom-select' name='items[]'>";
								foreach($items as $i)
								{
									if($i == $item->item) {
										echo "<option value='$i' selected>$i</option>";
									}
									else {
										echo "<option value='$i'>$i</option>";
									}
								}
								echo "</select></td>";
								echo "<td><input type='number' class='form-control' name='quantity[]' value=$item->quantity min=1 ></td>";
								echo "<td><button type='button' class='btn btn-light btnDelete'>Remove</button></td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>

				<button type="button" class="btn btn-primary" id="btnAddRow">Add Item</button>
				<button type="submit" class="btn btn-primary" name="submit" id="submit">Save Changes</button>
				<a href="index.php" class="btn btn-danger">Cancel</a>

			</form>

			<?php 
			
				if(isset($_POST['submit'])) 
				{
					$username = $_POST['username'];
					$balance = $_POST['balance'];
					$items = $_POST['items'];
					$quantity = $_POST['quantity'];

					if(count($items) > count(array_unique($items))) {
						echo "There are duplicate items.";
					}
					else {
						UpdateUserData($username, $balance, BuildInventory($items, $quantity));
					}
				}

			?>

			<script>
			
			$(".btnDelete").click(function() {
				$(this).closest('tr').remove();
			})

			$("#btnAddRow").click(function() {
				var tbl_data = "<tr>";
				tbl_data += "<td><select class='custom-select' name='items[]'>";
				<?php 
					foreach($items as $item) {
						echo "tbl_data += \"<option value='$item'>$item</option>\";";
					}
				?>
				tbl_data += "</select></td>";
				tbl_data += "<td><input type='number' class='form-control' name='quantity[]' value=1 min=1></td>";
				tbl_data += "<td><button type='button' class='btn btn-light btnDelete'>Remove</button></td>";
				tbl_data += "</tr>";
				$("#inventory tbody").append(tbl_data);
			});

			</script>

		</div>
	</body>
</html>