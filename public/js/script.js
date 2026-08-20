// ============================================
// SCRIPT.JS - Bus Travel Booking Admin
// ============================================

document.addEventListener('DOMContentLoaded', function() {

//===================pagActive====================
    let currentpage=window.location.pathname;
    document.querySelectorAll('.menu-item').forEach(link=>{
        const a=link.querySelector('a');
        if(a && new URL(a.href).pathname===currentpage){
            link.classList.add('active');
        }
        else{
            link.classList.remove('active');
        }
    });
    // ==================== 1. المخطط البياني (User Statistics) ====================
    document.querySelectorAll('.bar').forEach(function(bar) {
        var value = bar.getAttribute('data-value');
        if (value) {
            var heightPercent = (parseInt(value) / 1000) * 100;
            bar.style.height = heightPercent + '%';
        }
    });

    // ==================== 2. زر عرض كل المحافظات ====================
    var showAllBtn = document.getElementById('showAllBtn');
    if (showAllBtn) {
        showAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.extra-row').forEach(function(row) {
                row.style.display = 'table-row';
            });
            this.style.display = 'none';
        });
    }

    // ==================== 3. شريط البحث العام ====================
    var searchBtn = document.querySelector('.table-toolbar button');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            var input = document.querySelector('.table-toolbar input');
            if (!input) return;
            var query = input.value.trim();
            if (query === '') return;

            document.querySelectorAll('.data-table tbody tr').forEach(function(row) {
                if (row.textContent.toLowerCase().includes(query.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // ==================== 4. أزرار الفلترة العامة ====================
    var filterBtnsContainer = document.querySelector('.filter-buttons');
    if (filterBtnsContainer) {
        filterBtnsContainer.querySelectorAll('button').forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterBtnsContainer.querySelectorAll('button').forEach(function(b) {
                    b.classList.remove('filter-active');
                });
                this.classList.add('filter-active');

                var filter = this.textContent.trim().toLowerCase();

                document.querySelectorAll('.data-table tbody tr').forEach(function(row) {
                    var badge = row.querySelector('.badge');
                    if (!badge) {
                        // للإشعارات - فلترة unread / companies
                        if (filter === 'all') {
                            row.style.display = '';
                        } else if (filter === 'unread') {
                            row.style.display = row.classList.contains('unread-row') ? '' : 'none';
                        } else if (filter === 'companies') {
                            row.style.display = row.querySelector('.badge.company-badge') ? '' : 'none';
                        }
                        return;
                    }

                    var status = badge.textContent.trim().toLowerCase();
                    if (status === 'full') status = 'completed';
                    if (status === 'blocked' && filter === 'suspended') status = 'suspended';

                    if (filter === 'all' || status === filter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    }

    // ==================== 5. القوائم المنسدلة للإجراءات (شركات) ====================
    window.toggleActionMenu = function(button) {
        var menu = button.nextElementSibling;
        if (menu) menu.classList.toggle('show');
    };
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-dropdown')) {
            document.querySelectorAll('.action-menu').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });

    // ==================== 6. دالة إضافة سجل العمليات ====================
    function addLogEntry(company, action, reason) {
        var logBody = document.querySelector('.log-table tbody');
        if (!logBody) return;

        var today = new Date().toISOString().split('T')[0];
        var newRow = document.createElement('tr');
        newRow.innerHTML = '<td>' + today + '</td><td>Admin</td><td>' + company + '</td><td>' + action + '</td><td>' + (reason || '—') + '</td>';
        logBody.prepend(newRow);
    }

    // ==================== 7. أزرار الإجراءات في جدول الشركات ====================
    var tbody = document.querySelector('.data-table tbody');
    if (tbody) {
        tbody.addEventListener('click', function(e) {
            var link = e.target.closest('.action-menu li a');
            if (!link) return;

            e.preventDefault();

            var text = link.textContent.trim().toLowerCase();
            var row = link.closest('tr');
            var companyName = row ? row.cells[0].textContent.trim() : '';
            var statusCell = row ? row.querySelector('.badge') : null;
            var actionCell = row ? row.querySelector('.action-dropdown .action-menu') : null;

            // View Details
            if (text.includes('view')) {
                window.location.href = 'company-details.html';
                return;
            }

            // Approve
            if (text.includes('approve')) {
                if (confirm('Accept ' + companyName + '?')) {
                    if (statusCell) { statusCell.textContent = 'Active'; statusCell.className = 'badge active'; }
                    if (actionCell) {
                        actionCell.innerHTML = '<li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>' +
                            '<li><a href="#"><i class="fas fa-pause"></i> Suspend</a></li>' +
                            '<li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>';
                    }
                    addLogEntry(companyName, 'Approved', 'Company meets all requirements');
                    alert('Approved: ' + companyName);
                }
            }

            // Reject
            else if (text.includes('reject')) {
                if (confirm('Reject ' + companyName + '?')) {
                    addLogEntry(companyName, 'Rejected', 'Application denied');
                    row.style.display = 'none';
                    alert('Rejected: ' + companyName);
                }
            }

            // Suspend
            else if (text.includes('suspend')) {
                var duration = prompt('Suspension duration (e.g. 24h, 7 days):');
                if (!duration) return;
                var reason = prompt('Reason for suspension:');
                if (!reason) return;
                if (confirm('Suspend ' + companyName + ' for ' + duration + '?')) {
                    if (statusCell) { statusCell.textContent = 'Pending (Suspended)'; statusCell.className = 'badge pending'; }
                    if (actionCell) {
                        actionCell.innerHTML = '<li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>' +
                            '<li><a href="#"><i class="fas fa-play"></i> Activate</a></li>' +
                            '<li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>';
                    }
                    addLogEntry(companyName, 'Suspended (' + duration + ')', reason);
                    alert('Suspended: ' + companyName);
                }
            }
            // Activate
            else if (text.includes('activate')) {
                if (confirm('Activate ' + companyName + '?')) {
                    if (statusCell) { statusCell.textContent = 'Active'; statusCell.className = 'badge active'; }
                    if (actionCell) {
                        actionCell.innerHTML = '<li><a href="company-details.html"><i class="fas fa-eye"></i> View Details</a></li>' +
                            '<li><a href="#"><i class="fas fa-pause"></i> Suspend</a></li>' +
                            '<li><a href="#"><i class="fas fa-trash"></i> Delete</a></li>';
                    }
                    addLogEntry(companyName, 'Activated', 'Manual reactivation by admin');
                    alert('Activated: ' + companyName);
                }
            }

            // Delete
            else if (text.includes('delete')) {
                var reason = prompt('Reason for deletion:');
                if (!reason) return;
                if (confirm('PERMANENTLY DELETE ' + companyName + '?')) {
                    addLogEntry(companyName, 'Deleted Permanently', reason);
                    row.style.display = 'none';
                    alert('Deleted: ' + companyName);
                }
            }

            // Close dropdown
            var menu = link.closest('.action-menu');
            if (menu) menu.classList.remove('show');
        });
    }

    // ==================== 8. أزرار حظر وإلغاء حظر المستخدمين ====================
    if (tbody) {
        tbody.addEventListener('click', function(e) {
            var btn = e.target.closest('button');
            if (!btn) return;

            var row = btn.closest('tr');
            var userName = row ? row.cells[1].textContent.trim() : '';
            var statusCell = row ? row.querySelector('.badge') : null;

            // Block
            if (btn.classList.contains('btn-block')) {
                if (confirm('Block ' + userName + '?')) {
                    if (statusCell) { statusCell.textContent = 'Blocked'; statusCell.className = 'badge blocked'; }
                    btn.classList.remove('btn-block');
                    btn.classList.add('btn-unblock');
                    btn.innerHTML = '<i class="fas fa-unlock"></i> Unblock';
                    alert(userName + ' blocked.');
                }
            }

            // Unblock
            else if (btn.classList.contains('btn-unblock')) {
                if (confirm('Unblock ' + userName + '?')) {
                    if (statusCell) { statusCell.textContent = 'Active'; statusCell.className = 'badge active'; }
                    btn.classList.remove('btn-unblock');
                    btn.classList.add('btn-block');
                    btn.innerHTML = '<i class="fas fa-ban"></i> Block';
                    alert(userName + ' unblocked.');
                }
            }
        });
    }

});
