<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{

	public $rolename = "admin";
	public $icon = "icon-users";
	public $menu = "Order";
	public $menu_alias = "Order";

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Panel_layout');
		if (check_session('admin') == false) {
			redirect('auth/logout');
			die();
		}
	}

	public function index($page = 'List')
	{
		$data['title'] = "Data Order";
		$data['sub_title'] = 'All Data';
		$data['menu'] = "Data Order";
		$data['menu_alias'] = $this->menu_alias;
		$data['desc_menu'] = "";
		$data['sub_menu'] = $page;
		$data['icon'] = $this->icon;
		//csrf init
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$data['csrf'] = $csrf;

		// request data ke api
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiorder');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data['datatable'] = json_decode(curl_exec($ch))->data;

		curl_close($ch);

		// request data ke api user
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apimastermitra');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data['datamitra'] = json_decode(curl_exec($ch))->data;

		curl_close($ch);

		foreach ($data['datamitra'] as $m) {
			$data['mitras'][] = $m->nama_mitra;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiproduk');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data['dproduk'] = json_decode(curl_exec($ch))->data;

		curl_close($ch);

		foreach ($data['dproduk'] as $m) {
			$data['produks'][] = $m->nama_produk;
		}

		$data['action_form'] = site_url(current_role() . "/" . $this->menu_alias . "/add_process");
		$data['html_form'] = $this->load->view('pages/' . $this->rolename . '/' . $this->menu_alias . '/v_form', $data, TRUE);
		$data['html_add_new'] = $this->load->view('pages/' . $this->rolename . '/' . $this->menu_alias . '/v_add', $data, TRUE);

		$this->panel_layout->load('layout/panel/v_layout', 'pages/' . $this->rolename . '/' . $this->menu_alias . '/v_index', $data);
	}

	public function edit($kode = null)
	{
		if ($kode == null) {
			redirect(site_url(current_role() . '/' . $this->menu_alias));
		}

		$data['title'] = $this->menu;
		$data['sub_title'] = 'Data';
		$data['menu'] = $this->menu;
		$data['menu_alias'] = $this->menu_alias;
		$data['desc_menu'] = "";
		$data['sub_menu'] = "Edit";
		$data['icon'] = $this->icon;

		// $data['row'] = $this->crud->get_where('users', '*', ['id' => $id])->row();

		// request data ke api
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiorder');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, [
			'no_po' => $kode
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = json_decode(curl_exec($ch))->data;

		foreach ($result as $row) {
			if ($row->no_po === $kode) {
				$data['row'] = $row;
				break;
			}
		}

		curl_close($ch);

		// request data ke api user
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiuser');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data['datamitra'] = json_decode(curl_exec($ch))->data;

		curl_close($ch);

		foreach ($data['datamitra'] as $m) {
			$data['mitras'][] = $m->username;
		}


		//csrf init
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$data['csrf'] = $csrf;

		$data['action_form'] = site_url(current_role() . '/' . $this->menu_alias . "/edit_process/" . $kode);
		$data['html_form'] = $this->load->view('pages/' . $this->rolename . '/' . $this->menu_alias . '/v_form', $data, TRUE);

		$this->panel_layout->load('layout/panel/v_layout', 'pages/' . $this->rolename . '/' . $this->menu_alias . '/v_edit', $data);
	}

	public function edit_process($kode = null)
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		//csrf init
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$data['csrf'] = $csrf;

		if ($kode == NULL) {
			$response = array(
				'status' => 0,
				'message' => 'Edit gagal. Data tidak ditemukan ',
				'return_url' => '#',
				'csrf' => $csrf
			);
			echo json_encode($response);
			die();
		}

		// get input from user
		$input = $this->input->post(null, true);

		$data = [
			'no_po' => $input['no_po'],
			'kode_mitra' => $input['kode_mitra'],
			'kode_produk' => $input['kode_produk'],	
			'qty' => $input['qty'],
			'document_deadtime' => $input['document_deadtime'],
			'deadline' => $input['deadline']
		];

		// request data ke api user
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiorder');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		$update = json_decode(curl_exec($ch));

		curl_close($ch);

		if ($update->status == true) {
			$response = array(
				'status' => 1,
				'message' => 'Perubahan data berhasil disimpan',
				'return_url' => $input['submit'] == 'submit' ? '#edit' : site_url(current_role() . '/' . $this->menu_alias),
				'csrf' => $csrf
			);
		} else {
			$response = array(
				'status' => 0,
				// 'message' => 'Perubahan data gagal disimpan!. Silahkan diulang kembali',
				'message' => ucfirst($update->msg),
				'return_url' => '#',
				'csrf' => $csrf
			);
		}
		echo json_encode($response);
	}

	public function add_process()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		//csrf init
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$data['csrf'] = $csrf;

		// get input from user
		$input = $this->input->post(null, true);

		$data = [
			'no_po' => $input['no_po'],
			'kode_mitra' => $input['kode_mitra'],
			'kode_produk' => $input['kode_produk'],	
			'qty' => $input['qty'],
			'document_deadtime' => $input['document_deadtime'],
			'deadline' => $input['deadline']
		];

		// cek kode_po udah ada belon 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiorder');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = json_decode(curl_exec($ch))->data;

		foreach ($result as $row) {
			if ($row->no_po === $data['no_po']) {
				$response = array(
					'status' => 0,
					'message' => 'Kode po sudah terdaftar',
					'return_url' => '#',
					'csrf' => $csrf
				);
				exit(json_encode($response));
			}
		}

		curl_close($ch);

		// insert data
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiorder');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$insert = json_decode(curl_exec($ch))->status;

		curl_close($ch);

		// $insert = $this->db->insert('users', $data);

		if ($insert) {
			$response = array(
				'status' => 1,
				'message' => 'Data baru berhasil disimpan',
				'return_url' => $input['submit'] == 'submit' ? site_url(current_role() . '/' . $this->menu_alias . '/index/Add') : site_url(current_role() . '/' . $this->menu_alias),
				'csrf' => $csrf
			);
		} else {
			$response = array(
				'status' => 0,
				'message' => 'Data baru gagal disimpan!. Silahkan diulang kembali',
				'return_url' => '#',
				'csrf' => $csrf
			);
		}
		echo json_encode($response);
	}
}