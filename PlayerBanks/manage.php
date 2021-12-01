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
		<title>Manage Banks - Player Banks</title>
		<link rel="stylesheet" 
			href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
			integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
			crossorigin="anonymous">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="container">

			<span class="text-muted">Hello <?php echo $auth; ?>!</span>

			<h4 class="my-4 text-center"><?php if($user) echo "Manage $user->username's Bank"; else echo "Create New Bank" ?></h4>

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
									if($i->name == $item->name) {
										echo "<option value='$i->name' selected>$i->name</option>";
									}
									else {
										echo "<option value='$i->name'>$i->name</option>";
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
				<?php 
					if($user) {
						echo "<button type='submit' class='btn btn-danger' name='delete'>Delete Bank</button>";
					}
				?>
				<a href="index.php" class="btn btn-danger">Cancel</a>

			</form>

			<?php 
			
				if(isset($_POST['submit'])) 
				{
					$username = $_POST['username'];
					$balance = $_POST['balance'];
					$itemsArray = isset($_POST['items']) ? $_POST['items'] : [];
					$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : [];

					if(empty($username)) {
						echo "Please enter a username.";
					}
					else if(!IsUsernameUnique($username, $_GET['user'])) {
						echo "There is already a bank under this username.";
					}
					else if(count($itemsArray) > count(array_unique($itemsArray))) {
						echo "There are duplicate items.";
					}
					else {
						UpdateUserData($username, $balance, BuildInventory($itemsArray, $quantity), $_GET['user']);
						header("Location: view.php?user=$username");
					}
				}

				if(isset($_POST['delete']))
				{
					if($user) {
						DeleteUserData($_GET['user']);
						header("Location: index.php");
					}
					else {
						echo "There is no bank to delete.";
					}
				}

			?>

			<script>
			
			$("#inventory").on('click', '.btnDelete', function() {
				$(this).closest('tr').remove();
			});

			$("#btnAddRow").click(function() {
				var tbl_data = "<tr>";
				tbl_data += "<td><select class='custom-select' name='items[]'>";
				<?php 
					foreach($items as $item) {
						echo "tbl_data += \"<option value='$item->name'>$item->name</option>\";";
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