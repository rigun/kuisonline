<template>
 <div class="contentlist">
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
        >
            <v-card>
                <v-card-title>
                    Tambahkan Kasus
                </v-card-title>
                <v-card-text>
                        <v-flex xs12>
                        <v-text-field label="Judul*" required v-model="editData.title" :rules="[rules.required]"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <editor api-key="6igj5g6zwo9p5n58vrpw7ea8aph3ft79j7v8322mmay4ado7 " v-model="editData.body" :init="{plugins: 'wordcount image imagetools', height: '500'}"></editor>
                        </v-flex>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" dark @click.prevent="goto('manageCase')">Kembali</v-btn>
                    <v-btn color="green darken-1" dark @click.prevent="SendData()" :loading="load">Perbaharui</v-btn>
                </v-card-actions>
                </v-card>
                </v-form>
<v-snackbar
          v-model="snackbar"
          :color="color"
          :multi-line="true"
          :timeout="3000"
        >
          {{ text }}
          <v-btn
            dark
            flat
            @click="snackbar = false"
          >
            Close
          </v-btn>
        </v-snackbar>
 </div>
</template>
<script>


export default {
    mounted(){
        this.getData();
         if(localStorage.getItem('roles') == 'ma2019'){
          this.$router.push({name: 'DashboardContent'})
         }
    },

  data () {
    return {
      valid: true,
      load: false,
      snackbar: false,
      text: '',
      color: null,
      editData: {
        title: '',
        body: '',
      },
      loading: false,
      rules: {
        required: value => !!value || 'Data ini tidak boleh kosong',
        numberOnly: value => !isNaN(value) || 'Data tidak valid, hanya diijinkan memasukkan angka',
        textOnly: value => RegExp(/^[A-Za-z ]+$/).test(value) || 'Data tidak valid, hanya diijinkan memasukkan huruf'
      }
    }
  },
  methods: {
    getData () {
      this.editDialog = false
      this.deleteDialog = false
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/kasus/'+this.$route.params.id;
      axios.get(uri, config).then(response => {
        this.editData = response.data.result
        this.loadData = false
      })
    },
    goto (name) {
      this.$router.push({name: name})
    },
    SendData () {
      if (!this.$refs.form.validate()) {
        this.snackbar = true
        this.text = 'Mohon untuk melengkapi form yang tersedia'
        this.color = 'red'
        return
      }
      this.load = true
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/kasus/'+this.$route.params.id
      axios.post(uri, this.editData, config).then(response => {
          this.snackbar = true
        this.text = 'Kasus berhasil dibuat'
        this.color = 'green'
        this.load = false
        this.goto('manageCase')
      }).catch(error => {
        console.log(error.response)
        this.snackbar = true
        this.text = 'Kasus gagal dibuat, silahkan coba lagi.'
        this.color = 'red'
        this.load = false

      })
    },
  }
}
</script>
