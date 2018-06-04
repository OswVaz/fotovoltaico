<link rel="stylesheet" type="text/css" href="/catalog/view/theme/default/stylesheet/openpay_banks.css">

<div id="msgBox" role="alert"><i></i><span style="margin-left:10px;"></span></div>
<div class="content" id="payment">
    
    <h2>Pago con transferencia bancaria (SPEI)</h2>
    <div class="mb20">
        <img src="/catalog/view/theme/default/image/spei.png" alt="SPEI" class="tiendas">
    </div>
    <div class="well">
        Una vez que des clic en el botón <strong>Confirmar Orden</strong>, tu pedido será puesto en <strong>Espera de pago</strong> y podrás imprimir las instrucciones con las cuales podrás liquidar tu pedido. <br><a target="_blank" href="http://www.openpay.mx/bancos.html">Bancos que ofrecen el servicio SPEI </a>.
    </div>
    
    <div class="pull-right">
            <button type="button" class="btn btn-primary" id="button-confirm" data-loading-text="Processing"><?php echo $button_confirm; ?></button>
    </div>
    
</div>

<script type="text/javascript"><!--
  $(document).ready(function(){
    $('#button-confirm').click(function(){
      location = '<?php echo $continue ?>';
    });
  });
</script>