<?php

class ModelPaymentBanregio extends Model {

	public function install() {
		$this->db->query("
		INSERT INTO  `oc_setting` (`setting_id` ,`store_id` ,`code` ,`key` ,`value` ,`serialized`)
			VALUES 
			(NULL,'0','banregio','banregio_url_pruebas','https://testhub.banregio.com/adq','0'),
			(NULL,'0','banregio','banregio_url_produccion', 'https://colecto.banregio.com/adq','0');");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `banregio_payment` (
			  `bnrg_id_payment` int(11) NOT NULL AUTO_INCREMENT,
			  `bnrg_codigo_emisor` varchar(15) NOT NULL,
			  `bnrg_mensaje` text NOT NULL,
			  `bnrg_codigo_auth` varchar(16) NOT NULL,
			  `bnrg_monto` int(11) NOT NULL,
			  `bnrg_folio` varchar(16) NOT NULL,
			  `bnrg_codigo_proc` varchar(16) NOT NULL,
			  `bnrg_referencia` varchar(16) NOT NULL,
			  `fecha` text NOT NULL,
			  PRIMARY KEY (`bnrg_id_payment`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
	}

	public function uninstall() {
		//$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		$this->db->query("DROP TABLE IF EXISTS `banregio_payment`;");
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `banregio_payment` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['eway_order_id']);
			return $order;
		} else {
			return false;
		}
	}

	public function addRefundRecord($order, $result) {
		$transaction_id = $result->TransactionID;
		$total_amount = $result->Refund->TotalAmount / 100;
		$refund_amount = $order['refund_amount'] + $total_amount;

		if (isset($order['refund_transaction_id']) && !empty($order['refund_transaction_id'])) {
			$order['refund_transaction_id'] .= ',';
		}
		$order['refund_transaction_id'] .= $transaction_id;

		$this->db->query("UPDATE `eway_order` SET `modified` = NOW(), refund_amount = '" . (double)$refund_amount . "', `refund_transaction_id` = '" . $this->db->escape($order['refund_transaction_id']) . "' WHERE eway_order_id = '" . $order['eway_order_id'] . "'");
	}

	public function capture($order_id, $capture_amount, $currency) {
		$eway_order = $this->getOrder($order_id);

		if ($eway_order && $capture_amount > 0 ) {

			$capture_data = new stdClass();
			$capture_data->Payment = new stdClass();
			$capture_data->Payment->TotalAmount = (int)number_format($capture_amount, 2, '.', '') * 100;
			$capture_data->Payment->CurrencyCode = $currency;
			$capture_data->TransactionID = $eway_order['transaction_id'];

			if ($this->config->get('eway_test')) {
				$url = 'https://api.sandbox.ewaypayments.com/CapturePayment';
			} else {
				$url = 'https://api.ewaypayments.com/CapturePayment';
			}

			$response = $this->sendCurl($url, $capture_data);

			return json_decode($response);

		} else {
			return false;
		}
	}

	public function updateCaptureStatus($eway_order_id, $status) {
		$this->db->query("UPDATE `eway_order` SET `capture_status` = '" . (int)$status . "' WHERE `eway_order_id` = '" . (int)$eway_order_id . "'");
	}

	public function updateTransactionId($eway_order_id, $transaction_id) {
		$this->db->query("UPDATE `eway_order` SET `transaction_id` = '" . $transaction_id . "' WHERE `eway_order_id` = '" . (int)$eway_order_id . "'");
	}

	public function void($order_id) {
		$eway_order = $this->getOrder($order_id);
		if ($eway_order) {

			$data = new stdClass();
			$data->TransactionID = $eway_order['transaction_id'];

			if ($this->config->get('eway_test')) {
				$url = 'https://api.sandbox.ewaypayments.com/CancelAuthorisation';
			} else {
				$url = 'https://api.ewaypayments.com/CancelAuthorisation';
			}

			$response = $this->sendCurl($url, $data);

			return json_decode($response);

		} else {
			return false;
		}
	}

	public function updateVoidStatus($eway_order_id, $status) {
		$this->db->query("UPDATE `eway_order` SET `void_status` = '" . (int)$status . "' WHERE `eway_order_id` = '" . (int)$eway_order_id . "'");
	}

	public function refund($order_id, $refund_amount) {
		$eway_order = $this->getOrder($order_id);

		if ($eway_order && $refund_amount > 0) {

			$refund_data = new stdClass();
			$refund_data->Refund = new stdClass();
			$refund_data->Refund->TotalAmount = (int)number_format($refund_amount, 2, '.', '') * 100;
			$refund_data->Refund->TransactionID = $eway_order['transaction_id'];

			if ($this->config->get('eway_test')) {
				$url = 'https://api.sandbox.ewaypayments.com/Transaction/' . $eway_order['transaction_id'] . '/Refund';
			} else {
				$url = 'https://api.ewaypayments.com/Transaction/' . $eway_order['transaction_id'] . '/Refund';
			}

			$response = $this->sendCurl($url, $refund_data);

			return json_decode($response);
		} else {
			return false;
		}
	}

	public function updateRefundStatus($eway_order_id, $status) {
		$this->db->query("UPDATE `eway_order` SET `refund_status` = '" . (int)$status . "' WHERE `eway_order_id` = '" . (int)$eway_order_id . "'");
	}

	public function sendCurl($url, $data) {
		$ch = curl_init($url);

		$eway_username = html_entity_decode($this->config->get('eway_username'), ENT_QUOTES, 'UTF-8');
		$eway_password = html_entity_decode($this->config->get('eway_password'), ENT_QUOTES, 'UTF-8');

		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_USERPWD, $eway_username . ":" . $eway_password);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

		$response = curl_exec($ch);

		if (curl_errno($ch) != CURLE_OK) {
			$response = new stdClass();
			$response->Errors = "POST Error: " . curl_error($ch) . " URL: $url";
			$response = json_encode($response);
		} else {
			$info = curl_getinfo($ch);
			if ($info['http_code'] == 401 || $info['http_code'] == 404) {
				$response = new stdClass();
				$response->Errors = "Please check the API Key and Password";
				$response = json_encode($response);
			}
		}

		curl_close($ch);

		return $response;
	}

	private function getTransactions($eway_order_id) {
		$qry = $this->db->query("SELECT * FROM `eway_transactions` WHERE `eway_order_id` = '" . (int)$eway_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function addTransaction($eway_order_id, $transactionid, $type, $total, $currency) {
		$this->db->query("INSERT INTO `eway_transactions` SET `eway_order_id` = '" . (int)$eway_order_id . "', `created` = NOW(), `transaction_id` = '" . $this->db->escape($transactionid) . "', `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($total, $currency, false, false) . "'");
	}

	public function getTotalCaptured($eway_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `eway_transactions` WHERE `eway_order_id` = '" . (int)$eway_order_id . "' AND `type` = 'payment' ");

		return (double)$query->row['total'];
	}

	public function getTotalRefunded($eway_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `eway_transactions` WHERE `eway_order_id` = '" . (int)$eway_order_id . "' AND `type` = 'refund'");

		return (double)$query->row['total'];
	}

}