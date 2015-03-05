<?php if ($kassa_mode){ ?>
	<form method="POST" action="<?php echo $kassa_action; ?>">
		<div class="buttons">
			<h3><?php echo $method_label; ?></h3>	
			<table class="radio">
				<tbody>
					<?php if ($method_ym){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="PC" checked id="ym1"></td>
							<td><label for="ym1"><?php echo $method_ym_text;?></label></td>
						</tr>
					<?php } ?>
					<?php if ($method_cards){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="AC" id="ym2"></td>
							<td><label for="ym2"><?php echo $method_cards_text;?></label></td>
						</tr>
					<?php } ?>
					<?php if ($method_cash ){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="GP" id="ym3"></td>
							<td><label for="ym3"><?php echo $method_cash_text;?></label></td>
						</tr>
					<?}?>
					<? if ($method_mobile ){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="MC" id="ym4"></td>
							<td><label for="ym4"><?php echo $method_mobile_text;?></label></td>
						</tr>
					<?php } ?>
					<? if ($method_wm ){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="WM" id="ym5"></td>
							<td><label for="ym5"><?php echo $method_wm_text;?></label></td>
						</tr>
					<?php } ?>
					<? if ($method_sb ){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="SB" id="ym6"></td>
							<td><label for="ym6"><?php echo $method_sber_text;?></label></td>
						</tr>
					<?php } ?>
					<? if ($method_alfa ){?>
						<tr class="highlight">
							<td><input type="radio" name="paymentType" value="AB" id="ym7"></td>
							<td><label for="ym7"><?php echo $method_alfa_text;?></label></td>
						</tr>
					<?php } ?>
		   		</tbody>
			</table>
			<input type="hidden" name="shopid" value="<?php echo $shop_id;?>">
			<input type="hidden" name="scid" value="<?php echo $scid;?>">
			<input type="hidden" name="orderNumber" value="<?php echo $order_id;?>">
			<input type="hidden" name="sum" value="<?php echo $sum;?>" data-type="number" >
			<input type="hidden" name="customerNumber" value="<?php echo $customerNumber; ?>" >
			<input type="hidden" name="shopSuccessURL" value="<?php echo $shopSuccessURL; ?>" >
			<input type="hidden" name="shopFailURL" value="<?php echo $shopFailURL; ?>" >
			<input type="hidden" name="cms_name" value="ya_opencart2" >
			<div class="buttons">
				<div class="pull-right">
					<input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
				</div>
			</div>	   
		</div>	
	</form>
<?php } ?>
<?php  if ($p2p_mode) {  ?>
	<form action="<?php echo $p2p_action; ?>" method="POST">
	   <div class="buttons">
		   <h3><?php echo $method_label; ?></h3>
		   <table class="radio">
			   <tbody>
				<tr class="highlight">
					<td><input type="radio" name="payment-type" value="wallet" checked id="ym1"></td>
					<td><label for="ym1"><?php echo $method_ym_text;?></label></td>
				</tr>
			   <tr class="highlight">
					<td><input type="radio" name="payment-type" value="card" id="ym2"></td>
					<td><label for="ym2"><?php echo $method_cards_text;?></label></td>
				</tr>
				</tbody>
			</table>
	   </div>
	   <div class="buttons">
			<div class="pull-right">
				<input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
			</div>
		</div>
	</form>
<?php } ?>