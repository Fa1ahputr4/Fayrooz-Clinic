document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: localStorage.getItem('sidebarOpen') !== null 
            ? localStorage.getItem('sidebarOpen') === 'true' 
            : window.innerWidth >= 1024,

        toggle() {
            this.open = !this.open;
            localStorage.setItem('sidebarOpen', this.open);
        }
    });
});

document.documentElement.classList.add('no-transition');
document.documentElement.classList.add('x-cloak');
    window.addEventListener('load', () => {
        document.documentElement.classList.remove('no-transition');
        document.documentElement.classList.remove('x-cloak');
    });

    