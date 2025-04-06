<?php
require_once 'includes/config.php';

// Get search term if exists
$search_term = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Fetch first aid guides
$query = "SELECT * FROM first_aid_guides";
if ($search_term) {
    $query .= " WHERE title LIKE :search_term1 OR symptoms LIKE :search_term2";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'search_term1' => "%$search_term%",
        'search_term2' => "%$search_term%"
    ]);
} else {
    $stmt = $pdo->query($query);
}
$guides = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Include header
include 'header.php';
?>

<!-- First Aid Content -->
<div class="hero-section">
    <div class="hero-content">
        <h1>First Aid Guides</h1>
        <p>Learn essential first aid procedures for common emergencies</p>
        <div class="search-section">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search first aid procedures...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="first-aid-container">
        <?php if (empty($guides)): ?>
            <div class="no-results">
                <h2>No results found</h2>
                <p>Try searching for something else or browse our first aid guides.</p>
            </div>
        <?php else: ?>
        <div class="guides-grid">
            <?php foreach ($guides as $guide): ?>
                <div class="guide-card">
                    <h2><?php echo htmlspecialchars($guide['title']); ?></h2>
                    <div class="description">
                        <?php echo nl2br(htmlspecialchars($guide['description'])); ?>
                    </div>
                    <div class="symptoms">
                        <strong>Symptoms:</strong>
                        <?php echo nl2br(htmlspecialchars($guide['symptoms'])); ?>
                    </div>
                    <div class="steps">
                        <strong>Steps:</strong>
                        <?php echo nl2br(htmlspecialchars($guide['steps'])); ?>
                    </div>
                    <div class="precautions">
                        <strong>Precautions:</strong>
                        <?php echo nl2br(htmlspecialchars($guide['precautions'])); ?>
                    </div>
                    <button class="voice-btn" onclick="readAloud(this)">
                        <i class="fas fa-volume-up"></i>
                        Read Aloud
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color), #4a90e2);;
        color: white;
        padding: 2.5rem 0;
        position: relative;
        margin-top: 3rem;
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

    .hero-content .search-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-content .search-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        padding: 0.5rem;
        transition: all 0.3s ease;
        height: 50px;
    }

    .hero-content .search-container:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
    }

    .hero-content .search-container input {
        flex: 1;
        border: none;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        outline: none;
        background: transparent;
        height: 100%;
    }

    .hero-content .search-container input::placeholder {
        color: #999;
    }

    .hero-content .search-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-weight: 500;
        height: 45px;
    }

    .hero-content .search-btn:hover {
        background: #4a6be9;
        transform: translateY(-2px);
    }

    .hero-content .search-btn i {
        font-size: 1.2rem;
    }

    /* Main Content */
    .first-aid-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .guides-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 3rem;
        margin-top: 2rem;
        padding: 0 1rem;
    }

    .guide-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-width: 600px;
    }

    .guide-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .guide-card h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-size: 1.6rem;
        font-weight: 600;
    }

    .guide-card .description {
        color: var(--light-text);
        margin-bottom: 2rem;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .guide-card .symptoms {
        background: #f8f9fa;
        padding: 1.2rem;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        font-size: 1rem;
        border-left: 4px solid var(--primary-color);
    }

    .guide-card .steps {
        margin-bottom: 1.2rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    .guide-card .steps ol {
        padding-left: 1.5rem;
    }

    .guide-card .precautions {
        background: #e3f2fd;
        padding: 1.2rem;
        border-radius: 10px;
        margin-top: auto;
        font-size: 1rem;
        border-left: 4px solid #2196f3;
    }

    .voice-btn {
        background: var(--primary-color);
        color: var(--white);
        border: none;
        padding: 1rem 2rem;
        border-radius: 25px;
        cursor: pointer;
        margin-top: 2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .voice-btn:hover {
        background: #4a6be9;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(74, 107, 233, 0.3);
    }

    .voice-btn i {
        font-size: 1.1rem;
    }

    @media (max-width: 1400px) {
        .guide-card {
            min-width: 500px;
        }
    }

    @media (max-width: 1200px) {
        .guide-card {
            min-width: 450px;
        }
    }

    @media (max-width: 992px) {
        .guides-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .guide-card {
            min-width: 100%;
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 3rem 0;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .first-aid-container {
            padding: 1rem;
        }

        .guide-card {
            padding: 1.5rem;
        }

        .search-container {
            max-width: 100%;
            margin: 0 1rem;
        }
    }
</style>

    <script>
        // Voice Assistant Functionality
        function readAloud(button) {
            const card = button.closest('.guide-card');
            const text = [
                card.querySelector('h2').textContent,
                card.querySelector('.description').textContent,
                'Symptoms: ' + card.querySelector('.symptoms').textContent,
                'Steps: ' + card.querySelector('.steps').textContent,
                'Precautions: ' + card.querySelector('.precautions').textContent
            ].join('. ');

            if ('speechSynthesis' in window) {
                const speech = new SpeechSynthesisUtterance();
                speech.text = text;
                speech.volume = 1;
                speech.rate = 1;
                speech.pitch = 1;
                window.speechSynthesis.speak(speech);
            } else {
                alert('Text-to-speech is not supported in your browser.');
            }
        }

        // Search Functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm) {
                window.location.href = `first-aid.php?search=${encodeURIComponent(searchTerm)}`;
            }
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = `first-aid.php?search=${encodeURIComponent(searchTerm)}`;
                }
            }
        });
    </script>

<?php
// Include footer
include 'footer.php';
?> 