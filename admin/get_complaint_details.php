<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_GET['cid'])) {
    $conn = Connect();
    $stmt = $conn->prepare("SELECT c.*, s.name as student_name FROM complaint c LEFT JOIN student s ON c.sid = s.rollno WHERE c.cid = ?");
    $stmt->bind_param("i", $_GET['cid']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($complaint = $result->fetch_assoc()) {
        echo '<div class="complaint-card">';

        // Header
        echo '<div class="complaint-header">';
        echo '<h2 class="complaint-title">Complaint #'.htmlspecialchars($complaint['cid']).'</h2>';
        echo '<span class="complaint-date">Submitted on '.date('F j, Y', strtotime($complaint['date'])).' at '.date('g:i A', strtotime($complaint['date'])).'</span>';
        echo '</div>';

        // Main content
        echo '<div class="complaint-content">';

        // Student Information
        echo '<div class="complaint-section">';
        echo '<h3>Student Information</h3>';
        echo '<p><strong>Name:</strong> '.htmlspecialchars($complaint['Cby']).'</p>';
        echo '<p><strong>Student ID:</strong> '.htmlspecialchars($complaint['sid']).'</p>';
        echo '<p><strong>Email:</strong> '.htmlspecialchars($complaint['SEmail']).'</p>';
        echo '</div>';

        // Complaint Details
        echo '<div class="complaint-section">';
        echo '<h3>Complaint Details</h3>';
        echo '<p><strong>Type:</strong> '.htmlspecialchars($complaint['type']).'</p>';
        echo '<p><strong>Status:</strong> <span class="status-badge status-'.strtolower($complaint['status']).'">'.ucfirst($complaint['status']).'</span></p>';
        echo '<p><strong>Complaint Description:</strong></p>';
        echo '<div class="complaint-description">'.nl2br(htmlspecialchars($complaint['description'])).'</div>';
        echo '</div>';

        echo '</div>'; // End of complaint-content

        // Footer with actions
        echo '<div class="complaint-footer">';
        echo '<button class="btn btn-primary reply-btn" data-email="'.htmlspecialchars($complaint['SEmail']).'" data-cid="'.htmlspecialchars($complaint['cid']).'">Reply</button>';
        echo '<button class="btn btn-danger delete-btn" data-cid="'.htmlspecialchars($complaint['cid']).'">Delete</button>';
        echo '</div>';

        echo '</div>'; // End of complaint-card

        // Reply Modal
        echo '<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replyModalLabel">Reply to Complaint</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="replyForm">
                            <input type="hidden" id="replyEmail" name="email" value="'.htmlspecialchars($complaint['SEmail']).'">
                            <input type="hidden" id="replyCid" name="cid" value="'.htmlspecialchars($complaint['cid']).'">
                            <div class="form-group">
                                <label for="replyMessage">Message:</label>
                                <textarea class="form-control" id="replyMessage" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';

    } else {
        echo '<div class="alert alert-warning">Complaint not found</div>';
    }
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="alert alert-danger">Invalid request</div>';
}
?>
