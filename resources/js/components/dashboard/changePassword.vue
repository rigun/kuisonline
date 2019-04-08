<template>
    <div>
        <div class="columns">
            <div class="column">
                <div class="column">
                    <h1 class="title">Ubah Password</h1>
                    </div>
            </div>
        </div>
     <div class="columns">
         <div class="column">
             <div class="card">
                    <div class="card-content">
                    <v-form ref="form" v-model="valid" lazy-validation>
                       
                         <v-text-field
                            v-model="data.password_lama"
                            :append-icon="show1 ? 'visibility_off' : 'visibility'"
                            :rules="[rules.required, rules.min]"
                            :type="show1 ? 'text' : 'password'"
                            name="input-10-1"
                            label="Password Lama"
                            @click:append="show1 = !show1"
                        ></v-text-field>
                         <v-text-field
                            v-model="data.password_baru"
                            :append-icon="show2 ? 'visibility_off' : 'visibility'"
                            :rules="[rules.required, rules.min]"
                            :type="show2 ? 'text' : 'password'"
                            name="input-10-1"
                            label="Password Baru"
                            counter
                            @click:append="show2 = !show2"
                        ></v-text-field>
                        <div style="display:flex">
                        <v-btn @click="clear" color="red" dark style="margin-left: auto">Ulang</v-btn>
                        <v-btn
                        :disabled="!valid"
                        @click="submit"
                        color="blue"
                        dark
                        >
                        Ubah
                        </v-btn>

                        </div>
                    </v-form>
                    </div>
                </div>
            </div>
        </div>   
         <v-snackbar v-model="snackbar" :color="color" :multi-line="true" :timeout="timeout">
          {{ text }}
          <v-btn dark flat @click="snackbar = false">
            Close
          </v-btn>
        </v-snackbar>
    </div>
</template>
<script>
  export default {
      mounted() {
          if(this.$parent.first == 1){
              this.snackbar = true;
              this.text = 'Selamat datang, untuk dapat menggunakan Yesplis, anda harus merubah password anda terlebih dahulu, terima kasih.'
              this.color = 'blue';
          }else{
              this.timeout= 3000
          }
      },
    data: () => ({
      valid: true,
      show1: false,
      show2: false,
      snackbar: false,
      color: 'green',
      timeout: 10000,
      text: '',
      data: {
          password_lama: '',
          password_baru: '',
      },
      rules: {
          required: value => !!value || 'Required.',
          min: v => v.length >= 8 || 'Minimal 8 karakter',
        },
    }),

    methods: {
      submit () {
        if (this.$refs.form.validate()) {
            var config = {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }
          axios.post('/api/changepassword/'+localStorage.getItem('id'),this.data,config).then(response =>{
              if(response.data.status == 'success'){
                  this.color = 'green';
                  this.clear();
                  this.$parent.first = 0;
                  this.$router.push({name: 'DashboardContent'});
              }else{
                  this.color = 'red';
              }
              this.text = response.data.msg;
              this.snackbar = true;
          }).catch(error=>{
               this.color = 'red';
              this.snackbar = true;
              this.text = 'Coba Lagi';
          })
        }
      },
      clear () {
        this.$refs.form.reset()
      }
    }
  }
</script>