<?= $this->extend('templates/logged') ?>

<?= $this->section('content') ?>

<div class="text-center" style="margin-top: 200px;">WELCOME <b><?= strtoupper(user()['nama']); ?></b></div>

<?= $this->endSection() ?>