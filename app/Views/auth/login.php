<?= $this->extend('auth/template') ?>
<?= $this->section('customcss') ?>
<?= $this->endSection('customcss'); ?>
<?= $this->section('content') ?>
<p class="login-box-msg">Masuk Aplikasi</p>
<form @submit="loginProcess">
  <div class="input-group mb-3">
    <input v-model="username" type="username" class="form-control" v-bind:class="{ 'is-invalid': invalidUsername}" name="username" placeholder="Username / NIS">
    <div class="input-group-append">
      <div class="input-group-text">
        <span class="fas fa-user"></span>
      </div>
    </div>
    <div class="invalid-feedback">
      {{errorUsername}}
    </div>
  </div>
  <div class="input-group mb-3">
    <input v-model="password" type="password" class="form-control" v-bind:class="{ 'is-invalid': invalidPassword}" name="password" placeholder="Password">
    <div class="input-group-append">
      <div class="input-group-text">
        <span class="fas fa-lock"></span>
      </div>
    </div>
    <div class="invalid-feedback">
      {{errorPassword}}
    </div>
  </div>
  <div class="row">
    <!-- /.col -->
    <div class="col">
      <template v-if="isLoading">
        <button type="button" class="btn btn-primary btn-block" disabled>
          <div class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </button>
      </template>
      <template v-else>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
      </template>
    </div>
    <!-- /.col -->
  </div>
</form>
<?= $this->endSection('content'); ?>
<?= $this->section('customjs') ?>
<script>
  dataVue = {
    username: '',
    password: '',
    invalidUsername: false,
    errorUsername: '',
    invalidPassword: false,
    errorPassword: '',
    isLoading: false,
  }
  methodsVue = {
    cleanForm: function({
      onlyError = false
    }) {
      if (onlyError == false) {
        this.username = ''
        this.password = ''
      }
      this.invalidUsername = false
      this.errorUsername = ''
      this.invalidPassword = false
      this.errorPassword = ''
    },
    loginProcess: function(e) {
      e.preventDefault()
      this.cleanForm({
        onlyError: true
      })
      this.isLoading = true;
      var formData = new FormData()
      formData.append('username', this.username)
      formData.append('password', this.password)
      axios({
        method: 'post',
        url: '/api/auth/login',
        data: formData,
        headers: {
          "Content-Type": "multipart/form-data"
        }
      }).then((res) => {
        console.log(res)
        this.isLoading = false
        if (res.data.status === 1) {
          Toast.fire({
            icon: 'success',
            title: res.data.message
          })
          window.location.href = res.data.data.url
        } else {
          Toast.fire({
            icon: 'error',
            title: res.data.message
          })
          var data = res.data.data
          if (data.username) {
            this.invalidUsername = true
            this.errorUsername = data.username
          }
          if (data.password) {
            this.invalidPassword = true
            this.errorPassword = data.password
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
        console.log(response)
        this.isLoading = false
      })
    }
  }
</script>
<?= $this->endSection('customjs'); ?>