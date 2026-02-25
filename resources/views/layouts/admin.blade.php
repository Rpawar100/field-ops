<!DOCTYPE html>
<html lang="en" data-theme="dark-sidebar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Field Operations Admin</title>

    {{-- Google Fonts: Rubik (matches Edmin) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    {{-- Feather Icons (Edmin uses stroke icons) --}}
    <link href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css" rel="stylesheet" onerror="">

    <style>
        /* ================================================================
           Edmin-Derived Admin Layout Styles
           Color Palette:
             --theme-default:    #43B9B2  (primary teal)
             --theme-secondary:  #C280D2  (secondary purple)
             --body-color:       #F9FAFC  (body bg)
             --card-color:       #fff     (card bg)
             --body-font-color:  #3D3D47  (text)
             --sidebar-bg:       #22262c  (dark sidebar)
             --sidebar-card:     #292E37  (sidebar items)
           ================================================================ */
        :root {
            --theme-default: #43B9B2;
            --theme-secondary: #C280D2;
            --body-color: #F9FAFC;
            --card-color: #ffffff;
            --body-font-color: #3D3D47;
            --sidebar-bg: #22262c;
            --sidebar-card: #292E37;
            --sidebar-width: 265px;
            --sidebar-collapsed-width: 0px;
            --header-height: 60px;
            --text-gray: rgba(153, 153, 169, 0.8);
            --text-light-gray: #9B9B9B;
            --card-border-color: #f3f3f3;
            --card-box-shadow: 0 3px 18px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Rubik', sans-serif;
            background-color: var(--body-color);
            color: var(--body-font-color);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* ======================= PAGE WRAPPER ========================= */
        .page-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ======================= SIDEBAR ============================== */
        .page-sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s ease, width 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }

        .page-sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .page-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        /* Logo area */
        .sidebar-logo {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-logo .logo-text {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .sidebar-logo .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--theme-default);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-icon i {
            color: #fff;
            font-size: 1.1rem;
        }

        /* Sidebar navigation */
        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-main-title {
            padding: 18px 20px 6px;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-gray);
            white-space: nowrap;
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-list>li {
            position: relative;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: rgba(255, 255, 255, 0.55);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 400;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            gap: 12px;
            cursor: pointer;
        }

        .sidebar-link i,
        .sidebar-link svg {
            width: 18px;
            font-size: 1rem;
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar-link:hover {
            color: rgba(255, 255, 255, 0.85);
            background-color: rgba(255, 255, 255, 0.04);
            text-decoration: none;
        }

        .sidebar-link.active,
        .sidebar-list>li.active>.sidebar-link {
            color: var(--theme-default);
            border-left-color: var(--theme-default);
            background-color: rgba(67, 185, 178, 0.08);
        }

        .sidebar-link .chevron {
            margin-left: auto;
            font-size: 0.7rem;
            transition: transform 0.25s ease;
        }

        .sidebar-list>li.open>.sidebar-link .chevron {
            transform: rotate(90deg);
        }

        /* Submenu */
        .sidebar-submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
            background-color: rgba(0, 0, 0, 0.12);
        }

        .sidebar-list>li.open>.sidebar-submenu {
            max-height: 500px;
        }

        .sidebar-submenu li a {
            display: block;
            padding: 8px 20px 8px 52px;
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.2s;
        }

        .sidebar-submenu li a::before {
            content: '';
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            margin-right: 10px;
            vertical-align: middle;
        }

        .sidebar-submenu li a:hover,
        .sidebar-submenu li a.active {
            color: var(--theme-default);
        }

        .sidebar-submenu li a.active::before {
            background: var(--theme-default);
        }

        /* ======================= BODY WRAPPER ========================= */
        .page-body-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ======================= HEADER =============================== */
        .page-main-header {
            height: var(--header-height);
            background: var(--card-color);
            border-bottom: 1px solid var(--card-border-color);
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--body-font-color);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .toggle-sidebar:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }

        .header-search {
            position: relative;
            width: 280px;
        }

        .header-search input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            font-size: 0.85rem;
            background: var(--body-color);
            color: var(--body-font-color);
            outline: none;
            transition: border-color 0.2s;
        }

        .header-search input:focus {
            border-color: var(--theme-default);
        }

        .header-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light-gray);
            font-size: 0.85rem;
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-right .nav-icon-btn {
            position: relative;
            background: none;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--body-font-color);
            cursor: pointer;
            transition: background 0.2s;
            font-size: 1.05rem;
        }

        .header-right .nav-icon-btn:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }

        .header-right .nav-icon-btn .badge-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dc3545;
            border: 2px solid #fff;
        }

        /* User avatar dropdown */
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: var(--body-font-color);
        }

        .user-dropdown:hover {
            background: rgba(0, 0, 0, 0.03);
            color: var(--body-font-color);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--theme-default), var(--theme-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-dropdown .user-name {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .user-dropdown .user-role {
            font-size: 0.72rem;
            color: var(--text-light-gray);
        }

        /* ======================= CONTENT AREA ========================= */
        .page-body {
            flex: 1;
            padding: 24px;
        }

        /* Breadcrumb */
        .page-title-area {
            margin-bottom: 20px;
        }

        .page-title-area h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .page-title-area .breadcrumb {
            margin: 0;
            padding: 0;
            background: none;
            font-size: 0.8rem;
        }

        .page-title-area .breadcrumb-item a {
            color: var(--theme-default);
            text-decoration: none;
        }

        .page-title-area .breadcrumb-item.active {
            color: var(--text-light-gray);
        }

        /* ======================= FOOTER =============================== */
        .page-footer {
            padding: 14px 24px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-light-gray);
            border-top: 1px solid var(--card-border-color);
            background: var(--card-color);
        }

        .page-footer a {
            color: var(--theme-default);
            text-decoration: none;
        }

        /* ======================= CARDS (Edmin Style) ================== */
        .card {
            background: var(--card-color);
            border: 1px solid var(--card-border-color);
            border-radius: 10px;
            box-shadow: var(--card-box-shadow);
            margin-bottom: 24px;
        }

        .card .card-header {
            background: transparent;
            border-bottom: 1px solid var(--card-border-color);
            padding: 18px 20px;
            font-weight: 500;
        }

        .card .card-body {
            padding: 20px;
        }

        /* ======================= RESPONSIVE =========================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1035;
        }

        @media (max-width: 1199.98px) {
            .page-sidebar {
                transform: translateX(-100%);
            }

            .page-sidebar.open {
                transform: translateX(0);
            }

            .page-body-wrapper {
                margin-left: 0;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .header-search {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .page-body {
                padding: 16px;
            }

            .user-dropdown .user-info {
                display: none;
            }
        }

        /* ======================= UTILITY HELPERS ====================== */
        /* ======================= GLOBAL UTILITIES ===================== */
        .text-theme {
            color: var(--theme-default) !important;
        }

        .text-secondary-theme {
            color: var(--theme-secondary) !important;
        }

        .bg-theme {
            background-color: var(--theme-default) !important;
        }

        .bg-secondary-theme {
            background-color: var(--theme-secondary) !important;
        }

        .btn-theme {
            background-color: var(--theme-default);
            border-color: var(--theme-default);
            color: #fff;
        }

        .btn-theme:hover {
            background-color: #3aa5a0;
            border-color: #3aa5a0;
            color: #fff;
        }

        .btn-outline-theme {
            border-color: var(--theme-default);
            color: var(--theme-default);
        }

        .btn-outline-theme:hover {
            background-color: var(--theme-default);
            color: #fff;
        }

        /* Status Badges */
        .badge-status {
            font-size: 0.72rem;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .badge-status.completed,
        .badge-status.active,
        .badge-status.present {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .badge-status.in-progress,
        .badge-status.draft,
        .badge-status.leave {
            background-color: rgba(255, 193, 7, 0.15);
            color: #b38600;
        }

        .badge-status.pending,
        .badge-status.inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .badge-status.absent,
        .badge-status.deleted {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            --body-color: #1a1d21;
            /* Slightly darker background */
            --card-color: #22262c;
            /* Main card background */
            --body-font-color: #e6e6e6;
            /* Lighter primary text */
            --text-light-gray: #a6a6a6;
            /* Lighter secondary text */
            --card-border-color: #373b40;
            --card-box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
        }

        /* Enforce text color inheritance */
        body.dark-mode,
        body.dark-mode .card,
        body.dark-mode .page-body {
            color: var(--body-font-color);
        }

        /* Headings */
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6,
        body.dark-mode .h1,
        body.dark-mode .h2,
        body.dark-mode .h3,
        body.dark-mode .h4,
        body.dark-mode .h5,
        body.dark-mode .h6 {
            color: #ffffff !important;
        }

        /* Text Utilities Overrides */
        body.dark-mode .text-dark {
            color: #f0f0f0 !important;
        }

        body.dark-mode .text-muted {
            color: #aaaaaa !important;
        }

        body.dark-mode .text-secondary {
            color: #9c9c9c !important;
        }

        body.dark-mode .small {
            color: #aaaaaa !important;
        }

        /* Layout Components */
        body.dark-mode .page-main-header,
        body.dark-mode .page-footer {
            background: var(--card-color);
            border-color: var(--card-border-color);
            color: var(--body-font-color);
        }

        /* Forms & Inputs */
        body.dark-mode .header-search input,
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #15181c;
            border-color: #373b40;
            color: #ffffff;
        }

        body.dark-mode .form-control::placeholder {
            color: #6c757d;
            opacity: 1;
        }

        body.dark-mode .input-group-text {
            background-color: #2d3238;
            border-color: #373b40;
            color: #e0e0e0;
        }

        body.dark-mode .header-search input:focus,
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            border-color: var(--theme-default);
            background-color: #15181c;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(67, 185, 178, 0.25);
        }

        /* Dropdowns */
        body.dark-mode .dropdown-menu {
            background-color: var(--card-color);
            border-color: var(--card-border-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        body.dark-mode .dropdown-item {
            color: var(--body-font-color);
        }

        body.dark-mode .dropdown-item:hover,
        body.dark-mode .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        body.dark-mode .dropdown-divider {
            border-top-color: #373b40;
        }

        /* Tables */
        body.dark-mode .table {
            color: var(--body-font-color);
            border-color: #373b40;
        }

        body.dark-mode .table> :not(caption)>*>* {
            background-color: transparent !important;
            color: inherit;
            border-bottom-color: #373b40;
            box-shadow: none !important;
        }

        body.dark-mode .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #fff;
        }

        /* Badges & Specific overrides */
        body.dark-mode .badge.bg-light {
            background-color: #373b40 !important;
            color: #e0e0e0 !important;
        }

        body.dark-mode .card {
            background-color: var(--card-color);
        }

        /* Links */
        body.dark-mode a:not(.btn):not(.nav-link):not(.dropdown-item) {
            color: #6edff6;
        }

        body.dark-mode a:not(.btn):not(.nav-link):not(.dropdown-item):hover {
            color: #9eeaff;
        }
    </style>

    @yield('styles')
</head>

<body>
    <script>
        // Apply theme immediately to prevent FOUC
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>
    {{-- Sidebar Overlay (mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="page-wrapper">
        {{-- ============================================================
        SIDEBAR
        ============================================================ --}}
        <nav class="page-sidebar" id="pageSidebar">

            {{-- Logo --}}
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <span class="logo-text">Field Ops</span>
            </div>

            {{-- Navigation --}}
            {{-- Navigation --}}
            <ul class="sidebar-menu">

                {{-- General --}}
                <li class="sidebar-main-title">General</li>
                <ul class="sidebar-list">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>

                {{-- User Management --}}
                <li class="sidebar-main-title">People</li>
                <ul class="sidebar-list">
                    {{-- Users --}}
                    <li
                        class="{{ request()->routeIs('users.*') || request()->routeIs('onboarding.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.index') ? 'active' : '' }}">All Users</a></li>
                            <li><a href="{{ route('users.create') }}"
                                    class="{{ request()->routeIs('users.create') ? 'active' : '' }}">Add User</a></li>
                            <li><a href="{{ route('onboarding.index') }}"
                                    class="{{ request()->routeIs('onboarding.*') ? 'active' : '' }}">FA Onboarding</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Farmers --}}
                    <li class="{{ request()->routeIs('farmers.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-leaf"></i>
                            <span>Farmers</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('farmers.index') }}"
                                    class="{{ request()->routeIs('farmers.index') ? 'active' : '' }}">All Farmers</a>
                            </li>
                            <li><a href="{{ route('farmers.create') }}"
                                    class="{{ request()->routeIs('farmers.create') ? 'active' : '' }}">Register
                                    Farmer</a></li>
                        </ul>
                    </li>

                    {{-- Retailers --}}
                    <li class="{{ request()->routeIs('retailers.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-store"></i>
                            <span>Retailers</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('retailers.index') }}"
                                    class="{{ request()->routeIs('retailers.index') ? 'active' : '' }}">All
                                    Retailers</a></li>
                            <li><a href="{{ route('retailers.create') }}"
                                    class="{{ request()->routeIs('retailers.create') ? 'active' : '' }}">Register
                                    Retailer</a></li>
                        </ul>
                    </li>
                </ul>

                {{-- Territory & Geography --}}
                <li class="sidebar-main-title">Territories</li>
                <ul class="sidebar-list">
                    <li
                        class="{{ request()->routeIs('zones.*') || request()->routeIs('regions.*') || request()->routeIs('territories.*') || request()->routeIs('headquarters.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-map-marked-alt"></i>
                            <span>ZRTH Hierarchy</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('zones.index') }}"
                                    class="{{ request()->routeIs('zones.*') ? 'active' : '' }}">Zones</a></li>
                            <li><a href="{{ route('regions.index') }}"
                                    class="{{ request()->routeIs('regions.*') ? 'active' : '' }}">Regions</a></li>
                            <li><a href="{{ route('territories.index') }}"
                                    class="{{ request()->routeIs('territories.*') ? 'active' : '' }}">Territories</a>
                            </li>
                            <li><a href="{{ route('headquarters.index') }}"
                                    class="{{ request()->routeIs('headquarters.*') ? 'active' : '' }}">Headquarters</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="{{ request()->routeIs('states.*') || request()->routeIs('districts.*') || request()->routeIs('talukas.*') || request()->routeIs('villages.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-globe-asia"></i>
                            <span>SDTV Geography</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('states.index') }}"
                                    class="{{ request()->routeIs('states.*') ? 'active' : '' }}">States</a></li>
                            <li><a href="{{ route('districts.index') }}"
                                    class="{{ request()->routeIs('districts.*') ? 'active' : '' }}">Districts</a></li>
                            <li><a href="{{ route('talukas.index') }}"
                                    class="{{ request()->routeIs('talukas.*') ? 'active' : '' }}">Talukas</a></li>
                            <li><a href="{{ route('villages.index') }}"
                                    class="{{ request()->routeIs('villages.*') ? 'active' : '' }}">Villages</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('beats.*') ? 'active' : '' }}">
                        <a href="{{ route('beats.index') }}"
                            class="sidebar-link {{ request()->routeIs('beats.*') ? 'active' : '' }}">
                            <i class="fas fa-route"></i>
                            <span>Beats</span>
                        </a>
                    </li>
                </ul>

                {{-- Operations --}}
                <li class="sidebar-main-title">Operations</li>
                <ul class="sidebar-list">
                    {{-- Activities --}}
                    <li class="{{ request()->routeIs('activities.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-clipboard-list"></i>
                            <span>Activities</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('activities.index') }}"
                                    class="{{ request()->routeIs('activities.index') ? 'active' : '' }}">All
                                    Activities</a></li>
                            <li><a href="{{ route('activities.create') }}"
                                    class="{{ request()->routeIs('activities.create') ? 'active' : '' }}">Create
                                    Activity</a></li>
                        </ul>
                    </li>

                    {{-- Demos --}}
                    <li class="{{ request()->routeIs('demo.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-flask"></i>
                            <span>Demos</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('demo.index') }}"
                                    class="{{ request()->routeIs('demo.index') ? 'active' : '' }}">Overview</a></li>
                            <li><a href="{{ route('demo.distribution') }}"
                                    class="{{ request()->routeIs('demo.distribution') ? 'active' : '' }}">Distribution</a>
                            </li>
                            <li><a href="{{ route('demo.execution') }}"
                                    class="{{ request()->routeIs('demo.execution') ? 'active' : '' }}">Execution</a>
                            </li>
                            <li><a href="{{ route('demo.reconciliation') }}"
                                    class="{{ request()->routeIs('demo.reconciliation') ? 'active' : '' }}">Reconciliation</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Attendance --}}
                    <li class="{{ request()->routeIs('attendance.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-fingerprint"></i>
                            <span>Attendance</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('attendance.index') }}"
                                    class="{{ request()->routeIs('attendance.index') ? 'active' : '' }}">Daily Log</a>
                            </li>
                            <li><a href="{{ route('attendance.calendar') }}"
                                    class="{{ request()->routeIs('attendance.calendar') ? 'active' : '' }}">Calendar
                                    View</a></li>
                            <li><a href="{{ route('attendance.map') }}"
                                    class="{{ request()->routeIs('attendance.map') ? 'active' : '' }}">Map View</a></li>
                        </ul>
                    </li>

                    {{-- Tour Plans --}}
                    <li class="{{ request()->routeIs('atps.*') ? 'active' : '' }}">
                        <a href="{{ route('atps.index') }}"
                            class="sidebar-link {{ request()->routeIs('atps.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Tour Plans (ATP)</span>
                        </a>
                    </li>
                </ul>

                {{-- Reports & Admin --}}
                <li class="sidebar-main-title">Reports</li>
                <ul class="sidebar-list">
                    <li class="{{ request()->routeIs('reports.*') ? 'active open' : '' }}">
                        <a class="sidebar-link" data-toggle-sub>
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                            <i class="fas fa-chevron-right chevron"></i>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('reports.index') }}"
                                    class="{{ request()->routeIs('reports.index') ? 'active' : '' }}">Overview</a></li>
                            <li><a href="{{ route('reports.activities') }}"
                                    class="{{ request()->routeIs('reports.activities') ? 'active' : '' }}">Activity
                                    Report</a></li>
                            <li><a href="{{ route('reports.attendance') }}"
                                    class="{{ request()->routeIs('reports.attendance') ? 'active' : '' }}">Attendance
                                    Report</a></li>
                            <li><a href="{{ route('reports.demo') }}"
                                    class="{{ request()->routeIs('reports.demo') ? 'active' : '' }}">Demo Report</a>
                            </li>
                            <li><a href="{{ route('reports.coverage') }}"
                                    class="{{ request()->routeIs('reports.coverage') ? 'active' : '' }}">Coverage
                                    Report</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                        <a href="{{ route('audit-logs.index') }}"
                            class="sidebar-link {{ request()->routeIs('audit-logs.index') ? 'active' : '' }}">
                            <i class="fas fa-scroll"></i>
                            <span>Audit Logs</span>
                        </a>
                    </li>
                </ul>

            </ul>
        </nav>

        {{-- ============================================================
        BODY WRAPPER (Header + Content + Footer)
        ============================================================ --}}
        <div class="page-body-wrapper">

            {{-- ==================== HEADER ============================ --}}
            <header class="page-main-header">
                <div class="header-left">
                    {{-- Sidebar toggle --}}
                    <button class="toggle-sidebar" id="toggleSidebar" title="Toggle Sidebar">
                        <i class="fas fa-bars"></i>
                    </button>

                    {{-- Search --}}
                    <div class="header-search d-none d-lg-block">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search activities, farmers, users..." autocomplete="off">
                    </div>
                </div>

                <div class="header-right">
                    {{-- Fullscreen --}}
                    <button class="nav-icon-btn d-none d-md-flex" id="fullscreenBtn" title="Fullscreen">
                        <i class="fas fa-expand"></i>
                    </button>

                    {{-- Notifications --}}
                    <div class="dropdown">
                        <button class="nav-icon-btn" data-bs-toggle="dropdown" aria-expanded="false"
                            title="Notifications">
                            <i class="fas fa-bell"></i>
                            <span class="badge-dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 320px;">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-semibold">Notifications</h6>
                                <span class="badge bg-primary rounded-pill">3 New</span>
                            </div>
                            <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                                <a href="#" class="list-group-item list-group-item-action px-3 py-2">
                                    <div class="d-flex align-items-start gap-2">
                                        <span
                                            class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:32px;height:32px;flex-shrink:0;">
                                            <i class="fas fa-check fa-sm"></i>
                                        </span>
                                        <div>
                                            <p class="mb-0 small fw-medium">Attendance synced successfully</p>
                                            <small class="text-muted">2 min ago</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action px-3 py-2">
                                    <div class="d-flex align-items-start gap-2">
                                        <span
                                            class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:32px;height:32px;flex-shrink:0;">
                                            <i class="fas fa-exclamation fa-sm"></i>
                                        </span>
                                        <div>
                                            <p class="mb-0 small fw-medium">3 pending onboarding approvals</p>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action px-3 py-2">
                                    <div class="d-flex align-items-start gap-2">
                                        <span
                                            class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:32px;height:32px;flex-shrink:0;">
                                            <i class="fas fa-upload fa-sm"></i>
                                        </span>
                                        <div>
                                            <p class="mb-0 small fw-medium">Bulk upload completed — 124 farmers</p>
                                            <small class="text-muted">3 hours ago</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2 text-center border-top">
                                <a href="#" class="small text-decoration-none" style="color: var(--theme-default);">View
                                    all notifications</a>
                            </div>
                        </div>
                    </div>

                    {{-- Messages --}}
                    <div class="dropdown">
                        <button class="nav-icon-btn" data-bs-toggle="dropdown" aria-expanded="false" title="Messages">
                            <i class="fas fa-comment-dots"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 280px;">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0 fw-semibold">Messages</h6>
                            </div>
                            <div class="p-3 text-center text-muted small">
                                <i class="fas fa-inbox mb-2 d-block" style="font-size:1.5rem;"></i>
                                No new messages
                            </div>
                        </div>
                    </div>

                    <div class="vr mx-1 d-none d-md-block" style="height: 24px; align-self: center; opacity: 0.15;">
                    </div>

                    {{-- User dropdown --}}
                    <div class="dropdown">
                        <a href="#" class="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="user-info d-none d-sm-block">
                                <div class="user-name">{{ Auth::user()->name ?? 'Admin User' }}</div>
                                <div class="user-role">{{ Auth::user()->role ?? 'Administrator' }}</div>
                            </div>
                            <i class="fas fa-chevron-down ms-1" style="font-size: 0.65rem; opacity: 0.5;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="fas fa-user me-2 text-muted"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/settings') }}">
                                    <i class="fas fa-cog me-2 text-muted"></i> Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('form-logout').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="form-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- ==================== CONTENT AREA ====================== --}}
            <main class="page-body">

                {{-- Flash Messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle me-1"></i> Error!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-1"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- ==================== FOOTER ============================ --}}
            <footer class="page-footer">
                <span>&copy; {{ date('Y') }} Field Operations Management System. Built with</span>
                <a href="https://lab.ubicos.in/edmin/Edmin_webpack_template/template/" target="_blank"
                    rel="noopener">Edmin</a>
                <span>template.</span>
            </footer>

        </div>
    </div>

    {{-- Bootstrap 5.3 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery (required by DataTables / other plugins) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        (function () {
            'use strict';

            const sidebar = document.getElementById('pageSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('toggleSidebar');
            const body = document.querySelector('.page-body-wrapper');

            // Toggle sidebar
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    if (window.innerWidth < 1200) {
                        sidebar.classList.toggle('open');
                        overlay.classList.toggle('active');
                    } else {
                        sidebar.style.display = sidebar.style.display === 'none' ? '' : 'none';
                        body.style.marginLeft = sidebar.style.display === 'none' ? '0' : '';
                    }
                });
            }

            // Close sidebar on overlay click (mobile)
            if (overlay) {
                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                });
            }

            // Collapsible sub-menus
            document.querySelectorAll('[data-toggle-sub]').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    var parentLi = el.closest('li');
                    // Close other open items at the same level
                    var siblings = parentLi.parentElement.querySelectorAll(':scope > li.open');
                    siblings.forEach(function (sib) {
                        if (sib !== parentLi) sib.classList.remove('open');
                    });
                    parentLi.classList.toggle('open');
                });
            });

            // Fullscreen toggle
            var fsBtn = document.getElementById('fullscreenBtn');
            if (fsBtn) {
                fsBtn.addEventListener('click', function () {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(function () { });
                    } else {
                        document.exitFullscreen();
                    }
                });
            }
        })();
    </script>

    <script>
        // Dark Mode Logic
        (function () {
            // 1. Check local storage
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'dark') {
                document.body.classList.add('dark-mode');
            }

            // 2. Expose toggle function globally
            window.toggleDarkMode = function () {
                document.body.classList.toggle('dark-mode');
                const isDark = document.body.classList.contains('dark-mode');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');

                // Update switch UI if present
                const switchElem = document.getElementById('darkModeSwitch');
                if (switchElem) switchElem.checked = isDark;
            }

            // 3. Initialize switch state on load
            document.addEventListener('DOMContentLoaded', function () {
                const switchElem = document.getElementById('darkModeSwitch');
                if (switchElem && document.body.classList.contains('dark-mode')) {
                    switchElem.checked = true;
                }
            });
        })();
    </script>

    @yield('scripts')
</body>

</html>