<?php
class ControllerPaymentBanregio extends Controller {

	private $error = array();

  public function index() {
    $this->language->load('payment/banregio');
    $this->document->setTitle('Banregio: Configuraciones');
    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $_POST['banregio_url_pruebas'] = 'https://testhub.banregio.com/adq';
      $_POST['banregio_url_produccion'] = 'https://colecto.banregio.com/adq';
      $this->model_setting_setting->editSetting('banregio', $this->request->post);
      $this->session->data['success'] = 'Saved.';
      $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');
    $data['entry_banregio_text_config_one'] = $this->language->get('banregio_text_config_one');
    $data['entry_banregio_text_config_two'] = $this->language->get('banregio_text_config_two');
    $data['modo_operativo'] = $this->language->get('modo_operativo');
    $data['button_save'] = $this->language->get('text_button_save');
    $data['button_cancel'] = $this->language->get('text_button_cancel');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['entry_status'] = $this->language->get('entry_status');

    $data['action'] = $this->url->link('payment/banregio', 'token=' . $this->session->data['token'], 'SSL');
    $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');


    if (isset($this->request->post['banregio_Afiliacion'])) {
      $data['banregio_Afiliacion'] = $this->request->post['banregio_Afiliacion'];
    } else {
      $data['banregio_Afiliacion'] = $this->config->get('banregio_Afiliacion');
    }

    if (isset($this->request->post['banregio_ID_Medio'])) {
      $data['banregio_ID_Medio'] = $this->request->post['banregio_ID_Medio'];
    } else {
      $data['banregio_ID_Medio'] = $this->config->get('banregio_ID_Medio');
    }

    if (isset($this->request->post['banregio_modo_operativo'])) {
      $data['banregio_modo_operativo'] = $this->request->post['banregio_modo_operativo'];
    } else {
      $data['banregio_modo_operativo'] = $this->config->get('banregio_modo_operativo');
    }
    if (isset($this->request->post['banregio_url_pruebas'])) {
      $data['banregio_url_pruebas'] = $this->request->post['banregio_url_pruebas'];
    } else {
      $data['banregio_url_pruebas'] = $this->config->get('banregio_url_pruebas');
    }
    if (isset($this->request->post['banregio_url_produccion'])) {
      $data['banregio_url_produccion'] = $this->request->post['banregio_url_produccion'];
    } else {
      $data['banregio_url_produccion'] = $this->config->get('banregio_url_produccion');
    }

    if (isset($this->request->post['banregio_status'])) {
      $data['banregio_status'] = $this->request->post['banregio_status'];
    } else {
      $data['banregio_status'] = $this->config->get('banregio_status');
    }

    if (isset($this->request->post['banregio_order_status_id'])) {
      $data['banregio_order_status_id'] = $this->request->post['banregio_order_status_id'];
    } else {
      $data['banregio_order_status_id'] = $this->config->get('banregio_order_status_id');
    }

	$data['breadcrumbs'] = array();

	$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_home'),
		'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
	);

	$data['breadcrumbs'][] = array(
		'text' => $this->language->get('text_payment'),
		'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
	);


    $this->load->model('localisation/order_status');
    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


    $this->response->setOutput($this->load->view('payment/banregio', $data));
  }

  public function install() {
    //$this->load->model('payment/banregio');
   // $this->model_payment_banregio->install();
  }

}
