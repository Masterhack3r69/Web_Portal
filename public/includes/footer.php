<footer class="py-5 " style="background-color: #21BF73;">
  <nav aria-label="Footer Navigation">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="index.php#" class="nav-link px-2 text-light">Home</a></li>
      <li class="nav-item"><a href="program.php" class="nav-link px-2 text-light">Programs</a></li>
      <li class="nav-item"><a href="depsrtment.php" class="nav-link px-2 text-light">Departments</a></li>
      <li class="nav-item"><a href="news_update.php" class="nav-link px-2 text-light">News & Updates</a></li>
      <li class="nav-item"><a href="index.php#about" class="nav-link px-2 text-light">About Us</a></li>
    </ul>
  </nav>
  <p class="text-center text-light">© 2024 Company, Inc</p>
</footer>

    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
      const spinnerLinks = document.querySelectorAll('.sign-out, .profile-dropdown, .dropdown-item[href="./users/notifications.php"], a:not([href^="index.php#"])');

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