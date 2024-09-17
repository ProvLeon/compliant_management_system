<?php
session_start();
require_once(__DIR__ . '/../connection.php');

if(isset($_GET['cid']) && isset($_SESSION['ajay'])) {
    $conn = Connect();
    $stmt = $conn->prepare("SELECT * FROM complaint WHERE cid = ? AND type = ?");
    $stmt->bind_param("is", $_GET['cid'], $_SESSION['ajay']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($complaint = $result->fetch_assoc()) {
        // Start of HTML output
        echo '<div class="complaint-card">';

        // Header
        echo '<div class="complaint-header">';
        echo '<h2>Complaint #'.htmlspecialchars($complaint['cid']).'</h2>';
        echo '<span class="complaint-date">'.date('F j, Y, g:i a', strtotime($complaint['date'])).'</span>';
        echo '</div>';

        // Complaint body
        echo '<div class="complaint-body">';
        echo '<div class="complaint-from">';
        echo '<strong>From:</strong> '.htmlspecialchars($complaint['Cby']).' ('.htmlspecialchars($complaint['SEmail']).')';
        echo '</div>';
        echo '<div class="complaint-subject">';
        echo '<strong>Subject:</strong> '.htmlspecialchars($complaint['type']).' Complaint';
        echo '</div>';
        echo '<div class="complaint-description">';
        echo nl2br(htmlspecialchars($complaint['description']));
        echo '</div>';
        echo '</div>';

        // Complaint footer
        echo '<div class="complaint-footer">';
        echo '<div class="complaint-meta">';
        echo '<span><strong>Student ID:</strong> '.htmlspecialchars($complaint['sid']).'</span>';
        echo '</div>';

        // Status form
        echo '<form id="status-form" action="edit.php" method="POST">';
        echo '<input type="hidden" name="hidden_cid" value="'.htmlspecialchars($complaint['cid']).'">';
        echo '<select name="select_status" onchange="document.getElementById(\'status-form\').submit();">';
        $statuses = ['pending', 'approved', 'discard'];
        foreach ($statuses as $status) {
            $selected = ($complaint['status'] == $status) ? ' selected' : '';
            echo '<option value="'.$status.'"'.$selected.'>'.ucfirst($status).'</option>';
        }
        echo '</select>';
        echo '<input type="hidden" name="edit" value="1">';
        echo '</form>';

        // Action buttons
        echo '<div class="complaint-actions">';
        echo '<a href="forward_mail.php?mail='.htmlspecialchars($complaint['SEmail']).'" class="btn btn-reply"><i class="fa fa-reply"></i> Reply</a>';
        echo '<a href="index.php?deleteId='.htmlspecialchars($complaint['cid']).'" class="btn btn-delete" onclick="return confirm(\'Are you sure you want to delete this complaint?\');"><i class="fa fa-trash"></i> Delete</a>';
        echo '</div>';
        echo '</div>'; // End of complaint-footer

        echo '</div>'; // End of complaint-card

        // CSS for styling
        echo '<style>
            .complaint-card {
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
                margin: 16px auto;
                max-width: 600px;
                padding: 24px;
                font-family: Arial, sans-serif;
            }
            .complaint-header {
                border-bottom: 1px solid #f1f3f4;
                margin-bottom: 16px;
                padding-bottom: 16px;
            }
            .complaint-header h2 {
                color: #202124;
                font-size: 22px;
                margin: 0 0 8px;
            }
            .complaint-date {
                color: #5f6368;
                font-size: 13px;
            }
            .complaint-body {
                margin-bottom: 24px;
            }
            .complaint-from, .complaint-subject {
                margin-bottom: 12px;
                font-size: 14px;
                color: #202124;
            }
            .complaint-description {
                color: #202124;
                font-size: 14px;
                line-height: 1.5;
                white-space: pre-wrap;
            }
            .complaint-footer {
                border-top: 1px solid #f1f3f4;
                padding-top: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .complaint-meta {
                color: #5f6368;
                font-size: 13px;
            }
            #status-form select {
                padding: 8px;
                border-radius: 4px;
                border: 1px solid #dadce0;
                font-size: 14px;
                color: #3c4043;
            }
            .complaint-actions {
                display: flex;
                gap: 8px;
            }
            .btn {
                padding: 8px 16px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 500;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            .btn-reply {
                background-color: #1a73e8;
                color: white;
            }
            .btn-delete {
                background-color: #ea4335;
                color: white;
            }
            .btn:hover {
                opacity: 0.9;
            }
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
