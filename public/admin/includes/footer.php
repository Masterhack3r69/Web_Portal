
</div>
  </main>

  </div>
  <!-- Core JS Files -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Plugins -->
<script src="../../../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../../../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../../../assets/js/plugins/chartjs.min.js"></script>

<!-- Additional Libraries -->
<script async defer src="../../../assets/js/buttons.js"></script>
<script src="../../../assets/js/42d5adcbca.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Dashboard Scripts -->
<script src="../../../assets/js/main_bootrap.min.js"></script>

<!-- Text Editor (quill) -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="../../../assets/js/quillCustom.js"></script>

<!-- SweetAlert for Alerts -->
<script src="../../../assets/js/sweetalert.min.js"></script>

<!-- Custom JS Files -->
<script src="../../../assets/js/dashboard_main.js"></script>
<script src="../../../assets/js/form_builder.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>

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

<script>
function searchBar() {
  var input, filter, table, tr, td, i, txtValue, matchFound;
  input = document.getElementById("input");
  filter = input.value.toUpperCase();
  table = document.getElementById("tableSearch");
  tr = table.getElementsByTagName("tr");
  matchFound = false; 

  for (i = 1; i < tr.length; i++) { 
    tr[i].style.display = "";
    var cells = tr[i].getElementsByTagName("td");
    for (var j = 0; j < cells.length - 1; j++) { 
      if (cells[j]) {
        txtValue = cells[j].textContent || cells[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          matchFound = true;
          break;
        }
      }
    }
    if (!matchFound) {
      tr[i].style.display = "none"; 
    }
    matchFound = false; 
  }

  var noMatchMessage = document.getElementById("noMatchMessage");
  noMatchMessage.style.display = (filter && !Array.from(tr).some(row => row.style.display !== "none")) ? "block" : "none";
}
</script>
</body>
</html>