function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");

    const themeSwitch = document.getElementById("themeSwitch");

    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");

        if (themeSwitch) {
            themeSwitch.checked = true;
        }
    } else {
        localStorage.setItem("theme", "light");

        if (themeSwitch) {
            themeSwitch.checked = false;
        }
    }

    location.reload();
}

function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");

    if (sidebar) {
        sidebar.classList.toggle("show");
    }

    if (overlay) {
        overlay.classList.toggle("show");
    }
}

window.onload = function () {
    const themeSwitch = document.getElementById("themeSwitch");

    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");

        if (themeSwitch) {
            themeSwitch.checked = true;
        }
    } else {
        if (themeSwitch) {
            themeSwitch.checked = false;
        }
    }
};