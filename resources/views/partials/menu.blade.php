<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route("admin.home") }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            @can('users_manage')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-users nav-icon">

                        </i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                    @role('administrator')
                    <!--li class="nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                    </li-->
                    @endrole
                     @role('administrator')
                        <li class="nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-briefcase nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
						 <!--li class="nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-percentage nav-icon">

                                </i>
                                Comisións
                            </a>
                        </li-->
                        @endrole
                        <li class="nav-item">
                            <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    </ul>
                </li>
           @endcan
            @can('manage_product')
           <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-boxes nav-icon">

                    </i>
                    Productos
                </a>
            </li>
            
            @endcan

            @can('manage_client')
                <li class="nav-item">
                    <a href="{{ route('admin.clients.index') }}" class="nav-link {{ request()->is('admin/clients') || request()->is('admin/clients/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-user-plus nav-icon">

                        </i>
                        Clientes
                    </a>
                </li>
				<li class="nav-item">
                    <a href="{{ route('admin.checkin') }}" class="nav-link {{ request()->is('admin/check-in') || request()->is('admin/check-in/*') ? 'active' : '' }}">
                        <i class="fa fa-map-marker nav-icon"></i> Check-in 
                    </a>
                </li>
            @endcan

            @can('manage_order')
                <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->is('admin/orders') || request()->is('admin/orders/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-list-alt nav-icon">

                    </i>
                    Pedidos
                </a>
            </li>
            @endcan
            @can('manage_order')
            <li class="nav-item">
                <a href="{{ route('admin.notacredit.index') }}" class="nav-link {{ request()->is('admin/nota-de-credito-list') || request()->is('admin/nota-de-credito-list/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-list-alt nav-icon">

                    </i>
                    NC
                </a>
            </li>
			
			 <li class="nav-item">
                <a href="{{ route('admin.salesReports') }}" class="nav-link {{ request()->is('admin/sales-reports') || request()->is('admin/sales-reports/*') ? 'active' : '' }}">
                    <i class="fa-fw fas fa-list-alt nav-icon">

                    </i>
                    Reportes de Ventas
                </a>
            </li>
            @endcan
            @can('manage_invoice')
                <li class="nav-item">
                    <a href="{{ route("admin.invoices.index") }}" class="nav-link {{ request()->is('admin/invoices') || request()->is('admin/invoices/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-file-invoice nav-icon">

                        </i>
                        {{ trans('cruds.menu.manageinvoice') }}
                    </a>
                </li>
           @endcan
           @can('manage_receipt')
                <li class="nav-item">
                            <a href="{{ route("admin.receipts.index") }}" class="nav-link {{ request()->is('admin/receipts') || request()->is('admin/receipts/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-receipt nav-icon">

                                </i>
                                {{ trans('cruds.menu.managereceipt') }}
                            </a>
                </li>
                    
           @endcan
           @can('auth_cash_payment')
                <li class="nav-item">
                            <a href="{{ route("admin.receipts.cash") }}" class="nav-link {{ request()->is('admin/receipts/cash-transactions') || request()->is('admin/receipts/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-receipt nav-icon">

                                </i>
                                 {{ trans('cruds.menu.cashtran') }}
                            </a>
                        </li>
                    
           @endcan
        @can('manage_report')
           <li class="nav-item">
                            <a href="{{ route("admin.reports.index") }}" class="nav-link {{ request()->is('admin/reports') || request()->is('admin/reports/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-receipt nav-icon">

                                </i>
                                {{ trans('cruds.menu.reports') }}
                            </a>
                </li>
        @endcan
            <li class="nav-item">
                <a href="{{ route('auth.change_password') }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-key">

                    </i>
                    {{ trans('cruds.menu.changepass') }}
                </a>
            </li>
            @can('manage_web_setting')
            <li class="nav-item">
                        <a href="{{ route("admin.settings.index") }}" class="nav-link {{ request()->is('admin/settings') || request()->is('admin/settings/*') ? 'active' : '' }}">
                            <i class="fa-fw fas fa-cog nav-icon">

                            </i>
                            {{ trans('cruds.menu.settings') }}
                        </a>
                    </li>
            @endcan
            @can('replace_faulty_items')
                <li class="nav-item">
                    <a href="{{ route('admin.replace-item.index') }}" class="nav-link {{ request()->is('admin/replace-item') || request()->is('admin/replace-item/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-cog nav-icon">

                        </i>
                        Artículo Reemplazar
                    </a>
                </li>
            @endcan
            @can('pick_faulty_items')
                <li class="nav-item">
                    <a href="{{ route('admin.warranty-items.index') }}" class="nav-link {{ request()->is('admin/warranty-items') || request()->is('admin/warranty-items/*') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-cog nav-icon">

                        </i>
                        Artículos de garantía
                    </a>
                </li>
            @endcan
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>