<?php
defined('BASEPATH') or exit('No direct script access allowed');

class produk extends CI_Controller
{

	public $rolename = "user";
	public $icon = "icon-users";
	public $menu = "produk";
	public $menu_alias = "produk";

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Panel_layout');
		if (check_session('user') == false) {
			redirect('auth/logout');
			die();
		}
	}

	public function index($page = 'List')
	{
		$data['title'] = "Produk Mitra ";
		$data['sub_title'] = 'All Data';
		$data['menu'] = "Produk Mitra";
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
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiproduksi');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data['datatable'] = json_decode(curl_exec($ch))->data;

		curl_close($ch);

		// $data['datatable'] = $this->crud->get('users', '*')->result();

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
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiproduksi');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POSTFIELDS, [
			'id_produksi' => $kode
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = json_decode(curl_exec($ch))->data;
		$data['datatable'] = $result;

		foreach ($result as $row) {
			if ($row->id_produksi === $kode) {
				$data['row'] = $row;
				break;
			}
		}

		curl_close($ch);


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
			'id' => $kode,
			'kode_mitra' => $input['kode_mitra'],
			'kode_produk' => $input['kode_produk'],
			'qty' => $input['qty'],
			'waktu_produksi' => $input['waktu_produksi'],
			'no_po' => $input['no_po'],
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiproduksi');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		$update = json_decode(curl_exec($ch))->status;

		curl_close($ch);

		if ($update) {
			$response = array(
				'status' => 1,
				'message' => 'Perubahan data berhasil disimpan',
				'return_url' => $input['submit'] == 'submit' ? '#edit' : site_url(current_role() . '/' . $this->menu_alias),
				'csrf' => $csrf
			);
		} else {
			$response = array(
				'status' => 0,
				'message' => 'Perubahan data gagal disimpan!. Silahkan diulang kembali',
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
			'kode_mitra' => $input['kode_mitra'],
			'kode_produk' => $input['kode_produk'],
			'qty' => $input['qty'],
			'waktu_produksi' => $input['waktu_produksi'],
			'no_po' => @$input['no_po'] ?? null,
		];

		// cek kode_produk udah ada belon 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiproduksi');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = json_decode(curl_exec($ch))->data;

		foreach ($result as $row) {
			if ($row->no_po == $data['no_po']) {
				$response = array(
					'status' => 0,
					'message' => 'Kode produk sudah terdaftar',
					'return_url' => '#',
					'csrf' => $csrf
				);
				exit(json_encode($response));
			}
		}

		curl_close($ch);

		// insert data
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apiproduksi');
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
