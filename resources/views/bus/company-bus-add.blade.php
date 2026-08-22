<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Bus | Bus Travel Booking</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <i class="fas fa-bus"></i>
            <span>Bus Travel Booking</span>
        </div>
        <div class="user-info">
            <div class="icon-badge" onclick="window.location.href='company-notifications.html'">
                <i class="fas fa-bell"></i><sup>3</sup>
            </div>
            <span>Welcome, Company</span>
            <div class="avatar">C</div>
        </div>
    </nav>

    <aside class="sidebar">
        <a href="company-profile.html" class="sidebar-company-profile">
            <div class="sidebar-company-logo">
                <i class="fas fa-building fa-2x"></i>
            </div>
            <h4>Al-Sham Transport</h4>
        </a>
        <ul>
            <li class="menu-item">
                <i class="fas fa-tachometer-alt"></i><a href="company-dashboard.html">Dashboard</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-bus"></i><a href="company-trips.html">Trips</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-ticket-alt"></i><a href="company-bookings.html">Bookings</a>
            </li>
            <li class="menu-item active">
                <i class="fas fa-bus-alt"></i><a href="company-buses.html">Buses</a>
            </li>
             <li class="menu-item"><i class="fas fa-id-card"></i><a href="drivers.html">Drivers</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-tags"></i><a href="company-offers.html">Offers</a>
            </li>
          
        </ul>
    </aside>

    <main class="main-content">
        <h2 class="page-title">🚌 Add New Bus</h2>

        <div class="form-container">
            <form id="addBusForm">
                <div class="form-group">
                    <label>Bus Name / Number</label>
                    <input type="text" id="busName" placeholder="e.g. Bus 6" required>
                </div>

                <div class="form-group">
                    <label>Plate Number</label>
                    <input type="text" id="plateNumber" placeholder="e.g. SY-33445" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Bus Type</label>
                        <select id="busType" required onchange="updateCapacity()">
                            <option value="">Select type</option>
                            <option value="regular">Regular (44 seats)</option>
                            <option value="vip">VIP (26 seats)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" id="capacity" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="status" required>
                        <option value="available">Available</option>
                        <option value="maintenance">In Maintenance</option>
                    </select>
                </di>
             <div class="form-group">
                    <label>Bus Image (Optional)</label>
                    <div class="bus-image-upload">
                        <label for="busImage" class="btn-upload-image">
                            <i class="fas fa-cloud-upload-alt"></i> Choose Image
                        </label>
                        <input type="file" id="busImage" accept="image/*" hidden>
                        <span id="imageFileName" style="color: #64748b; font-size: 13px; margin-left: 10px;">No file chosen</span>
                    </div>
                    <div class="bus-image-preview" id="imagePreview" style="display: none; margin-top: 15px;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                    </div>
            </div>
                <div class="form-actions">
                    <button type="button" onclick="window.location.href='company-buses.html'" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit"><i class="fas fa-plus"></i> Add Bus</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        function updateCapacity() {
            var type = document.getElementById('busType').value;
            var capacityInput = document.getElementById('capacity');
            if (type === 'regular') {
                capacityInput.value = 44;
            } else if (type === 'vip') {
                capacityInput.value = 26;
            } else {
                capacityInput.value = '';
            }
        }

        document.getElementById('addBusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var name = document.getElementById('busName').value;
            var plate = document.getElementById('plateNumber').value;
            var type = document.getElementById('busType').value;
            var capacity = document.getElementById('capacity').value;
            var status = document.getElementById('status').value;

            if (!name || !plate || !type) {
                alert('Please fill all required fields.');
                return;
            }

            alert('✅ Bus added successfully! (Simulation)\n\n' +
                  'Name: ' + name + '\n' +
                  'Plate: ' + plate + '\n' +
                  'Type: ' + type + '\n' +
                  'Capacity: ' + capacity + '\n' +
                  'Status: ' + status);
            window.location.href = 'company-buses.html';
        });

        //*************************************************
        //**************************اضافة صولرة الباص الجديد*****************
        document.getElementById('busImage').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        document.getElementById('imageFileName').textContent = file.name;
        var reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('previewImg').src = event.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imageFileName').textContent = 'No file chosen';
        document.getElementById('imagePreview').style.display = 'none';
    }
});
    </script>
</body>
</html>