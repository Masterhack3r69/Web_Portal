<?php
$current_page = basename($_SERVER['SCRIPT_NAME'], ".php");

switch ($current_page) {
    case 'index':
        $breadcrumb = '<li class="breadcrumb-item active mb-0" aria-current="page">Home</li>';
        break;
    case 'program':
        $breadcrumb = '<li class="breadcrumb-item mb-0"><a href="index.php">Home</a></li>';
        $breadcrumb .= '<li class="breadcrumb-item active mb-0" aria-current="page">Program</li>';
        break;
    case 'department':
        $breadcrumb = '<li class="breadcrumb-item mb-0"><a href="index.php">Home</a></li>';
        $breadcrumb .= '<li class="breadcrumb-item active mb-0" aria-current="page">Department</li>';
        break;
    default:
        $breadcrumb = '<li class="breadcrumb-item mb-0"><a href="index.php">Home</a></li>';
        $breadcrumb .= '<li class="breadcrumb-item active mb-0 " aria-current="page">' . ucfirst($current_page) . '</li>';
        break;
}
?>

<div class="breadcrumb-container border bg-white mx-2 my-2">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb m-1 bg-white d-flex align-items-center">
            <span class="me-2 text-sm text-muted ">You are here:</span>
            <?php echo $breadcrumb; ?>
        </ol>
    </nav>
</div>

