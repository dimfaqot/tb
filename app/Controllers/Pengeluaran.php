<?php

namespace App\Controllers;

class Pengeluaran extends BaseController
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

        $q = $db->orderBy('tgl', 'DESC')->get()->getResultArray();
        $data = [];

        foreach ($q as $i) {
            if (date('m', $i['tgl']) == date('m') && date('Y', $i['tgl']) == date('Y')) {
                $data[] = $i;
            }
        }
        return view(menu()['controller'], ['judul' => menu()['menu'], 'data' => $q]);
    }

    public function inventaris(): string
    {
        $db = db(menu()['tabel']);

        $q = $db->where('kategori', "Inv")->orderBy('tgl', 'DESC')->get()->getResultArray();
        $data = [];

        foreach ($q as $i) {
            if (date('m', $i['tgl']) == date('m') && date('Y', $i['tgl']) == date('Y')) {
                $data[] = $i;
            }
        }

        return view(menu()['controller'], ['judul' => menu()['menu'], 'data' => $q]);
    }

    public function add()
    {
        $penjual = clear(upper_first($this->request->getVar('penjual')));
        $barang = clear(upper_first($this->request->getVar('barang')));
        $qty = (int)clear(str_replace(".", "", $this->request->getVar('qty')));
        $harga = (int)clear(str_replace(".", "", $this->request->getVar('harga')));
        $harga2 = ($qty > 0 ? $harga * $qty : $harga);
        $diskon = (int)clear(str_replace(".", "", $this->request->getVar('diskon')));

        $total = $harga2 - $diskon;

        $db = db(menu()['tabel']);

        $data = [
            'kategori' => "Inv",
            'penjual' => $penjual,
            'barang' => $barang,
            'harga' => $harga,
            'qty' => $qty,
            'diskon' => $diskon,
            'total' => $total,
            'tgl' => time(),
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
        $penjual = clear(upper_first($this->request->getVar('penjual')));
        $barang = clear(upper_first($this->request->getVar('barang')));
        $qty = (int)clear(str_replace(".", "", $this->request->getVar('qty')));
        $harga = (int)clear(str_replace(".", "", $this->request->getVar('harga')));
        $harga2 = ($qty > 0 ? $harga * $qty : $harga);
        $diskon = (int)clear(str_replace(".", "", $this->request->getVar('diskon')));

        $total = $harga2 - $diskon;

        $db = db(menu()['tabel']);
        $q = $db->where('id', $id)->get()->getRowArray();

        if (!$q) {
            gagal(base_url(menu()['controller']), "Id tidak ditemukan!.");
        }

        $q['penjual'] = $penjual;
        $q['barang'] = $barang;
        $q['harga'] = $harga;
        $q['qty'] = $qty;
        $q['diskon'] = $diskon;
        $q['total'] = $total;
        $q['petugas'] = user()['nama'];

        $db->where('id', $id);
        if ($db->update($q)) {
            sukses(base_url(menu()['controller']), "Update data berhasil.");
        } else {
            gagal(base_url(menu()['controller']), "Update data gagal!.");
        }
    }

    public function cari_barang()
    {
        $value = clear($this->request->getVar('value'));
        $db = db('barang');
        $q = $db->like('barang', $value, 'both')->orderBy('barang', 'ASC')->limit(8)->get()->getResultArray();

        sukses_js("Sukses", $q);
    }

    public function transaksi()
    {
        $data = json_decode(json_encode($this->request->getVar('daftar_transaksi')), true);
        $uang_pembayaran = (int)clear($this->request->getVar('uang_pembayaran'));
        $nama_penjual = upper_first(clear($this->request->getVar('nama_penjual')));

        $total = 0;
        foreach ($data as $i) {
            $total += (int)$i['total'];
        }

        if ($uang_pembayaran < $total) {
            gagal_js("Uang kurang!.");
        }

        $db = db('pengeluaran');
        $dbb = db('barang');

        $total_2 = 0;
        $err = [];
        foreach ($data as $i) {
            $data = [
                'tgl' => time(),
                'penjual' => $nama_penjual,
                'petugas' => user()['nama'],
                'barang' => $i['barang'],
                'qty' => $i['qty'],
                'diskon' => $i['diskon'],
                'harga' => $i['harga'],
                'total' => $i['total']
            ];


            if ($db->insert($data)) {
                $total_2 += $i['total'];

                $barang = $dbb->where('id', $i['id'])->get()->getRowArray();

                if ($barang) {
                    $barang['qty'] += (int)$i['qty'];
                    $dbb->where('id', $barang['id']);
                    $dbb->update($barang);
                }
            } else {
                $err[] = $i['barang'];
            }
        }

        sukses_js("Sukses", $uang_pembayaran - $total_2, implode(", ", $err));
    }
}
