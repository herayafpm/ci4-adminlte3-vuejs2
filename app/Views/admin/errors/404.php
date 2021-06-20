<?= $this->extend('admin/template') ?>
<?= $this->section('customcss') ?>
<?= $this->endSection('customcss'); ?>
<?= $this->section('content') ?>
<div class="error-page">
  <h2 class="headline text-warning"> 404</h2>

  <div class="error-content">
    <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman tidak ditemukan.</h3>

    <p>
      Kami tidak dapat menemukan halaman yang anda cari. <br>
      Anda bisa <a href="<?= base_url('admin/dashboard') ?>">kembali ke dashboard</a>
    </p>
  </div>
  <!-- /.error-content -->
</div>
<!-- /.error-page -->
<?= $this->endSection('content'); ?>
<?= $this->section('customjs') ?>
<?= $this->endSection('customjs'); ?>