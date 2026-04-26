document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.getElementById("theme-toggle");
    const htmlElement = document.documentElement;
    const currentTheme = localStorage.getItem("theme");

    const darkIcon = '<i class="fa-solid fa-cat"></i>';
    const lightIcon = '<i class="fa-solid fa-dog"></i>';

    if (currentTheme === "dark") {
        htmlElement.setAttribute("data-theme", "dark");
        if (toggleButton) toggleButton.innerHTML = darkIcon;
    } else {
        htmlElement.setAttribute("data-theme", "light");
        if (toggleButton) toggleButton.innerHTML = lightIcon;
    }

    if (toggleButton) {
        toggleButton.addEventListener("click", () => {
            const isDark = htmlElement.getAttribute("data-theme") === "dark";
            const newTheme = isDark ? "light" : "dark";

            htmlElement.setAttribute("data-theme", newTheme);
            localStorage.setItem("theme", newTheme);

            toggleButton.style.transform = "scale(0.8)";
            toggleButton.style.opacity = "0";

            setTimeout(() => {
                toggleButton.innerHTML = newTheme === "dark" ? darkIcon : lightIcon;
                toggleButton.style.transform = "scale(1)";
                toggleButton.style.opacity = "1";
            }, 150);
        });
    }
});
