<?php
require_once 'includes/config.php';

// Get symptoms from database
$stmt = $pdo->query("SELECT * FROM symptoms ORDER BY name");
$symptoms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Doctor - MediQuick</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .doctor-container {
            max-width: 1400px;
            margin: 0 auto;
            min-height: 80vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .search-section {
            grid-column: 1 / -1;
            margin-bottom: 3rem;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), #4a90e2);
            padding: 2rem;
            border-radius: 15px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .search-section h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .search-section p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-container input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-container button {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
        }

        .symptoms-section {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
            width: 600px;
        }

        .symptoms-section h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
        }

        .symptoms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .symptom-card {
            background: var(--white);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .symptom-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .symptom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .symptom-card:hover::before {
            opacity: 1;
        }

        .symptom-card.selected {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }

        .symptom-card.selected::before {
            opacity: 1;
            background: var(--white);
        }

        .symptom-card h3 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .result-section {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: flex;
            top: 2rem;
            width: 600px;
        }

        .result-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
        }

        .result-section h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .doctor-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .doctor-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .doctor-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .doctor-card p {
            color: var(--light-text);
            margin-bottom: 0.8rem;
            line-height: 1.6;
        }

        .disclaimer {
            background: #fff3cd;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
            border-left: 5px solid #ffc107;
        }

        .disclaimer p {
            margin: 0;
            color: #856404;
            line-height: 1.6;
        }

        .disclaimer strong {
            color: #856404;
        }

        @media (max-width: 1024px) {
            .doctor-container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .result-section {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .doctor-container {
                padding: 1rem;
            }

            .search-section {
                padding: 1.5rem;
            }

            .search-section h1 {
                font-size: 2rem;
            }

            .symptoms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Find Doctor Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="doctor-container">
                    <div class="search-section">
                        <h1>Find the Right Doctor</h1>
                        <p>Select your symptom to find the appropriate type of doctor for your condition.</p>
                        <div class="search-container">
                            <input type="text" id="symptomSearch" placeholder="Search for symptoms...">
                            <button class="search-btn"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <div class="symptoms-section">
                        <h2><i class="fas fa-list"></i> Available Symptoms</h2>
                        <div class="symptoms-grid" id="symptomsGrid">
                            <?php foreach ($symptoms as $symptom): ?>
                                <div class="symptom-card" data-id="<?php echo $symptom['id']; ?>" data-name="<?php echo htmlspecialchars($symptom['name']); ?>">
                                    <h3><?php echo htmlspecialchars($symptom['name']); ?></h3>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="result-section" id="resultSection">
                        <h2><i class="fas fa-user-md"></i> Recommended Doctor Type</h2>
                        <div id="doctorResult">
                            <div class="doctor-card">
                                <h3><i class="fas fa-info-circle"></i> Select a Symptom</h3>
                                <p>Please select a symptom from the list to get a doctor recommendation.</p>
                            </div>
                        </div>
                        <div class="disclaimer">
                            <p><strong><i class="fas fa-exclamation-circle"></i> Disclaimer:</strong> This is a general recommendation based on your symptom. Always consult with a healthcare professional for proper diagnosis and treatment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Doctor specialties mapping
        const doctorSpecialties = {
            // General Symptoms
            'fever': 'General Physician',
            'cough': 'General Physician',
            'cold': 'General Physician',
            'flu': 'General Physician',
            'fatigue': 'General Physician',
            'weakness': 'General Physician',
            'weight loss': 'General Physician',
            'weight gain': 'General Physician',
            
            // Neurological Symptoms
            'headache': 'Neurologist',
            'migraine': 'Neurologist',
            'dizziness': 'Neurologist',
            'vertigo': 'Neurologist',
            'seizure': 'Neurologist',
            'memory loss': 'Neurologist',
            'numbness': 'Neurologist',
            'tingling': 'Neurologist',
            'tremor': 'Neurologist',
            
            // Cardiovascular Symptoms
            'chest pain': 'Cardiologist',
            'heart pain': 'Cardiologist',
            'palpitation': 'Cardiologist',
            'irregular heartbeat': 'Cardiologist',
            'high blood pressure': 'Cardiologist',
            'low blood pressure': 'Cardiologist',
            'shortness of breath': 'Cardiologist',
            
            // Gastrointestinal Symptoms
            'abdominal pain': 'Gastroenterologist',
            'stomach pain': 'Gastroenterologist',
            'indigestion': 'Gastroenterologist',
            'diarrhea': 'Gastroenterologist',
            'constipation': 'Gastroenterologist',
            'nausea': 'Gastroenterologist',
            'vomiting': 'Gastroenterologist',
            'heartburn': 'Gastroenterologist',
            
            // Musculoskeletal Symptoms
            'back pain': 'Orthopedic Surgeon',
            'joint pain': 'Rheumatologist',
            'arthritis': 'Rheumatologist',
            'muscle pain': 'Orthopedic Surgeon',
            'bone pain': 'Orthopedic Surgeon',
            'fracture': 'Orthopedic Surgeon',
            'sprain': 'Orthopedic Surgeon',
            'strain': 'Orthopedic Surgeon',
            
            // Dermatological Symptoms
            'skin rash': 'Dermatologist',
            'acne': 'Dermatologist',
            'eczema': 'Dermatologist',
            'psoriasis': 'Dermatologist',
            'hives': 'Dermatologist',
            'itching': 'Dermatologist',
            'skin infection': 'Dermatologist',
            
            // Eye Symptoms
            'eye pain': 'Ophthalmologist',
            'vision problem': 'Ophthalmologist',
            'red eye': 'Ophthalmologist',
            'eye infection': 'Ophthalmologist',
            'dry eyes': 'Ophthalmologist',
            
            // ENT Symptoms
            'ear pain': 'ENT Specialist',
            'hearing loss': 'ENT Specialist',
            'tinnitus': 'ENT Specialist',
            'sore throat': 'ENT Specialist',
            'sinus pain': 'ENT Specialist',
            'nasal congestion': 'ENT Specialist',
            
            // Mental Health Symptoms
            'anxiety': 'Psychiatrist',
            'depression': 'Psychiatrist',
            'stress': 'Psychiatrist',
            'insomnia': 'Psychiatrist',
            'mood swings': 'Psychiatrist',
            
            // Women's Health
            'menstrual pain': 'Gynecologist',
            'vaginal discharge': 'Gynecologist',
            'breast pain': 'Gynecologist',
            'pregnancy': 'Gynecologist',
            
            // Children's Health
            'child fever': 'Pediatrician',
            'child cough': 'Pediatrician',
            'child rash': 'Pediatrician',
            'child development': 'Pediatrician',
            
            // Dental Symptoms
            'tooth pain': 'Dentist',
            'gum pain': 'Dentist',
            'dental infection': 'Dentist',
            
            // Allergic Symptoms
            'allergy': 'Allergist',
            'asthma': 'Allergist',
            'hay fever': 'Allergist',
            'food allergy': 'Allergist'
        };

        // Initialize variables
        const symptomSearch = document.getElementById('symptomSearch');
        const symptomsGrid = document.getElementById('symptomsGrid');
        const resultSection = document.getElementById('resultSection');
        const doctorResult = document.getElementById('doctorResult');

        // Search functionality
        symptomSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const symptomCards = document.querySelectorAll('.symptom-card');
            
            symptomCards.forEach(card => {
                const symptomName = card.querySelector('h3').textContent.toLowerCase();
                if (symptomName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Symptom selection
        document.addEventListener('click', function(e) {
            if (e.target.closest('.symptom-card')) {
                const card = e.target.closest('.symptom-card');
                const symptomName = card.dataset.name.toLowerCase();
                
                // Remove previous selection
                document.querySelectorAll('.symptom-card').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                
                // Find matching doctor specialty
                let doctorType = 'General Physician'; // Default
                let matchedSymptom = '';
                
                // Check for exact matches first
                for (const [key, value] of Object.entries(doctorSpecialties)) {
                    if (symptomName === key) {
                        doctorType = value;
                        matchedSymptom = key;
                        break;
                    }
                }
                
                // If no exact match, check for partial matches
                if (!matchedSymptom) {
                    for (const [key, value] of Object.entries(doctorSpecialties)) {
                        if (symptomName.includes(key)) {
                            doctorType = value;
                            matchedSymptom = key;
                            break;
                        }
                    }
                }
                
                // Get doctor description
                const doctorDescriptions = {
                    'General Physician': 'A primary care doctor who can diagnose and treat a wide range of general health issues.',
                    'Neurologist': 'Specializes in disorders of the nervous system, including the brain, spinal cord, and nerves.',
                    'Cardiologist': 'Specializes in heart and blood vessel conditions.',
                    'Gastroenterologist': 'Specializes in digestive system disorders.',
                    'Orthopedic Surgeon': 'Specializes in bone, joint, and muscle conditions.',
                    'Rheumatologist': 'Specializes in arthritis and other joint or muscle diseases.',
                    'Dermatologist': 'Specializes in skin, hair, and nail conditions.',
                    'Ophthalmologist': 'Specializes in eye and vision care.',
                    'ENT Specialist': 'Specializes in ear, nose, and throat conditions.',
                    'Psychiatrist': 'Specializes in mental health and emotional disorders.',
                    'Gynecologist': 'Specializes in women\'s reproductive health.',
                    'Pediatrician': 'Specializes in children\'s health and development.',
                    'Dentist': 'Specializes in oral health and dental care.',
                    'Allergist': 'Specializes in allergies and immune system disorders.'
                };
                
                // Display result
                doctorResult.innerHTML = `
                    <div class="doctor-card">
                        <h3><i class="fas fa-stethoscope"></i> ${doctorType}</h3>
                        <p>Based on your symptom "${card.querySelector('h3').textContent}", we recommend consulting a ${doctorType}.</p>
                        <p>${doctorDescriptions[doctorType]}</p>
                    </div>
                `;
                
                resultSection.style.display = 'block';
            }
        });
    </script>
</body>
</html>