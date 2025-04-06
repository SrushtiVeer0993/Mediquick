<?php
require_once '../includes/config.php';
require_once 'includes/connection.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';


// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO first_aid_guides (title, description, steps, symptoms, precautions)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $_POST['title'],
                        $_POST['description'],
                        $_POST['steps'],
                        $_POST['symptoms'],
                        $_POST['precautions']
                    ]);
                    $success = "First aid entry added successfully!";
                } catch (PDOException $e) {
                    $error = "Error adding first aid entry: " . $e->getMessage();
                }
                break;

            case 'edit':
                try {
                    $stmt = $pdo->prepare("
                        UPDATE first_aid_guides 
                        SET title = ?, description = ?, steps = ?, symptoms = ?, precautions = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([
                        $_POST['title'],
                        $_POST['description'],
                        $_POST['steps'],
                        $_POST['symptoms'],
                        $_POST['precautions'],
                        $_POST['id']
                    ]);
                    $success = "First aid entry updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating first aid entry: " . $e->getMessage();
                }
                break;

            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM first_aid_guides WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success = "First aid entry deleted successfully!";
                } catch (PDOException $e) {
                    $error = "Error deleting first aid entry: " . $e->getMessage();
                }
                break;
        }
    }
}

// Get all first aid entries
try {
    $stmt = $pdo->query("SELECT * FROM first_aid_guides ORDER BY created_at DESC");
    $firstAidEntries = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching first aid entries: " . $e->getMessage();
    $firstAidEntries = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage First Aid - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .first-aid-card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .first-aid-card:hover {
            transform: translateY(-5px);
        }
        .steps-list, .symptoms-list, .precautions-list {
            white-space: pre-line;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage First Aid Entries</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFirstAidModal">
                <i class="fas fa-plus"></i> Add New Entry
            </button>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($firstAidEntries as $entry): ?>
                <div class="col-md-6 mb-4">
                    <div class="first-aid-card p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h4><?php echo htmlspecialchars($entry['title']); ?></h4>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editFirstAidModal<?php echo $entry['id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteFirstAidModal<?php echo $entry['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-muted"><?php echo htmlspecialchars($entry['description']); ?></p>
                        
                        <div class="mb-3">
                            <h6 class="text-primary">Symptoms:</h6>
                            <div class="symptoms-list"><?php echo htmlspecialchars($entry['symptoms']); ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary">Steps:</h6>
                            <div class="steps-list"><?php echo htmlspecialchars($entry['steps']); ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary">Precautions:</h6>
                            <div class="precautions-list"><?php echo htmlspecialchars($entry['precautions']); ?></div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Last updated: <?php echo date('M d, Y', strtotime($entry['updated_at'])); ?></small>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editFirstAidModal<?php echo $entry['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit First Aid Entry</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="id" value="<?php echo $entry['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title" 
                                               value="<?php echo htmlspecialchars($entry['title']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($entry['description']); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Symptoms (one per line)</label>
                                        <textarea class="form-control" name="symptoms" rows="4" required><?php echo htmlspecialchars($entry['symptoms']); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Steps (one per line)</label>
                                        <textarea class="form-control" name="steps" rows="6" required><?php echo htmlspecialchars($entry['steps']); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Precautions (one per line)</label>
                                        <textarea class="form-control" name="precautions" rows="4" required><?php echo htmlspecialchars($entry['precautions']); ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteFirstAidModal<?php echo $entry['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete First Aid Entry</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this first aid entry?</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $entry['id']; ?>">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add First Aid Modal -->
    <div class="modal fade" id="addFirstAidModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New First Aid Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Symptoms (one per line)</label>
                            <textarea class="form-control" name="symptoms" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Steps (one per line)</label>
                            <textarea class="form-control" name="steps" rows="6" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Precautions (one per line)</label>
                            <textarea class="form-control" name="precautions" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
