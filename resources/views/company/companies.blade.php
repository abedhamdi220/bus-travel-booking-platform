<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies | Bus Travel Booking</title>

    <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="css/all.min.css">

    <!-- ملفات CSS المشتركة -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ==================== Top Navbar ==================== -->
    <nav class="navbar">
        <div class="brand">
            <i class="fas fa-bus"></i>
            <span>Bus Travel Booking</span>
        </div>
        <div class="user-info">
            <!-- أيقونة الإشعارات -->
            <div class="icon-badge" onclick="window.location.href='notifications.html'">
                <i class="fas fa-bell"></i>
                <sup>3</sup>
            </div>
           <span><a href="admin-profile.html">Welcome, Admin</a></span>
            <div class="avatar"><a href="admin-profile.html">A</a></div>
        </div>
    </nav>

    <!-- ==================== Sidebar ==================== -->
    <aside class="sidebar">
        <a href="admin-profile.html" class="sidebar-admin-profile">
        <div class="sidebar-admin-logo" id="sidebarAdminLogo">
            <i class="fas fa-user-circle fa-2x"></i>
        </div>
        <h4>Admin</h4>
    </a>
        <ul>
            <li class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <a href="dashboard.html">Dashboard</a>
            </li>
<!--       - <li class="menu-item">
                <i class="fas fa-bus"></i>
                <a href="trips.html">Trips</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-ticket-alt"></i>
                <a href="bookings.html">Bookings</a> -->
            </li>
            <li class="menu-item active">
                <i class="fas fa-building"></i>
                <a href="companies.html">Companies</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-users"></i>
                <a href="users.html">Users</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-cog"></i>
                <a href="settings.html">Settings</a>
            </li>
        </ul>
    </aside>

   <!-- ==================== Main Content ==================== -->
<main class="main-content">

    <!-- العنوان -->
    <h2 class="page-title">🏢 Companies Management</h2>
    <!--زر عرض الشركات الجديدة يلي بدها تسجل-->
      <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
            <a href="new-company-pending.html" class="btn-view-all">
                <i class="fas fa-building"></i> View New companies
            </a>
        </div>

        <!-- Best Company This Month -->
<div class="top-drivers-card" onclick="alert('🏆 Best Company: Al-Sham Transport\n⭐ Rating: 4.8\n📅 Since: 2015\n🚌 Trips: 24\n📊 Bookings: 1,250')">
    <div class="top-drivers-header">
        <div>
            <h3><i class="fas fa-trophy"></i> Best Company This Month</h3>
            <p>Based on ratings & seniority</p>
        </div>
        <div style="text-align: right;">
            <span style="font-size: 32px; font-weight: bold;">🏆</span>
        </div>
    </div>
    <div class="top-drivers-preview">
        <div class="top-company-info">
            <span style="font-size: 20px; font-weight: bold;">Al-Sham Transport</span>
            <span style="margin-left: 15px;">⭐ 4.8</span>
            <span style="margin-left: 15px;">📅 Since 2015</span>
            <span style="margin-left: 15px;">🚌 58 Trips</span>
        </div>
    </div>
</div>

    <!-- شريط البحث -->
    <div class="table-toolbar">
        <input type="text" placeholder="Search by company name...">
        <button><i class="fas fa-search"></i> Search</button>
    </div>

    <!-- أزرار الفلترة -->
    <div class="filter-buttons">
        <button class="filter-active">All</button>
        <button>Pending</button>
        <button>Active</button>
        <button>Suspended</button>
    </div>

    <!-- جدول الشركات -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Status</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- شركة بحالة Pending
            <tr>
                <td>company1</td>
                <td><span class="badge pending">Pending</span></td>
                <td>2026-04-15</td>
                <td>
                    <div class="action-dropdown">
                        <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                        <ul class="action-menu">
                            <li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                            <li><a href="#"><i class="fas fa-check"></i> Approve</a></li>
                            <li><a href="#"><i class="fas fa-times"></i> Reject</a></li>
                        </ul>
                    </div>
                </td>
            </tr> -->

            <!-- شركة Active -->
            <tr>
                <td>company2</td>
                <td><span class="badge active">Active</span></td>
                <td>2025-11-30</td>
                <td>
                    <div class="action-dropdown">
                        <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                        <ul class="action-menu">
                            <li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                            <li><a href="#"><i class="fas fa-pause"></i> Suspend</a></li>
        <!--                    <li><a href="#"><i class="fas fa-ban"></i> Restrict Trips</a></li> -->
                            <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

            <!-- شركة Active أخرى -->
            <tr>
                <td>company3</td>
                <td><span class="badge active">Active</span></td>
                <td>2026-01-10</td>
                <td>
                    <div class="action-dropdown">
                        <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                        <ul class="action-menu">
                            <li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                            <li><a href="#"><i class="fas fa-pause"></i> Suspend</a></li>
                       <!--      <li><a href="#"><i class="fas fa-ban"></i> Restrict Trips</a></li> -->
                            <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

            <!-- شركة موقوفة (Suspended) -->
            <tr>
                <td>company4</td>
                <td><span class="badge suspended">Suspended</span></td>
                <td>2025-09-05</td>
                <td>
                    <div class="action-dropdown">
                        <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                        <ul class="action-menu">
                            <li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                            <li><a href="#"><i class="fas fa-play"></i> Activate</a></li>
                            <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- سجل العمليات -->
    <div class="activity-log">
        <h3><i class="fas fa-history"></i> Activity Log</h3>
        <table class="log-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Admin</th>
                    <th>Company</th>
                    <th>Action</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2026-04-29</td>
                    <td>Admin</td>
                    <td>company1</td>
                    <td>Suspended (7 days)</td>
                    <td>Repeated complaints</td>
                </tr>
                <tr>
                    <td>2026-04-28</td>
                    <td>Admin</td>
                    <td>company4</td>
                    <td>Approved</td>
                    <td>—</td>
                </tr>
            </tbody>
        </table>
    </div>

</main>
    <!-- ملفات JavaScript المشتركة -->
    <script src="js/script.js"></script>

</body>
</html>