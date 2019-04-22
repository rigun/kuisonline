<template>
<div class="container loginStyle">
  <div class="background-all"></div>
    <v-layout justify-center>
    <v-flex xs12 md5>
      <v-form ref="form" v-model="valid" lazy-validation>
      <v-card>
        <v-card-text style="text-align:center">
          <img src="/images/logo.png" alt="" style="width: 150px">
        </v-card-text>
        <v-divider></v-divider>        
        <v-card-text>
        <v-flex xs12>
                <v-text-field
                v-model="data.username"
                :rules="[rules.required]"
                label="Username"
                :error-messages="errorMsg"
                required
              ></v-text-field>
        </v-flex>
        <v-flex xs12>
          <v-text-field
            v-model="data.password"
            :append-icon="show ? 'visibility' : 'visibility_off'"
            :rules="[rules.required]"
            :type="show ? 'text' : 'password'"
            label="Password"
            :error-messages="errorMsg"
            @click:append="show = !show"
          ></v-text-field>
        </v-flex>
          
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-slide-x-reverse-transition>
            <v-tooltip
              v-if="formHasErrors"
              left
            >
              <template v-slot:activator="{ on }">
                <v-btn
                  icon
                  class="my-0"
                  @click="resetForm"
                  v-on="on"
                >
                  <v-icon>refresh</v-icon>
                </v-btn>
              </template>
              <span>Refresh form</span>
            </v-tooltip>
          </v-slide-x-reverse-transition>
          <v-btn color="green" dark @click="submit()" :loading="load">Submit</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
    </v-flex>
  </v-layout>
</div>
</template>
<script>
import store from '../../store'

  export default {
    data: () => ({
      errorMsg: '',
      load: false,
      show: false,
      valid: true,
      data: {
        username: null,
        password: null
      },
      rules: {
        required: value => !!value || 'Data tidak boleh kosong.',
      },
      formHasErrors: false
    }),
    methods: {
      resetForm () {
          this.$refs.form.reset()
          this.formHasErrors = false
          this.load = false
          this.errorMsg = ''
      },
      submit () {
        if (!this.$refs.form.validate()) {
          this.formHasErrors = true
          return
        }
        this.load = true
        axios.post('/api/auth/login', this.data).then(response => {
            store.commit('loginUser')
            localStorage.setItem('token', response.data.access_token)
            localStorage.setItem('role', response.data.role)
            localStorage.setItem('id', response.data.id)
            localStorage.setItem('status', response.data.status)
            localStorage.setItem('username', this.data.username)
            this.$router.push({name: 'DashboardContent'})
            this.load = false;
        }).catch(error => {
            this.load = false;
            this.errorMsg = 'Username dan password tidak cocok'
        });
      }
    }
  }
</script>