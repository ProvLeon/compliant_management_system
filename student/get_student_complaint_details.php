<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_GET['cid']) && isset($_SESSION['userMail'])) {
    $conn = Connect();
    $stmt = $conn->prepare("SELECT * FROM complaint WHERE cid = ? AND sid = ?");
    $stmt->bind_param("is", $_GET['cid'], $_SESSION['userMail']);
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

        // Complaint Details
        echo '<div class="complaint-section">';
        echo '<h3>Complaint Details</h3>';
        echo '<p><strong>Type:</strong> '.htmlspecialchars($complaint['type']).'</p>';
        echo '<p><strong>Status:</strong> <span class="status-badge status-'.strtolower($complaint['status']).'">'.ucfirst($complaint['status']).'</span></p>';
        echo '<p><strong>Complaint Description:</strong></p>';
        echo '<div class="complaint-description">'.nl2br(htmlspecialchars($complaint['description'])).'</div>';
        echo '</div>';

        echo '</div>'; // End of complaint-content

        echo '</div>'; // End of complaint-card

        // CSS for styling
        echo '<style>
            .complaint-card {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                margin: 20px auto;
                max-width: 700px;
                padding: 30px;
                font-family: Arial, sans-serif;
            }
            .complaint-header {
                border-bottom: 2px solid #f0f0f0;
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            .complaint-title {
                color: #333;
                font-size: 24px;
                margin: 0 0 10px;
            }
            .complaint-date {
                color: #777;
                font-size: 14px;
            }
            .complaint-content {
                margin-bottom: 25px;
            }
            .complaint-section {
                margin-bottom: 25px;
            }
            .complaint-section h3 {
                color: #444;
                font-size: 18px;
                margin-bottom: 15px;
                padding-bottom: 8px;
                border-bottom: 1px solid #eee;
            }
            .complaint-section p {
                margin: 10px 0;
                line-height: 1.6;
            }
            .complaint-description {
                background-color: #f9f9f9;
                border-left: 4px solid #1a73e8;
                padding: 15px;
                margin-top: 10px;
                border-radius: 4px;
                font-style: italic;
            }
            .status-badge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
                text-transform: uppercase;
            }
            .status-pending { background-color: #fef3cd; color: #856404; }
            .status-approved { background-color: #d4edda; color: #155724; }
            .status-discard { background-color: #f8d7da; color: #721c24; }
        </style>';

    } else {
        echo '<div class="alert alert-warning">Complaint not found</div>';
    }
    $stmt->close();
    $conn->close();
} else {
    echo '<div class="alert alert-danger">Invalid request</div>';
}
?>
