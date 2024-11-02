<?php
include './includes/header.php';

$latestNews = query("SELECT 
    news.title AS news_title,
    news.id,
    news.content,
    news.small_description,
    news.image_url,
    news.created_at,
    programs.title AS program_title,
    departments.department_name,
    departments.id AS department_id
FROM 
    news
LEFT JOIN programs ON news.program_id = programs.id
LEFT JOIN departments ON news.department_id = departments.id
WHERE 
    news.status = 'approved'
ORDER BY 
    news.created_at DESC 
LIMIT 4")->fetch_all(MYSQLI_ASSOC);

?>

<div class="breadcrumb-container mt-3 pt-5 ">
    <?php include 'breadcrumb.php'; ?>
</div>

<div class="container-fluid mb-5 " style="min-height: 50vh;">
<h4 class="text-center m-2 py-3">News and Updates</h4>
  <div class="row">
    <div class="col-md-12">
          <div class="row">
              <?php if (!empty($latestNews)): ?>
                  <?php foreach ($latestNews as $news): ?>
                      <div class="col-md-6">
                          <div class="card shadow-sm border mb-3 rounded-2">
                              <div class="card-body p-2 pb-1">
                                  <div class="card-title fw-bolder text-center fs-5"> 
                                      <p class="fw-bolder py-1"><?php echo htmlspecialchars($news['news_title']); ?></p>
                                  </div>
                                  <hr class="horizontal dark m-0">
                                  <img src="<?php echo htmlspecialchars('../assets/img/uploads/' . $news['image_url']); ?>" class="img-fluid mb-2 rounded-2" style="height: 250px; width: 100%; object-fit: cover;" alt="News Image">
                                  <p class="small text-muted ps-2">Program: <?php echo htmlspecialchars($news['program_title']); ?></p>
                                  <div class="ps-2"><?php echo $news['content']; ?></div>
                                  
                              </div>
                              <div class="d-flex justify-content-between text-sm px-3 pb-2">
                                <a href="view_department.php?id=<?php echo htmlspecialchars($news['department_id']); ?>#news-container" class="text-danger border border-danger px-2">View</a>
                                <span><?php echo htmlspecialchars($news['department_name']); ?></span>
                                <span class="fw-light text-muted"><?php echo date('m/d/Y', strtotime($news['created_at'])); ?></span> 
                              </div>  
                          </div>
                      </div>
                  <?php endforeach; ?>
              <?php else: ?>
                  <div class="col-12">
                      <p class="text-center text-muted">No news updates available.</p>
                  </div>
              <?php endif; ?>
          </div>
    </div>
</div>

</div>

<?php include './includes/footer.php'; ?>

