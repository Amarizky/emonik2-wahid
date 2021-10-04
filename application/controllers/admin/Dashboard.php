<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Panel_layout');
		if (check_session('admin') == false) {
			redirect('auth/logout');
			die();
		}
	}

	public function index($unit_q = "")
	{
		$data['title'] = "Dashboard";
		$data['menu'] = "Dashboard";
		$data['sub_menu'] = "emonik";
		$data['sub_title'] = "emonik";
		$data['icon'] = 'icon-home2';
		$data['action'] = site_url(current_group());

		//ambil data dari api (apibahanbaku)
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://kahftekno.com/rest-emonikv2/index.php/apibahanbaku");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'emonik-api-key: restapiemonik'
		]);

		$server_output = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($server_output, true);

		$bahan = [];

		foreach ($response['data'] as $key) {
			@$bahan[$key['kode_material']] += $key['qty'];
		}

		$i = 1;
		foreach ($bahan as $k => $v) {
			$data['chart1']['labels'][] = ucwords($k);
			$data['chart1']['bLabels'][] = ucwords($k);
			$data['chart1']['data'][] = ucwords($v);
			$data['chart1']['bLinks'][] = $k;
			$i++;
		}


		//ambil data dari api (apiproduksi)
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://kahftekno.com/rest-emonikv2/index.php/apiproduksi");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'emonik-api-key: restapiemonik'
		]);

		$server_output = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($server_output, true);

		foreach ($response['data'] as $k => $v) {
			if (!is_null($v['kode_produk'])) {
				$data['chart2']['labels'][] = $v['kode_produk'];
				$data['chart2']['data'][] = $v['qty'];
			}
		}

		$data['chart2']['bLabels'] = $data['chart2']['labels'];
		$data['chart2']['labels'] = '"' . implode('","', $data['chart2']['labels']) . '"';
		$data['chart2']['data'] = implode(',', $data['chart2']['data']);

		// $data['total_emp'] = $this->crud->get_where('employees', 'employeeid', ['company_code' => current_ses('company_code')])->num_rows();
		$this->panel_layout->load('layout/panel/v_layout', 'pages/admin/v_dash', $data);
	}
}