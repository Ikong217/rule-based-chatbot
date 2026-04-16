function toggleTheme() {
    const html = document.documentElement;

    const current = html.getAttribute("data-theme");

    if (current === "dark") {
        html.setAttribute("data-theme", "light");
        localStorage.setItem("theme", "light");
    } else {
        html.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
    }
}

window.onload = () => {
    const saved = localStorage.getItem("theme");

    if (saved) {
        document.documentElement.setAttribute("data-theme", saved);
    }
};
