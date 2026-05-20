<aside class="main-sidebar elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="{{ route('admin.home') }}" class="brand-link">
        <span style="display:flex; align-items:center; gap:10px;">
            <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA" style="width:34px; height:34px; border-radius:50%; flex-shrink:0; object-fit:cover;">
            <span class="brand-text">COSECSA</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Trainees --}}
                @can('trainee_access')
                <li class="nav-item">
                    <a href="{{ route('admin.trainees.index') }}" class="nav-link {{ request()->is('admin/trainees*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-user-graduate"></i>
                        <p>{{ trans('cruds.trainee.title') }}</p>
                    </a>
                </li>
                @endcan

                {{-- Facilitators --}}
                @can('speaker_access')
                <li class="nav-item">
                    <a href="{{ route('admin.speakers.index') }}" class="nav-link {{ request()->is('admin/speakers*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-chalkboard-teacher"></i>
                        <p>{{ trans('cruds.speaker.title') }}</p>
                    </a>
                </li>
                @endcan

                {{-- Training Materials --}}
                @can('training_material_access')
                <li class="nav-item">
                    <a href="{{ route('admin.training-materials.index') }}" class="nav-link {{ request()->is('admin/training-materials*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-book"></i>
                        <p>{{ trans('cruds.trainingMaterial.title') }}</p>
                    </a>
                </li>
                @endcan

                {{-- Timetable --}}
                @can('schedule_access')
                <li class="nav-item">
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link {{ request()->is('admin/schedules*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-calendar-alt"></i>
                        <p>{{ trans('cruds.schedule.title') }}</p>
                    </a>
                </li>
                @endcan

                {{-- User Management --}}
                @can('user_management_access')
                <li class="nav-item has-treeview {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') ? 'menu-open' : '' }}">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-users-cog"></i>
                        <p>
                            User Management
                            <i class="right fa fa-fw fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('user_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user"></i>
                                <p>{{ trans('cruds.user.title') }}</p>
                            </a>
                        </li>
                        @endcan
                        @can('role_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-briefcase"></i>
                                <p>{{ trans('cruds.role.title') }}</p>
                            </a>
                        </li>
                        @endcan
                        @can('permission_access')
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt"></i>
                                <p>{{ trans('cruds.permission.title') }}</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                {{-- Quizzes --}}
                <li class="nav-item">
                    <a href="{{ route('admin.quizzes.index') }}" class="nav-link {{ request()->is('admin/quizzes*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-question-circle"></i>
                        <p>Quizzes</p>
                    </a>
                </li>

                {{-- Discussions --}}
                <li class="nav-item">
                    <a href="{{ route('admin.discussions.index') }}" class="nav-link {{ request()->is('admin/discussions*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-comments"></i>
                        <p>Discussions</p>
                    </a>
                </li>

                {{-- Messages --}}
                <li class="nav-item">
                    <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->is('admin/messages*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-envelope"></i>
                        <p>Messages</p>
                    </a>
                </li>

                {{-- Certificates --}}
                <li class="nav-item">
                    <a href="{{ route('admin.certificates.index') }}" class="nav-link {{ request()->is('admin/certificates*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-certificate"></i>
                        <p>Certificates</p>
                    </a>
                </li>

                {{-- Facilitator Directory --}}
                <li class="nav-item">
                    <a href="{{ route('admin.directory.index') }}" class="nav-link {{ request()->is('admin/directory*') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-address-book"></i>
                        <p>Directory</p>
                    </a>
                </li>

                {{-- Settings --}}
                @can('setting_access')
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
                @endcan

                {{-- Logout --}}
                <li class="nav-item" style="margin-top:auto;">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-fw fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
