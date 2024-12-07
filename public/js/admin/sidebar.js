
    // Sidebar toggle
    let sidebar = document.querySelector(".dashboard-sidebar");
    let closeBtn = document.querySelector("#btn");
    let homeSection = document.querySelector(".home-section");

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        homeSection.classList.toggle("shifted");
        menuBtnChange();
    });

    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else {
            closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    }



    document.getElementById('log_out').addEventListener('click', function (event) {
        event.preventDefault();

        // Use SweetAlert2 to show a confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to log out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, log out!',
            cancelButtonText: 'No, stay logged in',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If user clicks 'Yes', submit the logout form
                document.getElementById('logoutForm').submit();
            }
        });
    });
