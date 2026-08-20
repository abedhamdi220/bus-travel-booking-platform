<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings | Bus Travel Booking</title>
    <link rel="stylesheet" href="{{ asset('css/company/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/company/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <a href="{{ route('company.profile') }}">
    <div class="sidebar-company-profile">
        <div class="sidebar-company-logo" id="sidebarLogo">
            <i class="fas fa-building fa-2x"></i>
        </div>
        <h4>Al-Sham Transport</h4>
    </div>
    </a>
        <ul>
            <li class="menu-item"><i class="fas fa-tachometer-alt"></i><a href="{{ route('company.dashboard')}}">Dashboard</a></li>
            <li class="menu-item"><i class="fas fa-bus"></i><a href="{{ route('company.trips')}}">Trips</a></li>
            <li class="menu-item"><i class="fas fa-ticket-alt"></i><a href="{{ route('company.bookings')}}">Bookings</a></li>
            <li class="menu-item"><i class="fas fa-bus-alt"></i><a href="{{ route('company.buses')}}">Buses</a></li>
            <li class="menu-item"><i class="fas fa-id-card"></i><a href="{{ route('company.mydrivers')}}">Drivers</a></li>
            <li class="menu-item"><i class="fas fa-tags"></i><a href="{{ route('company.offers')}}">Offers</a></li>
        </ul>
    </aside>

    <!-- ==================== Main Content ==================== -->
    <main class="main-content">
        <h2 class="page-title">🎫 Bookings</h2>

        <!-- بطاقة حجوزات اليوم + مخطط العمولة -->
        <div class="stats-grid" style="grid-template-columns: 1fr 1fr;">
            <!-- Today's Bookings -->

            <div class="today-card">
                <div class="today-card-top">
                    <span>Today's Bookings</span>
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="today-card-number">
                    28
                </div>
                <div class="today-card-bottom">
                    <span class="today-trend up"><i class="fas fa-arrow-up"></i> +12%</span>
                    <span>vs yesterday</span>
                </div>
            </div>

            <!-- Commission Chart -->
            <div class="chart-box" style="margin: 0; padding: 20px;">
                <h3 style="font-size: 14px;"><i class="fas fa-chart-pie"></i> Commission</h3>
                <div style="height: 180px;">
                    <canvas id="commissionChart"></canvas>
                </div>
            </div>
        </div>

        <!--المخطط-->
        <!-- Bookings Chart -->
        <div class="chart-box">
            <h3><i class="fas fa-chart-line"></i> Daily Bookings (Last 7 Days)</h3>
            <div style="height: 300px;">
                <canvas id="bookingsChart"></canvas>
            </div>
        </div>

        <!-- فلترة بالتاريخ -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px; align-items: center;">
            <div class="table-toolbar" style="flex: 2; margin-bottom: 0;">
                <input type="text" placeholder="Search by booking #, passenger, or trip...">
                <button><i class="fas fa-search"></i> Search</button>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <label style="font-size: 13px; color: #64748b; white-space: nowrap;">From:</label>
                <input type="date" id="dateFrom"
                    style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px;">
                <label style="font-size: 13px; color: #64748b; white-space: nowrap;">To:</label>
                <input type="date" id="dateTo"
                    style="padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px;">
                <button onclick="filterByDate()"
                    style="padding: 10px 16px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Apply</button>
            </div>
        </div>

        <!-- أزرار فلترة الحالة -->
        <div class="filter-buttons">
            <button class="filter-active">All</button>
            <button>Confirmed</button>
            <button>Pending</button>
            <button>Completed</button>
            <button>Cancelled</button>
        </div>

        <!-- زر تصدير -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
            <button onclick="alert('Export to Excel (Simulation)')"
                style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
        </div>

        <!-- جدول الحجوزات -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>Booking #</th>
                    <th>Passenger</th>
                    <th>Trip</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Seat</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- حجز جديد - مميز بلون -->
                <tr class="new-booking">
                    <td>#BK-1023</td>
                    <td>Ahmed Mohammed</td>
                    <td>Damascus → Aleppo</td>
                    <td>2026-05-17</td>
                    <td>08:00 AM</td>
                    <td>12A</td>
                    <td>$20.00</td>
                    <td><span class="badge confirmed">Confirmed</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i
                                    class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="company-booking-details.html"><i class="fas fa-eye"></i> View Details</a>
                                </li>
                                <li><a href="#"><i class="fas fa-times"></i> Cancel Booking</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- حجز جديد - مميز بلون -->
                <tr class="new-booking">
                    <td>#BK-1024</td>
                    <td>Sara Ali</td>
                    <td>Homs → Latakia</td>
                    <td>2026-05-17</td>
                    <td>02:30 PM</td>
                    <td>15B</td>
                    <td>$25.00</td>
                    <td><span class="badge pending">Pending</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i
                                    class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="company-booking-details.html"><i class="fas fa-eye"></i> View Details</a>
                                </li>
                                <li><a href="#"><i class="fas fa-times"></i> Cancel Booking</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- حجز عادي -->
                <tr>
                    <td>#BK-1022</td>
                    <td>Khaled Youssef</td>
                    <td>Aleppo → Damascus</td>
                    <td>2026-05-16</td>
                    <td>10:00 AM</td>
                    <td>8C</td>
                    <td>$22.00</td>
                    <td><span class="badge completed">Completed</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i
                                    class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="company-booking-details.html"><i class="fas fa-eye"></i> View Details</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- حجز ملغي -->
                <tr>
                    <td>#BK-1021</td>
                    <td>Noura Hassan</td>
                    <td>Tartous → Hama</td>
                    <td>2026-05-15</td>
                    <td>07:00 AM</td>
                    <td>5D</td>
                    <td>$18.00</td>
                    <td><span class="badge cancelled">Cancelled</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i
                                    class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="company-booking-details.html"><i class="fas fa-eye"></i> View Details</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- حجز عادي -->
                <tr>
                    <td>#BK-1020</td>
                    <td>Bassam Haddad</td>
                    <td>Damascus → Homs</td>
                    <td>2026-05-14</td>
                    <td>04:00 PM</td>
                    <td>3A</td>
                    <td>$15.00</td>
                    <td><span class="badge completed">Completed</span></td>
                    <td>
                        <div class="action-dropdown">
                            <button class="btn-dots" onclick="toggleActionMenu(this)"><i
                                    class="fas fa-ellipsis-v"></i></button>
                            <ul class="action-menu">
                                <li><a href="company-booking-details.html"><i class="fas fa-eye"></i> View Details</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>

    <script src="{{ asset('js/script.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==================== فلترة حسب الحالة ====================
            var filterBtns = document.querySelector('.filter-buttons');
            if (filterBtns) {
                filterBtns.querySelectorAll('button').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        filterBtns.querySelectorAll('button').forEach(function(b) {
                            b.classList.remove('filter-active');
                        });
                        this.classList.add('filter-active');

                        var filter = this.textContent.trim().toLowerCase();

                        document.querySelectorAll('.data-table tbody tr').forEach(function(row) {
                            var badge = row.querySelector('.badge');
                            if (badge) {
                                var status = badge.textContent.trim().toLowerCase();
                                if (filter === 'all' || status === filter) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                        });
                    });
                });
            }

            // ==================== بحث ====================
            var searchBtn = document.querySelector('.table-toolbar button');
            if (searchBtn) {
                searchBtn.addEventListener('click', function() {
                    var input = document.querySelector('.table-toolbar input');
                    var query = input.value.trim().toLowerCase();
                    if (query === '') return;

                    document.querySelectorAll('.data-table tbody tr').forEach(function(row) {
                        if (row.textContent.toLowerCase().includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

        });


        //###########كود فلترة الجدول من تاريخ الى تاريخ############
        function filterByDate() {
            var from = document.getElementById('dateFrom').value;
            var to = document.getElementById('dateTo').value;
            if (!from || !to) {
                alert('Please select both dates.');
                return;
            }

            document.querySelectorAll('.data-table tbody tr').forEach(function(row) {
                var dateCell = row.cells[3].textContent.trim();
                if (dateCell >= from && dateCell <= to) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }



        //**********************كود المخطط********************

        var canvas = document.getElementById('bookingsChart');
        if (canvas) {
            var ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['May 13', 'May 14', 'May 15', 'May 16', 'May 17', 'May 18', 'May 19'],
                    datasets: [{
                        label: 'Bookings',
                        data: [12, 18, 15, 22, 28, 20, 25],
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 3,
                        tension: 0.4, //انحناء الخط
                        fill: true, //تعبئة المنطقة تحت الخط
                        pointBackgroundColor: 'white',
                        pointBorderColor: '#3498db',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(30, 41, 59, 0.9)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(item) {
                                    return '📊 ' + item.raw + ' bookings';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.04)',
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748b',
                                stepSize: 5
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        }


        //********************محطط العمولة والارباح***************


        var commCanvas = document.getElementById('commissionChart');
        if (commCanvas) {
            var ctx = commCanvas.getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Revenue', 'Commission'],
                    datasets: [{
                        data: [28125, 3125],
                        backgroundColor: ['#2ecc71', '#f39c12'],
                        borderWidth: 2,
                        borderColor: 'white'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>
