<template id="admin-list">
<div class="contentlist">
<div class="flex-container" >

      <div class="columns m-t-10">
        <div class="column">
          <h1 class="title">Daftar Kasus</h1>
        </div>
        <div class="column">
            <v-flex sm6 d-flex style="margin-left: auto">
              <v-btn slot="activator" color="blue lighten-2" dark @click.prevent="goto('formCase')">Tambah Kasus</v-btn>
            </v-flex>
        </div>
      </div>
       <nav class="level">
             <div class="level-left">
                <b-field grouped group-multiline>
                    <b-select v-model="perPage" :disabled="!isPaginated">
                        <option value="10">10 per page</option>
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                    </b-select>
                </b-field>
             </div>
            
           </nav>
        <b-table :data="caseList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="created_at">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="judul" label="Judul" sortable>{{ props.row.title }}</b-table-column>
                <b-table-column field="status" label="Status" sortable>
                    <v-btn color="green lighten-2" dark v-if="props.row.status == 0" @click="updateStatus(props.row.id,1)">Tampilkan kasus</v-btn>
                    <v-btn color="orange lighten-2" dark v-if="props.row.status == 1" @click="updateStatus(props.row.id,0)">Sembunyikan kasus</v-btn>
                </b-table-column>
                  <b-table-column label="Pengaturan" :visible="!loadData">
                    <v-btn color="blue lighten-2" dark @click="update(props.row.id)">Detail Kasus</v-btn>
                    <v-btn color="red lighten-2" dark @click="deleteId = props.row.id; deleteDialog = true">Hapus</v-btn>
                  </b-table-column>
            </template>
              <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon
                                icon="emoticon-sad"
                                size="is-large">
                            </b-icon>
                        </p>
                        <p>Belum ada data kasus, silahkan tambahkan kasus terlebih dahulu</p>
                    </div>
                </section>
            </template>
        </b-table>
      
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

        <!-- delete -->
          <v-layout row justify-center>
                  <v-dialog v-model="deleteDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">Hapus Data Kasus ?</v-card-title>
                      <v-card-text>Data yang dihapus tidak akan bisa dikembalikan lagi</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="deleteDialog = false; deleteId = -1">Batal</v-btn>
                        <v-btn color="red darken-1" flat @click="deleteDialog = false; deleteData()">Hapus</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-layout>
        <!-- delete -->
</div>
</div>
</template>

<script>
export default {
  mounted () {
      if(localStorage.getItem('roles') == 'ma2019'){
          this.$router.push({name: 'DashboardContent'})
      }
    this.getData()
  },
  data () {
    return {
      search: '',
      case: [],
      deleteDialog: false,
      load: false,
      snackbar: false,
      text: '',
      color: null,
      reset: false,
      deleteId: -1,
      loading: false,
      loadData: true,
      isPaginated: true,
      isPaginationSimple: true,
      defaultSortDirection: 'desc',
      currentPage: 1,
      perPage: 10,
    }
  },
  computed: {
    caseList () {
      if (this.case.length) {
        return this.case.filter((row, index) => {
          return true
        })
      }
    }
  },
  methods: {
    goto (name) {
      this.$router.push({name: name})
    },
    update(id){
      this.$router.push({name: 'editFormCase',params:{id: id}})
    },
    getData () {
      this.editDialog = false
      this.deleteDialog = false
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/kasus'
      axios.get(uri, config).then(response => {
        this.case = response.data
        this.loadData = false
      })
    },
    deleteData () {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/kasus/' + this.deleteId
      axios.delete(uri, config).then(response => {
        this.snackbar = true
        this.text = 'Data berhasil dihapus'
        this.color = 'green'
        this.getData()
      }).catch(error => {
        console.log(error)
        this.snackbar = true
        this.text = 'Coba Lagi'
        this.color = 'red'
      })
    },
    updateStatus (id,status) {
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      var uri = '/api/kasus/status/' + id
      axios.post(uri, { status: status }, config).then(response => {
        this.getData()
      }).catch(error => {
        this.snackbar = true
        this.text = 'Coba lagi'
        this.color = 'red'
      })
    }
  }
}
</script>
