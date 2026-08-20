<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Details | Bus Travel Booking</title>
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
            <li class="menu-item"><i class="fas fa-tachometer-alt"></i><a href="company-dashboard.html">Dashboard</a></li>
            <li class="menu-item"><i class="fas fa-bus"></i><a href="company-trips.html">Trips</a></li>
            <li class="menu-item"><i class="fas fa-ticket-alt"></i><a href="company-bookings.html">Bookings</a></li>
            <li class="menu-item active"><i class="fas fa-bus-alt"></i><a href="company-buses.html">Buses</a></li>
            <li class="menu-item"><i class="fas fa-id-card"></i><a href="company-drivers.html">Drivers</a></li>
            <li class="menu-item"><i class="fas fa-tags"></i><a href="company-offers.html">Offers</a></li>
        </ul>
    </aside>

    <!-- ==================== Main Content ==================== -->
    <main class="main-content">
        <h2 class="page-title">🚌 Bus Details</h2>

        <!-- زر الرجوع -->
        <div style="margin-bottom: 25px;">
            <a href="company-buses.html" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Buses
            </a>
        </div>

        <!-- الصف العلوي: صورة + معلومات -->
        <div class="details-top-row">
            <!-- بطاقة الصورة -->
            <div class="identity-card">
                <div class="bus-image-large">
                    <img src="images/photo_2026-05-17_21-25-57.jpg" alt="Bus 1" class="bus-detail-img" onerror="this.src='images/no-image.png'">
                </div>
            </div>

            <!-- بطاقة المعلومات -->
            <div class="identity-card">
                <div class="company-info">
                    <h3>Bus 1</h3>
                    <span class="badge active">Available</span>
                    <p><i class="fas fa-id-card"></i> Plate: SY-12345</p>
                    <p><i class="fas fa-users"></i> Capacity: 44 seats</p>
                    <p><i class="fas fa-tag"></i> Type: Regular</p>
                    <p><i class="fas fa-calendar-alt"></i> Acquired: 2024-03-15</p>
                    <p><i class="fas fa-road"></i> Total Trips: 320</p>
                    <p><i class="fas fa-tachometer-alt"></i> Mileage: 85,000 km</p>
                </div>
            </div>
        </div>

        <!-- الصف الثاني: صيانة + رحلات -->
        <div class="details-top-row">
            <!-- تاريخ الصيانة -->
            <div class="identity-card">
                <h3><i class="fas fa-tools"></i> Maintenance History</h3>
                <table class="data-table" style="box-shadow: none;">
                    <thead>
                        <tr><th>Date</th><th>Type</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2026-04-15</td>
                            <td>Regular Service</td>
                            <td><span class="badge completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>2026-02-10</td>
                            <td>Oil Change</td>
                            <td><span class="badge completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>2025-12-05</td>
                            <td>Tire Replacement</td>
                            <td><span class="badge completed">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- آخر الرحلات -->
            <div class="identity-card">
                <h3><i class="fas fa-bus"></i> Recent Trips</h3>
                <table class="data-table" style="box-shadow: none;">
                    <thead>
                        <tr><th>#</th><th>Route</th><th>Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#101</td><td>Damascus → Aleppo</td><td>2026-05-17</td>
                            <td><span class="badge active">Active</span></td>
                        </tr>
                        <tr>
                            <td>#89</td><td>Homs → Latakia</td><td>2026-05-15</td>
                            <td><span class="badge completed">Completed</span></td>
                        </tr>
                        <tr>
                            <td>#76</td><td>Damascus → Homs</td><td>2026-05-12</td>
                            <td><span class="badge completed">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="admin-actions-bar">
            <button onclick="window.location.href='company-buses.html'" style="background: #6c757d;">
                <i class="fas fa-arrow-left"></i> Back to Buses
            </button>
            <button style="background: #f39c12;" onclick="alert('Send to maintenance (Simulation)')">
                <i class="fas fa-tools"></i> Send to Maintenance
            </button>
            <button style="background: #e74c3c;" onclick="alert('Delete bus (Simulation)')">
                <i class="fas fa-trash"></i> Delete Bus
            </button>
        </div>
    </main>

    <script src="js/script.js"></script>
</body>
</html>