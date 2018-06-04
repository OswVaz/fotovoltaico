<form id = "banregio" action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="orderid" value="<?php echo $orderid; ?>" />
  <input type="hidden" name="callbackurl" value="<?php echo $callbackurl; ?>" />
  <input type="hidden" name="orderamount" value="<?php echo $orderamount; ?>" />
  <input type="hidden" name="url_back" value = "https://anamarmar.000webhostapp.com/ajax.php/"/>
  <input type="hidden" name="orderdate" value="<?php echo $orderdate; ?>" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="billemail" value="<?php echo $billemail; ?>" />
  <input type="hidden" name="billphone" value="<?php echo $billphone; ?>" />
  <input type="hidden" name="billaddress" value="<?php echo $billaddress; ?>" />
  <input type="hidden" name="billcountry" value="<?php echo $billcountry; ?>" />
  <input type="hidden" name="billprovince" value="<?php echo $billprovince; ?>" />
  <input type="hidden" name="billcity" value="<?php echo $billcity; ?>" />
  <input type="hidden" name="billpost" value="<?php echo $billpost; ?>" />
  <input type="hidden" name="deliveryname" value="<?php echo $deliveryname; ?>" />
  <input type="hidden" name="deliveryaddress" value="<?php echo $deliveryaddress; ?>" />
  <input type="hidden" name="deliverycity" value="<?php echo $deliverycity; ?>" />
  <input type="hidden" name="deliverycountry" value="<?php echo $deliverycountry; ?>" />
  <input type="hidden" name="deliveryprovince" value="<?php echo $deliveryprovince; ?>" />
  <input type="hidden" name="deliveryemail" value="<?php echo $deliveryemail; ?>" />
  <input type="hidden" name="deliveryphone" value="<?php echo $deliveryphone; ?>" />
  <input type="hidden" name="deliverypost" value="<?php echo $deliverypost; ?>" />

<div class = "row">
	<div class = "col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">Pago con tarjeta</div>
			<div class="panel-body">
				<div class = "row">
					<div class = "col-sm-12">
						<div class = "row">
							<div class = "col-sm-6">
								<label for = 'name_card'>Nombre del titular</label>
								<input type = "text" class = "form-control" name = 'name_card' id = 'name_card' value = "osw">
							</div>
							<div class = "col-sm-6">
								<label for = 'number_card'>N&uacute;mero de Tarjeta</label>
								<input type = "tel" maxlength= '16' class = "form-control" name = 'number_card' id = 'number_card' value = "4000000000000036">
							</div>
						</div>
						<div class = "row">
							<div class = "col-sm-7">
								<div class = "row">
									<div class = "col-sm-12">
									<label>Fecha de expiración</label>
									</div>
								</div>
								<div class = "row">
									<div class = "col-xs-6">
										<select class ="form-control" name = "mes">
											<option value = '01'>Enero</option>
											<option value = '02'>Febrero</option>
											<option value = '03'>Marzo</option>
											<option value = '04'>Abril</option>
											<option value = '05'>Mayo</option>
											<option value = '06'>Junio</option>
											<option value = '07'>Julio</option>
											<option value = '08'>Agosto</option>
											<option value = '09'>Septiembre</option>
											<option value = '10'>Octubre</option>
											<option value = '11'>Noviembre</option>
											<option value = '12' selected>Diciembre</option>
										</select>
									</div>
									<div class = "col-xs-6">
										<select class ="form-control" name = "anio">
											<option value = '18'>2018</option>
											<option value = '19'>2019</option>
											<option value = '20'>2020</option>
											<option value = '21'>2021</option>
											<option value = '22'>2022</option>
											<option value = '23'>2023</option>
											<option value = '24'>2024</option>
											<option value = '25'>2025</option>
											<option value = '26'>2026</option>
											<option value = '27'>2027</option>
											<option value = '28'>2028</option>
											<option value = '29'>2029</option>
											<option value = '30'>2030</option>
											<option value = '31'>2031</option>
											<option value = '32'>2032</option>
											<option value = '33'>2033</option>
											<option value = '34'>2034</option>
											<option value = '35'>2035</option>
											<option value = '36'>2036</option>
											<option value = '37'>2037</option>
											<option value = '38'>2038</option>
											<option value = '39'>2039</option>
											<option value = '40'>2040</option>
										</select>
									</div>
								</div>
							</div>
							<div class = "col-sm-5">
								<label for = 'expiration_date_card'>C&oacute;digo de seguridad</label>
								<input type = "password" class = "form-control" name = 'expiration_date_card' id = 'expiration_date_card' value = "123">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

  <div class="buttons">
    <div class="pull-right">
      <button id= "button-confirm" type="submit" class="btn btn-primary"><?php echo $button_confirm; ?></button>
    </div>
  </div>
</form>


<script type="text/javascript"><!--
$('#banregio').on('submit', function(e) {
	e.preventDefault();

	$.ajax({
		type: 'post',
		url: 'index.php?route=payment/banregio/confirm',
		cache: false,
		data: $('#banregio').serialize(),
		beforeSend: function() {
			$('#button-confirm').val('Cargando');
			// $('#button-confirm').prop('disabled',true);
		},
		success: function(e) {
			console.log(e);
			//location = '<?php echo $continue; ?>';
		}
	});
});
//--></script>
