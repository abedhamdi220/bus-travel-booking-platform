<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | Bus Travel Booking</title>
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
            <li class="menu-item">
                <i class="fas fa-building"></i>
                <a href="companies.html">Companies</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-users"></i>
                <a href="users.html">Users</a>
            </li>
            <li class="menu-item">
                <i class="fas fa-cog"></i>
                <a href="sittings.html">Sittings</a>
            </li>
        </ul>
    </aside>

    <!-- ==================== Main Content ==================== -->
    <main class="main-content">
        <h2 class="page-title">👤 Admin Profile</h2>

        <!-- ========== الصف العلوي: معلومات + أمان ========== -->
        <div class="profile-grid">

            <!-- القسم 1: المعلومات الشخصية -->
            <div class="profile-card">
                <h3><i class="fas fa-user"></i> Personal Information</h3>

                <!-- الصورة الشخصية مع إمكانية التعديل -->
                <div class="profile-avatar-section">
                    <div class="avatar-xlarge" id="profileAvatar">A</div>
                    <label for="photoUpload" class="btn-change-photo">
                        <i class="fas fa-camera"></i> Change Photo
                    </label>
                    <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                </div>

                <!-- جدول المعلومات -->
                <table class="info-table">
    <tr>
        <td><i class="fas fa-user"></i> Full Name</td>
        <td><input type="text" id="editName" value="Admin"></td>
    </tr>
    <tr>
        <td><i class="fas fa-id-badge"></i> Username</td>
        <td><input type="text" id="editUsername" value="@admin" disabled></td>
    </tr>
    <tr>
        <td><i class="fas fa-envelope"></i> Email</td>
        <td><input type="email" id="editEmail" value="admin@bustravel.com"></td>
    </tr>
    <tr>
        <td><i class="fas fa-phone"></i> Phone</td>
        <td><input type="text" id="editPhone" value="+963 990 000 000"></td>
    </tr>
    <tr>
        <td><i class="fas fa-map-marker-alt"></i> Governorate</td>
        <td><input type="text" id="editGovernorate" value="Damascus"></td>
    </tr>
    <tr>
        <td><i class="fas fa-calendar-alt"></i> Joined</td>
        <td><input type="text" value="2025-01-15" disabled></td>
    </tr>
    <tr>
        <td><i class="fas fa-shield-alt"></i> Role</td>
        <td><span class="badge active">Super Admin</span></td>
    </tr>
</table>
            </div>

            <!-- القسم 2: الأمان -->
            <div class="profile-card">
                <h3><i class="fas fa-lock"></i> Security</h3>
                <!-- تغيير كلمة المرور -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-weight: 600; color: #1e293b; margin-bottom: 10px; display: block;">Change Password</label>
                    <input type="password" placeholder="Current password">
                    <input type="password" placeholder="New password" style="margin-top: 8px;">
                    <input type="password" placeholder="Confirm new password" style="margin-top: 8px;">
                    <button class="btn-save-password"><i class="fas fa-save"></i>save password</button>
                </div>
<!--قوة كلمة المرور-->
                    <div class="password-strength">
                      <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <span id="strengthText" style="font-size: 12px; color: #64748b;">Enter a password</span>
            </div>

        </div>

        <!-- ========== أزرار الإجراءات ========== -->
        <div class="profile-actions">
            <button class="btn-submit"><i class="fas fa-save"></i> Edit Information</button>
            <button class="btn-outline"><i class="fas fa-history"></i> Activity Log</button>
            <button class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </div>

        <!-- ========== Footer ========== -->
        <div class="profile-footer">
            <span><i class="fas fa-circle" style="color: #2ecc71; font-size: 8px;"></i> Online</span>
            <span><i class="fas fa-clock"></i> Last Login: 2026-05-13, 08:30 AM</span>
            <span><i class="fas fa-globe"></i> IP: 192.168.1.1</span>
        </div>

    </main>

    <!-- ==================== JavaScript ==================== -->
    <script>
        // تغيير الصورة الشخصية
        document.getElementById('photoUpload').addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var avatar = document.getElementById('profileAvatar');
                    avatar.style.backgroundImage = 'url(' + event.target.result + ')';
                    avatar.style.backgroundSize = 'cover';
                    avatar.style.backgroundPosition = 'center';
                    avatar.textContent = '';
                    // تحديث الصورة في السايد بار
var sidebarLogo = document.getElementById('sidebarAdminLogo');
if (sidebarLogo) {
    var sidebarIcon = sidebarLogo.querySelector('i');
    if (sidebarIcon) sidebarIcon.style.display = 'none';
    sidebarLogo.style.backgroundImage = 'url(' + event.target.result + ')';
    sidebarLogo.style.backgroundSize = 'cover';
    sidebarLogo.style.backgroundPosition = 'center';
}
                };
                reader.readAsDataURL(file);
            }
        });


<!--***************************************زر كلمات المرور************************************-->


document.addEventListener('DOMContentLoaded', function() {
    var savePasswordBtn = document.querySelector('.btn-save-password');
    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', function() {
            // جلب حقول كلمة المرور
            var currentPassword = document.querySelector('.profile-card input[type="password"]:nth-of-type(1)');
            var newPassword = document.querySelector('.profile-card input[type="password"]:nth-of-type(2)');
            var confirmPassword = document.querySelector('.profile-card input[type="password"]:nth-of-type(3)');

            // التحقق من أن الحقول ليست فارغة
              if (!currentPassword.value|| !newPassword.value || !confirmPassword.value) {
                alert('Please fill all password fields.');
                return;
            }

            // التحقق من تطابق كلمة المرور الجديدة
            if (newPassword.value !== confirmPassword.value) {
                alert('New passwords do not match.');
                return;
            }

            // التحقق من طول كلمة المرور
            if (newPassword.value.length < 6) {
                alert('Password must be at least 6 characters.');
                return;
            }

            // محاكاة تغيير كلمة المرور
            alert('✅ Password changed successfully! (Simulation)');

            // مسح الحقول
            currentPassword.value = '';
            newPassword.value = '';
            confirmPassword.value = '';
        });
    }
});



///*************************زر تعديل البيانات للادمن*********************

document.addEventListener('DOMContentLoaded', function() {
    var editBtn = document.querySelector('.btn-submit');
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            var name = document.getElementById('editName').value;
            var email = document.getElementById('editEmail').value;
            var phone = document.getElementById('editPhone').value;
            var governorate = document.getElementById('editGovernorate').value;

            if (!name || !email) {
                alert('Name and Email are required.');
                return;
            }

            alert('✅ Information updated successfully! (Simulation)\n\n' +
                  'Name: ' + name + '\n' +
                  'Email: ' + email + '\n' +
                  'Phone: ' + phone + '\n' +
                  'Governorate: ' + governorate);
        });
    }
});

//****************************زر تسجيل الخروج للادمن بياخدني لصفحة login**********************
document.addEventListener('DOMContentLoaded', function() {
    var logoutBtn = document.querySelector('.btn-logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                alert('👋 Logged out successfully!');
                window.location.href = 'login.html';
            }
        });
    }
});


//*******************;كود قوة كلمة المرور***************
document.addEventListener('DOMContentLoaded', function() {
    var newPasswordInput = document.querySelector('.profile-card input[type="password"]:nth-of-type(2)');
    var strengthBar = document.getElementById('strengthBar');
    var strengthText = document.getElementById('strengthText');

    if (newPasswordInput && strengthBar && strengthText) {
        newPasswordInput.addEventListener('input', function() {
            var password = this.value;
            var strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[!@#$%^&*]/.test(password)) strength++;

            var width = (strength / 5) * 100;
            strengthBar.style.width = width + '%';

            if (strength <= 2) {
                strengthBar.style.background = '#e74c3c';
                strengthText.textContent = 'Weak password';
            } else if (strength <= 4) {
                strengthBar.style.background = '#f39c12';
                strengthText.textContent = 'Medium password';
            } else {
                strengthBar.style.background = '#2ecc71';
                strengthText.textContent = 'Strong password';
            }

            if (password === '') {
                strengthBar.style.width = '0%';
                strengthText.textContent = 'Enter a password';
            }
        });
    }
});



    </script>

</body>
</html>