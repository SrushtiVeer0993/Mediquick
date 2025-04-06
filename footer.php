    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>MediQuick</h3>
                    <p>Your trusted emergency health companion, providing quick access to medical information and services.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="first-aid.php">First Aid Guide</a></li>
                        <li><a href="pharmacy.php">Pharmacy Locator</a></li>
                        <li><a href="emergency.php">Emergency Contacts</a></li>
                        <li><a href="find-doctor.php">Find Doctor</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: support@mediquick.com</p>
                    <p>Phone: +1 (555) 123-4567</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 MediQuick. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #6a8aff 0%, #3a5bd9 100%);
            color: white;
            padding: 1.5rem 0 0.8rem;
            margin-top: 2rem;
            position: relative;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .footer-section h3 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
            margin-bottom: 0.6rem;
            font-size: 0.85rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.4rem;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 0.85rem;
        }

        .footer-section ul li a:hover {
            color: white;
            transform: translateX(3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            footer {
                padding: 1.2rem 0 0.6rem;
                margin-top: 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 0.8rem;
            }

            .footer-section ul li a:hover {
                transform: none;
            }
        }
    </style>
</body>
</html> 