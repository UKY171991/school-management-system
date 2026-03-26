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

    'title' => 'School Management',
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

    'logo' => '<b>School</b>MS',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

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
    'dashboard_url' => 'admin/dashboard',
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
    'laravel_css_path' => 'css/admin_custom.css',
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
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'key'         => 'language_selector',
            'text'        => 'Language',
            'topnav_right' => true,
            'icon'        => 'fas fa-language',
            'submenu'     => [
                [
                    'key'    => 'lang_en',
                    'text'   => 'English',
                    'url'    => 'lang/en',
                ],
                [
                    'key'    => 'lang_hi',
                    'text'   => 'हिन्दी (Hindi)',
                    'url'    => 'lang/hi',
                ],
            ],
        ],

        // Sidebar items:
        [
            'text' => 'Dashboard',
            'url'  => 'admin/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        [
            'text' => 'Schools',
            'url'  => 'admin/schools',
            'icon' => 'fas fa-fw fa-school',
        ],
        [
            'text' => 'Academic Mgmt',
            'icon' => 'fas fa-fw fa-university',
            'submenu' => [
                [
                    'text' => 'Branches',
                    'url'  => 'admin/branches',
                    'icon' => 'fas fa-fw fa-code-branch',
                ],
                [
                    'text' => 'Classes',
                    'url'  => 'admin/grades',
                    'icon' => 'fas fa-fw fa-school',
                ],
                [
                    'text' => 'Class & Sections',
                    'url'  => 'admin/sections',
                    'icon' => 'fas fa-fw fa-layer-group',
                ],
                [
                    'text' => 'Subject Management',
                    'url'  => 'admin/subjects',
                    'icon' => 'fas fa-fw fa-book',
                ],

                [
                    'text' => 'Syllabus Upload',
                    'url'  => 'admin/syllabus',
                    'icon' => 'fas fa-fw fa-file-pdf',
                ],
                [
                    'text' => 'Homework & Assign',
                    'url'  => 'admin/homework',
                    'icon' => 'fas fa-fw fa-laptop-house',
                ],
            ],
        ],
        [
            'text' => 'Student Management',
            'icon' => 'fas fa-fw fa-user-graduate',
            'submenu' => [
                [
                    'text' => 'Admission & Reg',
                    'url'  => 'admin/admissions',
                    'icon' => 'fas fa-fw fa-user-plus',
                ],
                [
                    'text' => 'Profile & Documents',
                    'url'  => 'admin/student-profiles',
                    'icon' => 'fas fa-fw fa-id-card',
                ],
                [
                    'text' => 'Attendance Tracking',
                    'url'  => 'admin/attendance',
                    'icon' => 'fas fa-fw fa-calendar-check',
                ],
                [
                    'text' => 'Attendance Report',
                    'url'  => 'admin/attendance/report',
                    'icon' => 'fas fa-fw fa-file-alt',
                ],
                [
                    'text' => 'Transfers & LC',
                    'url'  => 'admin/transfers',
                    'icon' => 'fas fa-fw fa-file-export',
                ],
                [
                    'text' => 'Print Student List',
                    'url'  => 'admin/students/print-menu',
                    'icon' => 'fas fa-fw fa-print',
                ],
            ],
        ],

        [
            'text' => 'Teacher & Staff',
            'icon' => 'fas fa-fw fa-chalkboard-teacher',
            'submenu' => [
                [
                    'text' => 'Teacher Profile',
                    'url'  => 'admin/teacher-profiles',
                    'icon' => 'fas fa-fw fa-user-tie',
                ],
                [
                    'text' => 'Attendance & Leave',
                    'url'  => 'admin/staff-attendance',
                    'icon' => 'fas fa-fw fa-user-clock',
                ],
                [
                    'text' => 'Teacher Timetable',
                    'url'  => 'admin/teacher-timetable',
                    'icon' => 'fas fa-fw fa-calendar-alt',
                ],
                [
                    'text' => 'Payroll & Salary',
                    'url'  => 'admin/payroll',
                    'icon' => 'fas fa-fw fa-money-check-alt',
                ],
            ],
        ],

        [
            'text' => 'Exams & Results',
            'icon' => 'fas fa-fw fa-file-signature',
            'submenu' => [
                [
                    'text' => 'Exam Types',
                    'url'  => 'admin/exam-types',
                    'icon' => 'fas fa-fw fa-list-alt',
                ],
                [
                    'text' => 'Exam Scheduling',
                    'url'  => 'admin/exams',
                    'icon' => 'fas fa-fw fa-calendar-day',
                ],
                [
                    'text' => 'Exam Timetable',
                    'url'  => 'admin/exam-timetable',
                    'icon' => 'fas fa-fw fa-calendar-alt',
                ],
                [
                    'text' => 'Marks Entry',
                    'url'  => 'admin/marks',
                    'icon' => 'fas fa-fw fa-marker',
                ],
                [
                    'text' => 'Report Cards',
                    'url'  => 'admin/report-cards',
                    'icon' => 'fas fa-fw fa-file-invoice',
                ],
                [
                    'text' => 'Exam Results',
                    'url'  => 'admin/exam-results',
                    'icon' => 'fas fa-fw fa-chart-bar',
                ],
                [
                    'text' => 'Exam Sheet Plan',
                    'url'  => 'admin/exam-sheet-plan',
                    'icon' => 'fas fa-fw fa-clipboard-list',
                ],
                [
                    'text' => 'Admit Cards',
                    'url'  => 'admin/admit-cards',
                    'icon' => 'fas fa-fw fa-id-card',
                ],
            ],
        ],

        [
            'text' => 'Finance & Fees',
            'icon' => 'fas fa-fw fa-wallet',
            'submenu' => [
                [
                    'text' => 'Fee Structure',
                    'url'  => 'admin/fee-structure',
                    'icon' => 'fas fa-fw fa-coins',
                ],
                [
                    'text' => 'Fee Payments',
                    'url'  => 'admin/fee-payments',
                    'icon' => 'fas fa-fw fa-credit-card',
                ],
            ],
        ],

        [
            'text' => 'Library Mgmt',
            'icon' => 'fas fa-fw fa-book-reader',
            'submenu' => [
                [
                    'text' => 'Books Catalog',
                    'url'  => 'admin/books',
                    'icon' => 'fas fa-fw fa-book-open',
                ],
                [
                    'text' => 'Issue / Return',
                    'url'  => 'admin/book-issue',
                    'icon' => 'fas fa-fw fa-exchange-alt',
                ],
            ],
        ],

        [
            'text' => 'Transport',
            'icon' => 'fas fa-fw fa-bus-alt',
            'submenu' => [
                [
                    'text' => 'Bus Routes',
                    'url'  => 'admin/transport-routes',
                    'icon' => 'fas fa-fw fa-route',
                ],
                [
                    'text' => 'Driver Details',
                    'url'  => 'admin/drivers',
                    'icon' => 'fas fa-fw fa-id-badge',
                ],
                [
                    'text' => 'Vehicle Details',
                    'url'  => 'admin/vehicles',
                    'icon' => 'fas fa-fw fa-bus',
                ],
            ],
        ],

        [
            'text' => 'Hostel Mgmt',
            'icon' => 'fas fa-fw fa-hotel',
            'submenu' => [
                [
                    'text' => 'Hostel Details',
                    'url'  => 'admin/hostels',
                    'icon' => 'fas fa-fw fa-building',
                ],
                [
                    'text' => 'Room Allocation',
                    'url'  => 'admin/hostel-rooms',
                    'icon' => 'fas fa-fw fa-bed',
                ],
            ],
        ],

        [
            'text' => 'Communication',
            'icon' => 'fas fa-fw fa-envelope-open-text',
            'submenu' => [
                [
                    'text' => 'Alerts & Email',
                    'url'  => 'admin/communication',
                    'icon' => 'fas fa-fw fa-paper-plane',
                ],
            ],
        ],

        [
            'text' => 'Portals',
            'icon' => 'fas fa-fw fa-user-lock',
            'submenu' => [
                [
                    'text' => 'Student Portal',
                    'url'  => 'admin/portal-student',
                    'icon' => 'fas fa-fw fa-user-circle',
                ],
                [
                    'text' => 'Teacher Portal',
                    'url'  => 'admin/portal-teacher',
                    'icon' => 'fas fa-fw fa-chalkboard',
                ],
                [
                    'text' => 'Parent Portal',
                    'url'  => 'admin/portal-parent',
                    'icon' => 'fas fa-fw fa-users',
                ],
            ],
        ],

        [
            'text' => 'User Management',
            'url'  => 'admin/users',
            'icon' => 'fas fa-fw fa-users-cog',
        ],

        [
            'header' => 'SYSTEM SETTINGS',
        ],
        [
            'text' => 'General Settings',
            'url'  => 'admin/general-settings',
            'icon' => 'fas fa-fw fa-cogs',
        ],
        [
            'text' => 'API Documentation',
            'url'  => 'admin/api-documentation',
            'icon' => 'fas fa-fw fa-code',
            'can'  => 'master-admin',
        ],
        ['header' => 'ACCOUNT SETTINGS'],
        [
            'text' => 'profile',
            'url'  => 'admin/settings/profile',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'admin/settings/password',
            'icon' => 'fas fa-fw fa-lock',
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
            'active' => true,
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
        'Select2' => [
            'active' => true,
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
            'active' => true,
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
        'CustomCSS' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/admin_custom.css',
                ],
            ],
        ],
        'CustomJS' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/admin_custom.js',
                ],
            ],
        ],
        'Flatpickr' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/flatpickr',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
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

    'i18n' => true,
];
