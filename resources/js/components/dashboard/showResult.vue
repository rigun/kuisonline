<template id="admin-list">
<div class="contentlist">
  <div class="flex-container" >
    <div class="columns m-t-10">
      <div class="column">
        <h1 class="title">Quiz </h1>
      </div>
      <div class="column" style="text-align: right">
          Jumlah kesempatan anda : {{kesempatan}}
      </div>
      </div>
      <div class="card" v-if="soal != null && kesempatan > 0">
          <div class="card-content">
              <h1>Tim <strong>{{name}}</strong> sedang menjawab soal : </h1>
              <h1>{{soal.quiz}}</h1>
          </div>
      </div>
      <div class="card" v-else>
          <div class="card-content">
              <h1>Nilai Tim <strong>{{name}}</strong> adalah : {{nilai}}</h1>
              <b-table :data="quizsList" :paginated="true" :per-page="perPage" :current-page.sync="currentPage" :loading="loadData" :pagination-simple="true" :narrowed="true" :mobile-cards="true" :striped="true" :hoverable="true" :default-sort-direction="defaultSortDirection" default-sort="value">
            <template slot-scope="props">
                <b-table-column label="No." sortable>{{ props.index + 1 }}</b-table-column>
                <b-table-column field="quiz" label="Soal" sortable>{{ props.row.quiz.quiz }}</b-table-column>
                <b-table-column field="value" label="Bobot" sortable>{{ props.row.quiz.value }}</b-table-column>
                <b-table-column field="jawaban" label="Jawaban" sortable>{{ props.row.option.option }}</b-table-column>
                <b-table-column label="Keterangan" sortable>
                    <v-chip v-if="props.row.status == 1" color="green" dark>Benar</v-chip>
                    <v-chip v-if="props.row.status == 0" color="red" dark>Salah</v-chip>
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
          </div>
      </div>
       <v-snackbar v-model="snackbar" :color="color" :multi-line="true" :timeout="3000">
          {{ text }} <v-btn dark flat @click="snackbar = false"> Close </v-btn>
        </v-snackbar>
</div>

</div>
</template>

<script>
export default {
    mounted(){
        this.getData()
    },
    data(){
        return {
            loadData: false,
            isPaginated: true,
            isPaginationSimple: true,
            defaultSortDirection: 'desc',
            currentPage: 1,
            perPage: 10,
            soal: {},
            jawaban: {},
            kesempatan: 0,
            nilai: 0,
            data: {
                quiz_id: -1,
                option_id: -1
            },
            snackbar: false,
            color: 'red',
            text: '',
            load: false,
            name: ''
        }
    },
    computed:{
        quizsList(){
            if(this.jawaban.length){
                return this.jawaban.filter((row, index) => {
                        return true;
                });
            }
        }
    },
    methods: {
        getData(){
            var config = {
              headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
              }
            }
            var uri = '/api/showquiz/' + this.$route.params.id
            axios.get(uri,config).then(response =>{
              this.soal = response.data.Soal
              this.nilai = response.data.Nilai
              this.kesempatan = response.data.Kesempatan
              this.jawaban = response.data.Jawaban
              this.name = response.data.Nama
            })
        },
        sendData(){
            this.load = true
            var uri
            var config = {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }
              uri = '/api/answer'
              this.data.quiz_id = this.soal.id
            axios.post(uri,this.data,config).then(response =>{
              this.snackbar = true;
              this.text = 'Jawaban anda berhasil di simpan';
              this.color = 'green';
              this.load = false
              this.getData();
            }).catch(error =>{
                console.log(error)
              this.snackbar = true;
              this.text = 'Coba lagi';
              this.color = 'red';
              this.load = false
            })
          },
    }
}
</script>
