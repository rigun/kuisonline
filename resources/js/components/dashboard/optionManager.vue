<template id="admin-list">
<div class="contentlist">
  <div class="flex-container" >
    <div class="columns m-t-10">
      <div class="column">
        <h1 class="title">Opsi Manager</h1> untuk soal {{quiz.quiz}}
      </div>
      <div class="column" style="display:flex">
        <a class="button is-success" style="margin-left: auto; margin-right: 10px;" @click.prevent="editDialog = true; inputType = 'new'">Add Opsi</a>
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
             <div class="level-right">
                    <b-field>
                        <b-input placeholder="Search..."
                            type="search"
                            icon="magnify"
                            v-model="search">
                        </b-input>
                    </b-field>
                 </div>
           </nav>
        <b-table :data="optionsList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="created_at">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="option" label="Opsi" sortable>{{ props.row.option }}</b-table-column>
                <b-table-column field="status" label="Status" sortable>
                    <v-chip color="green" dark v-if="props.row.status == 1">Benar</v-chip>
                    <v-chip color="red" dark v-if="props.row.status == 0">Salah</v-chip>
                </b-table-column>
                <b-table-column field="created_at" label="Created at" sortable>{{ props.row.created_at }}</b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" bottom>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="setEditData(props.row); editDialog = true; inputType = 'edit'">
                                <v-list-tile-title  >Edit</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="deleteId = props.row.id; deleteDialog = true" >
                                <v-list-tile-title >Delete</v-list-tile-title>
                            </v-list-tile>
                            </v-list>
                        </v-menu>
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
                        <p>Nothing here.</p>
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

        <!-- edit -->
         <v-layout row justify-center>
                <v-form ref="form" lazy-validation>
                            <v-dialog v-model="editDialog" persistent max-width="600px">
                              <v-card>
                                <v-card-title>
                                <span class="headline">Tambahkan Opsi</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-layout wrap>
                                      <v-flex xs12>
                                        <v-text-field label="Opsi*" v-model="editData.option" :rules="[rules.required]"></v-text-field>
                                      </v-flex>
                                      <v-flex xs12>
                                          <v-radio-group label="Kategori opsi*" v-model="editData.status" >
                                              <v-radio :value="1" label="Benar"></v-radio>
                                              <v-radio :value="0" label="Salah"></v-radio>
                                          </v-radio-group>
                                      </v-flex>
                                    </v-layout>
                                  <small>*Wajib diisi</small>
                                </v-card-text>
                                <v-card-actions>
                                  <v-spacer></v-spacer>
                                  <v-btn color="red darken-1" dark @click="editDialog = false;$refs.form.reset() ">Close</v-btn>
                                  <v-btn color="blue darken-1" dark :loading="load" v-if="inputType == 'new'" @click.prevent="sendData()">Add</v-btn>
                                  <v-btn color="orange darken-1" :loading="load" v-if="inputType == 'edit'" @click.prevent="UpdateData()">Update</v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-dialog>
                </v-form>
                          </v-layout>
        <!-- edit -->
        <!-- delete -->
          <v-layout row justify-center>
                  <v-dialog v-model="deleteDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">Delete this Opsi ?</v-card-title>
                      <v-card-text>The option will never be restore if you delete it.</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="deleteDialog = false; deleteId = -1">Cancel</v-btn>
                        <v-btn color="red darken-1" flat @click="deleteData()">Delete</v-btn>
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
  mounted(){
    this.getData();
  },
  data() {
      return{
          load: false,
          search: '',
          table: 0,
          roles: localStorage.getItem('roles'),
          header: localStorage.getItem('token'),
          options: [],
          dialog: false,
          deleteDialog: false,
          editDialog: false,
          inputType: 'new',
          snackbar: false,
          text: '',
          color: null,
          editData: {
            option: null,
            status: 0,
            quiz_id: -1
          },
          quiz: {},
          deleteId: -1,
          loading : false,
          loadData:true,
          isPaginated: true,
          isPaginationSimple: true,
          defaultSortDirection: 'desc',
          currentPage: 1,
          perPage: 10,
          optionError: '',
          rules: {
            required: value => !!value || `Tidak boleh kosong`,
            numberOnly: value => !isNaN(value) || 'Number only',
            textOnly: value => RegExp(/^[A-Za-z ]+$/).test(value) || 'Text only'
          }
        }
      },
        computed:{
            optionsList(){
                if(this.options.length){
                    return this.options.filter((row, index) => {
                            if(this.search != '') return row.option.toLowerCase().includes(this.search.toLowerCase())
                            return true;
                    });
                }
            }
        },
        methods:{
          setEditData(data){
            this.editData.id = data.id
            this.editData.option = data.option
            this.editData.status = data.status
          },
          getData(){
            this.deleteDialog = false
            this.editDialog = false
            this.load = false
            this.$refs.form.reset()
            var uri;
            var config = {
              headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
              }
            }
            uri = '/api/option/' + this.$route.params.id
            axios.get(uri,config).then(response =>{
              this.options = response.data.Opsi
              this.quiz = response.data.Quiz
              this.loadData = false
            })
          },
          sendData(){
            if (!this.$refs.form.validate()) {
              this.snackbar = true
              this.text = 'Mohon untuk melengkapi form'
              this.color = 'red'
              return
            }
            if (this.optionError !== ''){
              return
            }
            this.load = true
            var uri
            var config = {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }
            this.editData.quiz_id = this.$route.params.id
              uri = '/api/option'
            axios.post(uri,this.editData,config).then(response =>{
              this.snackbar = true;
              this.text = 'Success';
              this.color = 'green';
              this.getData();
            }).catch(error =>{
              this.snackbar = true;
              this.text = 'Coba lagi';
              this.color = 'red';
              this.load = false
            })
          },
          deleteData(){
            var uri
             var config = {
                  headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
              }
            uri = '/api/option/'+this.deleteId;
            axios.delete(uri,config).then(response =>{
                   this.snackbar = true;
                    this.text = 'Data berhasil dihapus';
                    this.color = 'green';
                    this.getData();
                }).catch(error =>{
                    this.snackbar = true;
                    this.text = 'Coba Lagi';
                    this.color = 'red';

                })
          },
          UpdateData(){
            if (!this.$refs.form.validate()) {
              this.snackbar = true
              this.text = 'Mohon untuk melengkapi form'
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
            this.editData.quiz_id = this.$route.params.id
            uri = '/api/option/'+this.editData.id;
            axios.patch(uri,this.editData,config).then(response =>{
                   this.snackbar = true;
                    this.text = 'Success';
                    this.color = 'green';
                    this.load = false
                    this.getData();
                }).catch(error =>{
                    this.snackbar = true;
                    this.text = 'Try Again';
                    this.color = 'red';
                    this.load = false
                })
          }
        }
    }
</script>
