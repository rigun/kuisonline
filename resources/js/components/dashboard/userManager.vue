<template id="admin-list">
<div class="contentlist">
  <div class="flex-container" >
    <div class="columns m-t-10">
      <div class="column">
        <h1 class="title">User Manager</h1>
      </div>
      <div class="column" style="display:flex">
        <a class="button is-success" style="margin-left: auto; margin-right: 10px;" @click.prevent="editDialog = true; inputType = 'new'">Add User</a>
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
        <b-table :data="usersList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="created_at">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="user.username" label="Username" sortable>{{ props.row.user.username }}</b-table-column>
                <b-table-column field="nilai" label="Nilai" sortable centered>{{ props.row.nilai }}</b-table-column>
                <b-table-column field="created_at" label="Mendaftar pada" sortable>{{ props.row.user.created_at }}</b-table-column>
                 <b-table-column field="status" label="Jumlah Kesempatan" sortable centered>
                     {{props.row.user.status}}
                </b-table-column>
                <b-table-column label="Option" sortable centered>
                  <v-btn @click="gotoRoute(props.row.user.id)" color="blue" dark>
                    Hasil Pengerjaan
                  </v-btn>
                </b-table-column>
                            <b-table-column label=""><v-menu transition="slide-x-transition" bottom>
                                <v-btn slot="activator" icon >
                                <v-icon>more_vert</v-icon>
                                </v-btn>
                            <v-list>
                            <v-list-tile  @click.prevent="setEditData(props.row.user); editDialog = true; inputType = 'edit'">
                                <v-list-tile-title  >Edit</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click.prevent="deleteId = props.row.user.id; deleteDialog = true" >
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
                                <span class="headline">Akun Pengguna</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-layout wrap>
                                      <v-flex xs12>
                                        <v-text-field label="Username*" required v-model="editData.username" :rules="[rules.required]" :error-messages="usernameError" @change="cekEmail()"></v-text-field>
                                      </v-flex>
                                      <v-flex xs12 v-if="inputType == 'edit'">
                                        <v-checkbox label="Reset Password" v-model="editData.reset"></v-checkbox>
                                      </v-flex>
                                    </v-layout>
                                  <small>*indicates required field</small>
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
                      <v-card-title class="headline">Delete this User ?</v-card-title>
                      <v-card-text>The user will never be restore if you delete it.</v-card-text>
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
  data(){
      return{
          load: false,
          search: '',
          table: 0,
          roles: localStorage.getItem('roles'),
          header: localStorage.getItem('token'),
          users: [],
          dialog: false,
          deleteDialog: false,
          editDialog: false,
          inputType: 'new',
          data:{
            name: null,
            namaEvent: null,
            username: null,
          },
          snackbar: false,
          text: '',
          color: null,
          editData: {
            name: null,
            username: null,
            namaEvent: null,
            reset: false,
          },
          deleteId: -1,
          loading : false,
          loadData:true,
          isPaginated: true,
          isPaginationSimple: true,
          defaultSortDirection: 'desc',
          currentPage: 1,
          perPage: 10,
          usernameError: '',
          rules: {
            required: value => !!value || `Tidak boleh kosong`,
            numberOnly: value => !isNaN(value) || 'Number only',
            textOnly: value => RegExp(/^[A-Za-z ]+$/).test(value) || 'Text only'
          }
        }
      },
        computed:{
            usersList(){
                if(this.users.length){
                    return this.users.filter((row, index) => {
                            if(this.search != '') return row.name.toLowerCase().includes(this.search.toLowerCase()) || row.username.toLowerCase().includes(this.search.toLowerCase()) || row.token.toLowerCase().includes(this.search.toLowerCase());  //menampilkan data data yang memiliki kemiripan nama dengan variable search yang diinputkan        
                            return true;
                    });
                }
            }
        },
        methods:{
          gotoRoute(id){
            this.$router.push({name: 'Result', params: {id: id}})
          },
          cekEmail(){ //mengecek username yang dimasukkan
              axios.get('/api/username/'+this.editData.username).then(response =>{
                  if(response.data.length > 0){ // apabila data tidak kosong, maka akan dicocokan dengan variable yang disediakan
                    this.usernameError = 'Username sudah digunakan'
                  }else{
                    this.usernameError = ''
                  }
              })
          },
          setEditData(data){
            this.editData.id = data.id
            this.editData.name = data.name
            this.editData.username = data.username
            this.editData.namaEvent = data.namaEvent
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
            uri = '/api/alluser'
            axios.get(uri,config).then(response =>{
              this.users = response.data;
              this.loadData = false;
            })
          },
          sendData(){
            if (!this.$refs.form.validate()) {
              this.snackbar = true
              this.text = 'Please fill the form'
              this.color = 'red'
              return
            }
            if (this.usernameError !== ''){
              return
            }
            this.load = true
            var uri
            var config = {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }
              uri = '/api/user'
            axios.post(uri,this.editData,config).then(response =>{
              this.snackbar = true;
              this.text = 'Success';
              this.color = 'green';
              this.getData();
            }).catch(error =>{
              this.snackbar = true;
              this.text = 'Try Again';
              this.color = 'red';
            })
          },
          deleteData(){
            var uri
             var config = {
                  headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
              }
            uri = '/api/user/'+this.deleteId;
            axios.delete(uri,config).then(response =>{
                   this.snackbar = true;
                    this.text = 'Data berhasil dihapus';
                    this.color = 'green';
                    this.getData();
                }).catch(error =>{
                    this.snackbar = true;
                    this.text = 'Coba Lagi';
                    this.color = 'red';
                    this.load = false;
                })
          },
          UpdateData(){
            if (!this.$refs.form.validate()) {
              this.snackbar = true
              this.text = 'Please fill the form'
              this.color = 'red'
              return
            }
            if (this.usernameError !== ''){
              return
            }
            this.load = true
            var uri
            var config = {
                  headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
                }
            uri = '/api/user/'+this.editData.id;
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
          },
            updateStatus(user){
                var config = {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                }
                var uri = '/api/user/status/'+user.id;
                if(user.status == 0){
                  user.status = 1
                }else{
                  user.status = 0
                }
                axios.patch(uri,{status : user.status},config).then(response =>{
                    this.getData();
                }).catch(error =>{
                    this.snackbar = true;
                    this.text = 'Try Again';
                    this.color = 'red';
                })
            }
        }
    }
</script>
