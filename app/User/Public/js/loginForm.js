const signUpButtons = document.querySelectorAll('.message a');
const registerForm = document.getElementById('register-form');
const loginForm = document.getElementById('login-form');

[].forEach.call(signUpButtons, function(e) {
    e.addEventListener('click',function (button) {
        toggleDisplay(button);
    },false);
});

function toggleDisplay(node) {
    if (registerForm.style.display === 'block') {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    } else {
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
    }
}