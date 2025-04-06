<?php
session_start();
require_once 'includes/connection.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO symptoms (name, description, category)
                        VALUES (?, ?, ?)
                    ");
                    $stmt->execute([
                        $_POST['name'],
                        $_POST['description'],
                        $_POST['category']
                    ]);
                    $success = "Symptom added successfully!";
                } catch (PDOException $e) {
                    $error = "Error adding symptom: " . $e->getMessage();
                }
                break;

            case 'edit':
                try {
                    $stmt = $pdo->prepare("
                        UPDATE symptoms 
                        SET name = ?, description = ?, category = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([
                        $_POST['name'],
                        $_POST['description'],
                        $_POST['category'],
                        $_POST['id']
                    ]);
                    $success = "Symptom updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating symptom: " . $e->getMessage();
                }
                break;

            case 'delete':
                try {
                    $stmt = $pdo->prepare("DELETE FROM symptoms WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success = "Symptom deleted successfully!";
                } catch (PDOException $e) {
                    $error = "Error deleting symptom: " . $e->getMessage();
                }
                break;
        }
    }
}

// Get all symptoms
try {
    $stmt = $pdo->query("SELECT * FROM symptoms ORDER BY id ASC");
    $symptoms = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching symptoms: " . $e->getMessage();
    $symptoms = [];
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Manage Symptoms</h1>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addSymptomModal">
                <i class="fas fa-plus"></i> Add New Symptom
            </button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($symptoms as $symptom): ?>
                            <tr>
                                <td><?php echo $symptom['id']; ?></td>
                                <td><?php echo htmlspecialchars($symptom['name']); ?></td>
                                <td><?php echo htmlspecialchars($symptom['category']); ?></td>
                                <td><?php echo htmlspecialchars($symptom['description']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($symptom['created_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editSymptomModal<?php echo $symptom['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteSymptomModal<?php echo $symptom['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSymptomModal<?php echo $symptom['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Symptom</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="<?php echo $symptom['id']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="name" 
                                                           value="<?php echo htmlspecialchars($symptom['name']); ?>" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Category</label>
                                                    <input type="text" class="form-control" name="category" 
                                                           value="<?php echo htmlspecialchars($symptom['category']); ?>" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($symptom['description']); ?></textarea>
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
                            <div class="modal fade" id="deleteSymptomModal<?php echo $symptom['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Symptom</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this symptom?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $symptom['id']; ?>">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Symptom Modal -->
<div class="modal fade" id="addSymptomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Symptom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" class="form-control" name="category" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Symptom</button>
                </div>
            </form>
        </div>
    </div>
</div>