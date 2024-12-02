<footer class="footer py-5" style="background-color: #21BF73;">
    <div class="container">
        <div class="row">
            <!-- Main Navigation -->
            <div class="col-md-4 mb-4">
                <h5 class="text-light mb-3">Quick Links</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="index.php#" class="nav-link px-0 text-light">Home</a></li>
                    <li class="nav-item"><a href="program.php" class="nav-link px-0 text-light">Programs</a></li>
                    <li class="nav-item"><a href="department.php" class="nav-link px-0 text-light">Departments</a></li>
                    <li class="nav-item"><a href="news_update.php" class="nav-link px-0 text-light">News & Updates</a></li>
                    <li class="nav-item"><a href="index.php#about" class="nav-link px-0 text-light">About Us</a></li>
                </ul>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-5 mb-4">
                <h5 class="text-light mb-3">Contact Us</h5>
                <ul class="nav flex-column">
                    <li class="nav-item text-light mb-2"><i class="fas fa-map-marker-alt me-2"></i>Cuarenta, San Jose, Province of Dinagat Islands </li>
                    <li class="nav-item text-light mb-2"><i class="fas fa-link me-2"></i>dinagatislands.com.ph</li>
                    <li class="nav-item text-light mb-2"><i class="fas fa-envelope me-2"></i>info@dinagatislands.gov.ph</li>
                </ul>
            </div>

            <!-- Social Media Links -->
            <div class="col-md-3 mb-4">
                <h5 class="text-light mb-3">Connect With Us</h5>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a>
                </div>
            </div>
        </div>

        <hr class="border-light mt-4 mb-4">
        
        <!-- Copyright -->
        <div class="row">
            <div class="col-12">
                <p class="text-center text-light mb-0">
                    2024 Provincial Government of Dinagat Islands: Programs and Services
                </p>
                <p class="text-center small mb-0">
                    <i class="fas fa-info-circle me-1" style="color: #F1464B;"></i>
                    This website is created for educational purposes only and is not affiliated with or endorsed by the official Provincial Government of Dinagat Islands.
                    All data and resources used in this website are permitted for educational use.
                </p>
            </div>
        </div>
    </div>
</footer>

    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/notifications.js"></script>  
    <script>
      const spinnerLinks = document.querySelectorAll('.sign-out, .profile-dropdown, a:not([href^="index.php#"]):not([data-bs-toggle="modal"]):not([data-bs-toggle="dropdown"]):not(#notificationButton):not(#userMenuButton)');

      spinnerLinks.forEach(link => {
          link.addEventListener('click', function(event) {
              const isSamePageLink = link.getAttribute('href').startsWith('index.php#');

              if (!isSamePageLink) {
                  event.preventDefault();
                  document.body.classList.add('show-spinner');

                  setTimeout(() => {
                      window.location.href = link.href;
                  }, 300);
              }
          });
      });

      window.addEventListener('load', () => {
          setTimeout(() => {
              document.body.classList.remove('show-spinner');
          }, 300);
      });
    </script>
  </body>
</html>