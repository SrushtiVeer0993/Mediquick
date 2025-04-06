<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=book_appointment.php');
    exit();
}

// Get doctor ID from URL
$doctor_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// If no doctor ID provided, redirect to consultation page
if (!$doctor_id) {
    header('Location: consultation.php');
    exit();
}

// Get doctor information
try {
    $db = Database::getInstance();
    $doctor = $db->fetch("SELECT * FROM doctors WHERE id = ?", [$doctor_id]);
    
    if (!$doctor) {
        header('Location: consultation.php');
        exit();
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header('Location: consultation.php');
    exit();
}
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - MediQuick</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f4f6f9;
            padding-top: 80px;
            color: #333;
        }

        .booking-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .booking-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .booking-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }

        .booking-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .booking-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .booking-header p {
            color: var(--secondary-color);
            font-size: 1.1rem;
        }

        .booking-header::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        .doctor-info {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: var(--transition);
        }

        .doctor-info:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }


        .doctor-details h3 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .doctor-details p {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .doctor-details p:last-child {
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin: 2rem auto;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 2rem;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--light-text);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(74, 107, 255, 0.1);
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #4a6be9;
            transform: translateY(-2px);
        }

        .form-text {
            font-size: 0.85rem;
            color: var(--secondary-color);
            margin-top: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.85rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            background: transparent;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
        }

        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .alert {
            padding: 1.2rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.8rem;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        /* Navigation Styles */
        .navbar {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a.active {
            color: var(--primary-color);
        }

        .nav-links a.active::after {
            width: 100%;
        }

        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: block;
            }
            
            .doctor-info {
                flex-direction: column;
                text-align: center;
            }
            
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .booking-card {
                padding: 1.5rem;
            }

            .form-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .form-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Booking Form -->
    <div class="booking-container">
        <div class="booking-card">
            <div class="booking-header">
                <h1>Book an Appointment</h1>
                <p>Fill in the details below to schedule your consultation</p>
            </div>

            <div class="doctor-info">
                <div class="doctor-details">
                    <h3>Dr. <?php echo htmlspecialchars($doctor['name']); ?></h3>
                    <p><?php echo htmlspecialchars($doctor['specialization'] ?? 'General Physician'); ?></p>
                    <p><?php echo htmlspecialchars($doctor['qualification'] ?? ''); ?></p>
                </div>
            </div>

            <?php if (isset($_SESSION['booking_error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php 
                echo $_SESSION['booking_error']; 
                unset($_SESSION['booking_error']);
                ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['booking_success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php 
                echo $_SESSION['booking_success']; 
                unset($_SESSION['booking_success']);
                ?>
            </div>
            <?php endif; ?>

            <form action="booking_process.php" method="POST" class="booking-form" id="bookingForm">
                <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
                
                <div class="form-container">
                    <div class="form-header">
                        <h2 class="form-title">Book Your Appointment</h2>
                        <p class="form-subtitle">Fill in the details below to schedule your consultation</p>
                    </div>

                    <div class="form-group">
                        <label for="patient_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="patient_name" name="patient_name" required >
                    </div>
                    
                    <div class="form-group">
                        <label for="patient_phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="patient_phone" name="patient_phone" required 
                               pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
                    </div>
                    
                    <div class="form-group">
                        <label for="patient_email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="patient_email" name="patient_email" required 
                               value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_date" class="form-label">Preferred Date *</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required 
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_time" class="form-label">Preferred Time *</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_type" class="form-label">Consultation Type</label>
                        <select class="form-control" id="appointment_type" name="appointment_type">
                            <option value="in-person">In-Person Consultation</option>
                            <option value="video">Video Consultation</option>
                            <option value="chat">Chat Consultation</option>
                            <option value="phone">Phone Consultation</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="symptoms" class="form-label">Describe your symptoms</label>
                        <textarea class="form-control" id="symptoms" name="symptoms" rows="4" 
                                  placeholder="Please describe your symptoms or reason for consultation"></textarea>
                        <small class="form-text text-muted">This is for your reference only and will not be stored in the system.</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Confirm Booking
                        </button>
                        <a href="consultation.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic form validation
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Validate phone number
                const phoneInput = document.getElementById('patient_phone');
                const phonePattern = /^[0-9]{10}$/;
                if (!phonePattern.test(phoneInput.value)) {
                    isValid = false;
                    phoneInput.classList.add('is-invalid');
                }
                
                // Validate email
                const emailInput = document.getElementById('patient_email');
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailInput.value)) {
                    isValid = false;
                    emailInput.classList.add('is-invalid');
                }
                
                // Validate date is not in the past
                const dateInput = document.getElementById('appointment_date');
                const selectedDate = new Date(dateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    isValid = false;
                    dateInput.classList.add('is-invalid');
                }
                
                if (isValid) {
                    // If all validations pass, submit the form
                    form.submit();
                } else {
                    // Show error message
                    alert('Please fill in all required fields correctly.');
                }
            });
        });
    </script>
</body>
</html>