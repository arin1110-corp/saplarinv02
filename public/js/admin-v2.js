function layout() {

    return {

        sidebarOpen: false,

        darkMode: false,

        isMobile: false,

        init() {

            // Theme
            this.darkMode = localStorage.getItem('theme') === 'dark';
            this.applyTheme();

            // Responsive
            this.handleResize();

            window.addEventListener('resize', () => {
                this.handleResize();
            });

        },

        handleResize() {

            this.isMobile = window.innerWidth < 1024;

            if (this.isMobile) {

                this.sidebarOpen = false;

                document.body.classList.remove('overflow-hidden');

            } else {

                this.sidebarOpen = true;

                document.body.classList.remove('overflow-hidden');

            }

        },

        toggleSidebar() {

            if (!this.isMobile) return;

            this.sidebarOpen = !this.sidebarOpen;

            if (this.sidebarOpen) {

                document.body.classList.add('overflow-hidden');

            } else {

                document.body.classList.remove('overflow-hidden');

            }

        },

        closeSidebar() {

            this.sidebarOpen = false;

            document.body.classList.remove('overflow-hidden');

        },

        toggleDark() {

            this.darkMode = !this.darkMode;

            this.applyTheme();

        },

        applyTheme() {

            if (this.darkMode) {

                document.documentElement.classList.add('dark');

                localStorage.setItem('theme', 'dark');

            } else {

                document.documentElement.classList.remove('dark');

                localStorage.setItem('theme', 'light');

            }

        }

    };

}