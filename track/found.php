<?php
	$partial = false; 
	include 'modal.php';
?>
<h2>Hallo <?=$order->customer_name?>,</h2>
<?php if($order->status == 'created'): ?>
	<p class="alert alert-info">Je order wordt door ons verwerkt. Zodra deze verwerkt is kun je hier je order in de gaten houden.</p>
<?php elseif($order->status == 'finished'): ?>
	<p class="alert alert-info">Je order is afgerond. Is er iets niet naar wens? Neem dan contact met ons op.</p>
<?php else: ?>
	<p>Bedankt voor je bestelling bij De Meubel Importeur. We doen ons uiterste best om je bestelling zo snel mogelijk bij je af te leveren. Hierbij proberen we je zo goed mogelijk op de hoogte te houden van de status. Je bestelling wordt geleverd op het moment dat alle producten op voorraad zijn.</p>

	<p class="alert alert-info">Door de onvoorspelbare situatie omtrent het coronavirus kan het voorkomen dat de levertijden veranderen. Wij doen ons uiterste best om de verwachte leverdatum na te komen.</p>
	<!--<p>Je hebt bij het bestellen aangegeven dat je je bestelling in <strong>week 10</strong> geleverd wil hebben.</p>-->
	<table>
		<tr>
			<th></th>
			<th>Product</th>
			<th>SKU</th>
			<th>Status</th>
		</tr>
		<?php foreach ($items as $item): ?>
			<?php if(!$item->product_id) continue; ?>
			<?php if(!$item->custom_sku) continue; ?>
			<?php if(!$item->custom_description) continue; ?>
			<?php
				if($item->delivery_id) {
					$date = 'Overgedragen aan transporteur';
					$status = 'truck';
				} elseif($item->purchase_id) {
					$purchase = getPurchase($item->purchase_id);
					if($purchase) {
						if($purchase->status == 'delivered') {
							$date = 'Op voorraad';
							$status = 'check';
							$partial = true;
						} else {
							$date = strtotime($purchase->delivery_date);
							$date = strtotime("+14 days", $date);
							$date = 'Verwacht in week '.ltrim(date("W", $date), '0');
							$status = 'calendar';
						}
					} else {
						$date = 'Onbekend';
						$status = 'question';
					}
				}
			?>
			<tr>
				<td><i class="fa fa-<?=$status?>"></i></td>
				<td><?=$item->custom_description?></td>
				<td><?=$item->custom_sku?></td>
				<td><?=$date?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php /*<p>
		Een gedeeltelijke levering met producten die op voorraad zijn is mogelijk maar daarvoor brengen we wel extra kosten in rekening. Alleen zo houden we onze prijzen laag.<br>
		Onze verzendkosten worden berekend aan de hand van de hoeveelheid producten die je besteld, hoe meer er in een bestelling zit hoe goedkoper het wordt.
	</p>*/?>
	<?php if($partial): ?>
		<p><strong>Goed nieuws!</strong> Er zijn producten in je bestelling op voorraad. Klik op de knop 'deellevering aanvragen' als je deze eerder wil ontvangen.</p>
		<p><a href="#ex1" rel="modal:open" class="single_add_to_cart_button button alt">Deellevering aanvragen</a></p>
	<?php endif; ?>
<?php endif; ?>