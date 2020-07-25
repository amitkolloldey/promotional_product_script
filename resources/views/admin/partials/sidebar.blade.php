<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @can('access manage users')
            <li class="nav-item has-treeview menu-open">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-users"></i>
                    <p>
                        Manage Users
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('view users')
                        <li class="nav-item">
                            <a href="{{route('users')}}"
                               class="nav-link {{ Request::path() == 'admin/users' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>All Users</p>
                            </a>
                        </li>
                    @endcan

                    @can('create user')
                        <li class="nav-item">
                            <a href="{{route('user_create')}}"
                               class="nav-link {{ Request::path() == 'admin/user/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add User</p>
                            </a>
                        </li>
                    @endcan

                    @can('view roles')
                        <li class="nav-item">
                            <a href="{{route('roles')}}"
                               class="nav-link {{ Request::path() == 'admin/roles' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                    @endcan

                    @can('create role')
                        <li class="nav-item">
                            <a href="{{route('role_create')}}"
                               class="nav-link {{ Request::path() == 'admin/role/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Role</p>
                            </a>
                        </li>
                    @endcan

                    @can('view permissions')
                        <li class="nav-item">
                            <a href="{{route('permissions')}}"
                               class="nav-link {{ Request::path() == 'admin/permissions' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                    @endcan

                    @can('create permission')
                        <li class="nav-item">
                            <a href="{{route('permission_create')}}"
                               class="nav-link {{ Request::path() == 'admin/permission/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Permission</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access store')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-shopping-bag"></i>
                    <p>
                        Store
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @can('view products')
                        <li class="nav-item">
                            <a href="{{route('products')}}"
                               class="nav-link {{ Request::path() == 'admin/products' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Products</p>
                            </a>
                        </li>
                    @endcan

                    @can('create product')
                        <li class="nav-item">
                            <a href="{{route('product_create')}}"
                               class="nav-link {{ Request::path() == 'admin/product/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Product</p>
                            </a>
                        </li>
                    @endcan

                    @can('view categories')
                        <li class="nav-item">
                            <a href="{{route('categories')}}"
                               class="nav-link {{ Request::path() == 'admin/categories' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                    @endcan

                    @can('create category')
                        <li class="nav-item">
                            <a href="{{route('category_create')}}"
                               class="nav-link {{ Request::path() == 'admin/category/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Category</p>
                            </a>
                        </li>
                    @endcan

                    @can('view personalisation types')
                        <li class="nav-item">
                            <a href="{{route('personalisation_types')}}"
                               class="nav-link {{ Request::path() == 'personalisation_types' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Personalisation Option Types</p>
                            </a>
                        </li>
                    @endcan

                    @can('create personalisation type')
                        <li class="nav-item">
                            <a href="{{route('personalisation_type_create')}}"
                               class="nav-link {{ Request::path() == 'admin/personalisation_type_create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Personalisation Option Type</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access orders')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-shopping-cart"></i>
                    <p>
                        Orders
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @can('view orders')
                        <li class="nav-item">
                            <a href="{{route('orders')}}"
                               class="nav-link {{ Request::path() == 'orders' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access quotations')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-tasks"></i>
                    <p>
                        Quotations
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @can('view quotations')
                        <li class="nav-item">
                            <a href="{{route('quotations')}}"
                               class="nav-link {{ Request::path() == 'quotations' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Quotations</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access questions')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-question-circle"></i>
                    <p>
                        Questions
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @can('view questions')
                        <li class="nav-item">
                            <a href="{{route('questions')}}"
                               class="nav-link {{ Request::path() == 'questions' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Questions</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access modules')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-briefcase"></i>
                    <p>
                        Modules
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('view primary colors')
                        <li class="nav-item">
                            <a href="{{route('primary_colors')}}"
                               class="nav-link {{ Request::path() == 'admin/primary_colors' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Primary Colors</p>
                            </a>
                        </li>
                    @endcan

                    @can('create primary color')
                        <li class="nav-item">
                            <a href="{{route('primary_color_create')}}"
                               class="nav-link {{ Request::path() == 'admin/primary_colors/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Primary Color</p>
                            </a>
                        </li>
                    @endcan

                    @can('view printing agencies')
                        <li class="nav-item">
                            <a href="{{route('printing_agencies')}}"
                               class="nav-link {{ Request::path() == 'admin/printingagencies' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Printing Agencies</p>
                            </a>
                        </li>
                    @endcan

                    @can('create printing agency')
                        <li class="nav-item">
                            <a href="{{route('printing_agency_create')}}"
                               class="nav-link {{ Request::path() == 'admin/printingagencies/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Printing Agency</p>
                            </a>
                        </li>
                    @endcan

                    @can('view quantity')
                        <li class="nav-item">
                            <a href="{{route('quantities')}}"
                               class="nav-link {{ Request::path() == 'admin/quantities' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Quantity</p>
                            </a>
                        </li>
                    @endcan

                    @can('create quantity')
                        <li class="nav-item">
                            <a href="{{route('quantity_create')}}"
                               class="nav-link {{ Request::path() == 'admin/quantity/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Quantity</p>
                            </a>
                        </li>
                    @endcan

                    @can('view manufacturers')
                        <li class="nav-item">
                            <a href="{{route('manufacturers')}}"
                               class="nav-link {{ Request::path() == 'admin/manufacturers' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Manufacturer</p>
                            </a>
                        </li>
                    @endcan

                    @can('create manufacturer')
                        <li class="nav-item">
                            <a href="{{route('manufacturer_create')}}"
                               class="nav-link {{ Request::path() == 'admin/manufacturer/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Manufacturer</p>
                            </a>
                        </li>
                    @endcan

                    @can('view personalisation options')
                        <li class="nav-item">
                            <a href="{{route('personalisation_options')}}"
                               class="nav-link {{ Request::path() == 'admin/personalisationoptions' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Personalisation Options</p>
                            </a>
                        </li>
                    @endcan

                    @can('create personalisation option')
                        <li class="nav-item">
                            <a href="{{route('personalisation_option_create')}}"
                               class="nav-link {{ Request::path() == 'admin/personalisationoption/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Personalisation Option</p>
                            </a>
                        </li>
                    @endcan

                    @can('view usb types')
                        <li class="nav-item">
                            <a href="{{route('usb_types')}}"
                               class="nav-link {{ Request::path() == 'admin/usb_types' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>USB Types</p>
                            </a>
                        </li>
                    @endcan

                    @can('create usb type')
                        <li class="nav-item">
                            <a href="{{route('usb_type_create')}}"
                               class="nav-link {{ Request::path() == 'admin/usb_type/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add USB Type</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access cms')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-file-o"></i>
                    <p>
                        CMS
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @can('view pages')
                        <li class="nav-item">
                            <a href="{{route('pages')}}"
                               class="nav-link {{ Request::path() == 'admin/pages' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Pages</p>
                            </a>
                        </li>
                    @endcan
                    @can('create page')
                        <li class="nav-item">
                            <a href="{{route('page_create')}}"
                               class="nav-link {{ Request::path() == 'admin/page/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Page</p>
                            </a>
                        </li>
                    @endcan
                </ul>

                <ul class="nav nav-treeview">
                    @can('view posts')
                        <li class="nav-item">
                            <a href="{{route('posts')}}"
                               class="nav-link {{ Request::path() == 'admin/posts' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Posts</p>
                            </a>
                        </li>
                    @endcan
                    @can('create post')
                        <li class="nav-item">
                            <a href="{{route('post_create')}}"
                               class="nav-link {{ Request::path() == 'admin/post/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Post</p>
                            </a>
                        </li>
                    @endcan
                </ul>

                <ul class="nav nav-treeview">
                    @can('view clients')
                        <li class="nav-item">
                            <a href="{{route('clients')}}"
                               class="nav-link {{ Request::path() == 'admin/clients' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Clients</p>
                            </a>
                        </li>
                    @endcan
                    @can('create client')
                        <li class="nav-item">
                            <a href="{{route('client_create')}}"
                               class="nav-link {{ Request::path() == 'admin/client/create' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Add Client</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access settings')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-cogs"></i>
                    <p>
                        Settings
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('view settings')
                        <li class="nav-item">
                            <a href="{{route('site_settings')}}"
                               class="nav-link {{ Request::path() == 'admin/settings' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    @endcan

                    @can('view messages')
                        <li class="nav-item">
                            <a href="{{route('messages')}}"
                               class="nav-link {{ Request::path() == 'admin/messages' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Messages</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access media')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-files-o"></i>
                    <p>
                        Media Library
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('unisharp.lfm.show')}}"
                           class="nav-link" target="_blank">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>All Media</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('access export import')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-upload"></i>
                    <p>
                        Export / Import
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    @can('export products')
                        <li class="nav-item">
                            <a href="{{route('products_export')}}"
                               class="nav-link {{ Request::path() == 'admin/products_export' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Export Products</p>
                            </a>
                        </li>
                    @endcan

                    @can('export categories')
                        <li class="nav-item">
                            <a href="{{route('categories_export')}}"
                               class="nav-link {{ Request::path() == 'admin/categories_export' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Export Categories</p>
                            </a>
                        </li>
                    @endcan

                    @can('export category markups')
                        <li class="nav-item">
                            <a href="{{route('category_markups_export')}}"
                               class="nav-link {{ Request::path() == 'admin/category_markups_export' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Export Category Markups</p>
                            </a>
                        </li>
                    @endcan

                    @can('export personalisation prices')
                        <li class="nav-item">
                            <a href="{{route('personalisation_prices_export')}}"
                               class="nav-link {{ Request::path() == 'admin/personalisation_prices_export' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Export Personalisation Prices</p>
                            </a>
                        </li>
                    @endcan

                    @can('export personalisation type markups')
                        <li class="nav-item">
                            <a href="{{route('personalisation_type_markups_export')}}"
                               class="nav-link {{ Request::path() == 'admin/personalisation_type_markups_export' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Export Personalisation Type Markups</p>
                            </a>
                        </li>
                    @endcan

                    @can('import products')
                        <li class="nav-item">
                            <a href="{{route('products_import')}}"
                               class="nav-link {{ Request::path() == 'admin/products_import' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Import Products</p>
                            </a>
                        </li>
                    @endcan

                    @can('import categories')
                        <li class="nav-item">
                            <a href="{{route('categories_import')}}"
                               class="nav-link {{ Request::path() == 'admin/import/categories' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Import Categories</p>
                            </a>
                        </li>
                    @endcan

                    @can('import category markups')
                        <li class="nav-item">
                            <a href="{{route('category_markups_import')}}"
                               class="nav-link {{ Request::path() == 'admin/import/category_markups' ? 'active' : '' }}">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Import Category Markups</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('access downloads')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fa fa-cloud-download"></i>
                    <p>
                        Downloads
                        <i class="right fa fa-angle-left"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ asset('storage/files/23/Sample%20Import%20Files/categories.xlsx') }}"
                           class="nav-link"
                           target="_blank">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>Categories Import Sample</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ asset('storage/files/23/Sample%20Import%20Files/products.xlsx') }}"
                           class="nav-link"
                           target="_blank">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>Products Import Sample</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ asset('storage/files/23/Sample%20Import%20Files/products_with_primary_colors.xlsx') }}"
                           class="nav-link"
                           target="_blank">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>Products With Primary Colors Import Sample</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ asset('storage/files/23/Sample%20Import%20Files/usb_products.xlsx') }}"
                           class="nav-link"
                           target="_blank">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>USB Products Import Sample</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ asset('storage/files/23/Sample%20Import%20Files/category_markups.xlsx') }}"
                           class="nav-link" target="_blank">
                            <i class="fa fa-circle-o nav-icon"></i>
                            <p>Category Markups Import Sample</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

    </ul>
</nav>
