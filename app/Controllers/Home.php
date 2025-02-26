<?php

namespace App\Controllers;

class Home extends BaseController
{

    function __construct()
    {
        helper('functions');
        if (!session('id')) {
            gagal(base_url(), "Kamu belum login!.");
            die;
        }
        if (url() !== 'logout') {
            menu();
        }
    }



    public function index(): string
    {
        $data = csv_to_array();
        $val = [];

        foreach ($data as $k => $i) {
            if ($k > 4 && $k < 164) {
                $exp = explode(";", $i[0]);
                $val[] = ['no' => ((array_key_exists(0, $exp) ? $exp[0] : "")), 'barang' => (array_key_exists(1, $exp) ? $exp[1] : ""), 'qty' => (array_key_exists(2, $exp) ? $exp[2] : ""), 'satuan' => (array_key_exists(3, $exp) ? $exp[3] : ""), 'beli' => (array_key_exists(4, $exp) ? $exp[4] : ""), 'jual' => (array_key_exists(5, $exp) ? $exp[5] : "")];
            }
        }
        $db = db('barangs');
        foreach ($val as $i) {

            $data = [
                'barang' => upper_first($i['barang']),
                'qty' => $i['qty'],
                'satuan' => upper_first($i['satuan']),
                'beli' => (int)str_replace(".", "", $i['beli']),
                'jual' => (int)str_replace(".", "", $i['jual']),
                'updated_at' => time(),
                'petugas' => user()['nama'],
            ];

            $db->insert($data);
        }
        return view('home', ['judul' => "Home"]);
    }

    public function delete()
    {
        $tabel = clear($this->request->getVar('tabel'));
        $id = clear($this->request->getVar('id'));

        $db = db($tabel);
        $q = $db->where('id', $id)->get()->getRowArray();

        if (!$q) {
            gagal_js("Id tidak ditemukan!.");
        }

        $db->where('id', $id);
        if ($db->delete()) {
            sukses_js("Delete data sukses.");
        } else {
            sukses_js("Delete data gagal!.");
        }
    }

    public function logout()
    {
        session()->remove('id');

        sukses(base_url(), 'Logout sukses!.');
    }
}
