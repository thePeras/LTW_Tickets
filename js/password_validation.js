function passwordsMatch() {
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirmPassword");
    var form = document.getElementsByTagName("form")[0];

    var regex = new RegExp("(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})");

    if (password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords don't match");
    } else if (!regex.test(password.value)) {
        confirm_password.setCustomValidity("The password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number and one special character");
    } else {
        confirm_password.setCustomValidity('');
        form.submit();
    }
}

function showPassword(){
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirmPassword");

    if (password.type === "password") {
        password.type = "text";
        confirm_password.type = "text";
    } else {
        password.type = "password";
        confirm_password.type = "password";
    }
}