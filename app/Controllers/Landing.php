<?php

namespace App\Controllers;

class Landing extends BaseController
{
    function __construct()
    {
        if (session('id')) {
            header("Location: " . base_url('home'));
            die;
        }
    }
    public function index(): string
    {
        return view('landing', ['judul' => "Landing"]);
    }

    //http://localhost:8080/auth/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MX0.2xnEwWnhfHBRO0_BPAhrRz0SEyE1erLGFFAJ9w-HQcs
    public function auth($jwt)
    {
        $decode = decode_jwt($jwt);
        $db = db('user');

        $q = $db->where('id', $decode['id'])->get()->getRowArray();

        if (!$q) {
            gagal(base_url(), "Id tidak ditemukan!.");
        }

        $data = [
            'id' => $decode['id']
        ];

        session()->set($data);
        sukses(base_url('home'), 'Login sukses.');
    }
}
