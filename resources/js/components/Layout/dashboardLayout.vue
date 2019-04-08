<template>
  <v-layout
    wrap
  >
  <div class="background-all"></div>
    <v-toolbar :class="{'sideActive': mini, 'sideDeactive':!mini, 'sideToggle':!drawer}" class="toolbarFixed">
        <v-toolbar-side-icon  @click.stop="showSide()"></v-toolbar-side-icon>
        <v-toolbar-title>Title</v-toolbar-title>
    </v-toolbar>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="mini"
      absolute
      :permanent="drawer"
      dark
      class="sideBarFixed"
    >
      <v-list class="pa-1">

        <v-list-tile avatar tag="div">
          <v-list-tile-avatar>
            <v-icon>account_circle</v-icon>
          </v-list-tile-avatar>

          <v-list-tile-content>
            <v-list-tile-title>John Leider</v-list-tile-title>
          </v-list-tile-content>

        </v-list-tile>
      </v-list>

      <v-list class="pt-0" dense>
        <v-divider light></v-divider>
        <v-list-tile @click="routerGoto('changePassword')"><v-list-tile-action><v-icon>dashboard</v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Home</v-list-tile-title></v-list-tile-content></v-list-tile>
        <v-list-tile @click="routerGoto('UserManager')" v-if="role == 'admin'"><v-list-tile-action><v-icon>group </v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Menejemen Peserta</v-list-tile-title></v-list-tile-content></v-list-tile>
        <v-list-tile @click="routerGoto('QuizManager')" v-if="role == 'admin'"><v-list-tile-action><v-icon>group </v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Menejemen Kuis</v-list-tile-title></v-list-tile-content></v-list-tile>
        <v-list-tile @click="routerGoto('Quiz')"><v-list-tile-action><v-icon>group </v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Kuis</v-list-tile-title></v-list-tile-content></v-list-tile>
        <v-list-tile @click="routerGoto('changePassword')"><v-list-tile-action><v-icon>lock </v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Password</v-list-tile-title></v-list-tile-content></v-list-tile>
        <v-list-tile @click="routerGoto('logoutDialog')"><v-list-tile-action><v-icon>power_settings_new </v-icon></v-list-tile-action><v-list-tile-content><v-list-tile-title>Keluar</v-list-tile-title></v-list-tile-content></v-list-tile>
      </v-list>
    </v-navigation-drawer>
     <!-- delete -->
                  <v-dialog v-model="logoutDialog" persistent max-width="290">
                    <v-card>
                      <v-card-title class="headline">Apakah Anda ingin keluar ?</v-card-title>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="logoutDialog = false;">Batal</v-btn>
                        <v-btn color="red darken-1" flat @click="routerGoto('Logout')">Keluar</v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
        <!-- delete -->
        <div id="appDashboard">
            <main class="dashboardContent" :class="{'sideActive': mini, 'sideDeactive':!mini,'sideToggle':!drawer}" >
            <transition name="fade">
            <router-view></router-view>
            </transition>
            </main>
        </div>
  </v-layout>
</template>
<script>
  export default {
    mounted () {
        this.cekSize()
    },
    data () {
      return {
        role: localStorage.getItem('role'),
        logoutDialog: false,
        drawer: true,
        mini: false,
        items: [
          { title: 'Home', icon: 'dashboard', click: 'changePassword' },
          { title: 'Peserta', icon: 'group', click: 'UserManager' },
          { title: 'Kuis', icon: 'group', click: 'QuizManager' },
          { title: 'Kuis', icon: 'group', click: 'QuizManager' },
          { title: 'Ubah Password', icon: 'lock', click: 'changePassword' },
          { title: 'Keluar', icon: 'power_settings_new', click: 'logoutDialog' }
        ],
        mini: false,
        right: null,
        window: {
            width: 0,
            height: 0
        }
      }
    },
    created() {
        window.addEventListener('resize', this.handleResize)
        this.handleResize();
    },
    destroyed() {
        window.removeEventListener('resize', this.handleResize)
    },
    methods:{
        routerGoto(name) {
            if(name == 'logoutDialog'){
                this.logoutDialog = true
                return
            }
            this.$router.push({name: name})
        },
        handleResize() {
           this.window.width = window.innerWidth;
        },
        cekSize(){
            if(this.window.width < 960){
               this.mini = true
           }else{
               this.mini = false
           }
        },
        showSide(){
            if(this.window.width < 960){
               this.drawer = !this.drawer
            }else{
               this.mini = !this.mini
            }
        }
    }
  }
</script>