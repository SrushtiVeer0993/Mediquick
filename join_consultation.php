<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=join_consultation.php');
    exit();
}

// Get appointment ID from URL
$appointment_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// If no appointment ID provided, redirect to consultations page
if (!$appointment_id) {
    header('Location: my_consultations.php');
    exit();
}

try {
    $db = Database::getInstance();
    
    // Get appointment details
    $appointment = $db->fetch(
        "SELECT a.*, d.name as doctor_name, d.specialization 
         FROM appointments a 
         JOIN doctors d ON a.doctor_id = d.id 
         WHERE a.id = ? AND a.user_id = ?",
        [$appointment_id, $_SESSION['user_id']]
    );
    
    if (!$appointment) {
        $_SESSION['error'] = 'Appointment not found or you do not have permission to access it.';
        header('Location: my_consultations.php');
        exit();
    }
    
    // Check if appointment is today and within the scheduled time
    $appointment_date = $appointment['appointment_date'];
    $appointment_time = $appointment['appointment_time'];
    $appointment_datetime = strtotime("$appointment_date $appointment_time");
    $current_datetime = time();
    
    // Allow joining 5 minutes before the scheduled time
    $can_join = ($current_datetime >= ($appointment_datetime - 300)) && 
                ($current_datetime <= ($appointment_datetime + 3600)); // Allow joining up to 1 hour after scheduled time
    
    // Check if consultation type supports video
    $supports_video = in_array($appointment['consultation_type'], ['video', 'chat']);
    
    // Generate a unique meeting ID based on appointment details
    $meeting_id = 'meet_' . md5($appointment_id . $appointment['doctor_id'] . $appointment['user_id']);
    
    // Get user details
    $user = $db->fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'An error occurred while retrieving appointment details.';
    header('Location: my_consultations.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Consultation - MediQuick</title>
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
            color: #333;
        }

        .consultation-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .consultation-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .consultation-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .consultation-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .consultation-header p {
            color: var(--secondary-color);
            font-size: 1.1rem;
        }

        .consultation-header::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        .appointment-details {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .doctor-info, .appointment-info {
            flex: 1;
            min-width: 300px;
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .doctor-profile {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .doctor-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1.5rem;
            border: 3px solid white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-details h3 {
            margin-bottom: 0.5rem;
            color: var(--dark-color);
            font-size: 1.3rem;
            font-weight: 600;
        }

        .doctor-details p {
            color: var(--secondary-color);
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .info-section {
            margin-bottom: 1.5rem;
        }

        .info-section h4 {
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .info-section h4 i {
            margin-right: 0.5rem;
        }

        .info-item {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .info-label {
            font-weight: 500;
            min-width: 120px;
            color: var(--dark-color);
        }

        .info-value {
            color: var(--secondary-color);
        }

        .meeting-id-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            background: rgba(0, 123, 255, 0.1);
            transition: var(--transition);
            font-weight: 500;
        }

        .meeting-id-link:hover {
            background: rgba(0, 123, 255, 0.2);
            color: #0056b3;
            transform: translateY(-2px);
        }

        .meeting-id-link i {
            font-size: 1rem;
            opacity: 0.8;
        }

        .meeting-id-link.copied {
            background: var(--success-color);
            color: white;
        }

        .meeting-id-link.copied i {
            opacity: 1;
        }

        .join-section {
            text-align: center;
            margin-top: 2rem;
        }

        .countdown {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
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
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
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

        .btn:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 1.2rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.8rem;
            border-left: 4px solid;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .video-container {
            width: 100%;
            height: 500px;
            background: #000;
            border-radius: var(--border-radius);
            overflow: hidden;
            margin-top: 2rem;
            display: none;
        }

        .video-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            color: white;
            font-size: 1.2rem;
        }

        .control-btn:hover {
            transform: scale(1.1);
        }

        .control-btn.mute {
            background: var(--secondary-color);
        }

        .control-btn.video {
            background: var(--secondary-color);
        }

        .control-btn.end {
            background: var(--danger-color);
        }

        .chat-container {
            width: 100%;
            height: 400px;
            background: white;
            border-radius: var(--border-radius);
            border: 1px solid #dee2e6;
            margin-top: 2rem;
            display: none;
        }

        .chat-messages {
            height: 320px;
            overflow-y: auto;
            padding: 1rem;
        }

        .chat-input {
            display: flex;
            padding: 1rem;
            border-top: 1px solid #dee2e6;
        }

        .chat-input input {
            flex: 1;
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 20px;
            margin-right: 0.5rem;
        }

        .chat-input button {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        .message {
            margin-bottom: 1rem;
            max-width: 80%;
        }

        .message.sent {
            margin-left: auto;
            background: var(--primary-color);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 20px 20px 0 20px;
        }

        .message.received {
            background: var(--light-color);
            padding: 0.75rem 1rem;
            border-radius: 20px 20px 20px 0;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--secondary-color);
            margin-top: 0.25rem;
            text-align: right;
        }

        /* Navigation Styles */
        .navbar {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            
            .appointment-details {
                flex-direction: column;
            }
            
            .video-container {
                height: 300px;
            }
            
            .consultation-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <h1>MediQuick</h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="first-aid.php">First Aid</a>
                <a href="pharmacy.php">Pharmacy</a>
                <a href="emergency.php">Emergency</a>
                <a href="consultation.php">Consultation</a>
                <a href="my_consultations.php" class="active">My Consultations</a>
            </div>
            <div class="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Consultation Join Page -->
    <div class="consultation-container">
        <div class="consultation-card">
            <div class="consultation-header">
                <h1>Join Consultation</h1>
                <p>Connect with your doctor for your scheduled appointment</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
            <?php endif; ?>

            <div class="appointment-details">
                <div class="doctor-info">
                    <div class="doctor-profile">
                        <div class="doctor-image">
                            <img src="assets/images/default-doctor.jpg" alt="<?php echo htmlspecialchars($appointment['doctor_name']); ?>">
                        </div>
                        <div class="doctor-details">
                            <h3>Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?></h3>
                            <p><?php echo htmlspecialchars($appointment['specialization'] ?? 'General Physician'); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h4><i class="fas fa-calendar-alt"></i> Appointment Details</h4>
                        <div class="info-item">
                            <span class="info-label">Date:</span>
                            <span class="info-value"><?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Time:</span>
                            <span class="info-value"><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Type:</span>
                            <span class="info-value"><?php echo ucfirst($appointment['consultation_type']); ?> Consultation</span>
                        </div>
                    </div>
                </div>
                
                <div class="appointment-info">
                    <div class="info-section">
                        <h4><i class="fas fa-user"></i> Patient Information</h4>
                        <div class="info-item">
                            <span class="info-label">Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <h4><i class="fas fa-info-circle"></i> Consultation Status</h4>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value"><?php echo ucfirst($appointment['status']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Meeting ID:</span>
                            <span class="info-value">
                                <a href="#" id="meetingIdLink" class="meeting-id-link" data-meeting-id="<?php echo htmlspecialchars($meeting_id); ?>">
                                    <?php echo htmlspecialchars($meeting_id); ?>
                                    <i class="fas fa-video"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!$supports_video): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> This consultation type does not support video/chat joining. Please attend your in-person appointment at the scheduled time.
            </div>
            <?php elseif (!$can_join): ?>
            <div class="alert alert-warning">
                <i class="fas fa-clock"></i> 
                <?php if ($current_datetime < ($appointment_datetime - 300)): ?>
                    Your consultation is scheduled for <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>. You can join 5 minutes before the scheduled time.
                <?php else: ?>
                    This consultation has ended. If you need to reschedule, please contact the doctor or book a new appointment.
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="join-section">
                <?php if ($supports_video && $can_join): ?>
                    <?php if ($appointment['consultation_type'] === 'video'): ?>
                        <div class="video-container" id="videoContainer">
                            <!-- Video will be embedded here -->
                        </div>
                        
                        <div class="video-controls" id="videoControls" style="display: none;">
                            <button class="control-btn mute" id="toggleMute">
                                <i class="fas fa-microphone"></i>
                            </button>
                            <button class="control-btn video" id="toggleVideo">
                                <i class="fas fa-video"></i>
                            </button>
                            <button class="control-btn end" id="endCall">
                                <i class="fas fa-phone-slash"></i>
                            </button>
                        </div>
                    <?php elseif ($appointment['consultation_type'] === 'chat'): ?>
                        <button id="joinChatBtn" class="btn btn-primary">
                            <i class="fas fa-comments"></i> Join Chat Consultation
                        </button>
                        
                        <div class="chat-container" id="chatContainer">
                            <div class="chat-messages" id="chatMessages">
                                <!-- Chat messages will appear here -->
                            </div>
                            <div class="chat-input">
                                <input type="text" id="messageInput" placeholder="Type your message...">
                                <button id="sendMessage">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="my_consultations.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to My Consultations
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Jitsi Meet External API -->
    <script src='https://meet.jit.si/external_api.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Meeting ID functionality
            const meetingIdLink = document.getElementById('meetingIdLink');
            if (meetingIdLink) {
                meetingIdLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // If video consultation is available and can join, initiate the call
                    const videoContainer = document.getElementById('videoContainer');
                    if (videoContainer) {
                        // Scroll to the video container
                        videoContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        // If video is not available, copy the meeting ID
                        const meetingId = this.getAttribute('data-meeting-id');
                        navigator.clipboard.writeText(meetingId).then(() => {
                            // Show copied state
                            this.classList.add('copied');
                            this.querySelector('i').classList.remove('fa-video');
                            this.querySelector('i').classList.add('fa-check');
                            
                            // Reset after 2 seconds
                            setTimeout(() => {
                                this.classList.remove('copied');
                                this.querySelector('i').classList.remove('fa-check');
                                this.querySelector('i').classList.add('fa-video');
                            }, 2000);
                        });
                    }
                });
            }
            
            // Video consultation functionality
            const videoContainer = document.getElementById('videoContainer');
            const videoControls = document.getElementById('videoControls');
            
            if (videoContainer) {
                // Show video container immediately
                videoContainer.style.display = 'block';
                videoControls.style.display = 'flex';
                
                // Initialize Jitsi Meet
                const domain = 'meet.jit.si';
                const options = {
                    roomName: '<?php echo $meeting_id; ?>',
                    width: '100%',
                    height: '100%',
                    parentNode: videoContainer,
                    userInfo: {
                        displayName: '<?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?>',
                        email: '<?php echo htmlspecialchars($user['email'] ?? ''); ?>'
                    },
                    configOverwrite: {
                        prejoinPageEnabled: false,
                        startWithVideoMuted: false,
                        startWithAudioMuted: false,
                        disableDeepLinking: true,
                        enableClosePage: false,
                        enableWelcomePage: false,
                        enableNoisyMicDetection: true,
                        enableNoAudioDetection: true,
                        enableP2P: true,
                        p2p: {
                            enabled: true,
                            preferH264: true,
                            disableH264: false,
                            useStunTurn: true
                        }
                    },
                    interfaceConfigOverwrite: {
                        TOOLBAR_BUTTONS: [
                            'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                            'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                            'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                            'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                            'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone'
                        ],
                        SHOW_JITSI_WATERMARK: false,
                        SHOW_WATERMARK_FOR_GUESTS: false,
                        SHOW_POWERED_BY: false,
                        SHOW_PROMOTIONAL_CLOSE: false,
                        SHOW_BRAND_WATERMARK: false,
                        SHOW_WATERMARK: false
                    }
                };
                
                const api = new JitsiMeetExternalAPI(domain, options);
                
                // Handle events
                api.addEventListeners({
                    readyToClose: handleClose,
                    participantLeft: handleParticipantLeft,
                    participantJoined: handleParticipantJoined,
                    videoConferenceJoined: handleVideoConferenceJoined,
                    videoConferenceLeft: handleVideoConferenceLeft,
                    audioMuteStatusChanged: handleAudioMuteStatusChanged,
                    videoMuteStatusChanged: handleVideoMuteStatusChanged,
                    screenSharingStatusChanged: handleScreenSharingStatusChanged,
                    chatMessageReceived: handleChatMessageReceived,
                    recordingStatusChanged: handleRecordingStatusChanged,
                    livestreamingStatusChanged: handleLivestreamingStatusChanged
                });
                
                // Control buttons
                document.getElementById('toggleMute').addEventListener('click', function() {
                    api.executeCommand('toggleAudio');
                    this.querySelector('i').classList.toggle('fa-microphone');
                    this.querySelector('i').classList.toggle('fa-microphone-slash');
                });
                
                document.getElementById('toggleVideo').addEventListener('click', function() {
                    api.executeCommand('toggleVideo');
                    this.querySelector('i').classList.toggle('fa-video');
                    this.querySelector('i').classList.toggle('fa-video-slash');
                });
                
                document.getElementById('endCall').addEventListener('click', function() {
                    api.dispose();
                    window.location.href = 'my_consultations.php';
                });
            }
            
            // Chat consultation functionality
            const joinChatBtn = document.getElementById('joinChatBtn');
            const chatContainer = document.getElementById('chatContainer');
            
            if (joinChatBtn) {
                // Start chat immediately when page loads
                setTimeout(() => {
                    joinChatBtn.click();
                }, 1000);
                
                joinChatBtn.addEventListener('click', function() {
                    // Show chat container
                    chatContainer.style.display = 'block';
                    
                    // Add welcome message
                    const chatMessages = document.getElementById('chatMessages');
                    const welcomeMessage = document.createElement('div');
                    welcomeMessage.className = 'message received';
                    welcomeMessage.innerHTML = `
                        <div>Welcome to your chat consultation with Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?>.</div>
                        <div>Please wait for the doctor to join. You can start typing your messages below.</div>
                        <div class="message-time"><?php echo date('g:i A'); ?></div>
                    `;
                    chatMessages.appendChild(welcomeMessage);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    
                    // Send message functionality
                    const messageInput = document.getElementById('messageInput');
                    const sendMessageBtn = document.getElementById('sendMessage');
                    
                    function sendMessage() {
                        const message = messageInput.value.trim();
                        if (message) {
                            const messageElement = document.createElement('div');
                            messageElement.className = 'message sent';
                            messageElement.innerHTML = `
                                <div>${message}</div>
                                <div class="message-time">${new Date().toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'})}</div>
                            `;
                            chatMessages.appendChild(messageElement);
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                            
                            // Clear input
                            messageInput.value = '';
                            
                            // In a real implementation, this would send the message to the server
                            // and potentially to the doctor's interface
                        }
                    }
                    
                    sendMessageBtn.addEventListener('click', sendMessage);
                    messageInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            sendMessage();
                        }
                    });
                });
            }
            
            // Helper functions for Jitsi Meet
            function handleClose() {
                console.log('Meeting closed');
                window.location.href = 'my_consultations.php';
            }
            
            function handleParticipantLeft(participant) {
                console.log('Participant left:', participant);
            }
            
            function handleParticipantJoined(participant) {
                console.log('Participant joined:', participant);
            }
            
            function handleVideoConferenceJoined() {
                console.log('Joined video conference');
            }
            
            function handleVideoConferenceLeft() {
                console.log('Left video conference');
                window.location.href = 'my_consultations.php';
            }
            
            function handleAudioMuteStatusChanged(data) {
                console.log('Audio mute status changed:', data);
            }
            
            function handleVideoMuteStatusChanged(data) {
                console.log('Video mute status changed:', data);
            }
            
            function handleScreenSharingStatusChanged(data) {
                console.log('Screen sharing status changed:', data);
            }
            
            function handleChatMessageReceived(data) {
                console.log('Chat message received:', data);
            }
            
            function handleRecordingStatusChanged(data) {
                console.log('Recording status changed:', data);
            }
            
            function handleLivestreamingStatusChanged(data) {
                console.log('Livestreaming status changed:', data);
            }
        });
    </script>
</body>
</html>