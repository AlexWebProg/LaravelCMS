<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.main') }}" class="brand-link">
        <img src="{{ asset('dist/img/BBLogo.png') }}" alt="BB Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Подписки</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user1-128x128.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.main') }}"
                       class="nav-link {{ (request()->route()->named('admin.main')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-home" aria-hidden="true"></i>
                        <p>
                            Рабочий стол
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}"
                       class="nav-link {{ (request()->route()->named('admin.user.*')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-users" aria-hidden="true"></i>
                        <p>
                            Пользователи
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ (request()->route()->named('admin.subRequests.*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->route()->named('admin.subRequests.*')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-envelope-o" aria-hidden="true"></i>
                        <p>
                            Заявки по подпискам
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($arSubRequestBasicInfo as $arSubRequest)
                            <li class="nav-item">
                                <a href="{{ route('admin.subRequests.index', $arSubRequest['status_code']) }}" class="nav-link {{ (request()->route()->named('admin.subRequests.*') && request()->route()->parameters['status'] == $arSubRequest['status_code']) ? 'active' : '' }}">
                                    <i class="{{ $arSubRequest['icon'] }} nav-icon" aria-hidden="true"></i>
                                    <p>{{ $arSubRequest['status_name_many'] }} <span class="badge badge-{{ $arSubRequest['color_type'] }} right">{{ $arSubRequest['requests_qnt'] }}</span></p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item {{ (request()->route()->named('admin.subscribes.*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->route()->named('admin.subscribes.*')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-address-card-o" aria-hidden="true"></i>
                        <p>
                            Подписки
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.subscribes.index') }}" class="nav-link {{ (request()->route()->named('admin.subscribes.index') || request()->route()->named('admin.subscribes.edit')) ? 'active' : '' }}">
                                <i class="fa fa-list nav-icon" aria-hidden="true"></i>
                                <p>Список</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.subscribes.create.chooseSubscriber') }}" class="nav-link {{ (request()->route()->named('admin.subscribes.create.chooseSubscriber') || request()->route()->named('admin.subscribes.create')) ? 'active' : '' }}">
                                <i class="fa fa-plus nav-icon" aria-hidden="true"></i>
                                <p>Добавить</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ (request()->route()->named('admin.subscribeSettings.*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->route()->named('admin.subscribeSettings.*')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-tasks" aria-hidden="true"></i>
                        <p>
                            Параметры подписок
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($arSubscribeSettingsTypes as $arSubscribeSettingsType)
                            <li class="nav-item">
                                <a href="{{ route('admin.subscribeSettings.index', $arSubscribeSettingsType['code']) }}" class="nav-link {{ (request()->route()->named('admin.subscribeSettings.*') && request()->route()->parameters['type'] == $arSubscribeSettingsType['code']) ? 'active' : '' }}">
                                    <i class="{{ $arSubscribeSettingsType['icon'] }} nav-icon" aria-hidden="true"></i>
                                    <p>{{ $arSubscribeSettingsType['short_name'] }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item {{ (request()->route()->named('admin.subscribers.*')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->route()->named('admin.subscribers.*')) ? 'active' : '' }}">
                        <i class="nav-icon fa fa-users" aria-hidden="true"></i>
                        <p>
                            Подписчики
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.subscribers.index') }}" class="nav-link {{ (request()->route()->named('admin.subscribers.index') || request()->route()->named('admin.subscribers.edit')) ? 'active' : '' }}">
                                <i class="fa fa-list nav-icon" aria-hidden="true"></i>
                                <p>Список</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.subscribers.create') }}" class="nav-link {{ (request()->route()->named('admin.subscribers.create')) ? 'active' : '' }}">
                                <i class="fa fa-plus nav-icon" aria-hidden="true"></i>
                                <p>Добавить</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
