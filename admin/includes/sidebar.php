<?php
// Get the current page name for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
    <div class="position-sticky">
        <div class="sidebar-header p-3 text-white">
            <h5><i class="fas fa-heartbeat me-2"></i>MediQuick Admin</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?> text-white" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?> text-white" href="users.php">
                    <i class="bi bi-people me-2"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_first_aid.php') ? 'active' : ''; ?> text-white" href="manage_first_aid.php">
                    <i class="bi bi-exclamation-triangle me-2"></i> First Aid
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_symptoms.php') ? 'active' : ''; ?> text-white" href="manage_symptoms.php">
                    <i class="bi bi-exclamation-triangle me-2"></i> Symptoms
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'doctors.php') ? 'active' : ''; ?> text-white" href="doctors.php">
                    <i class="bi bi-exclamation-triangle me-2"></i> Doctors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'appointment.php') ? 'active' : ''; ?> text-white" href="appointment.php">
                    <i class="bi bi-exclamation-triangle me-2"></i> Appointment
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a class="nav-link text-white" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav> 