<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Co-Drivers | Bus Travel Booking</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ==================== Top Navbar ==================== -->
    <nav class="navbar">
        <div class="brand"><i class="fas fa-bus"></i><span>Bus Travel Booking</span></div>
        <div class="user-info">
            <div class="icon-badge" onclick="window.location.href='company-notifications.html'"><i class="fas fa-bell"></i><sup>3</sup></div>
            <span>Welcome, Company</span>
            <div class="avatar">C</div>
        </div>
    </nav>

    <!-- ==================== Sidebar ==================== -->
    <aside class="sidebar">
        <a href="company-profile.html" class="sidebar-company-profile">
            <div class="sidebar-company-logo"><i class="fas fa-building fa-2x"></i></div>
            <h4>Al-Sham Transport</h4>
        </a>
        <ul>
            <li class="menu-item"><i class="fas fa-tachometer-alt"></i><a href="company-dashboard.html">Dashboard</a></li>
            <li class="menu-item"><i class="fas fa-bus"></i><a href="company-trips.html">Trips</a></li>
            <li class="menu-item"><i class="fas fa-ticket-alt"></i><a href="company-bookings.html">Bookings</a></li>
            <li class="menu-item"><i class="fas fa-bus-alt"></i><a href="company-buses.html">Buses</a></li>
            <li class="menu-item active"><i class="fas fa-id-card"></i><a href="drivers.html">Drivers</a></li>
            <li class="menu-item"><i class="fas fa-tags"></i><a href="company-offers.html">Offers</a></li>
        </ul>
    </aside>

    <!-- ==================== Main Content ==================== -->
    <main class="main-content">
        <h2 class="page-title">👥 Co-Drivers Management</h2>

        <!-- زر الرجوع -->
        <div style="margin-bottom: 25px;">
            <a href="drivers.html" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Drivers</a>
        </div>

        <!-- إحصائيات -->
        <div class="stats-grid">
            <div class="stat-card drivers-total">
                <div class="info"><h4>Total Co-Drivers</h4><h2>6</h2></div>
                <div class="icon-circle"><i class="fas fa-user-friends"></i></div>
            </div>
            <div class="stat-card drivers-available">
                <div class="info"><h4>Assigned</h4><h2>4</h2></div>
                <div class="icon-circle"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card drivers-pending">
                <div class="info"><h4>Unassigned</h4><h2>2</h2></div>
                <div class="icon-circle"><i class="fas fa-clock"></i></div>
            </div>
        </div>

        <!-- جدول المعاونين -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Co-Driver</th>
                    <th>Phone</th>
                    <th>Experience</th>
                    <th>Assigned Driver</th>
                    <th>Assigned Bus</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><strong>Ali</strong></td>
                    <td>+963 990 111</td>
                    <td>5 years</td>
                    <td>Ahmed Khalil</td>
                    <td>Bus 1</td>
                    <td>
                        <button class="btn-dismiss" onclick="confirmDismiss('Ali')"><i class="fas fa-trash"></i> Dismiss</button>
                        </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><strong>Khaled</strong></td>
                    <td>+963 990 222</td>
                    <td>3 years</td>
                    <td>Samer Haddad</td>
                    <td>Bus 3</td>
                    <td>
                        <button class="btn-dismiss" onclick="confirmDismiss('Khaled')"><i class="fas fa-trash"></i> Dismiss</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><strong>Sami</strong></td>
                    <td>+963 990 333</td>
                    <td>3 years</td>
                    <td>Bassam Ali</td>
                    <td>Bus 2</td>
                    <td>
                        <button class="btn-dismiss" onclick="confirmDismiss('Sami')"><i class="fas fa-trash"></i> Dismiss</button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><strong>Fadi</strong></td>
                    <td>+963 990 444</td>
                    <td>2 years</td>
                    <td>Omar Zidan</td>
                    <td>VIP Bus 2</td>
                    <td>
                        <button class="btn-dismiss" onclick="confirmDismiss('Fadi')"><i class="fas fa-trash"></i> Dismiss</button>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td><strong>Hassan</strong></td>
                    <td>+963 990 555</td>
                    <td>1 year</td>
                    <td><span style="color: #94a3b8;">— Unassigned —</span></td>
                    <td>—</td>
                    <td>
                        <button class="btn-dismiss" onclick="confirmDismiss('Hassan')"><i class="fas fa-trash"></i> Dismiss</button>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td><strong>Nabil</strong></td>
                    <td>+963 990 666</td>
                    <td>4 years</td>
                    <td><span style="color: #94a3b8;">— Unassigned —</span></td>
                    <td>—</td>
                    <td>
                        <button class="btn-dismiss" onclick="confirmDismiss('Nabil')"><i class="fas fa-trash"></i> Dismiss</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

    <script src="js/script.js"></script>
    <script>
        function confirmDismiss(name) {
            if (confirm('Are you sure you want to dismiss ' + name + '?')) {
                alert('✅ ' + name + ' dismissed successfully! (Simulation)');
            }
        }
    </script>
</body>
</html>