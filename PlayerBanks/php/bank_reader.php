<?php 

function FetchBankDataFromJSON()
{
	$json_data = file_get_contents('json/banks.json');
	$banks = json_decode($json_data);
	return $banks;
}

function WriteBankDataToJSON()
{
	global $banks;
	$json_data = json_encode($banks);
	file_put_contents('json/banks.json', $json_data);
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

function DeleteUserData($username)
{
	global $banks;
	foreach($banks as $index => $user) {
		if($user->username == $username) {
			unset($banks[$index]);
		}
	}
	WriteBankDataToJSON();
}

function UpdateUserData($username, $balance, $newInv, $prev_user = null)
{
	global $banks;
	$userFound = false;
	$userToSearch = $prev_user ? $prev_user : $username;

	foreach($banks as $user) {
		if($user->username == $userToSearch)
		{
			$user->username = $username;
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

	WriteBankDataToJSON();
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

function IsUsernameUnique($username, $prev_user = null)
{
	global $banks;
	if($prev_user && $username == $prev_user) {
		return true;
	}
	else {
		if(array_search($username, array_column($banks, "username")) == 0) {
			return true;
		}
		else {
			return false;
		}
	}
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