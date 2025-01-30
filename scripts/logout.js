var lgt = document.getElementById("logout")

        lgt.addEventListener("click", function(event) {
            var confirm = window.confirm("Are you sure you want to log out from your account?");
            if(!confirm){
                return 0;
            }
            event.preventDefault();
            window.location.href = "../../index.html";
        });