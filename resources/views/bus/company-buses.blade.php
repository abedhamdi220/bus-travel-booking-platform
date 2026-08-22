<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buses | Bus Travel Booking</title>
    <link rel="stylesheet" href="css/all.min.css">
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
            <div class="icon-badge" onclick="window.location.href='company-notifications.html'">
                <i class="fas fa-bell"></i>
                <sup>3</sup>
            </div>
            <span>Welcome, Company</span>
            <div class="avatar">C</div>
        </div>
    </nav>

    <!-- ==================== Sidebar ==================== -->
    <aside class="sidebar">
        <a href="company-profile.html" class="sidebar-company-profile">
            <div class="sidebar-company-logo">
                <i class="fas fa-building fa-2x"></i>
            </div>
            <h4>Al-Sham Transport</h4>
        </a>
        <ul>
            <li class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <a href="company-dashboard.html">Dashboard</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-bus"></i>
                <a href="company-trips.html">Trips</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-ticket-alt"></i>
                <a href="company-bookings.html">Bookings</a>
            </li>
            <li class="menu-item active">
                <i class="fas fa-bus-alt"></i>
                <a href="company-buses.html">Buses</a>
            </li>
             <li class="menu-item">
                <i class="fas fa-id-card"></i>
                <a href="drivers.html">Drivers</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-tags"></i>
                <a href="company-offers.html">Offers</a>
            </li>
           
        </ul>
    </aside>

    <!-- ==================== Main Content ==================== -->
    <main class="main-content">
        <h2 class="page-title">🚌 Buses Management</h2>

        <!-- إحصائيات -->
       <!--- <div class="stats-grid" id="statsCards">
            <div class="stat-card buses-total" data-filter="all">
                <div class="info">
                    <h4>Total Buses</h4>
                    <h2>25</h2>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-bus-alt"></i>
                </div>
            </div>
            <div class="stat-card buses-available" data-filter="available">
                <div class="info">
                    <h4>Available</h4>
                    <h2>18</h2>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-card buses-maintenance" data-filter="maintenance">
                <div class="info">
                    <h4>In Maintenance</h4>
                    <h2>4</h2>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
            <div class="stat-card buses-ontrip" data-filter="on trip">
                <div class="info">
                    <h4>On Trip</h4>
                    <h2>3</h2>
                </div>
                <div class="icon-circle">
                    <i class="fas fa-road"></i>
                </div>
            </div>
        </div>-->

        <div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
    <!-- إجمالي الباصات -->
    <div class="today-card">
        <div class="today-card-top">
            <span>Total Buses</span>
            <i class="fas fa-bus-alt"></i>
        </div>
        <div class="today-card-number">25</div>
        <div class="today-card-bottom">
            <span class="today-trend">🚌 All fleet</span>
        </div>
    </div>

    <!-- باصات في الصيانة -->
    <div class="today-card" style="background: linear-gradient(135deg, #e74c3c, #f39c12);">
        <div class="today-card-top">
            <span>In Maintenance</span>
            <i class="fas fa-tools"></i>
        </div>
        <div class="today-card-number">4</div>
        <div class="today-card-bottom">
            <span class="today-trend">🔧 Under repair</span>
        </div>
    </div>
</div>

        <!-- إضافة باص + بحث -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <button onclick="window.location.href='company-bus-add.html'" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-plus"></i> Add New Bus
            </button>
            <div class="table-toolbar" style="flex: 1; margin-bottom: 0;">
                <input type="text" placeholder="Search by bus number or plate...">
                <button><i class="fas fa-search"></i> Search</button>
            </div>
        </div>

        <!-- أزرار الفلترة -->
        <div class="filter-buttons" id="filterButtons">
            <button class="filter-active" data-filter="all">All</button>
            <button data-filter="available">Available</button>
            <button data-filter="maintenance">Maintenance</button>
            <button data-filter="on trip">On Trip</button>
        </div>

        <!-- جدول الباصات -->
        <table class="data-table" id="busesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bus</th>
                    <th>Plate Number</th>
                    <th>Capacity</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Regular Bus - Available -->
                <tr data-status="available" data-type="regular">
                    <td>1</td>
                    <td>Bus 1</td>
                    <td>SY-12345</td>
                    <td>44</td>
                    <td><span class="badge">Regular</span></td>
                    <td><span class="badge active">Available</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="bus-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                                <li><a href="#"><i class="fas fa-tools"></i> Send to Maintenance</a></li>
                                <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- Regular Bus - Available -->
                <tr data-status="available" data-type="regular">
                    <td>2</td>
                    <td>Bus 2</td>
                    <td>SY-67890</td>
                    <td>44</td>
                    <td><span class="badge">Regular</span></td>
                    <td><span class="badge active">Available</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="bus-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                                <li><a href="#"><i class="fas fa-tools"></i> Send to Maintenance</a></li>
                                <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- Regular Bus - Maintenance -->
                <tr data-status="maintenance" data-type="regular">
                    <td>3</td>
                    <td>Bus 3</td>
                    <td>SY-11223</td>
                    <td>44</td>
                    <td><span class="badge">Regular</span></td>
                    <td><span class="badge suspended">Maintenance</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="bus-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                                <li><a href="#"><i class="fas fa-check-circle"></i> Return to Service</a></li>
                                <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- VIP Bus - Available -->
                <tr data-status="available" data-type="vip">
                    <td>4</td>
                    <td>VIP Bus 1</td>
                    <td>SY-99887</td>
                    <td>26</td>
                    <td><span class="badge">VIP</span></td>
                    <td><span class="badge active">Available</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="bus-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                                <li><a href="#"><i class="fas fa-tools"></i> Send to Maintenance</a></li>
                                <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- VIP Bus - On Trip -->
                <tr data-status="on trip" data-type="vip">
                    <td>5</td>
                    <td>VIP Bus 2</td>
                    <td>SY-77665</td>
                    <td>26</td>
                    <td><span class="badge">VIP</span></td>
                    <td><span class="badge on-trip">On Trip</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="bus-details.html"><i class="fas fa-eye"></i> View Details</a></li>
                                <li><a href="#"><i class="fas fa-tools"></i> Send to Maintenance</a></li>
                                <li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

    <script src="js/script.js"></script>
    
    <!-- كود تفعيل البطاقات والفلترة -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // تفعيل الضغط على البطاقات الإحصائية
        document.querySelectorAll('#statsCards .stat-card').forEach(function(card) {
            card.addEventListener('click', function() {
                var filter = this.getAttribute('data-filter');
                filterTable(filter);
                
                document.querySelectorAll('#filterButtons button').forEach(function(btn) {
                    btn.classList.remove('filter-active');
                    if (btn.getAttribute('data-filter') === filter) {
                        btn.classList.add('filter-active');
                    }
                });
            });
        });
        // تفعيل أزرار الفلترة
        document.querySelectorAll('#filterButtons button').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var filter = this.getAttribute('data-filter');
                filterTable(filter);
                
                document.querySelectorAll('#filterButtons button').forEach(function(b) {
                    b.classList.remove('filter-active');
                });
                this.classList.add('filter-active');
            });
        });

        function filterTable(filter) {
            document.querySelectorAll('#busesTable tbody tr').forEach(function(row) {
                if (filter === 'all') {
                    row.style.display = '';
                } else {
                    var status = row.getAttribute('data-status');
                    row.style.display = (status === filter) ? '' : 'none';
                }
            });
        }
    });
    </script>
</body>
</html>