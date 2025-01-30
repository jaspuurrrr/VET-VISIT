var swiper = new Swiper(".welcome", {
    spaceBetween: 30,
    centeredSlides: true,
    autoplay: {
        delay: 6000,
        disableOnInteraction: false,
    },
  });

let menu = document.querySelector('#menu-icon')
let navbar = document.querySelector('.navbar')

menu.onclick = () => {
    menu.classList.toggle('bx-x');
    navbar.classList.toggle('active');
}

window.onscroll = () => {
    menu.classList.remove('bx-x');
    navbar.classList.remove('active');
} 

// main.js

document.addEventListener("DOMContentLoaded", function () {
        var popupLink = document.getElementById("popup-link");
        var popup2Link = document.getElementById("popup2-link");
        var modal = document.getElementById("modal")
        var popupWindow = document.getElementById("popup-cont");
        var popup2Window = document.getElementById("popup2-cont");
        var closeButton = document.getElementById("closebutt");
        var close2Button = document.getElementById("close2butt");
        var loginbut = document.getElementById("login");
        var signupbut = document.getElementById("signup");

        // Show the pop-up window when the link is clicked
        popupLink.addEventListener("click", function(event) {
          event.preventDefault();
          //modal.style.display = "block";
          popupWindow.style.display = "block";
        });
        popup2Link.addEventListener("click", function(event) {
          event.preventDefault();
          //modal.style.display = "block";
          popup2Window.style.display = "block";
        });
        // Hide the pop-up window when the close button is clicked
        closeButton.addEventListener("click", function() {
          //modal.style.display = "none";
          popupWindow.style.display = "none";
        });
        close2Button.addEventListener("click", function() {
          //modal.style.display = "none";
          popup2Window.style.display = "none";
        });

        var SwitchToLG = document.getElementById("switchtologin");
        var SwitchToSU = document.getElementById("switchtosignup");

        SwitchToSU.addEventListener("click", function(event) {
            //modal.style.display = "none";
            popupWindow.style.display = "none";
            //modal.style.display = "block";
            popup2Window.style.display = "block";
        });

        SwitchToLG.addEventListener("click", function(event) {
            //modal.style.display = "none";
            popup2Window.style.display = "none";
            //modal.style.display = "block";
            popupWindow.style.display = "block";
        });

        loginbut.addEventListener("click", function(){

            var email = document.getElementById("emaillog").value;
            var password = document.getElementById("passwordlog").value;
            if (email.trim() === "" || password.trim() === "") {
                // Display an alert for invalid input
                alert("Invalid input. Email and password cannot be empty or contain only white spaces.");
                document.getElementById("emaillog").value = '';
                document.getElementById("passwordlog").value = '';
                exit();
            }
            //alert(email + " " + password)

            fetch('php/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email, password: password }),
            })
            .then(response => response.json())
            .then(data => {
                // Check the response and display the appropriate message
                if (data.error === 'Account does not exist.' || data.error === 'Incorrect password.') {
                    alert(data.error)
                } else if (data.acctype == 'client') {
                    // If successful, redirect to page
                    window.location.href = 'pages/client-page/client-cal.html';
                } else if (data.acctype == 'vet') {
                    // If successful, redirect to page
                    window.location.href = 'pages/vet-page/vet-appointment-mngr.html';
                } else if (data.acctype == 'admin' ){
                    window.location.href = 'pages/admin-page/admin-appointment-mng.html';
                } else {
                    console.log(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

        });

        signupbut.addEventListener('click', function () {

            // Get email and password values
            var email = document.getElementById('emailsign').value;
            var password = document.getElementById('passwordsign').value;
            var repassword = document.getElementById('repassword').value;
            var acctype = 'client';


            const emailPattern =  /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            const passwordPattern = /^(?:(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*)$/;

            fetch('php/signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email}),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return 0;
                } else {
                    if (email == null || email == "" || password == null || password == "" || repassword == null || repassword == "" ){
                        alert("All fields are required!");
                        return 0;
                    }else if( password !== repassword ){
                        alert("Passwords do not match.");
                        return 0;
                    }else if (!emailPattern.test(email)) {
                        alert("Invalid email format.");
                        return 0;
                    }else if (password.length < 8 || !passwordPattern.test(password)){
                        alert("Password should be at least 8 characters long. It must have at least 1 uppercase letter, 1 lowercase letter, and 1 number.");
                        return 0;
                    }

                    // Send a POST request to signup.php with JSON data
                    fetch('php/signup.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ email: email, password: password, acctype: acctype}),
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Check the response and display the appropriate message
                        if (data.error) {
                            //document.getElementById('errorMessage').textContent = data.error;
                            alert(data.error);
                        } else {
                            window.location.href = 'pages/client-page/user-reg.html'
                            //window.location.href = //next page 'login.html';
                        }
                    })
                    .catch(error => {
                        //alert()
                        console.error('Error:', error);
                    });
                }
            })
            .catch(error => {
                //alert()
                console.error('Error:', error);
            });  
        });
    })




