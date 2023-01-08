<template>
    <div class="wrapper">
      <!-- Preloader -->
      <!-- <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="/images/preloader.jpg" alt="AdminLTELogo" height="100" width="100">
      </div> -->

      <!-- Navbar -->
      <nav class="main-header bg-transparent border-0 navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item font-weight-bold">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="color-6-red fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item font-weight-bold dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                  <i class="color-6-red fas fa-bell"></i>
                  <!-- <span class="badge badge-warning navbar-badge">15</span> -->
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                  <span class="dropdown-item dropdown-header">15 Notifications</span>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                  <i class="fas fa-envelope mr-2"></i> 4 new messages
                  <span class="float-right text-muted text-sm">3 mins</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                  <i class="fas fa-users mr-2"></i> 8 friend requests
                  <span class="float-right text-muted text-sm">12 hours</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                  <i class="fas fa-file mr-2"></i> 3 new reports
                  <span class="float-right text-muted text-sm">2 days</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
              </div>
            </li>
            <li class="nav-item font-weight-bold">
              <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                  <i class="color-6-red fas fa-expand-arrows-alt"></i>
              </a>
            </li>
            <li class="nav-item font-weight-bold">
              <form method="POST" @submit.prevent="logout">
                <button type="submit" class="nav-link btn btn-default bg-transparent border-0">
                  <i class="color-6-red fas fa-sign-out-alt"></i>
                </button>
              </form>
            </li>
        </ul>
      </nav>

      <!-- Main Sidebar Container -->
      <!-- elevation-4 -->
      <aside class="main-sidebar bg-color-6-red " style="height:100vh;">
        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-0 d-flex flex-row">
            <div class="image">
              <img src="/images/default-profile-icon.png" style="width:30px;height:30px;object-fit:cover;border: 2px; border-color:#eee;" class="img-circle border-primary" alt="User Image">
            </div>
            <div class="info">
              <span class="d-flex font-weight-bold color-4-white text-wrap" >{{ this.$page.props.user.first_name +' '+ this.$page.props.user.last_name }}</span>
            </div>
          </div>

          <div class="user-panel text-wrap d-inline">
            <div class="image">
              <span v-for="role in this.UserRoles" :key="role" class="badge badge-pill text-wrap badge-custom mr-1 my-1 text-xs">
                <span v-if="role == 'IFodderContentManager'">iFodder</span>
                <span v-if="role == 'IHealthContentManager'">iHealth</span>
                <span v-if="role == 'ReportsUser'">Reports</span>
                <span v-if="role == 'Vet'">Vet</span>
                <span v-if="role == 'VetAide'">Vet Aide</span>
                <span v-if="role == 'Admin'">Admin</span>
              </span> 
            </div>
          </div>

          <hr style="border-top:1px solid #fff;">

          
          <!-- Sidebar Menu -->
          <nav >
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin') && this.UserRoles[0] == 'Admin'">
                <a href="#" class="nav-link">
                  <i class=" nav-icon fas fa-user-cog"></i>
                  <p class="">
                    Admin
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item font-weight-bold">
                    <jet-nav-link jet-nav-link :href="route(this.UserRoles[0]+'.AdminConnections')" :active="route().current(this.UserRoles[0]+'.AdminConnections')" :class="'pl-2'">
                      <i class=" nav-icon fas fa-key"></i>
                        <span class="">
                          Connections
                        </span>
                    </jet-nav-link>
                  </li>

                  <li class="nav-item font-weight-bold">
                    <jet-nav-link jet-nav-link :href="route(this.UserRoles[0]+'.AdminConfig')" :active="route().current(this.UserRoles[0]+'.AdminConfig')" :class="'pl-2'">
                      <i class=" nav-icon fas fa-cog"></i>
                        <span class="">
                          Config
                        </span>
                    </jet-nav-link>
                  </li>
                </ul>
              </li>

            <!-- Show if admin -->
              <li class="nav-item font-weight-bold">
                <a href="#" class="nav-link">
                  <i class=" nav-icon fas fa-user-circle"></i>
                  <p class="">
                    Profile
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item font-weight-bold">
                    <jet-nav-link :href="route(this.UserRoles[0]+'.ChangePasswordPage')" :active="route().current(this.UserRoles[0]+'.ChangePasswordPage')" :class="'pl-2'">
                      <i class=" nav-icon fas fa-key"></i>
                        <span class="">
                          Change Password
                        </span>
                    </jet-nav-link>
                  </li>
                </ul>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin') && this.UserRoles[0] == 'Admin'" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminDashboard')" :active="route().current(this.UserRoles[0]+'.AdminDashboard')">
                  <i class="nav-icon fas fa-chart-area"></i>
                  Dashboard
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Vet') && this.UserRoles[0] == 'Vet'" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.VetDashboard')" :active="route().current(this.UserRoles[0]+'.VetDashboard')">
                  <i class="nav-icon fas fa-chart-area"></i>
                  Dashboard
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('VetAide') && this.UserRoles[0] == 'VetAide'" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.VetAideDashboard')" :active="route().current(this.UserRoles[0]+'.VetAideDashboard')">
                  <i class="nav-icon fas fa-chart-area"></i>
                  Dashboard
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('IHealthContentManager') && this.UserRoles[0] == 'IHealthContentManager'" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.IHealthContentManagerDashboard')" :active="route().current(this.UserRoles[0]+'.IHealthContentManagerDashboard')">
                  <i class="nav-icon fas fa-chart-area"></i>
                  Dashboard
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('IFodderContentManager') && this.UserRoles[0] == 'IFodderContentManager'" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.IFodderContentManagerDashboard')" :active="route().current(this.UserRoles[0]+'.IFodderContentManagerDashboard')">
                  <i class="nav-icon fas fa-chart-area"></i>
                  Dashboard
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('ReportsUser') && this.UserRoles[0] == 'ReportsUser'" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.ReportsUserDashboard')" :active="route().current(this.UserRoles[0]+'.ReportsUserDashboard')">
                  <i class="nav-icon fas fa-chart-area"></i>
                  Dashboard
                </jet-nav-link>
              </li>
              
              <!-- End of Dashboard -->

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin') || UserRoles.includes('Vet') || UserRoles.includes('VetAide') || UserRoles.includes('ReportsUser') || UserRoles.includes('IHealthContentManager') || UserRoles.includes('IFodderContentManager')" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminFarmers')" :active="route().current(this.UserRoles[0]+'.AdminFarmers')">
                  <i class=" nav-icon fas fa-tractor"></i>
                  <span class="">
                    Farmers
                  </span>
                </jet-nav-link>
              </li>
              <li class="nav-item font-weight-bold">
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminStaff')" :active="route().current(this.UserRoles[0]+'.AdminStaff')">
                  <i class=" nav-icon fas fa-people-carry "></i>
                  <span class="">
                    Users Management
                  </span>
                </jet-nav-link>
              </li>
              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin') || UserRoles.includes('Vet') || UserRoles.includes('VetAide') || UserRoles.includes('IFodderContentManager') || UserRoles.includes('IHealthContentManager') || UserRoles.includes('ReportsUser') " >
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminHealthGuide')" :active="route().current(this.UserRoles[0]+'.AdminHealthGuide')">
                  <i class=" nav-icon fas fa-file-medical-alt"></i>
                  <span class="">
                    Health Guide
                  </span>
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin')" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminDirectories')" :active="route().current(this.UserRoles[0]+'.AdminDirectories')">
                  <i class=" nav-icon fas fa-address-book"></i>
                    Directories
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold">
                <a href="#" class="nav-link">
                  <i class=" nav-icon fas fa-chart-pie"></i>
                  <p class="">
                    Reports
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item font-weight-bold">
                    <jet-nav-link :href="route(this.UserRoles[0]+'.AdminReportsHealthVisit')" :active="route().current(this.UserRoles[0]+'.AdminReportsHealthVisit')" :class="'pl-2'">
                      <i class=" nav-icon fas fa-bullhorn"></i>
                        <span class="">
                          Top Health Issues
                        </span>
                    </jet-nav-link>

                    <jet-nav-link v-if="this.UserRoles.includes('Admin')"  :href="route(this.UserRoles[0]+'.AdminVisitRatings')" :active="route().current(this.UserRoles[0]+'.AdminVisitRatings')" :class="'pl-2'">
                      <i class=" nav-icon fas fa-grin-stars"></i>
                        <span class="">
                          Visit Rating
                        </span>
                    </jet-nav-link>

                    <jet-nav-link :href="route(this.UserRoles[0]+'.AdminReports')" :active="route().current(this.UserRoles[0]+'.AdminReports')" :class="'pl-2'">
                      <i class=" nav-icon fas fa-bullhorn"></i>
                        <span class="">
                          Other Reports
                        </span>
                    </jet-nav-link>
                  </li>
                </ul>
              </li>
              
              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin') || UserRoles.includes('Vet') || UserRoles.includes('VetAide') || UserRoles.includes('IFodderContentManager') || UserRoles.includes('ReportsUser') || UserRoles.includes('IHealthContentManager')" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminCategories')" :active="route().current(this.UserRoles[0]+'.AdminCategories')">
                  <i class=" nav-icon fas fa-calendar-minus"></i>
                    <span class="">
                      Categories and Feeds
                    </span>
                </jet-nav-link>
              </li>

              <li class="nav-item font-weight-bold" v-if="UserRoles.includes('Admin') || UserRoles.includes('Vet') || UserRoles.includes('VetAide') || UserRoles.includes('IFodderContentManager') || UserRoles.includes('ReportsUser') || UserRoles.includes('IHealthContentManager')" >
                <jet-nav-link :href="route(this.UserRoles[0]+'.AdminPricing')" :active="route().current(this.UserRoles[0]+'.AdminPricing')">
                  <i class=" nav-icon fas fa-tag"></i>
                    <span class="">
                      Pricing
                    </span>
                </jet-nav-link>
              </li> 

               <!-- <li class="nav-item font-weight-bold">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-chart-pie"></i>
                  <p>
                    Charts
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item font-weight-bold">
                    <a href="pages/charts/chartjs.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>ChartJS</p>
                    </a>
                  </li>
                  <li class="nav-item font-weight-bold">
                    <a href="pages/charts/flot.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Flot</p>
                    </a>
                  </li>
                  <li class="nav-item font-weight-bold">
                    <a href="pages/charts/inline.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Inline</p>
                    </a>
                  </li>
                  <li class="nav-item font-weight-bold">
                    <a href="pages/charts/uplot.html" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>uPlot</p>
                    </a>
                  </li>
                </ul>
              </li> -->
              <!-- <li class="nav-header">EXAMPLES</li> -->
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
        
        <!-- Brand Logo -->
        <div class="container-fluid bg-white" style="position:absolute; bottom:0;">
          <div class="row px-2 pt-2">
            <div class="col mb-2">
              <img src="/images/sidebar/da_pcc.png" class="img-fluid" style="" alt="">
            </div>
            <div class="col mb-2">
              <img src="/images/sidebar/kbgan.png" class="img-fluid" style="" alt="">
            </div>
          </div>
        </div>

        <!-- <a :href="route('Admin.AdminDashboard')" class="brand-link" style="text-decoration:none; text-transform:none;">
          <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/Philippine_Carabao_Center.svg" class="brand-image rounded-circle" style="opacity: .8; object-fit:cover; width:35px; height:35px;">
        </a> -->
      </aside>

      <!-- Page Content -->
      <main class="my-3 bg-color-8-light-red-gray" style="overflow-y: auto;">
        <slot :UserRoles="UserRoles"></slot>
      </main>
    </div>
</template>

<script>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
import JetBanner from '@/Jetstream/Banner'
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetNavLink from '@/Jetstream/NavLink'

export default {
  components: {
    JetApplicationLogo,
    JetBanner,
    JetApplicationMark,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
  },

  data() {
    return {
      showingNavigationDropdown: false,
      roleData: null,
    }
  },
  props: [ 'user', 'UserRoles' ],
  methods: {
    switchToTeam(team) {
      this.$inertia.put(route('current-team.update'), {
        'team_id': team.id
      }, {
        preserveState: false
      })
    },

    logout() {
      this.$inertia.post(route('logout'));
    },
  },

  computed: {
    path() {
      return window.location.pathname
    },
  },
  created: function(){
    console.log("Admin Layout");
    console.log(window.location.pathname);
    console.log(this.UserRoles);
  },
  mounted: function(){
  }

}
</script>
