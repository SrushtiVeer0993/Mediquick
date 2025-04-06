<?php
require_once 'includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's emergency contacts
$stmt = $pdo->prepare("SELECT * FROM emergency_contacts WHERE user_id = ?");
$stmt->execute([$user_id]);
$emergency_contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's appointments
$stmt = $pdo->prepare("
    SELECT a.*, d.name as doctor_name, d.specialization 
    FROM appointments a 
    LEFT JOIN doctors d ON a.doctor_id = d.id 
    WHERE a.user_id = ? 
    ORDER BY a.appointment_date DESC
");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include 'header.php';
?>

    <style>
    .profile-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .profile-sidebar {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        height: fit-content;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
        background: var(--primary-color);
        color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        font-size: 3rem;
            margin: 0 auto 1rem;
        }
        
        .profile-name {
        font-size: 1.5rem;
            font-weight: 600;
        color: var(--text-color);
            margin-bottom: 0.5rem;
        text-align: center;
        }
        
        .profile-email {
        color: var(--light-text);
            font-size: 1rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    .nav-link {
        color: var(--text-color);
        padding: 0.8rem 1rem;
        border-radius: 10px;
        transition: all 0.3s ease;
            display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .nav-link:hover,
    .nav-link.active {
        background: var(--primary-color);
        color: white;
    }

    .nav-link i {
        font-size: 1.2rem;
        }
        
        .profile-section {
        background: white;
            border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

    .profile-section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
        font-size: 1.4rem;
            font-weight: 600;
        color: var(--text-color);
            display: flex;
            align-items: center;
        gap: 0.8rem;
        margin: 0;
        }
        
        .section-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
        }
        
        .info-item {
        background: rgba(74, 107, 255, 0.05);
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(74, 107, 255, 0.1);
        transform: translateY(-2px);
        }
        
        .info-label {
        color: var(--light-text);
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
        }
        
        .info-value {
        color: var(--text-color);
        font-size: 1.1rem;
        font-weight: 500;
        }
        
    .contact-item, .appointment-item {
            padding: 1.2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .contact-item:hover, .appointment-item:hover {
        background: rgba(74, 107, 255, 0.05);
    }

    .contact-item:last-child, .appointment-item:last-child {
        border-bottom: none;
    }

    .contact-name, .appointment-title {
            font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.3rem;
    }

    .contact-number, .appointment-specialization {
        color: var(--light-text);
        font-size: 0.9rem;
    }

    .appointment-details {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        color: var(--light-text);
            font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .appointment-date, .appointment-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        }
        
        .appointment-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
    .appointment-status.upcoming {
        background: #e3f2fd;
            color: #1976d2;
        }
        
    .appointment-status.completed {
        background: #e8f5e9;
            color: #388e3c;
        }
        
    .appointment-status.cancelled {
        background: #ffebee;
            color: #d32f2f;
        }
        
    .action-btn {
        width: 35px;
        height: 35px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
            transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 0;
        }

        .profile-section {
            padding: 1.5rem;
        }

        .appointment-details {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .appointment-title {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }
        }
    </style>

<div class="container profile-container">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-3">
            <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                </div>
                <div class="profile-name"><?php echo htmlspecialchars($user['name']); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#personal">
                        <i class="fas fa-user-circle"></i>
                        Personal Info
                    </a>
                    <a class="nav-link" href="#contacts">
                        <i class="fas fa-address-book"></i>
                        Emergency Contacts
                    </a>
                    <a class="nav-link" href="#appointments">
                        <i class="fas fa-calendar-check"></i>
                        Appointments
                    </a>
                    <a class="nav-link text-danger" href="javascript:void(0)" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </nav>
            </div>
        </div>

    <!-- Profile Content -->
        <div class="col-lg-9">
            <!-- Personal Information -->
            <div class="profile-section" id="personal">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle"></i>
                        Personal Information
                    </h2>
                    <button class="btn btn-primary" onclick="openEditProfileModal()">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['name']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Email</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></div>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="info-item">
                            <div class="info-label">Password</div>
                            <div class="info-value">••••••••</div>
                    </div>
                    </div>
                </div>
            </div>
            
            <!-- Emergency Contacts and Appointments Row -->
            <div class="row g-4">
                <!-- Emergency Contacts -->
                <div class="col-md-6">
                    <div class="profile-section" id="contacts">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-address-book"></i>
                                Emergency Contacts
                            </h2>
                            <button class="btn btn-primary" onclick="openAddContactModal()">
                                <i class="fas fa-plus"></i>
                                Add Contact
                            </button>
                        </div>
                        <div class="list-group">
                            <?php foreach ($emergency_contacts as $contact): ?>
                                <div class="list-group-item contact-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="contact-name"><?php echo htmlspecialchars($contact['name']); ?></div>
                                            <div class="contact-number"><?php echo htmlspecialchars($contact['phone']); ?></div>
                                            <?php if ($contact['relationship']): ?>
                                                <div class="contact-relationship"><?php echo htmlspecialchars($contact['relationship']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-primary action-btn" title="Edit" onclick="openEditContactModal(<?php echo $contact['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger action-btn" title="Delete" onclick="deleteContact(<?php echo $contact['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                    </div>
                    </div>
                    </div>
                            <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Appointments -->
                <div class="col-md-6">
                    <div class="profile-section" id="appointments">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-calendar-check"></i>
                                Appointments
                            </h2>
                        </div>
                        <div class="list-group">
                            <?php foreach ($appointments as $appointment): ?>
                                <div class="list-group-item appointment-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="appointment-title">
                                                <?php echo htmlspecialchars($appointment['doctor_name']); ?>
                                                <span class="appointment-specialization">
                                                    <?php echo htmlspecialchars($appointment['specialization']); ?>
                                                </span>
                    </div>
                                            <div class="appointment-details">
                                                <span class="appointment-date">
                                                    <i class="fas fa-calendar"></i>
                                                    <?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?>
                                                </span>
                                                <span class="appointment-time">
                                                    <i class="fas fa-clock"></i>
                                                    <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                                </span>
                                                <span class="appointment-status <?php echo strtolower($appointment['status']); ?>">
                                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                                </span>
                    </div>
                    </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-primary action-btn" title="View Details" onclick="viewAppointmentDetails(<?php echo $appointment['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($appointment['status'] === 'Upcoming'): ?>
                                                <button class="btn btn-danger action-btn" title="Cancel" onclick="cancelAppointment(<?php echo $appointment['id']; ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                    </div>
                </div>
            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                            </div>
                        </div>
                    </div>
                            </div>

<!-- Modals -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
            <div class="modal-body">
                <div class="alert" id="profileAlert" style="display: none;"></div>
                <form id="editProfileForm" onsubmit="updateProfile(event)">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addContactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Emergency Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert" id="contactAlert" style="display: none;"></div>
                <form id="addContactForm" onsubmit="addContact(event)">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Relationship</label>
                        <input type="text" class="form-control" name="relationship">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Contact</button>
                </form>
            </div>
        </div>
    </div>
                </div>

<div class="modal fade" id="viewAppointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert" id="appointmentAlert" style="display: none;"></div>
                <div id="appointmentDetails"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update modal functions to use Bootstrap modals
    function openEditProfileModal() {
        new bootstrap.Modal(document.getElementById('editProfileModal')).show();
    }

    function openAddContactModal() {
        new bootstrap.Modal(document.getElementById('addContactModal')).show();
    }

    function closeModal(modalId) {
        bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
    }

    // Update menu item clicks
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').substring(1);
            
            // Only handle profile section navigation
            if (targetId !== 'logout') {
                e.preventDefault();
                
                // Update active state
                document.querySelectorAll('.nav-link').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
                
                // Scroll to section
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Profile update
    async function updateProfile(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch('handlers/update_profile.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            const alert = document.getElementById('profileAlert');
            
            if (data.success) {
                alert.className = 'alert alert-success';
                alert.textContent = 'Profile updated successfully!';
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert.className = 'alert alert-error';
                alert.textContent = data.message || 'Failed to update profile';
            }
            
            alert.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
            const alert = document.getElementById('profileAlert');
            alert.className = 'alert alert-error';
            alert.textContent = 'An error occurred. Please try again.';
            alert.style.display = 'block';
        }
    }

    // Contact management
    async function addContact(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        try {
            const response = await fetch('handlers/add_contact.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            const alert = document.getElementById('contactAlert');
            
            if (data.success) {
                alert.className = 'alert alert-success';
                alert.textContent = 'Contact added successfully!';
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert.className = 'alert alert-error';
                alert.textContent = data.message || 'Failed to add contact';
            }
            
            alert.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
            const alert = document.getElementById('contactAlert');
            alert.className = 'alert alert-error';
            alert.textContent = 'An error occurred. Please try again.';
            alert.style.display = 'block';
        }
    }

    async function deleteContact(contactId) {
        if (!confirm('Are you sure you want to delete this contact?')) return;
        
        try {
            const response = await fetch('handlers/delete_contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ contact_id: contactId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete contact');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    // Appointment management
    async function cancelAppointment(appointmentId) {
        if (!confirm('Are you sure you want to cancel this appointment?')) return;
        
        try {
            const response = await fetch('handlers/cancel_appointment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ appointment_id: appointmentId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to cancel appointment');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    async function viewAppointmentDetails(appointmentId) {
        try {
            const response = await fetch('handlers/get_appointment_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ appointment_id: appointmentId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                const appointment = data.appointment;
                const detailsHtml = `
                    <div class="appointment-details-view">
                        <div class="detail-group">
                            <h4>Doctor Information</h4>
                            <p><strong>Name:</strong> ${appointment.doctor_name}</p>
                            <p><strong>Specialization:</strong> ${appointment.specialization}</p>
                            <p><strong>Contact:</strong> ${appointment.doctor_email}</p>
                        </div>
                        <div class="detail-group">
                            <h4>Appointment Information</h4>
                            <p><strong>Date:</strong> ${new Date(appointment.appointment_date).toLocaleDateString()}</p>
                            <p><strong>Time:</strong> ${new Date('1970-01-01T' + appointment.appointment_time).toLocaleTimeString()}</p>
                            <p><strong>Status:</strong> <span class="appointment-status ${appointment.status.toLowerCase()}">${appointment.status}</span></p>
                        </div>
                        ${appointment.notes ? `
                            <div class="detail-group">
                                <h4>Notes</h4>
                                <p>${appointment.notes}</p>
                            </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('appointmentDetails').innerHTML = detailsHtml;
                document.getElementById('viewAppointmentModal').style.display = 'flex';
            } else {
                alert(data.message || 'Failed to load appointment details');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    }

    // Add styles for appointment details view
    const style = document.createElement('style');
    style.textContent = `
        .appointment-details-view {
            padding: 1rem;
        }
        
        .detail-group {
            margin-bottom: 1.5rem;
        }
        
        .detail-group h4 {
            color: var(--text-color);
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
        }
        
        .detail-group p {
            margin-bottom: 0.5rem;
            color: var(--light-text);
        }
        
        .detail-group strong {
            color: var(--text-color);
            margin-right: 0.5rem;
        }
    `;
    document.head.appendChild(style);

    // Add logout function
    function logout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = 'logout.php';
        }
    }
</script>

<?php
// Include footer
include 'footer.php';
?> 