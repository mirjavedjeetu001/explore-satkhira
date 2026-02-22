<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $adminSiteName = $siteSettings['site_name'] ?? 'Satkhira Portal';
    @endphp
    <title>@yield('title', 'Dashboard') - {{ $adminSiteName }} Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f4f6f9;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, #1a3c34 0%, #0d1f1a 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar.collapsed .sidebar-brand a span,
        .sidebar.collapsed .nav-title,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-link .badge {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-brand a {
            font-size: 1.5rem;
            display: flex;
            justify-content: center;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 15px;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }
        
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand a {
            color: #fff;
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 700;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .sidebar-nav .nav-title {
            color: rgba(255,255,255,0.5);
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left-color: #28a745;
        }
        
        .sidebar-nav .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .sidebar-nav .nav-link .badge {
            margin-left: auto;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* Top Navbar */
        .top-navbar {
            background: #fff;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .top-navbar .navbar-title {
            font-weight: 600;
            color: #333;
        }
        
        .user-dropdown img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        /* Content Area */
        .content-wrapper {
            padding: 25px;
        }
        
        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
        }
        
        /* Tables */
        .admin-table {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .admin-table .table {
            margin: 0;
        }
        
        .admin-table .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        .admin-table .table td {
            vertical-align: middle;
        }
        
        /* Status Badges */
        .badge-pending { background: #ffc107; color: #000; }
        .badge-approved { background: #28a745; }
        .badge-rejected { background: #dc3545; }
        .badge-active { background: #28a745; }
        .badge-suspended { background: #dc3545; }
        
        /* Forms */
        .admin-form {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
            
            .main-content,
            .main-content.expanded {
                margin-left: 0;
            }
        }
        
        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: none;
            border: none;
            color: #495057;
            font-size: 1.2rem;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .sidebar-toggle-btn:hover {
            color: #28a745;
        }

        /* Pagination Fix */
        .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }
        .pagination .page-item .page-link {
            color: #28a745;
            border-color: #dee2e6;
            padding: 8px 14px;
        }
        .pagination .page-item.active .page-link {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .pagination .page-item .page-link:hover {
            background-color: #e9ecef;
            color: #1a5f2a;
        }
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
        }
        nav[aria-label="Pagination Navigation"] svg {
            width: 18px;
            height: 18px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-leaf me-2"></i><span>{{ $adminSiteName }}</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-title">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> <span>Analytics</span>
            </a>
            
            <div class="nav-title">Content Management</div>
            <a href="{{ route('admin.upazilas.index') }}" class="nav-link {{ request()->routeIs('admin.upazilas.*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i> <span>Upazilas</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> <span>Categories</span>
            </a>
            <a href="{{ route('admin.listings.index') }}" class="nav-link {{ request()->routeIs('admin.listings.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> <span>Listings</span>
                @php $pendingListings = \App\Models\Listing::pending()->count(); @endphp
                @if($pendingListings > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingListings }}</span>
                @endif
            </a>
            <a href="{{ route('admin.listing-images.index') }}" class="nav-link {{ request()->routeIs('admin.listing-images.*') ? 'active' : '' }}">
                <i class="fas fa-ad"></i> <span>Ads/Offers</span>
                @php $pendingImages = \App\Models\ListingImage::pending()->count(); @endphp
                @if($pendingImages > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingImages }}</span>
                @endif
            </a>
            <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> <span>Comments</span>
                @php $pendingComments = \App\Models\Comment::pending()->count(); @endphp
                @if($pendingComments > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingComments }}</span>
                @endif
            </a>
            
            <div class="nav-title">MP Section</div>
            <a href="{{ route('admin.mp-profiles.index') }}" class="nav-link {{ request()->routeIs('admin.mp-profiles.*') ? 'active' : '' }}">
                <i class="fas fa-user-tie"></i> <span>MP Profiles</span>
            </a>
            <a href="{{ route('admin.mp-questions.index') }}" class="nav-link {{ request()->routeIs('admin.mp-questions.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i> <span>MP Questions</span>
                @php $pendingQuestions = \App\Models\MpQuestion::pending()->count(); @endphp
                @if($pendingQuestions > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingQuestions }}</span>
                @endif
            </a>
            
            <div class="nav-title">Appearance</div>
            <a href="{{ route('admin.sliders.index') }}" class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> <span>Sliders</span>
            </a>
            <a href="{{ route('admin.news.index') }}" class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> <span>News & Events</span>
            </a>
            
            <div class="nav-title">Users & Settings</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> <span>Users</span>
                @php $pendingUsers = \App\Models\User::pending()->count(); @endphp
                @if($pendingUsers > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingUsers }}</span>
                @endif
            </a>
            <a href="{{ route('admin.team.index') }}" class="nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> <span>Team Members</span>
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> <span>Contact Messages</span>
                @php $unreadContacts = \App\Models\Contact::unread()->count(); @endphp
                @if($unreadContacts > 0)
                    <span class="badge bg-danger">{{ $unreadContacts }}</span>
                @endif
            </a>
            <a href="{{ route('admin.settings.general') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> <span>Settings</span>
            </a>
            
            <div class="nav-title">Quick Links</div>
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> <span>View Website</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle-btn me-3" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="navbar-title">@yield('title', 'Dashboard')</span>
            </div>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=28a745&color=fff" alt="{{ auth()->user()->name }}">
                    <span class="ms-2 d-none d-sm-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        // Check localStorage for sidebar state
        if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 992) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }
        
        sidebarToggle?.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                // Mobile: show/hide sidebar
                sidebar.classList.toggle('show');
            } else {
                // Desktop: collapse/expand sidebar
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
