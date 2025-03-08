<?php

namespace App\Controllers;

class Barang extends BaseController
{
    function __construct()
    {
        helper('functions');
        if (!session('id')) {
            gagal(base_url(), "Kamu belum login!.");
            die;
        }
        menu();
    }


    public function index(): string
    {

        $db = db(menu()['tabel']);

        $data = $db->orderBy('updated_at', 'DESC')->get()->getResultArray();
        return view(menu()['controller'], ['judul' => menu()['menu'], 'data' => $data]);
    }

    public function add()
    {
        $kode = clear(strtoupper($this->request->getVar('kode')));
        $barang = clear(upper_first($this->request->getVar('barang')));
        $satuan = clear(upper_first($this->request->getVar('satuan')));
        $qty = (int)clear(str_replace(".", "", $this->request->getVar('qty')));
        $beli = (int)clear(str_replace(".", "", $this->request->getVar('beli')));
        $jual = (int)clear(str_replace(".", "", $this->request->getVar('jual')));

        $db = db(menu()['tabel']);
        if ($db->where('barang', $barang)->get()->getRowArray()) {
            gagal(base_url(menu()['controller']), "Barang sudah ada!.");
        }


        $data = [
            'kode' => $kode,
            'barang' => $barang,
            'qty' => $qty,
            'satuan' => $satuan,
            'beli' => $beli,
            'jual' => $jual,
            'tgl' => time(),
            'updated_at' => time(),
            'petugas' => user()['nama']
        ];

        if ($db->insert($data)) {
            sukses(base_url(menu()['controller']), "Tambah data berhasil.");
        } else {
            gagal(base_url(menu()['controller']), "Tambah data gagal!.");
        }
    }
    public function update()
    {
        $id = clear($this->request->getVar('id'));
        $kode = clear(strtoupper($this->request->getVar('kode')));
        $barang = clear(upper_first($this->request->getVar('barang')));
        $satuan = clear(upper_first($this->request->getVar('satuan')));
        $beli = (int)clear(str_replace(".", "", $this->request->getVar('beli')));
        $qty = (int)clear(str_replace(".", "", $this->request->getVar('qty')));
        $jual = (int)clear(str_replace(".", "", $this->request->getVar('jual')));

        $db = db(menu()['tabel']);
        $q = $db->where('id', $id)->get()->getRowArray();

        if (!$q) {
            gagal(base_url(menu()['controller']), "Id tidak ditemukan!.");
        }

        if ($db->whereNotIn('id', [$id])->where('barang', $barang)->get()->getRowArray()) {
            gagal(base_url(menu()['controller']), "Barang sudah ada!.");
        }


        $q['kode'] = $kode;
        $q['barang'] = $barang;
        $q['qty'] = $qty;
        $q['satuan'] = $satuan;
        $q['beli'] = $beli;
        $q['jual'] = $jual;
        $q['updated_at'] = time();
        $q['petugas'] = user()['nama'];

        $db->where('id', $id);
        if ($db->update($q)) {
            sukses(base_url(menu()['controller']), "Update data berhasil.");
        } else {
            gagal(base_url(menu()['controller']), "Update data gagal!.");
        }
    }
    public function update_td()
    {
        $id = clear($this->request->getVar('id'));
        $col = clear($this->request->getVar('col'));
        $val = clear(upper_first($this->request->getVar('val')));

        if ($col !== "barang") {
            $val = (int)clear(str_replace(".", "", $this->request->getVar('val')));
        }

        $db = db(menu()['tabel']);
        $q = $db->where('id', $id)->get()->getRowArray();

        if (!$q) {
            gagal(base_url(menu()['controller']), "Id tidak ditemukan!.");
        }


        $q[$col] = $val;
        $q['updated_at'] = time();
        $q['petugas'] = user()['nama'];


        $db->where('id', $id);
        if ($db->update($q)) {
            sukses_js("Update data berhasil.");
        } else {
            gagal_js("Update data gagal!.");
        }
    }
}
