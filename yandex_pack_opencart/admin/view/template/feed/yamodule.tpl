<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
		<div class="pull-right"> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
		<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
		</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<?php if($err_token) { ?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-circle"></i>
					<?php echo $err_token; ?>
					<button type="button" class="close" data-dismiss="alert">×</button>
				</div>
				<?php } ?>
				<ul class="nav nav-tabs">
					<li class=""><a href="#tab-kassa" data-toggle="tab"><?php echo $kassa; ?></a></li>
					<li class=""><a href="#tab-p2p" data-toggle="tab"><?php echo $p2p; ?></a></li>
					<li><a href="#tab-metrika" data-toggle="tab"><?php echo $metrika; ?></a></li>
					<li class=""><a href="#tab-market" data-toggle="tab"><?php echo $market; ?></a></li>
					<li><a href="#tab-pokupki" data-toggle="tab"><?php echo $pokupki; ?></a></li>
				</ul>
				<div class="tab-content bootstrap">
					<div class="tab-pane" id="tab-pokupki">
						<?php foreach ($pokupki_status as $po) { echo $po; } ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $pokupki; ?></h3>
							</div>
							<div class="panel-body">
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="pokupki_form form-horizontal">
									<input type="hidden" value="pokupki" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_stoken"><?php echo $pokupki_stoken; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_stoken" value="<?php echo $ya_pokupki_stoken; ?>" id="ya_pokupki_stoken" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_yapi"><?php echo $pokupki_yapi; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_yapi" value="<?php echo $ya_pokupki_yapi; ?>" id="ya_pokupki_yapi" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_number"><?php echo $pokupki_number; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_number" value="<?php echo $ya_pokupki_number; ?>" id="ya_pokupki_number" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_login"><?php echo $pokupki_login; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_login" value="<?php echo $ya_pokupki_login; ?>" id="ya_pokupki_login" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_upw"><?php echo $pokupki_upw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_upw" value="<?php echo $ya_pokupki_upw; ?>" id="ya_pokupki_upw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_idapp"><?php echo $pokupki_idapp; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_idapp" value="<?php echo $ya_pokupki_idapp; ?>" id="ya_pokupki_idapp" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_pw"><?php echo $pokupki_pw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_pw" value="<?php echo $ya_pokupki_pw; ?>" id="ya_pokupki_pw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_token"><?php echo $pokupki_token; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_token" value="<?php echo $ya_pokupki_gtoken; ?>" id="ya_pokupki_token" disabled="disabled" class="form-control"/>
											<p class="help-block">
												<a href="<?php echo $ya_pokupki_callback; ?>"><?php echo $pokupki_gtoken; ?></a>
											</p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_idpickup"><?php echo $pokupki_idpickup; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_idpickup" value="<?php echo $ya_pokupki_idpickup; ?>" id="ya_pokupki_idpickup" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_sapi"><?php echo $pokupki_sapi; ?></label>
										<div class="col-sm-8">
											<input type="text" disabled="disabled" name="ya_pokupki_sapi" value="<?php echo $ya_pokupki_sapi; ?>" id="ya_pokupki_sapi" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $pokupki_method; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_pokupki_yandex"><input type="checkbox" <?php echo ($ya_pokupki_yandex ? ' checked="checked"' : ''); ?> name="ya_pokupki_yandex" id="ya_pokupki_yandex" class="" value="1"/> <?php echo $pokupki_set_1; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_pokupki_sprepaid"><input type="checkbox" <?php echo ($ya_pokupki_sprepaid ? ' checked="checked"' : ''); ?> name="ya_pokupki_sprepaid" id="ya_pokupki_sprepaid" class="" value="1"/> <?php echo $pokupki_set_2; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_pokupki_cash"><input type="checkbox" <?php echo ($ya_pokupki_cash ? ' checked="checked"' : ''); ?> name="ya_pokupki_cash" id="ya_pokupki_cash" class="" value="1"/> <?php echo $pokupki_set_3; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_pokupki_bank"><input type="checkbox" <?php echo ($ya_pokupki_bank ? ' checked="checked"' : ''); ?> name="ya_pokupki_bank" id="ya_pokupki_bank" class="" value="1"/> <?php echo $pokupki_set_4; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_pokupki_callback"><?php echo $pokupki_callback; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_pokupki_callback" disabled="disabled" value="<?php echo $ya_pokupki_callback; ?>" id="ya_metrika_callback" class="form-control"/>
										</div>
									</div>
									<?php echo $data_carrier ?>
								</form>
							</div>
							<div class="panel-footer clearfix">
								<button type="button" onclick="$('.pokupki_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $pokupki_sv; ?>
								</button>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-metrika">
						<?php foreach ($metrika_status as $me) { echo $me; } ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $metrika; ?></h3>
							</div>
							<div class="panel-body">
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="metrika_form form-horizontal">
									<input type="hidden" value="metrika" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $active; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_metrika_active ? ' checked="checked"' : ''); ?> name="ya_metrika_active" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_metrika_active ? ' checked="checked"' : ''); ?> name="ya_metrika_active" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_number"><?php echo $metrika_number; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_number" value="<?php echo $ya_metrika_number; ?>" id="ya_metrika_number" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_idapp"><?php echo $metrika_idapp; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_idapp" value="<?php echo $ya_metrika_idapp; ?>" id="ya_metrika_idapp" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_pw"><?php echo $metrika_pw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_pw" value="<?php echo $ya_metrika_pw; ?>" id="ya_metrika_pw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_uname"><?php echo $metrika_uname; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_uname" value="<?php echo $ya_metrika_uname; ?>" id="ya_metrika_uname" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_upw"><?php echo $metrika_upw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_upw" value="<?php echo $ya_metrika_upw; ?>" id="ya_metrika_upw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_o2auth"><?php echo $metrika_o2auth; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_o2auth" value="<?php echo $ya_metrika_o2auth; ?>" disabled="disabled" id="ya_metrika_o2auth" class="form-control"/>
											<p class="help-block">
												<a href="<?php echo $ya_metrika_callback; ?>"><?php echo $metrika_gtoken; ?></a>
											</p>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $metrika_set; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_metrika_webvizor"><input type="checkbox" <?php echo ($ya_metrika_webvizor ? ' checked="checked"' : ''); ?> name="ya_metrika_webvizor" id="ya_metrika_webvizor" class="" value="1"/> <?php echo $metrika_set_1; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_clickmap"><input type="checkbox" <?php echo ($ya_metrika_clickmap ? ' checked="checked"' : ''); ?> name="ya_metrika_clickmap" id="ya_metrika_clickmap" class="" value="1"/> <?php echo $metrika_set_2; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_out"><input type="checkbox" <?php echo ($ya_metrika_out ? ' checked="checked"' : ''); ?> name="ya_metrika_out" id="ya_metrika_out" class="" value="1"/> <?php echo $metrika_set_3; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_otkaz"><input type="checkbox" <?php echo ($ya_metrika_otkaz ? ' checked="checked"' : ''); ?> name="ya_metrika_otkaz" id="ya_metrika_otkaz" class="" value="1"/> <?php echo $metrika_set_4; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_hash"><input type="checkbox" <?php echo ($ya_metrika_hash ? ' checked="checked"' : ''); ?> name="ya_metrika_hash" id="ya_metrika_hash" class="" value="1"/> <?php echo $metrika_set_5; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $metrika_celi; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_metrika_cart"><input type="checkbox" <?php echo ($ya_metrika_cart ? ' checked="checked"' : ''); ?> name="ya_metrika_cart" id="ya_metrika_cart" class="" value="1"/> <?php echo $celi_cart; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_metrika_order"><input type="checkbox"<?php echo ($ya_metrika_order ? ' checked="checked"' : ''); ?> name="ya_metrika_order" id="ya_metrika_order" class="" value="1"/> <?php echo $celi_order; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_metrika_callback"><?php echo $metrika_callback; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_metrika_callback" disabled="disabled" value="<?php echo $ya_metrika_callback; ?>" id="ya_metrika_callback" class="form-control"/>
										</div>
									</div>
								</form>
							</div>
							<div class="panel-footer clearfix">
								<button type="button" onclick="$('.metrika_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $metrika_sv; ?>
								</button>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-kassa">
						<?php foreach ($kassa_status as $k) { echo $k; } ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $kassa; ?></h3>
							</div>
							<div class="panel-body">
								<?php if($mod_status) { ?>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="kassa_form form-horizontal">
									<input type="hidden" value="kassa" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $active; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_kassa_active ? ' checked="checked"' : ''); ?> name="ya_kassa_active" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_kassa_active ? ' checked="checked"' : ''); ?> name="ya_kassa_active" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $kassa_test; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_kassa_test ? ' checked="checked"' : ''); ?> name="ya_kassa_test" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_kassa_test ? ' checked="checked"' : ''); ?> name="ya_kassa_test" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_os"><?php echo $kassa_os; ?></label>
										<div class="col-sm-8">
											<select name="ya_kassa_os" id="ya_kassa_os" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
												<?php if ($order_status['order_status_id'] == $ya_kassa_os) { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
												<?php } else { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_sid"><?php echo $kassa_sid; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_sid" value="<?php echo $ya_kassa_sid; ?>" id="ya_kassa_sid" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_scid"><?php echo $kassa_scid; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_scid" value="<?php echo $ya_kassa_scid; ?>" id="ya_kassa_scid" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_pw"><?php echo $kassa_pw; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_pw" value="<?php echo $ya_kassa_pw; ?>" id="ya_kassa_pw" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $log; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_kassa_log ? ' checked="checked"' : ''); ?> name="ya_kassa_log" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_kassa_log ? ' checked="checked"' : ''); ?> name="ya_kassa_log" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $kassa_method; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_kassa_wallet"><input type="checkbox" <?php echo ($ya_kassa_wallet ? ' checked="checked"' : ''); ?> name="ya_kassa_wallet" id="ya_kassa_wallet" class="" value="1"/> <?php echo $kassa_wallet; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_kassa_card"><input type="checkbox" <?php echo ($ya_kassa_card ? ' checked="checked"' : ''); ?> name="ya_kassa_card" id="ya_kassa_card" class="" value="1"/> <?php echo $kassa_card; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_kassa_terminal"><input type="checkbox" <?php echo ($ya_kassa_terminal ? ' checked="checked"' : ''); ?> name="ya_kassa_terminal" id="ya_kassa_terminal" class="" value="1"/> <?php echo $kassa_terminal; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_kassa_mobile"><input type="checkbox" <?php echo ($ya_kassa_mobile ? ' checked="checked"' : ''); ?> name="ya_kassa_mobile" id="ya_kassa_mobile" class="" value="1"/> <?php echo $kassa_mobile; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_kassa_wm"><input type="checkbox" <?php echo ($ya_kassa_wm ? ' checked="checked"' : ''); ?> name="ya_kassa_wm" id="ya_kassa_wm" class="" value="1"/> <?php echo $kassa_wm; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_kassa_sber"><input type="checkbox" <?php echo ($ya_kassa_sber ? ' checked="checked"' : ''); ?> name="ya_kassa_sber" id="ya_kassa_sber" class="" value="1"/> <?php echo $kassa_sber; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_kassa_alfa"><input type="checkbox" <?php echo ($ya_kassa_alfa ? ' checked="checked"' : ''); ?> name="ya_kassa_alfa" id="ya_kassa_alfa" class="" value="1"/> <?php echo $kassa_alfa; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_check"><?php echo $kassa_check; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_check" value="<?php echo $ya_kassa_check; ?>" id="ya_kassa_check" disabled="disabled" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_aviso"><?php echo $kassa_aviso; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_aviso" value="<?php echo $ya_kassa_aviso; ?>" id="ya_kassa_aviso" disabled="disabled" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_fail"><?php echo $kassa_fail; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_fail" value="<?php echo $ya_kassa_fail; ?>" id="ya_kassa_fail" disabled="disabled" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_kassa_success"><?php echo $kassa_success; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_kassa_success" value="<?php echo $ya_kassa_success; ?>" id="ya_kassa_success" disabled="disabled" class="form-control"/>
										</div>
									</div>
								</form>
								<?php }  else { ?>
								<div class="alert alert-danger">
									<i class="fa fa-exclamation-circle"></i>
									<?php echo $mod_off; ?>
									<button type="button" class="close" data-dismiss="alert">×</button>
								</div>
								<?php }?>
							</div>
							<?php if($mod_status) { ?>
							<div class="panel-footer clearfix">
								<button type="button" onclick="$('.kassa_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $kassa_sv; ?>
								</button>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane" id="tab-p2p">
						<?php foreach ($p2p_status as $p) { echo $p; } ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $p2p; ?></h3>
							</div>
							<div class="panel-body">
								<?php if($mod_status) { ?>
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="p2p_form form-horizontal">
									<input type="hidden" value="p2p" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $active; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_p2p_active ? ' checked="checked"' : ''); ?> name="ya_p2p_active" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_p2p_active ? ' checked="checked"' : ''); ?> name="ya_p2p_active" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_p2p_os"><?php echo $p2p_os; ?></label>
										<div class="col-sm-8">
											<select name="ya_p2p_os" id="ya_p2p_os" class="form-control">
											<?php foreach ($order_statuses as $order_status) { ?>
												<?php if ($order_status['order_status_id'] == $ya_p2p_os) { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
												<?php } else { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
												<?php } ?>
											<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_p2p_number"><?php echo $p2p_number; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_p2p_number" value="<?php echo $ya_p2p_number; ?>" id="ya_p2p_number" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_p2p_idapp"><?php echo $p2p_idapp; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_p2p_idapp" value="<?php echo $ya_p2p_idapp; ?>" id="ya_p2p_idapp" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_p2p_pw"><?php echo $p2p_pw; ?></label>
										<div class="col-sm-8">
											<textarea name="ya_p2p_pw" id="ya_p2p_pw" class="form-control"><?php echo $ya_p2p_pw; ?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $log; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_p2p_log ? ' checked="checked"' : ''); ?> name="ya_p2p_log" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_p2p_log ? ' checked="checked"' : ''); ?> name="ya_p2p_log" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_p2p_linkapp"><?php echo $p2p_linkapp; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_p2p_linkapp" disabled="disabled" value="<?php echo $ya_p2p_linkapp; ?>" id="ya_p2p_linkapp" class="form-control"/>
										</div>
									</div>
								</form>
								<?php } else { ?>
								<div class="alert alert-danger">
									<i class="fa fa-exclamation-circle"></i>
									<?php echo $mod_off; ?>
									<button type="button" class="close" data-dismiss="alert">×</button>
								</div>
								<?php }?>
							</div>
							<?php if($mod_status) { ?>
							<div class="panel-footer clearfix">
								<button type="button" onclick="$('.p2p_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
									<i class="process-icon-save"></i> <?php echo $p2p_sv; ?>
								</button>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane" id="tab-market">
						<?php foreach ($market_status as $m) { echo $m; } ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $market; ?></h3>
							</div>
							<div class="panel-body">
								<form action="<?php echo $action; ?>" method="POST" id="form-seting" class="market_form form-horizontal">
									<input type="hidden" value="market" name="type_data"/>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $active; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_active ? ' checked="checked"' : ''); ?> name="ya_market_active" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_market_active ? ' checked="checked"' : ''); ?> name="ya_market_active" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_prostoy; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_prostoy ? ' checked="checked"' : ''); ?> name="ya_market_prostoy" value="1"/> <?php echo $active_on; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_market_prostoy ? ' checked="checked"' : ''); ?> name="ya_market_prostoy" value="0"/> <?php echo $active_off; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_out; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_catall ? ' checked="checked"' : ''); ?> name="ya_market_catall" value="1"/> <?php echo $market_out_all; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo (!$ya_market_catall ? ' checked="checked"' : ''); ?> name="ya_market_catall" value="0"/> <?php echo $market_out_sel; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_dostup; ?></label>
										<div class="col-sm-8">
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 1 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="1"/> <?php echo $market_dostup_1; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 2 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="2"/> <?php echo $market_dostup_2; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 3 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="3"/> <?php echo $market_dostup_3; ?></label>
											<label class="radio-inline">
												<input type="radio" <?php echo ($ya_market_set_available == 4 ? ' checked="checked"' : ''); ?> name="ya_market_set_available" value="4"/> <?php echo $market_dostup_4; ?></label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_market_shopname"><?php echo $market_s_name; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_market_shopname" value="<?php echo $ya_market_shopname; ?>" id="ya_market_shopname" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_market_localcoast"><?php echo $market_d_cost; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_market_localcoast" value="<?php echo $ya_market_localcoast; ?>" id="ya_market_localcoast" class="form-control"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_color_option; ?></label>
										<div class="col-sm-8">
											<div class="scrollbox" style="height: 100px; overflow-x: auto; width: 100%;">
												<?php $class = 'odd'; ?>
												<?php foreach ($options as $option) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($option['option_id'], $ya_market_color_options)) { ?>
													<input type="checkbox" name="ya_market_color_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
													<?php echo $option['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="ya_market_color_options[]" value="<?php echo $option['option_id']; ?>" />
													<?php echo $option['name']; ?>
													<?php } ?>
												</div>
												<?php } ?>
											</div>
											<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php echo $market_size_option; ?><br/><?php echo $market_size_unit; ?></label>
										<div class="col-sm-8">
											<div class="scrollbox" style="height: 160px; overflow-x: auto; width: 100%;">
												<?php $class = 'odd'; ?>
												<?php foreach ($options as $option) { ?>
												<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
												<div class="<?php echo $class; ?>">
													<?php if (in_array($option['option_id'], $ya_market_size_options)) { ?>
													<input type="checkbox" name="ya_market_size_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
													<?php echo $option['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="ya_market_size_options[]" value="<?php echo $option['option_id']; ?>" />
													<?php echo $option['name']; ?>
													<?php } ?>
												</div>
												<?php } ?>
											</div>
											<a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $market_cat; ?></label>
										<div class="col-sm-8">
											<div class="panel panel-default">
												<div class="tree-panel-heading tree-panel-heading-controls clearfix">
													<div class="tree-actions pull-right">
														<a onclick="hidecatall($('#categoryBox')); return false;" id="collapse-all-categoryBox" class="btn btn-default">
															<i class="icon-collapse-alt"></i><?php echo $market_sv_all; ?>
														</a>
														<a onclick="showcatall($('#categoryBox')); return false;" id="expand-all-categoryBox" class="btn btn-default">
															<i class="icon-expand-alt"></i><?php echo $market_rv_all; ?>
														</a>
														<a onclick="checkAllAssociatedCategories($('#categoryBox')); return false;" id="check-all-categoryBox" class="btn btn-default">
															<i class="icon-check-sign"></i><?php echo $market_ch_all; ?>
														</a>
														<a onclick="uncheckAllAssociatedCategories($('#categoryBox')); return false;" id="uncheck-all-categoryBox" class="btn btn-default">
															<i class="icon-check-empty"></i><?php echo $market_unch_all; ?>
														</a>
													</div>
												</div>		
												<ul id="categoryBox" class="tree">
													<?php echo $market_cat_tree; ?>
												</ul>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-sm-4"><?php echo $market_set; ?></label>
										<div class="col-sm-8">
											<div class="checkbox">
												<label for="ya_market_available"><input type="checkbox" <?php echo ($ya_market_available? ' checked="checked"' : ''); ?> name="ya_market_available" id="ya_market_available" class="" value="1"/> <?php echo $market_set_1; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_homecarrier"><input type="checkbox" <?php echo ($ya_market_homecarrier ? ' checked="checked"' : ''); ?> name="ya_market_homecarrier" id="ya_market_homecarrier" class="" value="1"/> <?php echo $market_set_2; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_combination"><input type="checkbox" <?php echo ($ya_market_combination ? ' checked="checked"' : ''); ?> name="ya_market_combination" id="ya_market_combination" class="" value="1"/> <?php echo $market_set_3; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_features"><input type="checkbox" <?php echo ($ya_market_features ? ' checked="checked"' : ''); ?> name="ya_market_features" id="ya_market_features" class="" value="1"/> <?php echo $market_set_4; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_dimensions"><input type="checkbox" <?php echo ($ya_market_dimensions ? ' checked="checked"' : ''); ?> name="ya_market_dimensions" id="ya_market_dimensions" class="" value="1"/> <?php echo $market_set_5; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_allcurrencies"><input type="checkbox" <?php echo ($ya_market_allcurrencies ? ' checked="checked"' : ''); ?> name="ya_market_allcurrencies" id="ya_market_allcurrencies" class="" value="1"/> <?php echo $market_set_6; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_store"><input type="checkbox" <?php echo ($ya_market_store ? ' checked="checked"' : ''); ?> name="ya_market_store" id="ya_market_store" class="" value="1"/> <?php echo $market_set_7; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_delivery"><input type="checkbox" <?php echo ($ya_market_delivery ? ' checked="checked"' : ''); ?> name="ya_market_delivery" id="ya_market_delivery" class="" value="1"/> <?php echo $market_set_8; ?></label>
											</div>
											<div class="checkbox">
												<label for="ya_market_pickup"><input type="checkbox" <?php echo ($ya_market_pickup ? ' checked="checked"' : ''); ?> name="ya_market_pickup" id="ya_market_pickup" class="" value="1"/> <?php echo $market_set_9; ?></label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label" for="ya_market_dynamic"><?php echo $market_lnk_yml; ?></label>
										<div class="col-sm-8">
											<input type="text" name="ya_market_dynamic" value="<?php echo $ya_market_lnk_yml; ?>" id="ya_market_dynamic" disabled="disabled" class="form-control"/>
										</div>
									</div>
								</form>
								<div class="panel-footer clearfix">
									<button type="button" onclick="$('.market_form').submit(); return false;" value="1" id="module_form_submit_btn_3" name="submitmarketModule" class="btn btn-default">
										<i class="process-icon-save"></i> <?php echo $market_sv; ?>
									</button>
									<!-- <button type="submit" class="btn btn-default btn btn-default" name="generatemanual"><i class="process-icon-refresh"></i> <?php echo $market_gen; ?></button> -->
								</div>
							</div>
						</div>
					</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
function showcatall($tree)
{
$tree.find("ul.tree").each(
	function()
	{
		$(this).slideDown();
	}
);
}

function hidecatall($tree)
{
$tree.find("ul.tree").each(
	function()
	{
		$(this).slideUp();
	}
);
}
function checkAllAssociatedCategories($tree)
{
$tree.find(":input[type=checkbox]").each(
	function()
	{
		$(this).prop("checked", true);
		$(this).parent().addClass("tree-selected");
	}
);
}

function uncheckAllAssociatedCategories($tree)
{
$tree.find(":input[type=checkbox]").each(
	function()
	{
		$(this).prop("checked", false);
		$(this).parent().removeClass("tree-selected");
	}
);
}

$(document).ready(function(){
	$('.nav-tabs a:first').tab('show');
	var view = $.totalStorage('tab_ya');
	if(view == null)
		$.totalStorage('tab_ya', 'tab-kassa');
	else
		$('.nav-tabs li a[href="#'+ view +'"]').click();

	$('.nav-tabs li').click(function(){
		var view = $(this).find('a').first().attr('href').replace('#', '');
		$.totalStorage('tab_ya', view);
	});

	$('.tree-item-name label').click(function(){
		$(this).parent().find('input').click();
	});

	$('.tree-folder-name input').change(function(){
		if ($(this).prop("checked"))
		{
			$(this).parent().addClass("tree-selected");
			$(this).parents('.tree-folder').first().find('ul input').prop("checked", true).parent().addClass("tree-selected");
		}
		else
		{
			$(this).parent().removeClass("tree-selected");
			$(this).parents('.tree-folder').first().find('ul input').prop("checked", false).parent().removeClass("tree-selected");
		}
	});

	$('.tree-toggler').click(function(){
		$(this).parents('.tree-folder').first().find('ul').slideToggle('slow');
	});

	$('.tree input').change(function(){
		if ($(this).prop("checked"))
		{
			$(this).parent().addClass("tree-selected");
		}
		else
		{
			$(this).parent().removeClass("tree-selected");
		}
	});

	var market_cat = JSON.parse('<?php echo json_encode($ya_market_categories); ?>');
	console.log(market_cat);
	for (i in market_cat)
		$('#categoryBox input[value="'+ market_cat[i] +'"]').prop("checked", true).change();
});
</script>
<script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter27746007 = new Ya.Metrika({ id:27746007 }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script>
<noscript><div><img src="//mc.yandex.ru/watch/27746007" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<style>
.bootstrap .tree-panel-heading-controls {
    line-height: 2.2em;
    font-size: 1.1em;
    color: #00aff0;
}

.bootstrap .tree-panel-heading-controls i {
    font-size: 14px;
}

.bootstrap .tree {
    list-style: none;
    padding: 0 0 0 20px;
}

.bootstrap .tree input {
    vertical-align: baseline;
    margin-right: 4px;
    line-height: normal;
}

.bootstrap .tree i {
    font-size: 14px;
}

.bootstrap .tree .tree-item-name,.bootstrap .tree .tree-folder-name {
    padding: 2px 5px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
}

.bootstrap .tree .tree-item-name label,.bootstrap .tree .tree-folder-name label {
    font-weight: 400;
}

.bootstrap .tree .tree-item-name:hover,.bootstrap .tree .tree-folder-name:hover {
    background-color: #eee;
    cursor: pointer;
}

.bootstrap .tree .tree-selected {
    color: #fff;
    background-color: #00aff0;
}

.bootstrap .tree .tree-selected:hover {
    background-color: #009cd6;
}

.bootstrap .tree .tree-selected i.tree-dot {
    background-color: #fff;
}

.bootstrap .panel-footer {
	height: 73px;
	border-color: #eee;
	background-color: #fcfdfe;
}

.bootstrap .tree i.tree-dot {
    display: inline-block;
    position: relative;
    width: 6px;
    height: 6px;
    margin: 0 4px;
    background-color: #ccc;
    -webkit-border-radius: 6px;
    border-radius: 6px;
}

.bootstrap .tree .tree-item-disable,.bootstrap .tree .tree-folder-name-disable {
    color: #ccc;
}

.bootstrap .tree .tree-item-disable:hover,.bootstrap .tree .tree-folder-name-disable:hover {
    color: #ccc;
    background-color: none;
}

.bootstrap .tree-actions {
    display: inline-block;
}

.bootstrap .tree-panel-heading-controls {
    padding: 5px;
    border-bottom: solid 1px #dfdfdf;
}

.bootstrap .tree-actions .twitter-typeahead {
    padding: 0 0 0 4px;
}

.bootstrap #categoryBox {
	padding: 10px 5px 5px 20px;
}

.bootstrap .tree-panel-label-title {
    font-weight: 400;
    margin: 0;
    padding: 0 0 0 8px;
}

.scrollbox > div {
	height: 23px;
}
</style>
<script type="text/javascript" src="view/javascript/jquery.total-storage.js"></script>