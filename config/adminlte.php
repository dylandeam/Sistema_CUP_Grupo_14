<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Sistema de Admisión Estudiantil',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>FICCT</b>',
    'logo_img' => 'images/Escudo_FICCT.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Escudo FICCT',


    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    
    'menu' => [
        // PAQUETE 1: Autenticación y Control de Acceso
        [
            'text'    => 'Autenticación y Control de Acceso',
            'icon'    => 'fas fa-fw fa-shield-alt',
            'classes' => 'bg-dark text-white',
            'submenu' => [

                
                [
                    'text'    => 'Roles',
                    'url'     => 'admin/roles',
                    'icon'    => 'fas fa-fw fa-cog',
                    'classes' => 'bg-blue text-white',
                ],

                [
                    'text'    => 'Gestionar Cuentas Masivas',
                    'url'     => 'admin/cuentas-masivas',
                    'icon' => 'fas fa-clipboard-list',
                    'classes' => 'bg-blue text-white',
                ],

                [
                    'text'    => 'Bitácora',
                    'url'     => 'admin/bitacora',
                    'icon' => 'fas fa-clipboard-list',
                    'classes' => 'bg-blue text-white',
                ],


            ],
        ],

        // PAQUETE 2: Gestión del Personal y Usuarios
        [
            'text'    => 'Gestión del Personal y Usuarios',
            'icon' => 'fas fa-users',
            'classes' => 'bg-dark text-white',
            'submenu' => [
                [
                    'text'    => 'Administrativos',
                    'url'     => 'admin/administrativos',
                    'icon'    => 'fas fa-fw fa-users',
                    'classes' => 'bg-blue text-white',
                ],
               
                [
                    'text'    => 'Docentes',
                    'icon'    => 'fas fa-fw fa-chalkboard-teacher',
                    'classes' => 'bg-blue text-white',
                    'submenu' => [
                        [
                            'text' => 'Listado de Docentes',
                            'url'  => 'admin/docentes',
                            'icon' => 'fas fa-fw fa-list',
                            'classes' => 'bg-light text-dark',
                        ],

                        [
                            'text' => 'Contratación de Docentes',
                            'url'  => 'admin/docentes/create',
                            'icon' => 'fas fa-fw fa-user-plus',
                            'classes' => 'bg-light text-dark',
                        ],
                    ],
                ],

                [
                    'text'    => 'Postulantes',
                    'url'     => 'admin/postulantes',
                    'icon'    => 'fas fa-fw fa-users',
                    'classes' => 'bg-blue text-white',
                ],
            ],
        ],

        // PAQUETE 3: Administración Académica
        [
            'text'    => 'Administración Académica',
            'icon'    => 'fas fa-fw fa-graduation-cap',
            'classes' => 'bg-dark text-white',
            'submenu' => [
                [
                    'text'    => 'Modalidades',
                    'url'     => 'admin/modalidades',
                    'icon'    => 'fas fa-fw fa-cogs',
                    'classes' => 'bg-blue text-white',
                ],

                [
                    'text'    => 'Grupos',
                    'icon'    => 'fas fa-users',
                    'classes' => 'bg-blue text-white',
                    'submenu' => [
                        [
                            'text' => 'Listado de Grupos',
                            'url'  => 'admin/grupos',
                            'icon' => 'fas fa-fw fa-list',
                            'classes' => 'bg-light text-dark',
                        ],

                        [
                            'text' => 'Horarios de Grupos',
                            'url'  => 'admin/grupos/horariosgrupos',
                            'icon' => 'fas fa-fw fa-clock',
                            'classes' => 'bg-light text-dark',
                        ],
                    ],
                ],

                [
                    'text'    => 'Gestiones',
                    'url'     => 'admin/gestiones',
                    'icon'    => 'fas fa-fw fa-tasks',
                    'classes' => 'bg-blue text-white',
                ],

                [ 
                    'text'    => 'Materias',
                    'url'     => 'admin/materias',
                    'icon'    => 'fas fa-fw fa-book',
                    'classes' => 'bg-blue text-white',
                ],

                [ 
                    'text'    => 'Horario',
                    'url'     => 'admin/horarios',
                    'icon'    => 'fas fa-clock',
                    'classes' => 'bg-blue text-white',
                ],

                [ 
                    'text'    => 'Turnos',
                    'url'     => 'admin/turnos',
                    'icon'    => 'fas fa-clock',
                    'classes' => 'bg-blue text-white',
                ],

                [ 
                    'text'    => 'Aulas',
                    'url'     => 'admin/aulas',
                    'icon'    => 'fas fa-fw fa-university',
                    'classes' => 'bg-blue text-white',
                ],

                [ 
                    'text'    => 'Carga Horaria',
                    'url'     => 'admin/carga_horaria',
                    'icon'    => 'fas fa-fw fa-clock',
                    'classes' => 'bg-blue text-white',
                ],

                [ 
                    'text'    => 'Carreras',
                    'url'     => 'admin/carreras',
                    'icon'    => 'fas fa-fw fa-graduation-cap',
                    'classes' => 'bg-blue text-white',
                ],
                
            ],
        ],

        // PAQUETE 4: Procesos Académicos y Administrativos
        [
            'text'    => 'Procesos Académicos y Administrativos',
            'icon'    => 'fas fa-fw fa-graduation-cap',
            'classes' => 'bg-dark text-white',
            'submenu' => [
                [
                    'text'    => 'Inscripción',
                    'icon'    => 'fas fa-fw fa-chalkboard-teacher',
                    'classes' => 'bg-blue text-white',
                    'submenu' => [
                        [
                            'text' => 'Inscripción de Postulantes',
                            'url'  => 'admin/inscripciones',
                            'icon' => 'fas fa-fw fa-user-plus',
                            'classes' => 'bg-light text-dark',
                        ],

                        [
                            'text' => 'Boleta de Inscripción',
                            'url'  => 'admin/inscripciones/boleta',
                            'icon' => 'fas fa-fw fa-file-invoice',
                            'classes' => 'bg-light text-dark',
                        ],
                    ],
                ],

                [
                    'text'    => 'Examen',
                    'icon'    => 'fas fa-fw fa-clipboard-list',
                    'classes' => 'bg-blue text-white',
                    'submenu' => [
                        [
                            'text' => 'Examen de Admisión',
                            'url'  => 'admin/examenes',
                            'icon' => 'fas fa-fw fa-clipboard-list',
                            'classes' => 'bg-light text-dark',
                        ],

                        [
                            'text' => 'Registrar Notas de Examen',
                            'url'  => 'admin/notas_examen/create',
                            'icon' => 'fas fa-fw fa-clock',
                            'classes' => 'bg-light text-dark',
                        ],
                    ],
                ],

                [
                    'text'    => 'Pagos',
                    'url'     => 'admin/pagos',
                    'icon'    => 'fas fa-fw fa-money-bill-wave',
                    'classes' => 'bg-blue text-white',
                ],

                [
                    'text'    => 'Historial Academico',
                    'url'     => 'admin/historial-academico',
                    'icon'    => 'fas fa-fw fa-history',
                    'classes' => 'bg-blue text-white',
                ],
                
                [
                    'text'    => 'Reportes',
                    'url'     => 'admin/reportes',
                    'icon'    => 'fas fa-fw fa-chart-bar',
                    'classes' => 'bg-blue text-white',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'DatatablesPlugins' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
