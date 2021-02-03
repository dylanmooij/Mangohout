<?php
$count = 0;
foreach ($items as $item) {
	if($item->delivery_id) continue;
	if(in_array($item->id, $_POST['items'])) {
		$count++;
	}
}

if($count > 0) {
	createDelivery($order, $_POST['items']);
}
