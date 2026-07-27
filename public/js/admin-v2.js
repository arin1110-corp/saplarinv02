document.addEventListener("alpine:init", () => {
    Alpine.data("layout", () => ({
        sidebarOpen: false,
        sidebarMini: false,
        darkMode: false,
        isMobile: false,

        init() {
            this.darkMode = localStorage.getItem("theme") === "dark";
            this.applyTheme();

            this.handleResize();

            window.addEventListener("resize", () => {
                this.handleResize();
            });
        },

        handleResize() {
            this.isMobile = window.innerWidth < 1024;

            if (this.isMobile) {
                this.sidebarOpen = false;
            } else {
                this.sidebarOpen = true;
            }
        },

        toggleSidebar() {
            if (this.isMobile) {
                this.sidebarOpen = !this.sidebarOpen;

                document.body.classList.toggle(
                    "overflow-hidden",
                    this.sidebarOpen,
                );
            } else {
                this.sidebarMini = !this.sidebarMini;
            }
        },

        closeSidebar() {
            this.sidebarOpen = false;

            document.body.classList.remove("overflow-hidden");
        },

        toggleDark() {
            this.darkMode = !this.darkMode;

            this.applyTheme();
        },

        applyTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add("dark");

                localStorage.setItem("theme", "dark");
            } else {
                document.documentElement.classList.remove("dark");

                localStorage.setItem("theme", "light");
            }
        },
    }));
});
