<template>
<div class="card" style="margin-bottom: 30px;">
        <p class="timeBox" v-if="cases != ''">
        <!-- {{cases.title}} - {{hours}}:{{minutes}}:{{seconds}} -->
        {{cases.title}}
    </p>

    <div class="lock">
    </div>
    <div class="background-ma2019">
    </div>
    
    <div class="card-content" style="margin-top: 20px;">
        <div class="columns">
            <div class="column is-one-quarter" style="text-align:center">
                <img src="/images/LOGO HMPSM BARU.png" alt="" style="width: 150px; align-self: center;">
            </div>
            <div class="column" style="text-align:center">
                MANAGEMENT COMPETITION 2019 <br/>
                HIMPUNAN MAHASISWA PROGRAM STUDI MANAJEMEN <br/>
                        FAKULTAS BISNIS DAN EKONOMIKA <br/>
                UNIVERSITAS ATMA JAYA YOGYAKARTA <br/>

            </div>
            <div class="column is-one-quarter" style="text-align:center">
                <img src="/images/LOGO MC BARU BG TERANG.png" alt="" style="width: 150px; align-self: center;">
            </div>
        </div>
        <hr>
        <div class="isiText" v-if="cases == ''">
            <p style="text-align:center">
                Belum ada soal untuk ditampilkan
            </p>           
        </div>
        <div v-else>
            <div class="soalKasus" v-html="cases.body"></div>
        </div>

    </div>
</div>
</template>
<style>
.timeBox{
    text-align: right;
    width: 100%;
    padding: 7px 13px;
    font-size: 24px;
    color: green;
    background: greenyellow;
    position: fixed;
    z-index: 100000;
    right: 0;
    top: 66px;
}
.soalKasus img{
    margin: auto;
}
.lock{
    background: white;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 0;
}
.background-ma2019{
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    opacity: 0.1;
    background: url('/images/LOGO MC BARU BG TERANG.png');
    background-size: 100%;
    background-position: center;
    margin-top: 209px;
    margin-left: 17px;
    margin-right: 17px;
}
.card{
  position: relative
}
</style>

<script>
import moment from 'moment'

export default {
 mounted () {
    this.getData()
    Echo.channel('caseChannel')
    .listen('CaseClosed', (data) => {
      if(data.status == 1){
        location.reload()
      }else{
        this.cases = ""
      }
    });
  },
  data () {
    return {
      search: '',
      cases: {},
      deleteDialog: false,
      load: false,
      snackbar: false,
      text: '',
      color: null,
      reset: false,
      deleteId: -1,
      loading: false,
      interval: null,
    now: Math.trunc((new Date()).getTime() / 1000),
    date: null,
      loadData: true,
      isPaginated: true,
      isPaginationSimple: true,
      defaultSortDirection: 'desc',
      currentPage: 1,
      perPage: 10,
    }
  },
  // computed:{
  //       seconds() {
  //           var s;
  //           s = (this.date - this.now) % 60
  //           if(s > -1){
  //               return this.twoDigit(s);
  //           }else{
  //               return '00';
  //           }
  //       },

  //       minutes() {
  //           var m;
  //           m = Math.trunc((this.date - this.now) / 60) % 60
  //           if(m > -1){
  //               return this.twoDigit(m);
  //           }else{
  //               return '00';
  //           }
  //       },

  //       hours() {
  //           var h;
  //           h = Math.trunc((this.date - this.now) / 60 / 60) % 24
  //           if(h > -1){
  //               return this.twoDigit(h);
  //           }else{
  //               return '00';
  //           }
  //       },
  //   },
  //    destroyed(){
  //           clearInterval(this.interval);
  //       },
  methods: {
    goto (name) {
      this.$router.push({name: name})
    },
    update(id){
      this.$router.push({name: 'editFormCase',params:{id: id}})
    },
      // twoDigit(value){
      //       if(value.toString().length <= 1)
      //           {
      //               return "0"+value.toString();
      //           }
      //           return value.toString();
      //   },
        //  countDown(date){
        //     this.date = Math.trunc(Date.parse(moment(date).add(60, 'm')) / 1000)
        //     this.now = Math.trunc((new Date()).getTime() / 1000);
        //     if(this.date - this.now > 0){
        //         this.interval = setInterval(() => {
        //             if(this.date - this.now > 0){
        //               console.log('interval')
        //                 this.now = Math.trunc((new Date()).getTime() / 1000);
        //             }else{
        //                 this.updateStatus(this.cases.id,0);
        //             }
        //         },1000);
        //     }
        // },
    getData () {
      this.editDialog = false
      this.deleteDialog = false
      var uri
      var config = {
        headers: {
          Authorization: 'Bearer ' + localStorage.getItem('token')
        }
      }
      uri = '/api/onkasus'
      axios.get(uri, config).then(response => {
        this.cases = response.data
        
        this.loadData = false
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
        location.reload(); 
      }).catch(error => {
        this.snackbar = true
        this.text = 'Coba lagi'
        this.color = 'red'
      })
    }
  }
}
</script>
