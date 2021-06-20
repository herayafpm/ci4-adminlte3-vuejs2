<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $_title ?? '' ?> | <?= env('app.appName') ?></title>
  <meta name="description" content="<?= $_meta_description ?? '' ?>">
  <meta name="keywords" content="<?= $_meta_keywords ?? '' ?>">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name=" viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte') ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte') ?>/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <?= $this->renderSection('customcss') ?>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= base_url() ?>"><?= env('app.appName') ?></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <div id="app">
          <?= $this->renderSection('content') ?>
        </div>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('assets/vendor/adminlte') ?>/dist/js/adminlte.min.js"></script>
  <script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
  <script src="<?= base_url() ?>/assets/vendor/vuejs/vue.min.js"></script>
  <script src="<?= base_url() ?>/assets/vendor/axios/axios.min.js"></script>
  <script>
    var Toast;
    $(function() {
      Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 8000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
      <?php if ($_session->getFlashdata('success')) : ?>
        Toast.fire({
          icon: 'success',
          title: "<?= $_session->getFlashdata('success') ?>"
        })
      <?php endif; ?>
      <?php if ($_session->getFlashdata('error')) : ?>
        Toast.fire({
          icon: 'error',
          title: "<?= $_session->getFlashdata('error') ?>"
        })
      <?php endif; ?>
    });
  </script>
  <!-- Vue -->
  <script>
    var dataVue = {}
    var createdVue = function() {

    }
    var methodsVue = {}
  </script>
  <?= $this->renderSection('customjs') ?>
  <script>
    new Vue({
      el: '#app',
      data: dataVue,
      created: createdVue,
      methods: methodsVue
    })
  </script>

</body>

</html>