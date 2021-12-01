<?php 

function FetchItemDataFromJSON()
{
	$json_data = file_get_contents('json/items.json');
	$items = json_decode($json_data);
	return $items;
}

?>