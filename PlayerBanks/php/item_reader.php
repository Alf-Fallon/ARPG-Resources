<?php 

function FetchItemDataFromJSON()
{
	$json_data = file_get_contents('json/items.json');
	$items = json_decode($json_data);
	return $items;
}

function GetItemData($item)
{
	global $items;

	foreach($items as $i) {
		if($i->name == $item) {
			return $i;
		}
	}
}

function BuildInventory($itemArray, $quantity)
{
	global $items;

	$inventory = [];
	foreach($itemArray as $index => $i) {
		$data = GetItemData($i);
		$item = new StdClass();
		$item->name = $data->name;
		$item->image = $data->image;
		$item->quantity = $quantity[$index];
		$inventory[] = $item;
	}
	return $inventory;
}



?>