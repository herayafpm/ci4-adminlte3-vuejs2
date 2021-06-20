<?= $this->extend('admin/template'); ?>
<?= $this->section('customcss'); ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets/vendor/adminlte') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<?= $this->endSection('customcss'); ?>
<?= $this->section('content'); ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-0">
        <div class="card-tools">
          <button @click="openModalCU" class="btn btn-sm btn-success">
            <i class="fas fa-fw fa-plus"></i> Tambah
          </button>
          <button @click="refreshDatatable" class="btn btn-sm btn-info">
            <i class="fas fa-fw fa-sync"></i> Segarkan
          </button>
        </div>
      </div>
      <div class="card-body">
        <h5>Filter</h5>
        <div class="form-group row">
          <div class="col-md-4"><input class="form-control form-control-sm" type="text" v-model="search_data_username" placeholder="Username (min. 3 karakter)" /></div>
          <div class="col-md-4"><input class="form-control form-control-sm" type="text" v-model="search_data_nama" placeholder="Nama (min. 3 karakter)" /></div>
        </div>
        <table v-once id="datatable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Nama</th>
              <th>Tugas</th>
              <th>Dibuat Pada</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr>
              <th>No</th>
              <th>Username</th>
              <th>Nama</th>
              <th>Tugas</th>
              <th>Dibuat Pada</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<?= $this->endSection('content'); ?>
<?= $this->section('modal'); ?>
<!-- Modal -->
<!-- Create Edit Modal -->
<div class="modal fade" id="cuModal" tabindex="-1" role="dialog" aria-labelledby="cuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 v-if="edit_mode" class="modal-title" id="cuModalLabel">Edit Data</h5>
        <h5 v-else class="modal-title" id="cuModalLabel">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form @submit="cuData">
        <div class="modal-body">
          <div class="mb-3">
            <label for="data-nama-form" class="form-label">Nama</label>
            <input v-model='data_nama' type="text" class="form-control" v-bind:class="{ 'is-invalid': invalid_data_nama}" id="data-nama-form" placeholder="Masukkan nama">
            <div class="invalid-feedback">
              {{error_data_nama}}
            </div>
          </div>
          <div class="mb-3">
            <label for="admin-username-form" class="form-label">Username</label>
            <input v-model='data_username' type="text" class="form-control" v-bind:class="{ 'is-invalid': invalid_data_username}" id="admin-username-form" placeholder="Masukkan username">
            <div class="invalid-feedback">
              {{error_data_username}}
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Status Aktif</label>
            <div class="form-group form-check">
              <input v-model='data_aktif' class="form-check-input" type="checkbox" id="data-status-form" name="data-status-form">
              <label class="form-check-label" for="data-status-form">
                Aktif?
              </label>
            </div>
          </div>
          <label for="data-password-form" class="form-label">Password</label>
          <input v-if="edit_mode" v-model='data_password' type="password" class="form-control" v-bind:class="{'is-invalid': invalid_data_password}" id="data-password-form" placeholder="Jika tidak ingin diubah, kosongi">
          <input v-else v-model='data_password' type="password" class="form-control" v-bind:class="{'is-invalid': invalid_data_password}" id="data-password-form" placeholder="Masukkan min 6 karakter">
          <div class="invalid-feedback">
            {{error_data_password}}
          </div>
        </div>
        <div class="modal-footer">
          <template v-if="isLoading">
            <button class="btn btn-primary" type="button" disabled>
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </button>
          </template>
          <template v-else>
            <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Tutup</button>
            <button v-if="edit_mode" type="submit" class="btn btn-primary">Ubah</button>
            <button v-else type="submit" class="btn btn-primary">Tambah</button>
          </template>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection('modal'); ?>
<?= $this->section('customjs'); ?>
<!-- DataTables  & Plugins -->
<script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets/vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script>
  var tabel = null;
  var datas = [];
  var id = null;
  var data = {};

  function ubah(index) {
    vue.openModalCU(true, datas[index])
  }

  function hapus(id) {
    hapusData({
      fun: function() {
        vue.deleteData(id)
      }
    })
  }
  $(function() {
    tabel = $("#datatable").DataTable({
      "language": {
        "buttons": {
          "pageLength": {
            "_": "Tampil %d baris <i class='fas fa-fw fa-caret-down'></i>",
            "-1": "Tampil Semua <i class='fas fa-fw fa-caret-down'></i>"
          }
        },
        "lengthMenu": "Tampil _MENU_ data per hal",
        "zeroRecords": "Data tidak ditemukan",
        "info": "Tampil halaman _PAGE_ dari _PAGES_",
        "infoEmpty": "Tidak ada data",
        "infoFiltered": "(difilter dari _MAX_ total data)"
      },
      "dom": 'Bfrtip',
      "buttons": [{
        extend: "pageLength",
        attr: {
          "class": "btn btn-primary"
        },
      }],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "ordering": true, // Set true agar bisa di sorting
      "order": [
        [0, 'desc']
      ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
      'columnDefs': [{
        "targets": [6],
        "orderable": false
      }],
      "ajax": {
        "url": "<?= $_uri_datatable ?>", // URL file untuk proses select datanya
        "type": "POST",
        "data": function(d) {
          return {
            ...d,
            ...data
          }
        }
      },
      "initComplete": function(settings, json) {
        datas = json.data;
      },
      "scrollY": "<?= $_scroll_datatable ?>",
      "scrollCollapse": true,
      "lengthChange": true,
      "lengthMenu": [
        [10, 25, 50, -1],
        ['10 baris', '25 baris', '50 baris', 'Tampilkan Semua']
      ],
      "columns": [{
          "data": "admin_id",
        },
        {
          "data": "admin_username",
        },
        {
          "data": "admin_nama",
        },
        {
          "data": "role_nama",
        },
        {
          "data": "admin_created_at",
          "render": function(dt, type, row, meta) {
            return toLocaleDate(row.admin_created_at, 'LLL');
          }
        },
        {
          "data": "admin_aktif",
          "render": function(dt, type, row, meta) { // Tampilkan kolom aksi
            var html = 'Non-Aktif'
            if (row.admin_aktif == 1) {
              html = 'Aktif'
            }
            return html
          }
        },
        {
          "render": function(dt, type, row, meta) { // Tampilkan kolom aksi
            var html = '';
            html += '<button type="button" class="btn btn-link text-info" onClick="ubah(' + meta.row + ')"><i class="fa fa-fw fa-edit" aria-hidden="true" title="Edit ' +
              row.admin_nama + '"></i></button>'
            html += '<form method="POST" class="d-inline deleteData"><button type="button" class="btn btn-link text-danger" onClick="hapus(' + row.admin_id + ')"><i class="fa fa-fw fa-trash" aria-hidden="true" title="Hapus ' + row.admin_nama + '"></i></button></form>'
            return html
          }
        },
      ],
    });

    tabel.on('order.dt page.dt', function() {
      tabel.column(0, {
        order: 'applied',
        page: 'applied',
      }).nodes().each(function(cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>
<script>
  dataVue = {
    search_data_nama: '',
    search_data_username: '',
    data_id: 0,
    data_nama: '',
    invalid_data_nama: false,
    error_data_nama: '',
    data_username: '',
    invalid_data_username: false,
    error_data_username: '',
    data_password: '',
    invalid_data_password: false,
    error_data_password: '',
    data_aktif: false,
    isLoading: false,
    edit_mode: false,
  }
  createdVue = function() {

  }
  watchsVue = {
    search_data_nama: function(value) {
      var min_karakter = 3;
      if (value.length >= min_karakter) {
        data.admin_nama = value;
      }
      if (value.length == 0 && value.length < min_karakter) {
        data.admin_nama = "";
      }
      if (value.length >= min_karakter || value.length == 0) {
        tabel.ajax.reload(function(json) {
          datas = json.data
        })
      }
    },
    search_data_username: function(value) {
      var min_karakter = 3;
      if (value.length >= min_karakter) {
        data.admin_username = value;
      }
      if (value.length == 0 && value.length < min_karakter) {
        data.admin_username = "";
      }
      if (value.length >= min_karakter || value.length == 0) {
        tabel.ajax.reload(function(json) {
          datas = json.data
        })
      }
    }
  }
  methodsVue = {
    cleanDataModal: function({
      onlyError = false
    } = {}) {
      if (!onlyError) {
        this.data_nama = ''
        this.data_id = 0
        this.data_username = ''
        this.data_password = ''
        this.data_aktif = true
        this.edit_mode = false
      }
      this.invalid_data_nama = false
      this.error_data_nama = ''
      this.invalid_data_username = false
      this.error_data_username = ''
      this.invalid_data_password = false
      this.error_data_password = ''
    },
    openModalCU: function(edit = false, data = {}) {
      this.cleanDataModal()
      this.edit_mode = false
      if (edit == true) {
        this.data_id = data.admin_id
        this.data_nama = data.admin_nama
        this.data_username = data.admin_username
        this.data_aktif = data.admin_aktif == 1
        this.edit_mode = true
      }
      $('#cuModal').modal('show')
    },
    cuData: function(e) {
      this.isLoading = true
      this.cleanDataModal({
        onlyError: true
      })
      e.preventDefault()
      if (this.edit_mode) {
        this.editData()
      } else {
        this.addData()
      }
    },
    addData: function() {
      var formData = new FormData()
      formData.append('admin_nama', this.data_nama)
      formData.append('admin_username', this.data_username)
      formData.append('admin_password', this.data_password)
      formData.append('admin_aktif', this.data_aktif ? 1 : 0)
      axios({
        method: 'post',
        url: '/api/admin/admin/create',
        data: formData,
        headers: {
          "Content-Type": "multipart/form-data"
        }
      }).then((res) => {
        this.isLoading = false
        if (res.data.status === 1) {
          Toast.fire({
            icon: 'success',
            title: res.data.message
          })
          this.reloadDatatable('#cuModal')
        } else {
          Toast.fire({
            icon: 'error',
            title: res.data.message
          })
          var data = res.data.data
          if (data.admin_nama) {
            this.invalid_data_nama = true
            this.error_data_nama = data.admin_nama
          }
          if (data.admin_username) {
            this.invalid_data_username = true
            this.error_data_username = data.admin_username
          }
          if (data.admin_password) {
            this.invalid_data_password = true
            this.error_data_password = data.admin_password
          }
        }
      }).catch(err => {
        var response = err.response
        if (response.status === 400) {
          // 400 (Client Error)
        } else if (response.status === 500) {
          // 500 (Server Error)
          alert(response.data.message)
        } else if (response.status === 404) {
          // 404 (Not Found)
          alert(response.statusText)
        }
        this.isLoading = false
      })
    },
    editData: function() {
      var formData = new FormData()
      formData.append('admin_nama', this.data_nama)
      formData.append('admin_username', this.data_username)
      formData.append('admin_password', this.data_password)
      formData.append('admin_aktif', this.data_aktif ? 1 : 0)
      axios({
        method: 'post',
        url: '/api/admin/admin/' + this.data_id + '/update',
        data: formData,
        headers: {
          "Content-Type": "multipart/form-data"
        }
      }).then((res) => {
        this.isLoading = false
        if (res.data.status === 1) {
          Toast.fire({
            icon: 'success',
            title: res.data.message
          })
          this.reloadDatatable('#cuModal')
        } else {
          Toast.fire({
            icon: 'error',
            title: res.data.message
          })
          var data = res.data.data
          if (data.admin_nama) {
            this.invalid_data_nama = true
            this.error_data_nama = data.admin_nama
          }
          if (data.admin_username) {
            this.invalid_data_username = true
            this.error_data_username = data.admin_username
          }
          if (data.admin_password) {
            this.invalid_data_password = true
            this.error_data_password = data.admin_password
          }
        }
      }).catch(err => {
        var response = err.response
        if (response.status === 400) {
          // 400 (Client Error)

        } else if (response.status === 500) {
          // 500 (Server Error)
          alert(response.data.message)
        } else if (response.status === 404) {
          // 404 (Not Found)
          alert(response.statusText)
        }
        this.isLoading = false
      })
    },
    deleteData: function(data_id) {
      axios({
        method: 'post',
        url: '/api/admin/admin/' + data_id + '/delete',
        headers: {
          "Content-Type": "multipart/form-data"
        }
      }).then((res) => {
        this.isLoading = false
        if (res.data.status === 1) {
          Toast.fire({
            icon: 'success',
            title: res.data.message
          })
          this.reloadDatatable(null)
        } else {
          Toast.fire({
            icon: 'error',
            title: res.data.message
          })
        }
      }).catch(err => {
        var response = err.response
        if (response.status === 400) {
          // 400 (Client Error)
          var data = response.data.data
          alert(response.data.message)
        } else if (response.status === 500) {
          // 500 (Server Error)
          alert(response.data.message)
        } else if (response.status === 404) {
          // 404 (Not Found)
          alert(response.statusText)
        }
        this.isLoading = false
      })
    },
    reloadDatatable: function(id_modal) {
      this.cleanDataModal()
      if (id_modal != null) {
        $(id_modal).modal('hide')
      }
      tabel.ajax.reload(function(json) {
        datas = json.data
      })
    },
    refreshDatatable: function() {
      data = {};
      this.search_data_nama = ''
      this.search_data_username = ''
      tabel.ajax.reload(function(json) {
        datas = json.data
      })
    }
  }
</script>
<?= $this->endSection('customjs'); ?>