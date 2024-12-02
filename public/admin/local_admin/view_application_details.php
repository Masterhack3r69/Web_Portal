<?php
$title = 'Application Details';
include '../includes/header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'view_applications.php';</script>";
    exit;
}

$submission_id = $_GET['id'];
$sql = "SELECT fs.*, p.title as program_title 
        FROM form_submissions fs 
        JOIN programs p ON fs.program_id = p.id 
        WHERE fs.id = ?";
$result = query($sql, [$submission_id]);

if ($result->num_rows === 0) {
    echo "<script>window.location.href = 'view_applications.php';</script>";
    exit;
}

$submission = $result->fetch_assoc();
$submission_data = json_decode($submission['submission_data'], true);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Application Details - <?php echo htmlspecialchars($submission['program_title']); ?></h5>
                    <a href="view_applications.php?program_id=<?php echo $submission['program_id']; ?>" class="btn btn-sm bg-gradient-primary">Back to List</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="mb-4">
                            <div class="card px-3">
                                <div class="card-body" style="min-height: 600px;">
                                    <!-- Letter Content -->
                                    <div class="mb-5">
                                        <?php if (isset($submission_data['Dear_Governor_Demerey'])): ?>
                                        <div class="mb-3">Dear Governor Demerey,</div>
                                        <div class="text-justify mb-4">
                                            <?php echo nl2br(htmlspecialchars($submission_data['Dear_Governor_Demerey'])); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4 ms-4">
                                        <div class="float-end align-items-end">
                                            <?php if (isset($submission_data['Full_Name_(First,_Middle,_Last)'])): ?>
                                            <div class="mb-1">
                                                <strong>Name:</strong>
                                                <?php echo htmlspecialchars($submission_data['Full_Name_(First,_Middle,_Last)']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if (isset($submission_data['Emailing_Address_(Purok,_Barangay,_Municipality,_Province)'])): ?>
                                            <div class="mb-1">
                                                <strong>Address:</strong>
                                                <?php echo htmlspecialchars($submission_data['Emailing_Address_(Purok,_Barangay,_Municipality,_Province)']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if (isset($submission_data['Email_Address'])): ?>
                                            <div class="mb-1">
                                                <strong>Email:</strong>
                                                <?php echo htmlspecialchars($submission_data['Email_Address']); ?>
                                            </div>
                                            <?php endif; ?>

                                            <?php if (isset($submission_data['Contact_Number'])): ?>
                                            <div class="mb-1">
                                                <strong>Contact No:</strong>
                                                <?php echo htmlspecialchars($submission_data['Contact_Number']); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
