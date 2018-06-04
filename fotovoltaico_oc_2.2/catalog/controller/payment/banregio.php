<?php
class ControllerPaymentBanregio extends Controller {
  public function index() {
    $this->language->load('payment/banregio');
    $data['button_confirm'] = $this->language->get('button_confirm');
    $data['continue'] = $this->url->link('checkout/success');
    $data['action'] = "";

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    if ($order_info) {
      $data['text_config_one'] = trim($this->config->get('banregio_Afiliacion'));
      $data['text_config_two'] = trim($this->config->get('banregio_ID_Medio'));
      $data['orderid'] = date('His') . $this->session->data['order_id'];
      $data['callbackurl'] = $this->url->link('payment/banregio/callback');
      $data['orderdate'] = date('YmdHis');
      $data['currency'] = $order_info['currency_code'];
      $data['orderamount'] = $this->currency->format($order_info['total'], $data['currency'] , false, false);
      $data['billemail'] = $order_info['email'];
      $data['billphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
      $data['billaddress'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
      $data['billcountry'] = html_entity_decode($order_info['payment_iso_code_2'], ENT_QUOTES, 'UTF-8');
      $data['billprovince'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');;
      $data['billcity'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
      $data['billpost'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
      $data['deliveryname'] = html_entity_decode($order_info['shipping_firstname'] . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
      $data['deliveryaddress'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
      $data['deliverycity'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
      $data['deliverycountry'] = html_entity_decode($order_info['shipping_iso_code_2'], ENT_QUOTES, 'UTF-8');
      $data['deliveryprovince'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
      $data['deliveryemail'] = $order_info['email'];
      $data['deliveryphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
      $data['deliverypost'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
      if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/banregio.tpl')){
        $this->template = $this->config->get('config_template') . '/template/payment/banregio.tpl';
      } else {
        $this->template = '/payment/banregio.tpl';
      }

    return $this->load->view($this->template, $data);

    }



  }
public function confirm() {
    if ($this->session->data['payment_method']['code'] == 'banregio') {
      $id_afiliacion = trim($this->config->get('banregio_Afiliacion'));
      $id_medio = trim($this->config->get('banregio_ID_Medio'));

      $option_modo_operativo =  trim($this->config->get('banregio_modo_operativo')); // 0 = pruebas, 1 = produccion
      if($option_modo_operativo){
          $modo_transaccion = 'PRD';
          $url = trim($this->config->get('banregio_url_produccion'));
      }else{
          $modo_transaccion = 'AUT';  
          $url = trim($this->config->get('banregio_url_pruebas'));
      }
      
      $modo_entrada = 'MANUAL'; //obligatorio por banregio
      $comando_transaccion = "VENTA";
      //$url_respuesta = 'https://anamarmar.000webhostapp.com/ajax.php';
      date_default_timezone_set('America/Mexico_City');
      $name_card = $_POST['name_card'];
      $num_tarjeta = $_POST['number_card'];
      $exp_tarjeta = $_POST['mes'] . $_POST['anio'];
      $code_tarjeta = $_POST['expiration_date_card'];
      $hr_local = date('His') ;
      $fecha_local = date('dmY');
      $folio = $_POST['orderid']. '_'.rand(0,100);
      $monto  = $_POST['orderamount'];

      $var_1 = $id_afiliacion;
      $var_2 = $id_medio;
      $var_3 = $num_tarjeta;
      $var_4 = $exp_tarjeta;
      $var_5 = $code_tarjeta;
      $var_6 = $monto;
      $var_7 = $folio;
      $var_8 = $modo_transaccion;
      $var_9 = $hr_local;
      $var_10 = $fecha_local;
      $var_11 = $modo_entrada;
      $var_12 = $comando_transaccion;

      $vars =  'BNRG_ID_AFILIACION='.$var_1.'&BNRG_ID_MEDIO='.$var_2.'&BNRG_NUMERO_TARJETA='.$var_3.'&BNRG_FECHA_EXP='.$var_4.'&BNRG_CODIGO_SEGURIDAD='.$var_5.'&BNRG_MONTO_TRANS='.$var_6.'&BNRG_FOLIO='.$var_7.'&BNRG_MODO_TRANS='.$var_8.'&BNRG_HORA_LOCAL='.$var_9.'&BNRG_FECHA_LOCAL='.$var_10.'&BNRG_MODO_ENTRADA='.$var_11.'&BNRG_CMD_TRANS='.$var_12;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_POST      ,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS    ,$vars);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
      curl_setopt($ch, CURLOPT_HEADER      ,1);  // DO NOT RETURN HTTP HEADERS
      curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
      $Rec_Data = curl_exec($ch);

      // var_dump($Rec_Data);
 //       ob_start();
 // header("Content-Type: text/html");
            
      // curl_close($ch);
 //  $result = json_decode(json_encode($Rec_Data), true);


error_log(print_r($Rec_Data, true),3,'/tmp/valdo5');
      $this->load->model('checkout/order');
      $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));

    }
  }

  public function confirms(){


      //datos front


      // $info = 'BNRG_ID_AFILIACION='.$id_afiliacion.'&BNRG_ID_MEDIO='.$id_medio.'&BNRG_MODO_TRANS='.$modo_transaccion.'&BNRG_MODO_ENTRADA='.$modo_entrada.'&BNRG_CMD_TRANS='.$comando_transaccion.'&BNRG_URL_RESPUESTA='.$url_respuesta.'&BNRG_HORA_LOCAL='.$hr_local.'&BNRG_FECHA_LOCAL='.$fecha_local.'&BNRG_FOLIO='.$folio.'&BNRG_MONTO_TRANS='.$monto.'&BNRG_CODIGO_SEGURIDAD='.$code_tarjeta.'&BNRG_FECHA_EXP='.$exp_tarjeta.'&BNRG_NUMERO_TARJETA='.$num_tarjeta;
      
      $info = array('BNRG_ID_AFILIACION'=>$id_afiliacion,'BNRG_ID_MEDIO'=>$id_medio,'BNRG_MODO_TRANS'=>$modo_transaccion,'BNRG_MODO_ENTRADA'=>$modo_entrada,'BNRG_CMD_TRANS'=>$comando_transaccion,'BNRG_HORA_LOCAL'=>$hr_local,'BNRG_FECHA_LOCAL'=>$fecha_local,'BNRG_FOLIO'=>$folio,'BNRG_MONTO_TRANS'=>$monto,'BNRG_CODIGO_SEGURIDAD'=>$code_tarjeta,'BNRG_FECHA_EXP'=>$exp_tarjeta,'BNRG_NUMERO_TARJETA'=>$num_tarjeta,'BNRG_URL_RESPUESTA'=>$url_respuesta);
      

        // $response = $this->curl_bnrg($info, 'https://anamarmar.000webhostapp.com/ajax.php');
        $response = $this->curl_bnrg($info, $url);

      echo '<br><br>...   '. $response;



  }


}

?>
