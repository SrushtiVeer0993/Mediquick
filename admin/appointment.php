<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

$db = Database::getInstance();

$sql = "SELECT a.id, u.name AS user, d.name AS doctor, a.appointment_date, a.appointment_time, 
               a.consultation_type, a.status, a.created_at
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN doctors d ON a.doctor_id = d.id
        ORDER BY a.created_at DESC";

$appointments = $db->fetchAll($sql);

// Set page title for the header
$page_title = "Appointments";
?>

<div class="container-fluid px-4">
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar-check me-1"></i>
                Appointments List
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Consultation Type</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($appointments): ?>
                        <?php foreach ($appointments as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['user']) ?></td>
                                <td><?= htmlspecialchars($row['doctor']) ?></td>
                                <td><?= $row['appointment_date'] ?></td>
                                <td><?= $row['appointment_time'] ?></td>
                                <td><?= $row['consultation_type'] ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $row['status'] == 'scheduled' ? 'primary' : 
                                            ($row['status'] == 'completed' ? 'success' : 
                                            ($row['status'] == 'cancelled' ? 'danger' : 'secondary')); 
                                    ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td><?= $row['created_at'] ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit_appointment.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_appointment.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-danger btn-sm" title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this appointment?');">
                                           <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#appointmentsTable').DataTable({
            "order": [[0, "desc"]],
            "pageLength": 10,
            "language": {
                "search": "Search appointments:",
                "lengthMenu": "Show _MENU_ appointments per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ appointments"
            }
        });
    });
</script>

