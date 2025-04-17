 // Simpan state sidebar di localStorage
 document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        open: localStorage.getItem('sidebarOpen') === 'true' || window.innerWidth >= 1024,

        toggle() {
            this.open = !this.open
            localStorage.setItem('sidebarOpen', this.open)
        }
    })
})

// Handle resize event
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1024) {
        Alpine.store('sidebar').open = true
    }
})