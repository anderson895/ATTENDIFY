<div class="sidebar">
            <ul class="sidebar--items">
                <li>
                    <a href="home">
                        <span class="icon icon-1"><i class="ri-home-line"></i></span>
                        <span class="sidebar--item">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="manage-course">
                        <span class="icon icon-1"><i class="ri-calendar-todo-fill"></i></span>
                        <span class="sidebar--item">Create Schedule Courses</span>
                    </a>
                </li>
                <li>
                    <a href="manage-subject">
                        <span class="icon icon-1"><i class="ri-file-edit-fill"></i></span>
                        <span class="sidebar--item">Manage Subject</span>
                    </a>
                </li>
                <li>
                    <a href="create-room">
                        <span class="icon icon-1"><i class="ri-window-line"></i></span>
                        <span class="sidebar--item" style="white-space: nowrap;">Schedule Room</span>
                    </a>
                </li>
                <li>
                    <a href="manage-depthead">
                        <span class="icon icon-1"><i class="ri-team-fill"></i></span>
                        <span class="sidebar--item">Manage Department Head</span>
                    </a>
                </li>
                <li>
    <a href="#" class="dropdown-btn">
        <span class="icon icon-1"><i class="ri-user-settings-fill"></i></span>
        <span class="sidebar--item">Manage Professor</span>
        <span class="dropdown-icon">&#9662;</span> <!-- Down arrow for dropdown -->
    </a>
    <ul class="dropdown-container">
        <li>
            <a href="view-professor">
                <span class="icon icon-1"><i class="ri-survey-fill"></i></span>
                <span class="sidebar--item">View By Course and Subject</span>
            </a>
        </li>
        <li>
            <a href="manage-professor">
                <span class="icon icon-1"><i class="ri-list-settings-fill"></i></span>
                <span class="sidebar--item">Manage Professor Settings</span>
            </a>
        </li>
    </ul>
</li>


<style>
    .dropdown-container {
        display: none;
        list-style: none;
        padding-left: 0; /* Adjust for left-aligned icons */
        color:rgb(253, 253, 253);
    }
    .dropdown-container li {
        padding-left: 1rem; /* Indentation for dropdown items */
    }
    .dropdown-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }
    .dropdown-btn:hover {
        color:rgb(255, 255, 255);
    }
    .dropdown-icon {
        font-size: 12px;
    }
    .dropdown-container a {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
    }
    .dropdown-container a:hover {
        color:rgb(252, 253, 255);
    }
    .icon.icon-1 {
        margin-right: 10px; /* Spacing between icon and text */
    }
</style>
                    <a href="download-record">
                        <span class="icon icon-1"><i class="ri-mail-download-fill"></i></span>
                        <span class="sidebar--item">Download Attendance</span>
                    </a>
                </li>
                
            </ul>
            <ul class="sidebar--bottom-items">
                <li>
                    <a href="javascript:void(0)" onclick="showLogoutModal()">
                        <span class="icon icon-1"><i class="ri-logout-box-r-line"></i></span>
                        <span class="sidebar--item">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        

        
<script>
document.addEventListener("DOMContentLoaded", function() {
    var currentUrl = window.location.href;
    var links = document.querySelectorAll('.sidebar a');
    links.forEach(function(link) {
        if (link.href === currentUrl) {
            link.id = 'active--link';
        }
    });
    document.querySelector('.dropdown-btn').addEventListener('click', function () {
        const dropdown = document.querySelector('.dropdown-container');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
});

// Add Modal Functions
function showLogoutModal() {
    document.getElementById('logoutModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function hideLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

function confirmLogout() {
    window.location.href = "logout";
}

// Close modal when clicking outside
window.onclick = function(event) {
    var modal = document.getElementById('logoutModal');
    if (event.target == modal) {
        hideLogoutModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideLogoutModal();
    }
});
</script>

<!-- Add Logout Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Logout</h2>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to logout?</p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn cancel-btn" onclick="hideLogoutModal()">Cancel</button>
            <button class="modal-btn confirm-btn" onclick="confirmLogout()">Logout</button>
        </div>
    </div>
</div>

<style>
    /* Existing dropdown styles remain unchanged */
    
    /* Add Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        background-color: #fff;
        width: 90%;
        max-width: 400px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.3s ease;
    }

    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .modal-header h2 {
        margin: 0;
        color: #8d1c1c;
        font-size: 1.5rem;
    }

    .modal-body {
        padding: 20px;
        text-align: center;
    }

    .modal-body p {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
    }

    .modal-footer {
        padding: 20px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .modal-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-btn {
        background-color: #e0e0e0;
        color: #333;
    }

    .cancel-btn:hover {
        background-color: #d0d0d0;
    }

    .confirm-btn {
        background-color: #8d1c1c;
        color: white;
    }

    .confirm-btn:hover {
        background-color: #7a1818;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            transform: translate(-50%, -60%);
            opacity: 0;
        }
        to {
            transform: translate(-50%, -50%);
            opacity: 1;
        }
    }
</style>