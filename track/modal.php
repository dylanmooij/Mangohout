<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<div id="ex1" class="modal">
	<h2>Deellevering aanvragen</h2>
	<p>Selecteer de producten welke je eerder wil ontvangen. Onze vervoerder neemt binnen 5 werkdagen contact met je op om een datum in te plannen.</p>
	<form method="post">
		<table>
			<tr>
				<th></th>
				<th>Product</th>
			</tr>
			<?php foreach ($items as $item): ?>
				<?php if(!$item->product_id) continue; ?>
				<?php if(!$item->custom_sku) continue; ?>
				<?php if(!$item->custom_description) continue; ?>
				<?php if($item->delivery_id) continue; ?>
				<?php if(!$item->purchase_id) continue; ?>
				<?php
					$purchase = getPurchase($item->purchase_id);
					if($purchase) {
						if($purchase->status == 'delivered') {
							$date = 'Op voorraad';
							$status = 'check';
							$partial = true;
						} else {
							continue;
						}
					} else {
						continue;
					}
				?>
				<tr>
					<td><input type="checkbox" name="items[]" value="<?=$item->id?>" checked /></td>
					<td><?=$item->custom_description?></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<input type="submit" class="single_add_to_cart_button button alt" value="Aanvragen" />
	</form>
</div>