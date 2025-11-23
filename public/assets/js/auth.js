document.addEventListener("DOMContentLoaded", () => {
    const regForm = document.getElementById("registerForm");
    if (regForm) {
        regForm.addEventListener("submit", (e) => {
            const pass = document.querySelector("input[name='password']").value;
            const confirm = document.querySelector("input[name='confirm_password']").value;

            if (pass !== confirm) {
                alert("Password tidak sama!");
                e.preventDefault();
            }
        });
    }
});
