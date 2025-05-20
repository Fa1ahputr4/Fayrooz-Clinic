<div x-data="{
    show: {{ session('success') ? 'true' : 'false' }},
    type: '{{ session('success') ? 'success' : 'error' }}',
    message: '{{ session('success') ? session('success') : session('error') }}',
    timeout: null,
    progress: 100,
    duration: 4000,
    startProgress() {
        this.progress = 100;
        let start = Date.now();
        const updateProgress = () => {
            const timeElapsed = Date.now() - start;
            this.progress = Math.max(100 - (timeElapsed / this.duration * 100), 0);

            if (this.progress > 0) {
                requestAnimationFrame(updateProgress);
            }
        };
        requestAnimationFrame(updateProgress);

        // Hide after duration
        this.timeout = setTimeout(() => this.show = false, this.duration);
    },
    close() {
        this.show = false;
        this.progress = 0;
        clearTimeout(this.timeout);
    },
    pause() {
        clearTimeout(this.timeout);
    },
    resume() {
        let remainingTime = (this.progress / 100) * this.duration;
        this.timeout = setTimeout(() => this.show = false, remainingTime);
    }
}" x-show="show" x-transition x-init="startProgress()"
    class="fixed top-5 right-5 z-50 w-80 max-w-full px-4 py-3 rounded-lg shadow-lg text-white"
    :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
        'bg-yellow-500': type === 'warning'
    }"
    style="display: none;" @mouseenter="pause" @mouseleave="resume">
    <div class="flex items-start justify-between gap-3">
        <span x-text="message" class="flex-1 text-sm"></span>
        <button @click="close" class="text-white font-bold text-lg leading-none">&times;</button>
    </div>
    <!-- Progress bar -->
    <div class="mt-2 h-1 w-full bg-white/30 rounded overflow-hidden">
        <div class="h-full bg-white transition-all duration-50" :style="`width: ${progress}%`"></div>
    </div>
</div>
