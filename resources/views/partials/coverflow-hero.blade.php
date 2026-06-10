<div class="hero relative w-full h-screen overflow-hidden bg-black font-['Inter']" 
     x-data="heroCarousel()" 
     x-init="init()"
     x-cloak>
    
    <!-- Slides Container -->
    <template x-for="(slide, index) in slides" :key="index">
        <div 
            class="slide absolute inset-0 transition-all duration-0 pointer-events-none"
            :class="{
                'opacity-100 z-10': activeIndex === index,
                'opacity-0 z-0': activeIndex !== index
            }"
        >
            <!-- Background Image / Pattern -->
            <div class="absolute inset-0 bg-cover bg-center" :style="'background-image: url(' + slide.background + ')'"></div>
            
            <!-- Noise Overlay -->
            <div class="absolute inset-0 bg-[repeating-linear-gradient(0deg,rgba(0,0,0,0.03)_0px,rgba(0,0,0,0.03)_1px,transparent_1px,transparent_2px)]"></div>
            
            <!-- Gradient Scrim -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            
            <!-- Content -->
            <div class="absolute inset-0 flex flex-col justify-center items-start px-6 md:px-12 lg:px-20 z-10">
                <div class="max-w-3xl">
                    <!-- Tag -->
                    <div class="inline-flex items-center gap-1.5 text-[11px] tracking-[0.18em] uppercase text-white/70 mb-4 font-medium">
                        <span class="w-8 h-px bg-red-500"></span>
                        <span x-text="slide.tag"></span>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="font-['Playfair_Display'] text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-bold text-white leading-[1.1] mb-4"
                        x-text="slide.title.replace(/\n/g, ' ')"></h2>
                    
                    <!-- Subtitle -->
                    <p class="text-base md:text-lg text-white/70 max-w-lg leading-relaxed mb-8" 
                       x-text="slide.subtitle"></p>
                    
                    <!-- Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a :href="slide.button_link || '/shop'" 
                           class="px-8 py-3.5 bg-red-600 hover:bg-red-700 text-white text-sm tracking-[0.1em] uppercase font-semibold rounded-full transition-all duration-300 transform hover:scale-105">
                            Shop Now
                        </a>
                        <a href="{{ route('shop') }}" 
                           class="px-8 py-3.5 bg-transparent border-2 border-white/30 hover:border-white text-white text-sm tracking-[0.1em] uppercase font-semibold rounded-full transition-all duration-300">
                            Explore More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    <!-- Progress Bar -->
    <div class="absolute bottom-0 left-0 w-full h-1 bg-white/10 z-30">
        <div class="progress-fill h-full bg-red-600 w-0 transition-all duration-[0s] linear"
             :class="{ 'running': !isPaused }"
             :style="'transition-duration: ' + slideDuration + 's; width: ' + progress + '%'"></div>
    </div>
    
    <!-- Bottom Navigation (Centered) -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-6 z-30">
        <!-- Counter -->
        <div class="text-[12px] tracking-[0.12em] text-white/50 font-mono" 
             x-text="currentCounter"></div>
        
        <!-- Navigation Buttons -->
        <div class="flex gap-2">
            <button @click="prevSlide" 
                    class="w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 text-white hover:bg-red-600 hover:border-red-600 transition-all duration-300 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="nextSlide" 
                    class="w-10 h-10 rounded-full bg-black/40 backdrop-blur-sm border border-white/20 text-white hover:bg-red-600 hover:border-red-600 transition-all duration-300 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        
        <!-- Dots Indicator -->
        <div class="flex gap-2">
            <template x-for="(slide, idx) in slides" :key="idx">
                <button @click="goToSlide(idx)"
                        class="h-1 rounded-full transition-all duration-300"
                        :class="activeIndex === idx ? 'w-8 bg-red-600' : 'w-3 bg-white/30 hover:bg-white/50'">
                </button>
            </template>
        </div>
    </div>
    
    <!-- Thumbnails (Right Side - Fixed) -->
    <div class="absolute right-6 top-1/2 -translate-y-1/2 w-20 flex flex-col gap-2 z-30">
        <template x-for="(slide, index) in slides.slice(0, 4)" :key="index">
            <div @click="goToSlide(index)"
                 class="thumb group relative w-16 h-16 rounded-lg overflow-hidden cursor-pointer transition-all duration-300"
                 :class="{ 'ring-2 ring-red-600 scale-105': activeIndex === index, 'opacity-60 hover:opacity-100': activeIndex !== index }">
                <div class="thumb-bg w-full h-full bg-cover bg-center transition-transform duration-300 group-hover:scale-110"
                     :style="'background-image: url(' + slide.background + ')'"></div>
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                    <span class="text-white text-xs font-medium" x-text="'0' + (index + 1)"></span>
                </div>
            </div>
        </template>
    </div>
    
    <!-- Loading State -->
    <div x-show="slides.length === 0" class="absolute inset-0 flex items-center justify-center bg-black z-50">
        <div class="w-12 h-12 border-3 border-white/20 border-t-red-600 rounded-full animate-spin"></div>
    </div>
</div>

<style>
    .progress-fill.running {
        transition-timing-function: linear !important;
        width: 100% !important;
    }
    
    /* Slide transition animations */
    .slide {
        transition: opacity 0s;
    }
    
    .slide.active {
        animation: slideIn 0.85s cubic-bezier(0.76, 0, 0.24, 1) forwards;
    }
    
    @keyframes slideIn {
        from {
            clip-path: polygon(100% 0, 100% 0, 100% 100%, 100% 100%);
        }
        to {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
        }
    }
    
    /* Thumbnail styles */
    .thumb-bg {
        filter: brightness(0.5);
        transition: filter 0.3s ease, transform 0.4s ease;
    }
    
    .thumb:hover .thumb-bg {
        filter: brightness(0.7);
    }
    
    .thumb.active .thumb-bg {
        filter: brightness(0.8);
    }
</style>

<script>
    function heroCarousel() {
        return {
            slides: [],
            activeIndex: 0,
            isAnimating: false,
            autoPlayInterval: null,
            progress: 0,
            isPaused: false,
            slideDuration: 5.5,
            
            get currentCounter() {
                const current = this.activeIndex + 1;
                const total = this.slides.length;
                return `${current.toString().padStart(2, '0')} / ${total.toString().padStart(2, '0')}`;
            },
            
            async init() {
                await this.fetchBanners();
                this.startAutoPlay();
                this.startProgress();
            },
            
            async fetchBanners() {
                try {
                    const response = await fetch('/api/banners');
                    const data = await response.json();
                    
                    if (data.length === 0) {
                        this.useDefaultData();
                    } else {
                        this.slides = data.map((item, idx) => ({
                            tag: item.title?.split(' ')[0] || 'New Collection',
                            title: item.title || 'Ja Lanka',
                            subtitle: item.subtitle || 'Premium Japanese Kitchen & Food',
                            background: item.image ? `/storage/${item.image}` : this.getPatternBackground(idx),
                            button_link: item.button_link || '/shop',
                            button_text: item.button_text || 'Shop Now'
                        }));
                    }
                } catch (error) {
                    console.error('Error loading banners:', error);
                    this.useDefaultData();
                }
            },
            
            useDefaultData() {
                this.slides = [
                    {
                        tag: "New Collection",
                        title: "Taste of Authentic Japan",
                        subtitle: "Curated groceries sourced directly from local Japanese producers — delivered to your door.",
                        background: this.getPatternBackground(0)
                    },
                    {
                        tag: "Pantry Essentials",
                        title: "Premium Flavours From the East",
                        subtitle: "Sauces, spices, and pantry staples — hand-picked for quality and tradition.",
                        background: this.getPatternBackground(1)
                    },
                    {
                        tag: "Fresh & Frozen",
                        title: "Wholesome Meals Start Here",
                        subtitle: "Fresh produce and frozen favourites from Japan's finest farms and fisheries.",
                        background: this.getPatternBackground(2)
                    },
                    {
                        tag: "New Arrivals",
                        title: "Discover What's New This Week",
                        subtitle: "Seasonal specials and limited-stock imports — refreshed every week.",
                        background: this.getPatternBackground(3)
                    }
                ];
            },
            
            getPatternBackground(index) {
                const colors = [
                    { bg1: '#0f0812', accent: '#a78bfa' },
                    { bg1: '#080f08', accent: '#4ade80' },
                    { bg1: '#060810', accent: '#60a5fa' },
                    { bg1: '#100a04', accent: '#fb923c' }
                ];
                const c = colors[index % colors.length];
                
                let svg = `<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='100%' style='background:${c.bg1}'>`;
                for (let r = 0; r < 12; r++) {
                    for (let c2 = 0; c2 < 12; c2++) {
                        const opacity = (Math.random() * 0.12 + 0.03).toFixed(2);
                        svg += `<rect x="${c2 * 60}" y="${r * 50}" width="56" height="48" rx="4" fill="${c.accent}" opacity="${opacity}"/>`;
                    }
                }
                svg += '</svg>';
                return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
            },
            
            goToSlide(index) {
                if (this.isAnimating || index === this.activeIndex) return;
                this.isAnimating = true;
                this.activeIndex = index;
                this.resetProgress();
                setTimeout(() => {
                    this.isAnimating = false;
                }, 850);
                this.resetAutoPlay();
            },
            
            nextSlide() {
                if (this.isAnimating) return;
                this.isAnimating = true;
                this.activeIndex = (this.activeIndex + 1) % this.slides.length;
                this.resetProgress();
                setTimeout(() => {
                    this.isAnimating = false;
                }, 850);
                this.resetAutoPlay();
            },
            
            prevSlide() {
                if (this.isAnimating) return;
                this.isAnimating = true;
                this.activeIndex = (this.activeIndex - 1 + this.slides.length) % this.slides.length;
                this.resetProgress();
                setTimeout(() => {
                    this.isAnimating = false;
                }, 850);
                this.resetAutoPlay();
            },
            
            startAutoPlay() {
                this.autoPlayInterval = setInterval(() => {
                    if (!this.isPaused) {
                        this.nextSlide();
                    }
                }, (this.slideDuration + 0.5) * 1000);
            },
            
            resetAutoPlay() {
                if (this.autoPlayInterval) {
                    clearInterval(this.autoPlayInterval);
                }
                this.startAutoPlay();
            },
            
            startProgress() {
                setInterval(() => {
                    if (!this.isAnimating && !this.isPaused) {
                        // Progress handled by CSS
                    }
                }, 100);
            },
            
            resetProgress() {
                this.progress = 0;
                const progressBar = document.querySelector('.progress-fill');
                if (progressBar) {
                    progressBar.classList.remove('running');
                    progressBar.style.width = '0%';
                    void progressBar.offsetWidth;
                    progressBar.classList.add('running');
                }
            }
        };
    }
</script>