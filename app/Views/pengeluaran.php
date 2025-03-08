<?= $this->extend('templates/logged') ?>

<?= $this->section('content') ?>

<h6><i class="<?= menu()['icon']; ?>"></i> <?= strtoupper(menu()['menu']); ?></h6>
<button class="btn btn-sm btn-light my-3 transaksi"><i class="fa-solid fa-cash-register"></i> TRANSAKSI</b></button>
<button class="btn btn-sm btn-success my-3 body_total"></button>

<?php if (count($data) == 0): ?>
    <div style="font-size:small;"><span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i></span> DATA TIDAK DITEMUKAN!.</div>
<?php else: ?>
    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text bg-secondary border border-light">Cari Data</span>
        <input type="text" class="form-control cari bg-secondary border border-light text-light" placeholder="....">
    </div>
    <table class="table table-sm table-dark table-bordered" style="font-size: 14px;">
        <thead>
            <tr>
                <th>#</th>
                <th>Tgl</th>
                <th>Barang</th>
                <th>Total</th>
                <th>Act</th>
            </tr>
        </thead>
        <tbody class="tabel_search">
            <?php $total = 0; ?>
            <?php foreach ($data as $k => $i): ?>
                <?php $total += $i['total']; ?>
                <tr>
                    <td><?= $k + 1; ?></td>
                    <td><?= date('d/m/Y', $i['tgl']); ?></td>
                    <td><?= $i['barang']; ?></td>
                    <td class="text-end"><?= angka($i['total']); ?></td>
                    <td><a data-id="<?= $i['id']; ?>" href="" class="text-secondary btn_detail"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<script>
    $(".body_total").text("<?= angka($total); ?>");
    let data = <?= json_encode($data); ?>;
    let data_selected = {};

    $(document).on("click", ".btn_detail", function(e) {
        e.preventDefault();
        let id = $(this).data("id");

        let val = [];

        data.forEach(e => {
            if (e.id == id) {
                val = e;
                stop();
            }
        });
        const popup1 = new Modal("button");
        let html = `<div class="container">
                        <div class="mb-3">
                            <label style="font-size: 12px;">Tgl</label>
                            <input type="text" value="${time_php_to_js(val.tgl)}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Penjual</label>
                            <input type="text" value="${(val.penjual==''?'-':val.penjual)}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Barang</label>
                            <input type="text" value="${val.barang}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Qty</label>
                            <input type="text" value="${angka(val.qty)}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Harga</label>
                            <input type="text" value="${angka(val.harga)}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Diskon</label>
                            <input type="text" value="${angka(val.diskon)}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Total</label>
                            <input type="text" value="${angka(val.total)}" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="mb-3">
                            <label style="font-size: 12px;">Petugas</label>
                            <input type="text" value="${val.petugas}" class="form-control form-control-sm" readonly>
                        </div>
                    </div>`;

        popup1.html(html);
    })


    $(document).on("click", ".transaksi", function(e) {
        e.preventDefault();
        const popup1 = new Modal("button");


        let html = `<div class="container">
                        <table id="penjualan" class="table table-sm table-dark table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th class="text-center">Barang</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Diskon</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Act</th>
                                </tr>
                            </thead>
                            <tbody class="isi_tabel">
                                <tr>
                                    <td style="vertical-align:middle" data-colspan="3" data-empty_cols="0" class="cari_barang add_barang" contenteditable="true"></td>
                                    <td style="vertical-align:middle" class="text-end add_harga angka_text" contenteditable="true">0</td>
                                    <td style="vertical-align:middle" class="text-end add_qty angka_text" contenteditable="true">0</td>
                                    <td style="vertical-align:middle" class="text-end add_diskon angka_text" contenteditable="true">0</td>
                                    <td style="vertical-align:middle" class="text-end add_total">0</td>
                                    <td style="vertical-align:middle" class="text-center"><a class="add_transaksi fw-bold fs-5 text-success" href="">+</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="daftar_transaksi" class="mt-2"></div>
                    </div>`

        popupButton.html(html);

        setTimeout(() => {
            $('#fullscreen').on('shown.bs.modal', function() {
                let input = $('.cari_barang');
                input.focus();
            });
        }, 200);
    })


    $(document).on('keyup', '.cari_barang', function(e) {
        e.preventDefault();
        let value = $(this).text();
        let colspan = $(this).data("colspan");
        let empty_cols = parseInt($(this).data("empty_cols"));

        $(".remove_all").remove();

        post("penjualan/cari_barang", {
            value
        }).then(res => {
            if (res.status == "200") {
                $(".remove_all").remove();
                let html = '';

                for (let i = 0; i < empty_cols; i++) {
                    html += '<td class="remove_all"></td>';
                }
                if (res.data.length == 0) {
                    html += '<td class="remove_all" colspan="' + colspan + '"><i class="fa-solid fa-triangle-exclamation"></i> Data tidak ditemukan</td>';
                } else {
                    html += '<td class="remove_all" colspan="' + colspan + '">';
                    html += '<div class="bg-light text-secondary search_list" style="position: absolute;">';
                    res.data.forEach((e, i) => {
                        html += '<div data-id="' + e.id + '" data-satuan="' + e.satuan + '" data-qty="' + e.qty + '" data-barang="' + e.barang + '" data-jual="' + e.jual + '" class="p-1 border-bottom border-secondary select_barang" style="cursor: pointer;">' + e.barang + ' || ' + angka(e.qty) + '</div>';
                    })
                    html += '</div>';
                    html += '</td>';

                }
                $("#penjualan tr:last").after(html);

            } else {
                popup_confirm.message(res.status, res.message);
            }
        })


    });
    $(document).on('click', '.select_barang', function(e) {
        e.preventDefault();
        let barang = $(this).data("barang");
        let id = $(this).data("id");

        // data_selected['id'] = id;
        data_selected['id'] = id;

        $("#penjualan tr:last td:first").text(barang);
        $(".search_list").remove();


    });

    $(document).on('keyup', '.add_qty', function(e) {
        e.preventDefault();
        let qty = parseInt(str_replace(".", "", $(this).text()));
        let diskon = parseInt(str_replace(".", "", $(".add_diskon").text()));
        let harga = parseInt(str_replace(".", "", $(".add_harga").text()));
        let total = parseInt(str_replace(".", "", $(".add_total").text()));


        if (data_selected.id == undefined) {
            message("400", "Barang belum dipilih!.");
            $(this).text(0);
            return;
        }

        if (qty == 0 || qty == "") {
            message("400", "Minimal pembelian: 1 Barang");
            $(this).text(1);
            $(".add_total").text(angka(harga - diskon));
            return;
        }

        $(".add_total").text(angka((harga * qty) - diskon));
    });

    $(document).on('keyup', '.add_harga', function(e) {
        e.preventDefault();
        let harga = parseInt(str_replace(".", "", $(this).text()));
        let qty = parseInt(str_replace(".", "", $(".add_qty").text()));
        let diskon = parseInt(str_replace(".", "", $(".add_diskon").text()));
        let total = parseInt(str_replace(".", "", $(".add_total").text()));

        if (data_selected.id == undefined) {
            message("400", "Barang belum dipilih!.");
            $(this).text(0);
            return;
        }

        if (qty > 0) {
            $(".add_total").text(angka((harga * qty) - diskon));
        } else {
            $(".add_total").text(angka(harga - diskon));
        }

    });

    $(document).on('keyup', '.add_diskon', function(e) {
        e.preventDefault();
        let diskon = parseInt(str_replace(".", "", $(this).text()));
        let qty = parseInt(str_replace(".", "", $(".add_qty").text()));
        let harga = parseInt(str_replace(".", "", $(".add_harga").text()));
        let total = parseInt(str_replace(".", "", $(".add_total").text()));

        if (data_selected.id == undefined) {
            message("400", "Barang belum dipilih!.");
            $(this).text(0);
            return;
        }

        if (diskon > 0) {
            $(".add_total").text(angka((harga * qty) - diskon));
        } else {
            $(".add_total").text(angka(harga * qty));
        }
        data_selected['diskon'] = diskon;

    });

    let daftar_transaksi = [];
    const isi_transaksi_template = (order = "") => {
        let html = "";

        let total = 0;
        daftar_transaksi.forEach((e, i) => {
            total += e.total;
            html += '<tr>';
            html += '<td>' + (i + 1) + '</td>';
            html += '<td>' + e.barang + '</td>';
            html += '<td class="text-end">' + angka(e.qty) + '</td>';
            html += '<td class="text-end">' + angka(e.diskon) + '</td>';
            html += '<td class="text-end">' + angka(e.total) + '</td>';
            if (order == "") {
                html += '<td class="text-center"><a href="" class="del_list text-danger" data-index="' + i + '"><i class="fa-solid fa-circle-xmark"></i></a></td>';
            }
            html += '</tr>';
        })
        html += '<tr>';
        html += '<th colspan="4" class="text-center">TOTAL</th>';
        html += '<th colspan="2" class="text-end">' + (angka(total)) + '</th>';
        html += '</tr>';
        if (order == "") {
            $(".body_btn_pembayaran").html('<button data-total="' + total + '" class="btn_pembayaran btn btn-sm btn-light"><i class="fa-solid fa-cash-register"></i> PEMBAYARAN</button>')

        }
        return html;
    }

    $(document).on('click', '.add_transaksi', function(e) {

        e.preventDefault();
        let barang = $(".add_barang").text();
        let qty = parseInt(str_replace(".", "", $(".add_qty").text()));
        let diskon = parseInt(str_replace(".", "", $(".add_diskon").text()));
        let harga = parseInt(str_replace(".", "", $(".add_harga").text()));
        let total = parseInt(str_replace(".", "", $(".add_total").text()));
        let id = data_selected.id;

        if (data_selected.id == undefined) {
            message("400", "Barang belum dipilih!.");
            $(this).text(0);
            return;
        }
        if (total == 0) {
            message("400", "Harga belum diisi!.");
            return;
        }

        daftar_transaksi.push({
            barang,
            id,
            qty,
            diskon,
            harga,
            total
        });
        console.log(daftar_transaksi);
        if ($("#daftar_transaksi").is(":empty")) {
            let html = "";
            html += `
                        <h6 style="font-size:10px">DAFTAR TRANSAKSI</h6>
                        <table id="rincian_transaksi" class="table table-sm table-dark table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th text-center>#</th>
                                    <th text-center>Barang</th>
                                    <th text-center>Qty</th>
                                    <th text-center>Diskon</th>
                                    <th text-center>Total</th>
                                    <th text-center>Act</th>
                                </tr>
                            </thead>
                            <tbody class="isi_transaksi">
                                <tr>
                                    <td>1</td>
                                    <td>${barang}</td>
                                    <td class="text-end">${angka(qty)}</td>
                                    <td class="text-end">${angka(diskon)}</td>
                                    <td class="text-end">${angka(total)}</td>
                                    <td class="text-center"><a href="" class="del_list text-danger text-center" data-index="0"><i class="fa-solid fa-circle-xmark"></i></a></td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-center">TOTAL</th>
                                    <th colspan="2" class="text-end">${angka(total)}</th>
                                    </tr>
                        </tbody>
                        </table>
                        `;
            html += '<div class="d-grid mt-2 body_btn_pembayaran">';
            html += '<button data-total="' + total + '" class="btn_pembayaran btn btn-sm btn-light"><i class="fa-solid fa-cash-register"></i> TRANSAKSI</button>';
            html += '</div>';
            $("#daftar_transaksi").html(html);

        } else {

            let html = isi_transaksi_template();

            $(".isi_transaksi").html(html);
        }


        $(".search_list").remove();

        $(".add_barang").text("");
        $(".add_qty").text("0");
        $(".add_diskon").text("0");
        $(".add_harga").text("0");
        $(".add_total").text("0");

    });


    $(document).on('click', '.del_list', function(e) {
        e.preventDefault();
        let index = parseInt($(this).data("index"));
        let data = [];

        daftar_transaksi.forEach((e, i) => {
            if (i !== index) {
                data.push(e);
            }
        })
        daftar_transaksi = data;
        let html = isi_transaksi_template();

        $(".isi_transaksi").html(html);
    });


    $(document).on('click', '.btn_pembayaran', function(e) {
        e.preventDefault();
        let myModal = document.getElementById("fullscreen");
        let modal = bootstrap.Modal.getOrCreateInstance(myModal);
        modal.hide();

        let total = $(this).data("total");
        let html = "";
        html += `<div class="container border border-light rounded p-2">
                        <div class="text-center mb-3">
                            <span class="text-secondary" style="font-size: small;">TOTAL</span>
                            <div class="fw-bold total_pembayaran">${angka(total)}</div>
                        </div>
                        <hr>
                        <div class="text-center mb-3">
                            <span class="text-secondary" style="font-size: small;">Penjual/Toko</span>
                            <input type="text" class="mb-2 form-control nama_penjual text-center" value="" placeholder="Nama penjual/toko">
                            <span class="text-secondary" style="font-size: small;">Uang Pembayaran</span>
                            <input type="text" class="mt-2 form-control uang_pembayaran text-center angka" value="${angka(total)}" placeholder="Uang pembayaran">
                        </div>

                    <div class="d-grid">
                        <button class="btn btn-sm btn-success btn_transaksi"><i class="fa-solid fa-wallet"></i> OK</button>
                    </div>`;

        html += '<div class="accordion accordion-flush mt-3" id="accordionFlushExample">';
        html += '<div class="accordion-item bg-dark">';
        html += '<h2 class="accordion-header" id="flush-headingOne">';
        html += '<button style="font-size: small;" class="p-1 accordion-button collapsed bg-dark text-light border border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">';
        html += 'DETAIL';
        html += '</button>';
        html == '</h2>';
        html += '<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">';
        html += `<table id="rincian_transaksi" class="table table-sm table-dark table-bordered" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th text-center>#</th>
                                    <th text-center>Barang</th>
                                    <th text-center>Qty</th>
                                    <th text-center>Diskon</th>
                                    <th text-center>Harga</th>
                                </tr>
                            </thead>
                            <tbody class="isi_transaksi">`;
        html += isi_transaksi_template("no");
        html += '</tbody>'
        html += '</table>';
        html += '</div>';
        html += '</div>';
        html += '</div>';


        html += '</div>';



        popupButton.html(html);

        setTimeout(() => {
            $('#fullscreen').on('shown.bs.modal', function() {
                let input = $('.uang_pembayaran');
                input.focus();

                // Pindahkan kursor ke akhir teks di input
                let inputElement = input[0];
                let length = inputElement.value.length;
                inputElement.setSelectionRange(length, length);
            });
        }, 200);

    });

    $(document).on('click', '.btn_transaksi', function(e) {
        e.preventDefault();
        let uang_pembayaran = parseInt(str_replace(".", "", $(".uang_pembayaran").val()));
        let total_pembayaran = parseInt(str_replace(".", "", $(".total_pembayaran").text()));
        let nama_penjual = $(".nama_penjual").val();

        if (nama_penjual == "") {
            message("400", "Pembeli kosong!.");
            return;
        }

        if (uang_pembayaran < total_pembayaran) {
            message("400", "Uang kurang!.");
            $(this).val(angka(total_pembayaran));

            return;
        }

        post("pengeluaran/transaksi", {
            uang_pembayaran,
            nama_penjual,
            daftar_transaksi
        }).then(res => {
            if (res.status == "200") {
                let myModal = document.getElementById("fullscreen");
                let modal = bootstrap.Modal.getOrCreateInstance(myModal);
                modal.hide();

                let html = "";
                html += `<div class="container border border-light rounded p-2">
                                <div class="text-center mb-3">
                                    <span class="text-secondary" style="font-size: small;">UANG KEMBALIAN</span>
                                    <div class="fw-bold total_pembayaran">${angka(res.data)}</div>
                                </div>
                                <hr>`;
                if (res.data2.length > 0) {
                    html += '<div class="bg-opacity-25 bg-danger border border-danger mb-2" px-5 pb-1 rounded text-center" style="font-size: medium;">GAGAL: ' + res.data + '</div>';
                }

                popupButton.html(html);
            } else {
                message(res.message);
            }
        })

        $(document).on('click', '.btn_close_modal', function(e) {
            e.preventDefault();
            setTimeout(() => {
                location.reload();
            }, 300);
        });
    });
</script>
<?= $this->endSection() ?>