<?php
require_once 'includes/config.php';

// Get user's emergency contacts if logged in
$user_contacts = [];
if (is_logged_in()) {
    $stmt = $pdo->prepare("SELECT * FROM emergency_contacts WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Include header
include 'header.php';
?>

<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color), #4a90e2);
        color: white;
        padding: 2.5rem 0;
        margin-top: 3rem;
        position: relative;
        overflow: hidden;
        margin-left: 200px;
        margin-right: 200px;
        border-radius: 20px;    
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('../assets/images/pattern.png') repeat;
        opacity: 0.1;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .hero-content h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }

    .hero-content p {
        font-size: 1rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
        color: white;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 0;
            margin-left: 1rem;
            margin-right: 1rem;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hero-content p {
            font-size: 1rem;
        }
    }

    /* Main Content */
    .emergency-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .contacts-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-top: 2rem;
        padding: 0 1rem;
    }

    .contact-section {
        background: var(--white);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-width: 400px;
    }

    .contact-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .contact-section h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .contact-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .contact-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .contact-item:last-child {
        border-bottom: none;
    }

    .contact-item:hover {
        background: #f8f9fa;
    }

    .contact-info {
        flex: 1;
    }

    .contact-name {
        font-weight: 600;
        color: var(--text-color);
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
    }

    .contact-number {
        color: var(--light-text);
        font-size: 1rem;
    }

    .contact-actions {
        display: flex;
        gap: 0.5rem;
    }

    .call-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.5rem;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        width: 35px;
        height: 35px;
    }

    .call-btn i {
        font-size: 1rem;
    }

    .call-btn:hover {
        background: #4a6be9;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(74, 107, 233, 0.3);
    }

    .add-contact-form {
        margin-top: 0;
        padding-top: 0;
        border-top: none;
    }

    .add-contact-form h3 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(74, 107, 233, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn:hover {
        background: #4a6be9;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(74, 107, 233, 0.3);
    }

    .alert {
        padding: 1.2rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    .alert-success {
        background: #e3f2fd;
        color: #1565c0;
        border-left: 4px solid #2196f3;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border-left: 4px solid #f44336;
    }

    .emergency-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
    }

    #floatingSosButton {
        background: #dc3545;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        transition: all 0.3s ease;
    }

    #floatingSosButton:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    #floatingSosButton i {
        font-size: 1.5rem;
    }

    @media (max-width: 1400px) {
        .contact-section {
            min-width: 350px;
        }
    }

    @media (max-width: 1200px) {
        .contact-section {
            min-width: 300px;
        }
    }

    @media (max-width: 992px) {
        .contacts-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .contact-section {
            min-width: 100%;
            padding: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 0;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .hero-content .search-container {
            max-width: 100%;
            margin: 0 1rem;
        }

        .emergency-container {
            padding: 1rem;
        }

        .contacts-grid {
            grid-template-columns: 1fr;
        }

        .contact-section {
            padding: 1.5rem;
        }

        .contact-actions {
            flex-direction: row;
            gap: 0.5rem;
        }

        .call-btn {
            width: 35px;
            height: 35px;
            padding: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
    }
</style>

<!-- Emergency Content -->
<div class="hero-section">
    <div class="hero-content">
        <h1>Emergency Contacts</h1>
        <p>Quick access to emergency services and your personal contacts</p>
    </div>
</div>

<div class="emergency-container">
    <div class="contacts-grid">
        <!-- Emergency Services -->
        <div class="contact-section">
            <h2><i class="fas fa-ambulance"></i> Emergency Services</h2>
            <ul class="contact-list">
                <?php foreach ($emergency_numbers as $service => $number): ?>
                    <li class="contact-item">
                        <div class="contact-info">
                            <div class="contact-name"><?php echo ucfirst(str_replace('_', ' ', $service)); ?></div>
                            <div class="contact-number"><?php echo $number; ?></div>
                        </div>
                        <div class="contact-actions">
                            <button class="call-btn" onclick="callEmergency('<?php echo $number; ?>')">
                                <i class="fas fa-phone"></i> 
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Personal Emergency Contacts -->
        <div class="contact-section">
            <h2><i class="fas fa-user-friends"></i> Personal Contacts</h2>
            <?php if (is_logged_in()): ?>
                <ul class="contact-list">
                    <?php foreach ($user_contacts as $contact): ?>
                        <li class="contact-item">
                            <div class="contact-info">
                                <div class="contact-name"><?php echo htmlspecialchars($contact['name']); ?></div>
                                <div class="contact-number"><?php echo htmlspecialchars($contact['phone']); ?></div>
                                <?php if ($contact['relationship']): ?>
                                    <div class="contact-relationship"><?php echo htmlspecialchars($contact['relationship']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="contact-actions">
                                <button class="call-btn" onclick="callEmergency('<?php echo htmlspecialchars($contact['phone']); ?>')" title="Call">
                                    <i class="fas fa-phone"></i>
                                </button>
                                <button class="call-btn" onclick="sendLocation('<?php echo htmlspecialchars($contact['phone']); ?>')" title="Share Location">
                                    <i class="fas fa-location-arrow"></i>
                                </button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">
                    <p>Please <a href="login.php">login</a> to manage your emergency contacts.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Add Contact Form -->
        <div class="contact-section">
            <div class="add-contact-form">
                <h3><i class="fas fa-user-plus"></i> Add Contact</h3>
                <form id="addContactForm" method="POST" action="api/add_contact.php">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="relationship">Relationship</label>
                        <input type="text" id="relationship" name="relationship">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn">Add Contact</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Button -->
<div class="emergency-button">
    <button id="floatingSosButton">
        <i class="fas fa-exclamation-triangle"></i>
        <span>EMERGENCY</span>
    </button>
</div>

<script>
    function callEmergency(number) {
        window.location.href = `tel:${number}`;
    }

    function sendLocation(phone) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const location = `${position.coords.latitude},${position.coords.longitude}`;
                    const message = `My current location: https://www.google.com/maps?q=${location}`;
                    window.location.href = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                },
                function(error) {
                    alert('Error getting location: ' + error.message);
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    // Handle emergency button click
    document.getElementById('floatingSosButton').addEventListener('click', function() {
        if (confirm('Are you sure you want to trigger an emergency alert? This will notify all your emergency contacts.')) {
            // In a real implementation, this would send alerts to all emergency contacts
            alert('Emergency alert sent to all contacts!');
        }
    });

    // Handle add contact form submission
    document.getElementById('addContactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('api/add_contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Contact added successfully!');
                location.reload();
            } else {
                alert('Error adding contact: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding contact. Please try again.');
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?> 