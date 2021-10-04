<?php

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

defined('BASEPATH') or exit('No direct script access allowed');

class produk extends CI_Controller
{

	public $rolename = "admin";
	public $icon = "icon-users";
	public $menu = "Produk";
	public $menu_alias = "produk";

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

		$data['action_form'] = site_url(current_role() . "/" . $this->menu_alias . "/add_process");
		$data['html_form'] = $this->load->view('pages/' . $this->rolename . '/' . $this->menu_alias . '/v_form', $data, TRUE);
		$data['html_add_new'] = $this->load->view('pages/' . $this->rolename . '/' . $this->menu_alias . '/v_add', $data, TRUE);

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

		foreach ($result as $row) {
			if ($row->kode_produk === $kode) {
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
			'id_produksi' => $input['id_produksi'],
			'kode_produk' => $input['kode_produk'],
			'kode_mitra' => $input['kode_mitra'],
			'qty' => $input['qty'],
			'waktu_produksi' => $input['waktu_produksi'],
			'no_po' => $input['no_po']
		];

		if (!empty($input['is_reset'])) {
			$data['password'] = password_hash(DEFAULT_PASS, PASSWORD_BCRYPT);
		}

		//check apakah upload avatar?
		if (isset($_FILES['avatar']['name']) && !empty($_FILES['avatar']['name'])) {
			$config['upload_path']          =  FCPATH . 'assets/avatar/';
			$config['allowed_types']        =  'jpg|jpeg|png|JPG|JPEG|PNG';
			$config['encrypt_name']         =  TRUE;
			$config['file_ext_tolower']     =  TRUE;
			$config['detect_mime']			=  TRUE;

			$this->upload->initialize($config);
			if ($this->upload->do_upload('avatar')) {
				$file_data = $this->upload->data();
				// Resize Image
				if ($file_data['file_size'] > 1024) {
					resize_avatar('', $file_data['file_name']);
				}
				$file_name = $file_data['file_name'];
				$data['avatar'] = $file_name;
			} else {
				$response = array(
					'status' => 0,
					'message' => 'Error upload. Detail: ' . $this->upload->display_errors(),
					'return_url' => '#',
					'csrf' => $csrf
				);
				echo json_encode($response);
				die();
			}
		}

		$update = $this->crud->update('users', $data, ['id' => $kode]);
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
			'id_produksi' => $input['id_produksi'],
			'kode_produk' => $input['kode_produk'],
			'kode_mitra' => $input['kode_mitra'],
			'qty' => $input['qty'],
			'waktu_produksi' => $input['waktu_produksi'],
			'no_po' => $input['no_po']
		];

		// cek kode_produk udah ada belon 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apikomposisiproduksi');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'emonik-api-key: restapiemonik'
		));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = json_decode(curl_exec($ch))->data;

		foreach ($result as $row) {
			if ($row->kode_produk === $data['kode_produk']) {
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
		curl_setopt($ch, CURLOPT_URL, 'https://kahftekno.com/rest-emonikv2/index.php/apikomposisiproduksi');
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

	function details($label)
	{
		$data['title'] = $this->menu;
		$data['sub_title'] = 'Data';
		$data['menu'] = $this->menu;
		$data['menu_alias'] = $this->menu_alias;
		$data['desc_menu'] = "";
		$data['sub_menu'] = "Detail";
		$data['icon'] = $this->icon;
		$data['label'] = $label;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://kahftekno.com/rest-emonikv2/index.php/apibahanbaku/");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'emonik-api-key: restapiemonik'
		]);

		$server_output = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($server_output, true);
		$label = str_replace('%20', ' ', $label);

		$data['kode_material'] = $response['data'][0]['kode_material'];
		foreach ($response['data'] as $key) {
			$data['kode_mitra'][] = $key['kode_mitra'];
			$data['qty'][] = $key['qty'];
			$data['supplier'][] = $key['supplier'];
		}

		$this->panel_layout->load('layout/panel/v_layout', 'pages/' . $this->rolename . '/' . $this->menu_alias . '/v_detail_bahanbaku', $data);
	}

	function detailproduksi($label)
	{
		$data['title'] = $this->menu;
		$data['sub_title'] = 'Data';
		$data['menu'] = $this->menu;
		$data['menu_alias'] = $this->menu_alias;
		$data['desc_menu'] = "";
		$data['sub_menu'] = "Detail";
		$data['icon'] = $this->icon;
		$data['label'] = ucwords($label);

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

		foreach ($response['data'] as $key) {
			if ($key['kode_produk'] == $label) {
				$data['kode_mitra'] = $key['kode_mitra'];
				$data['qty'] = $key['qty'];
				break;
			}
		}

		$this->panel_layout->load('layout/panel/v_layout', 'pages/' . $this->rolename . '/' . $this->menu_alias . '/v_detail_produksi', $data);
	}
}