<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=booking_confirmation.php');
    exit();
}

// Check if booking details exist in session
if (!isset($_SESSION['booking_details'])) {
    header('Location: consultation.php');
    exit();
}

// Get booking details from session
$booking = $_SESSION['booking_details'];

// Clear booking details from session after retrieving them
// This ensures the details are only shown once
unset($_SESSION['booking_details']);

// Define variables for video consultation support
$supports_video = true; // Set to true to enable video consultation
$can_join = true; // Set to true to allow joining the consultation

// Generate a unique meeting ID for video consultations using available booking details
$meeting_id = 'meet_' . md5($booking['appointment_id'] . $booking['doctor_name'] . $booking['patient_name']);
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - MediQuick</title>
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
        }

        .confirmation-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .receipt-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .receipt-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--success-color);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px dashed #e9ecef;
        }

        .receipt-header h1 {
            color: var(--success-color);
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .receipt-icon {
            font-size: 3rem;
            color: var(--success-color);
            margin-bottom: 1rem;
        }

        .receipt-number {
            background: var(--light-color);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: inline-block;
            margin-top: 1rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .receipt-date {
            background: var(--light-color);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: inline-block;
            margin-top: 1rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .booking-details {
            background: var(--light-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .booking-details h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .detail-row {
            display: flex;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-label {
            font-weight: 600;
            width: 40%;
            color: var(--secondary-color);
        }

        .detail-value {
            width: 60%;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 200px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-success {
            background: var(--success-color);
            color: white;
            border: none;
        }

        .btn-success:hover {
            background: #218838;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px dashed #e9ecef;
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .receipt-note {
            font-size: 0.8rem;
            color: var(--secondary-color);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .navbar, .action-buttons {
                display: none;
            }
            .receipt-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }

        @media (max-width: 768px) {
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label, .detail-value {
                width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>


    <!-- Confirmation Content -->
    <div class="confirmation-container">
        <div class="receipt-card">
            <div class="receipt-header">
                <div class="receipt-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Booking Confirmed!</h1>
                <p>Your appointment has been successfully booked</p>
                <div class="receipt-number">
                    Receipt #<?php echo $booking['receipt_number']; ?>
                </div>
                <div class="receipt-date">
                    Date: <?php echo date('F j, Y', strtotime($booking['booking_date'])); ?>
                </div>
            </div>

            <div class="booking-details">
                <h2>Appointment Details</h2>
                
                <div class="detail-row">
                    <div class="detail-label">Doctor</div>
                    <div class="detail-value">Dr. <?php echo htmlspecialchars($booking['doctor_name']); ?> (<?php echo htmlspecialchars($booking['doctor_specialization']); ?>)</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Patient Name</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['patient_name']); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Contact</div>
                    <div class="detail-value">
                        Phone: <?php echo htmlspecialchars($booking['patient_phone']); ?><br>
                        Email: <?php echo htmlspecialchars($booking['patient_email']); ?>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Appointment Date & Time</div>
                    <div class="detail-value">
                        <?php 
                        $date = new DateTime($booking['appointment_date']);
                        echo $date->format('l, F j, Y'); 
                        ?> at 
                        <?php 
                        $time = new DateTime($booking['appointment_time']);
                        echo $time->format('g:i A'); 
                        ?>
                    </div>
                </div>
                
                <?php if (!empty($booking['appointment_type'])): ?>
                <div class="detail-row">
                    <div class="detail-label">Consultation Type</div>
                    <div class="detail-value">
                        <?php 
                        $type = $booking['appointment_type'];
                        if ($type === 'video') {
                            echo 'Video Consultation';
                        } elseif ($type === 'chat') {
                            echo 'Chat Consultation';
                        } elseif ($type === 'phone') {
                            echo 'Phone Consultation';
                        } else {
                            echo htmlspecialchars($type);
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($booking['symptoms'])): ?>
                <div class="detail-row">
                    <div class="detail-label">Medical Concern</div>
                    <div class="detail-value"><?php echo nl2br(htmlspecialchars($booking['symptoms'])); ?></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="action-buttons">
                <a href="my_consultations.php" class="btn btn-primary">
                    <i class="fas fa-calendar-alt"></i> View My Consultations
                </a>
                <div class="join-section">
                    <?php if ($supports_video && $can_join): ?>
                        <?php if ($booking['appointment_type'] === 'video'): ?>
                            <button id="joinVideoBtn" class="btn btn-primary">
                                <i class="fas fa-video"></i> Join Video Consultation
                            </button>
                            
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
                        <?php elseif ($booking['appointment_type'] === 'chat'): ?>
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
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>

            <div class="receipt-footer">
                <p>Thank you for choosing MediQuick!</p>
                <p>Please keep this receipt for your records.</p>
                <p class="receipt-note">This is a computer-generated receipt and does not require a signature.</p>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

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
            const joinVideoBtn = document.getElementById('joinVideoBtn');
            const videoContainer = document.getElementById('videoContainer');
            const videoControls = document.getElementById('videoControls');
            
            if (joinVideoBtn) {
                joinVideoBtn.addEventListener('click', function() {
                    // Open a new window for the video call
                    const meetingWindow = window.open('', 'Video Consultation', 'width=1200,height=800');
                    
                    // Initialize Jitsi Meet in the new window
                    const domain = 'meet.jit.si';
                    const options = {
                        roomName: '<?php echo $meeting_id; ?>',
                        width: '100%',
                        height: '100%',
                        parentNode: meetingWindow.document.body,
                        userInfo: {
                            displayName: '<?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?>',
                            email: '<?php echo htmlspecialchars($user['email'] ?? ''); ?>'
                        },
                        configOverwrite: {
                            prejoinPageEnabled: true,
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
                            SHOW_WATERMARK: false,
                            DISABLE_LOGIN_BUTTON: true
                        }
                    };
                    
                    // Create a script element to load the Jitsi Meet API in the new window
                    const script = meetingWindow.document.createElement('script');
                    script.src = 'https://meet.jit.si/external_api.js';
                    script.onload = function() {
                        // Initialize the Jitsi Meet API in the new window
                        const api = new meetingWindow.JitsiMeetExternalAPI(domain, options);
                        
                        // Handle events
                        api.addEventListeners({
                            readyToClose: function() {
                                console.log('Meeting closed');
                                meetingWindow.close();
                            },
                            participantLeft: function(participant) {
                                console.log('Participant left:', participant);
                            },
                            participantJoined: function(participant) {
                                console.log('Participant joined:', participant);
                            },
                            videoConferenceJoined: function() {
                                console.log('Joined video conference');
                            },
                            videoConferenceLeft: function() {
                                console.log('Left video conference');
                                meetingWindow.close();
                            },
                            audioMuteStatusChanged: function(data) {
                                console.log('Audio mute status changed:', data);
                            },
                            videoMuteStatusChanged: function(data) {
                                console.log('Video mute status changed:', data);
                            },
                            screenSharingStatusChanged: function(data) {
                                console.log('Screen sharing status changed:', data);
                            },
                            chatMessageReceived: function(data) {
                                console.log('Chat message received:', data);
                            },
                            recordingStatusChanged: function(data) {
                                console.log('Recording status changed:', data);
                            },
                            livestreamingStatusChanged: function(data) {
                                console.log('Livestreaming status changed:', data);
                            }
                        });
                    };
                    
                    // Add the script to the new window
                    meetingWindow.document.head.appendChild(script);
                    
                    // Add a title to the new window
                    meetingWindow.document.title = 'Video Consultation - MediQuick';
                    
                    // Add a loading message
                    meetingWindow.document.body.innerHTML = '<div style="text-align: center; margin-top: 50px;"><h2>Loading Video Consultation...</h2><p>Please wait while we connect you to the meeting.</p></div>';
                });
            }
            
            // Chat consultation functionality
            const joinChatBtn = document.getElementById('joinChatBtn');
            const chatContainer = document.getElementById('chatContainer');
            
            if (joinChatBtn) {
                joinChatBtn.addEventListener('click', function() {
                    // Show chat container
                    chatContainer.style.display = 'block';
                    
                    // Add welcome message
                    const chatMessages = document.getElementById('chatMessages');
                    const welcomeMessage = document.createElement('div');
                    welcomeMessage.className = 'message received';
                    welcomeMessage.innerHTML = `
                        <div>Welcome to your chat consultation with Dr. <?php echo htmlspecialchars($booking['doctor_name']); ?>.</div>
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
        });
    </script>
</body>
</html>

<?php
// Function to generate a JWT token for Jitsi Meet authentication
function generateJWTToken($userId, $meetingId) {
    // This is a placeholder function. In a real implementation, you would use a JWT library
    // to generate a proper JWT token with the necessary claims for Jitsi Meet.
    // For example, you might use the firebase/php-jwt library.
    
    // For now, we'll return a dummy token
    return 'dummy_jwt_token';
}
?>