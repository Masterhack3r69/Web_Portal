
    </div>
  </main>

  </div>
  <!-- Core JS Files -->


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script src="../../../assets/js/core/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="../../../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../../../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../../../assets/js/plugins/chartjs.min.js"></script>

<!-- Additional Libraries -->
<script async defer src="../../../assets/js/buttons.js"></script>
<script src="../../../assets/js/42d5adcbca.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Dashboard Scripts -->
<script src="../../../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>

<!-- Text Editor (Summernote) -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<!-- SweetAlert for Alerts -->
<script src="../../../assets/js/sweetalert.min.js"></script>

<!-- Custom JS Files -->
<script src="../../../assets/js/main.js"></script>
<script src="../../../assets/js/form_builder.js"></script>
<script src="../../../assets/js/sweetAlertcustom.js"></script>
<script src="../index.php"></script>

    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['success_message'])): ?>
            swal("Good job!", "<?php echo $_SESSION['success_message']; ?>", "success", {
                button: "Continue",
            });
            <?php unset($_SESSION['success_message']);  ?>
        <?php endif; ?>
    });
</script>


<script>
    function getRandomGradient() {
    const colors = [
        '#FF5733', '#FFBD33', '#75FF33', 
        '#33FF57', '#33FFBD', '#33A1FF', 
        '#3357FF', '#A133FF', '#FF33A1'
    ];
    const color1 = colors[Math.floor(Math.random() * colors.length)];
    const color2 = colors[Math.floor(Math.random() * colors.length)];
    return `linear-gradient(135deg, ${color1}, ${color2})`;
}

document.addEventListener('DOMContentLoaded', function() {
    const existingCards = document.querySelectorAll('.program-card');
    existingCards.forEach(card => {
        const imgElement = card.querySelector('#program-img');
        if (!imgElement.src || imgElement.src.endsWith('uploads/')) { 
            imgElement.style.display = 'none';
            card.style.background = getRandomGradient();
            card.style.color = 'white';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const existingCards = document.querySelectorAll('.card-img-container');
    existingCards.forEach(card => {
        const imgElement = card.querySelector('#program-img');
        if (!imgElement.src || imgElement.src.endsWith('uploads/')) { 
            imgElement.style.display = 'none';
            card.style.background = getRandomGradient();
            card.style.color = 'white';
        }
    });
});

let logoutTimer;

function resetLogoutTimer() {
    clearTimeout(logoutTimer);
    logoutTimer = setTimeout(() => {
        fetch('../../logout.php', { method: 'GET' }) 
            .then(() => {
                window.location.href = '../index.php'; 
            })
            .catch(error => console.error('Logout error:', error));
    }, 30 * 60 * 1000); 
}

window.onload = resetLogoutTimer;
document.onmousemove = resetLogoutTimer;
document.onkeydown = resetLogoutTimer;
document.onclick = resetLogoutTimer;
document.onscroll = resetLogoutTimer;

</script>


</body>
</html>