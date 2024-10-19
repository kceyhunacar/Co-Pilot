 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
 <div class="sidebar-menu">
     <div class="sidebar-header">
         {{-- <div class="col-md-6 col-sm-8 clearfix"> --}}

         {{-- </div> --}}
         <div class="logo">
             <a href="{{ route('admin.dashboard') }}">

                 <img src="{{ asset('backend/Lita_Logo_Beyaz.png') }}" alt="Lita">

             </a>
         </div>
     </div>
     <div class="main-menu">
         <div class="menu-inner">
             <nav>
                 <ul class="metismenu" id="menu">
                     @if ($usr->can('dashboard.view'))
                         <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="ti-dashboard"></i>
                                 <span>Dashboard</span></a>
                         </li>
                     @endif


                
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Charter
                             </span></a>
                         <ul
                             class="collapse {{ Route::is('admin.charter.create') || Route::is('admin.charter.index') || Route::is('admin.charter.edit') || Route::is('admin.charter.show') ? 'in' : '' }}">
                             <li class="{{ Route::is('admin.charter.index') || Route::is('admin.charter.edit') ? 'active' : '' }}">
                                 <a href="{{ route('admin.charter.index') }}">All Charters</a>
                             </li>
                             <li class="{{ Route::is('admin.charter.create') ? 'active' : '' }}"><a href="{{ route('admin.charter.create') }}">Create Charter</a></li>
                         </ul>
                     </li>
                     <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                User
                            </span></a>
                        <ul
                            class="collapse {{ Route::is('admin.user.create') || Route::is('admin.user.index') || Route::is('admin.user.edit') || Route::is('admin.user.show') ? 'in' : '' }}">
                            <li class="{{ Route::is('admin.user.index') || Route::is('admin.user.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.index') }}">All Users</a>
                            </li>
                            <li class="{{ Route::is('admin.user.create') ? 'active' : '' }}"><a href="{{ route('admin.user.create') }}">Create User</a></li>
                        </ul>
                        </li>

                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Type
                             </span></a>
                         <ul
                             class="collapse {{ Route::is('admin.type.create') || Route::is('admin.type.index') || Route::is('admin.type.edit') || Route::is('admin.type.show') ? 'in' : '' }}">
                             <li class="{{ Route::is('admin.type.index') || Route::is('admin.type.edit') ? 'active' : '' }}">
                                 <a href="{{ route('admin.type.index') }}">All Types</a>
                             </li>
                             <li class="{{ Route::is('admin.type.create') ? 'active' : '' }}"><a href="{{ route('admin.type.create') }}">Create Type</a></li>
                         </ul>
                     </li>

                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Feature
                             </span></a>
                         <ul
                             class="collapse {{ Route::is('admin.feature.create') || Route::is('admin.feature.index') || Route::is('admin.feature.edit') || Route::is('admin.feature.show') ? 'in' : '' }}">
                             <li class="{{ Route::is('admin.feature.index') || Route::is('admin.feature.edit') ? 'active' : '' }}">
                                 <a href="{{ route('admin.feature.index') }}">All Features</a>
                             </li>
                             <li class="{{ Route::is('admin.feature.create') ? 'active' : '' }}"><a href="{{ route('admin.feature.create') }}">Create Feature</a></li>
                         </ul>
                     </li>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Destination
                             </span></a>
                         <ul
                             class="collapse {{ Route::is('admin.destination.create') || Route::is('admin.destination.index') || Route::is('admin.destination.edit') || Route::is('admin.destination.show') ? 'in' : '' }}">
                             <li class="{{ Route::is('admin.destination.index') || Route::is('admin.destination.edit') ? 'active' : '' }}">
                                 <a href="{{ route('admin.destination.index') }}">All Destinations</a>
                             </li>
                             <li class="{{ Route::is('admin.destination.create') ? 'active' : '' }}"><a href="{{ route('admin.destination.create') }}">Create Destination</a>
                             </li>
                         </ul>
                     </li>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Feature Category
                             </span></a>
                         <ul
                             class="collapse {{ Route::is('admin.featurecategory.create') || Route::is('admin.featurecategory.index') || Route::is('admin.featurecategory.edit') || Route::is('admin.featurecategory.show') ? 'in' : '' }}">
                             <li class="{{ Route::is('admin.featurecategory.index') || Route::is('admin.featurecategory.edit') ? 'active' : '' }}">
                                 <a href="{{ route('admin.featurecategory.index') }}">All Feature Category</a>
                             </li>
                             <li class="{{ Route::is('admin.featurecategory.create') ? 'active' : '' }}"><a href="{{ route('admin.featurecategory.create') }}">Create Feature
                                     Category</a></li>
                         </ul>
                     </li>



                     @if ($usr->can('role.create') || $usr->can('role.view') || $usr->can('role.edit') || $usr->can('role.delete'))
                         <li>
                             <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                     Roles & Permissions
                                 </span></a>
                             <ul
                                 class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                                 @if ($usr->can('role.view'))
                                     <li class="{{ Route::is('admin.roles.index') || Route::is('admin.roles.edit') ? 'active' : '' }}"><a
                                             href="{{ route('admin.roles.index') }}">All Roles</a></li>
                                 @endif
                                 @if ($usr->can('role.create'))
                                     <li class="{{ Route::is('admin.roles.create') ? 'active' : '' }}"><a href="{{ route('admin.roles.create') }}">Create Role</a></li>
                                 @endif
                             </ul>
                         </li>
                     @endif


                     @if ($usr->can('admin.create') || $usr->can('admin.view') || $usr->can('admin.edit') || $usr->can('admin.delete'))
                         <li>
                             <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                                     Admins
                                 </span></a>
                             <ul
                                 class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">

                                 @if ($usr->can('admin.view'))
                                     <li class="{{ Route::is('admin.admins.index') || Route::is('admin.admins.edit') ? 'active' : '' }}"><a
                                             href="{{ route('admin.admins.index') }}">All Admins</a></li>
                                 @endif

                                 @if ($usr->can('admin.create'))
                                     <li class="{{ Route::is('admin.admins.create') ? 'active' : '' }}"><a href="{{ route('admin.admins.create') }}">Create Admin</a></li>
                                 @endif
                             </ul>
                         </li>
                     @endif

                 </ul>
             </nav>
         </div>
     </div>
 </div>
 <!-- sidebar menu area end -->
