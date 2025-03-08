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
        // $data = csv_to_array('penjualan.csv');
        // $val = [];

        // foreach ($data as $k => $i) {
        //     if ($k > 4 && $k < 164) {
        //         $exp = explode(";", $i[0]);
        //         $val[] = ['no' => ((array_key_exists(0, $exp) ? $exp[0] : "")), 'barang' => (array_key_exists(1, $exp) ? $exp[1] : ""), 'qty' => (array_key_exists(4, $exp) ? $exp[4] : ""), 'satuan' => (array_key_exists(3, $exp) ? $exp[3] : ""),  'harga' => (array_key_exists(5, $exp) ? str_replace(".", "", $exp[5]) : 0)];
        //         // $val[] = $i;
        //     }
        // }

        // dd($val);
        $db = db('penjualan');
        // $dbb = db('barang');
        // $q = $db->orderBy('barang', 'ASC')->get()->getResultArray();

        // foreach ($val as $i) {
        //     $i['harga'] = (int)$i['harga'];
        //     $i['qty'] = (int)$i['qty'];
        //     // $d = $db->where("barang", $i['barang'])->get()->getResultArray();
        //     // $qty = 0;

        //     // foreach ($d as $i) {
        //     //     $qty += $i['qty'];
        //     // }
        //     if ($i['qty'] > 0) {
        //         // $q = $dbb->where("barang", $i['barang'])->get()->getRowArray();
        //         // $q['qty'] = $q['qty'] - $i['keluar'];

        //         // $dbb->where('id', $q['id']);

        //         // if($dbb->update($q)){
        //         // $time = strtotime("31 May 2024");
        //         // $data = [
        //         //     'barang' => upper_first($i['barang']),
        //         //     'qty' => $i['qty'],
        //         //     'no_nota' => no_nota($time),
        //         //     'satuan' => upper_first($i['satuan']),
        //         //     'harga' => $i['harga'] / $i['qty'],
        //         //     'total' => $i['harga'] * $i['qty'],
        //         //     'tgl' => $time,
        //         //     // 'updated_at' => time(),
        //         //     'petugas' => user()['nama'],
        //         // ];
        //         // $db->insert($data);

        //         // }
        //     }
        // }
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

    public function get_laporan()
    {
        $tahun = clear($this->request->getVar('tahun'));

        $db_masuk = db("penjualan");
        $db_keluar = db("pengeluaran");

        $masuk = $db_masuk->get()->getResultArray();
        $keluar = $db_keluar->get()->getResultArray();

        $data_masuk = [];
        $data_keluar = [];


        // mencari tahun pemasukan
        foreach ($masuk as $i) {
            if ($tahun == 'All') {
                $data_masuk[] = $i;
            } else {
                if ($tahun == date('Y', $i['tgl'])) {
                    $data_masuk[] = $i;
                }
            }
        }

        // mencari tahun pengeluaran
        foreach ($keluar as $i) {
            if ($tahun == 'All') {
                $data_keluar[] = $i;
            } else {
                if ($tahun == date('Y', $i['tgl'])) {
                    $data_keluar[] = $i;
                }
            }
        }

        // mencari bulan

        $detail_masuk = [];

        // masuk
        foreach (bulan() as $b) {
            $temp = [];
            $total = 0;
            foreach ($data_masuk as $i) {
                $i['tanggal'] = date('d/m/Y', $i['tgl']);
                if ($b['angka'] == date('m', $i['tgl'])) {
                    $temp[] = $i;
                    $total +=  $i['total'];
                }
            }
            $detail_masuk[] = ['bulan' => $b['satuan'], 'bln' => $b['bulan'], 'data' => $temp, 'total' => $total];
        }

        $detail_keluar = [];
        // keluar
        foreach (bulan() as $b) {
            $temp = [];
            $total = 0;
            foreach ($data_keluar as $i) {
                $i['tanggal'] = date('d/m/Y', $i['tgl']);
                if ($b['angka'] == date('m', $i['tgl'])) {
                    $temp[] = $i;
                    $total +=  $i['harga'];
                }
            }
            $detail_keluar[] = ['bulan' => $b['satuan'], 'bln' => $b['bulan'], 'data' => $temp, 'total' => $total];
        }

        sukses_js('Connection success.', $detail_masuk, $detail_keluar);
    }
}
