<?php
class ModelPaymentbanregio extends Model {
  public function getMethod($address, $total) {
    $this->load->language('payment/banregio');

    $method_data = array(
      'code'     => 'banregio',
      'title'    => $this->language->get('text_title'),
      'terms'      => '',
      'sort_order' => $this->config->get('banregio_sort_order'),
      'banregio_Afiliacion' => $this->config->get('banregio_Afiliacion'),
      'banregio_ID_Medio' => $this->config->get('banregio_ID_Medio')
    );

    return $method_data;
  }
}
