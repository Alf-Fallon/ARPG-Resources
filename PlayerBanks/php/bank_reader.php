<?php 

function FetchBankDataFromJSON()
{
	$json_data = file_get_contents('json/banks.json');
	$banks = json_decode($json_data);
	return $banks;
}

function GetUserData($username = null)
{
	global $banks;

	if($username != null)
	foreach($banks as $user) {
		if($user->username == $username) 
		{
			return $user;
		}
	}
	return false;

}

function UpdateUserData($username, $balance, $newInv)
{
	global $banks;
	$userFound = false;

	foreach($banks as $user) {
		if($user->username == $username)
		{
			$user->balance = $balance;
			$user->inventory = $newInv;
			$userFound = true;
		}
	}

	if(!$userFound) {
		$user = new StdClass();
		$user->username = $username;
		$user->balance = $balance;
		$user->inventory = $newInv;
		$banks[] = $user;
	}

	$json_data = json_encode($banks);
	file_put_contents('json/banks.json', $json_data);
	header("Location: view.php?user=$username");
}

function BuildInventory($items, $quantity)
{
	$inventory = [];
	foreach($items as $index => $i) {
		$item = new StdClass();
		$item->item = $i;
		$item->quantity = $quantity[$index];
		$inventory[] = $item;
	}
	return $inventory;
}

function CountItems($items)
{
	$item_sum = 0;

	foreach($items as $item) {
		$item_sum += $item->quantity;
	}

	return $item_sum;
}

?>