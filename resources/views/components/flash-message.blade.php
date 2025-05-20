<div 
    x-data="{
        show: false,
        type: 'success',
        message: '',
        timeout: null,
        progress: 100,
        init() {
            window.addEventListener('flash-message', event => {
                this.type = event.detail.type;
                this.message = event.detail.message;
                this.show = true;
                this.progress = 100;

                // Clear previous timeout & progress
                clearTimeout(this.timeout);

                // Progress bar animation
                let start = Date.now();
                let duration = 4000;

                const frame = () => {
                    let timePassed = Date.now() - start;
                    this.progress = Math.max(100 - (timePassed / duration * 100), 0);

                    if (timePassed < duration) {
                        requestAnimationFrame(frame);
                    }
                };
                requestAnimationFrame(frame);

                // Auto-hide after 4 seconds
                this.timeout = setTimeout(() => this.show = false, duration);
            });
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
            let remainingTime = (this.progress / 100) * 4000;
            this.timeout = setTimeout(() => this.show = false, remainingTime);
        }
    }" 
    x-show="show"
    x-transition
    class="fixed top-5 right-5 z-50 w-80 max-w-full px-4 py-3 rounded-lg shadow-lg text-white"
    :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
        'bg-yellow-500': type === 'warning'
    }"
    style="display: none;"
    @mouseenter="pause" 
    @mouseleave="resume"
>
    <div class="flex items-start justify-between gap-3">
        <span x-text="message" class="flex-1 text-sm"></span>
        <button @click="close" class="text-white font-bold text-lg leading-none">&times;</button>
    </div>
    <!-- Progress bar -->
    <div class="mt-2 h-1 w-full bg-white/30 rounded overflow-hidden">
        <div 
            class="h-full bg-white transition-all duration-50"
            :style="`width: ${progress}%`"
        ></div>
    </div>
</div>