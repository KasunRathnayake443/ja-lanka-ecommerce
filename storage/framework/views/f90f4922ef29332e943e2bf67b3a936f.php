<div class="hero relative w-full h-screen overflow-hidden bg-[#080808]"
     x-data="heroCarousel()"
     x-init="init()"
     x-cloak>

    
    <div id="heroSlideWrap" class="absolute inset-0"></div>

    
    <div class="absolute bottom-0 left-0 w-full h-[2px] bg-white/10 z-30">
        <div id="heroProgress" class="h-full bg-white/60 w-0"></div>
    </div>

    
    <div class="absolute bottom-6 left-12 z-30 items-center gap-5 hidden lg:flex">
        <div class="text-[11px] tracking-[0.13em] text-white/35 tabular-nums whitespace-nowrap"
             x-text="counter"></div>
        <div class="flex gap-1">
            <button @click="prev" aria-label="Previous slide"
                    class="w-9 h-9 flex items-center justify-center rounded-sm
                           bg-white/[0.07] border border-white/[0.14] text-white/65
                           hover:bg-white/15 hover:border-white/30 hover:text-white transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="next" aria-label="Next slide"
                    class="w-9 h-9 flex items-center justify-center rounded-sm
                           bg-white/[0.07] border border-white/[0.14] text-white/65
                           hover:bg-white/15 hover:border-white/30 hover:text-white transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        <div id="heroDots" class="flex gap-1.5 items-center"></div>
    </div>

    
    <div id="heroThumbStrip"
         class="absolute right-0 top-0 bottom-0 w-[180px] z-20 flex-col hidden lg:flex">
    </div>

    
    <div class="absolute bottom-0 left-0 right-0 h-[72px] z-30 flex items-center
                px-4 gap-3 lg:hidden">

        
        <div class="text-[10px] tracking-[0.1em] text-white/40 tabular-nums whitespace-nowrap shrink-0 w-[34px]"
             x-text="counter"></div>

        
        <button @click="prev" aria-label="Previous slide"
                class="w-8 h-8 shrink-0 flex items-center justify-center rounded-sm
                       bg-white/[0.07] border border-white/[0.14] text-white/65
                       hover:bg-white/15 hover:text-white transition-all duration-200">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="next" aria-label="Next slide"
                class="w-8 h-8 shrink-0 flex items-center justify-center rounded-sm
                       bg-white/[0.07] border border-white/[0.14] text-white/65
                       hover:bg-white/15 hover:text-white transition-all duration-200">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        
        <div id="heroThumbMobile"
             class="flex gap-2 items-center flex-1 overflow-hidden justify-center">
        </div>
    </div>

    
    <div x-show="loading"
         class="absolute inset-0 flex items-center justify-center bg-[#080808] z-50">
        <div class="w-10 h-10 border-2 border-white/20 border-t-white/70 rounded-full animate-spin"></div>
    </div>
</div>


<style>
    /* ── Slide states ────────────────────────────────────────────── */
    .hs-slide {
        position: absolute;
        inset: 0;
        pointer-events: none;
        clip-path: polygon(100% 0, 100% 0, 100% 100%, 100% 100%);
    }
    .hs-slide.hs-idle {
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
        pointer-events: all;
    }
    .hs-slide.hs-in {
        pointer-events: all;
        animation: hsWipeIn 0.82s cubic-bezier(0.76, 0, 0.24, 1) forwards;
    }
    .hs-slide.hs-out {
        pointer-events: none;
        animation: hsWipeOut 0.82s cubic-bezier(0.76, 0, 0.24, 1) forwards;
    }
    .hs-slide.hs-in.hs-rev  { animation: hsWipeInRev  0.82s cubic-bezier(0.76, 0, 0.24, 1) forwards; }
    .hs-slide.hs-out.hs-rev { animation: hsWipeOutRev 0.82s cubic-bezier(0.76, 0, 0.24, 1) forwards; }

    @keyframes hsWipeIn     { from { clip-path: polygon(100% 0,100% 0,100% 100%,100% 100%) } to { clip-path: polygon(0 0,100% 0,100% 100%,0 100%) } }
    @keyframes hsWipeOut    { from { clip-path: polygon(0 0,100% 0,100% 100%,0 100%) }       to { clip-path: polygon(0 0,0 0,0 100%,0 100%) } }
    @keyframes hsWipeInRev  { from { clip-path: polygon(0 0,0 0,0 100%,0 100%) }              to { clip-path: polygon(0 0,100% 0,100% 100%,0 100%) } }
    @keyframes hsWipeOutRev { from { clip-path: polygon(0 0,100% 0,100% 100%,0 100%) }        to { clip-path: polygon(100% 0,100% 0,100% 100%,100% 100%) } }

    /* ── Background Ken Burns ────────────────────────────────────── */
    .hs-bg {
        position: absolute;
        inset: -4%;
        background-size: cover;
        background-position: center;
        transform: scale(1.06);
        transition: transform 6.5s ease;
        will-change: transform;
    }
    .hs-slide.hs-idle .hs-bg,
    .hs-slide.hs-in   .hs-bg { transform: scale(1); }

    /* ── Overlays ────────────────────────────────────────────────── */
    .hs-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            105deg,
            rgba(5,3,12,0.95) 25%,
            rgba(5,3,12,0.55) 60%,
            rgba(5,3,12,0.15) 100%
        );
    }
    .hs-noise {
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(
            0deg,
            rgba(0,0,0,0.025) 0px, rgba(0,0,0,0.025) 1px,
            transparent 1px, transparent 2px
        );
        pointer-events: none;
    }

    /* ── Slide text content ──────────────────────────────────────── */
    .hs-content {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* desktop: clear of right thumb strip */
        padding: 0 220px 80px 64px;
        z-index: 4;
    }
    @media (max-width: 1023px) {
        .hs-content {
            /* tablet: no thumb strip, still need bottom clearance for mobile bar */
            padding: 0 48px 100px 44px;
        }
    }
    @media (max-width: 640px) {
        .hs-content {
            /* mobile: full width, clear the 72px bottom bar */
            padding: 0 24px 90px 24px;
        }
    }

    /* ── Tag ─────────────────────────────────────────────────────── */
    .hs-tag {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 11px;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.45);
        margin-bottom: 22px;
        font-weight: 500;
    }
    /* Neutral short line — no red */
    .hs-tag-line {
        display: block;
        width: 22px;
        height: 1px;
        background: rgba(255,255,255,0.35);
    }

    /* ── Title ───────────────────────────────────────────────────── */
    .hs-title {
        font-family: 'Playfair Display', Georgia, serif;
        /* Bigger: was clamp(2rem,5.5vw,3.8rem) → now up to 5rem desktop */
        font-size: clamp(2.4rem, 6.5vw, 5rem);
        font-weight: 700;
        line-height: 1.08;
        color: #fff;
        margin-bottom: 22px;
        max-width: 680px;
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.65s 0.22s ease, transform 0.65s 0.22s ease;
    }

    /* ── Subtitle ────────────────────────────────────────────────── */
    .hs-sub {
        /* Bigger: was 0.9rem → now 1.05rem, wider max-width */
        font-size: clamp(0.95rem, 1.5vw, 1.1rem);
        color: rgba(255,255,255,0.58);
        max-width: 500px;
        line-height: 1.85;
        margin-bottom: 38px;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s 0.34s ease, transform 0.6s 0.34s ease;
    }

    /* ── Buttons ─────────────────────────────────────────────────── */
    .hs-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        opacity: 0;
        transform: translateY(14px);
        transition: opacity 0.55s 0.44s ease, transform 0.55s 0.44s ease;
    }

    /* Reveal text on active/idle */
    .hs-slide.hs-idle .hs-title,
    .hs-slide.hs-in   .hs-title,
    .hs-slide.hs-idle .hs-sub,
    .hs-slide.hs-in   .hs-sub,
    .hs-slide.hs-idle .hs-btns,
    .hs-slide.hs-in   .hs-btns {
        opacity: 1;
        transform: translateY(0);
    }

    .hs-btn-primary {
        display: inline-block;
        padding: 13px 32px;
        background: #fff;
        color: #0a0a0a;
        border: none;
        border-radius: 2px;
        font-size: 0.72rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .hs-btn-primary:hover { background: #e5e5e5; }

    .hs-btn-ghost {
        display: inline-block;
        padding: 13px 32px;
        background: transparent;
        color: rgba(255,255,255,0.65);
        border: 1px solid rgba(255,255,255,0.22);
        border-radius: 2px;
        font-size: 0.72rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s;
    }
    .hs-btn-ghost:hover { border-color: rgba(255,255,255,0.55); color: #fff; }

    /* ── Progress bar ────────────────────────────────────────────── */
    #heroProgress.go {
        transition: width var(--hs-dur, 5.5s) linear;
        width: 100% !important;
    }

    /* ── Desktop thumbnail strip ─────────────────────────────────── */
    .hs-thumb {
        position: relative;
        flex: 1;
        cursor: pointer;
        overflow: hidden;
        transition: flex 0.55s cubic-bezier(0.76, 0, 0.24, 1);
        min-height: 0;
    }
    .hs-thumb.hs-ton { flex: 2.4; }

    /* Subtle white inset border — no red */
    .hs-thumb::before {
        content: '';
        position: absolute;
        inset: 0;
        border-left: 2px solid transparent;
        z-index: 3;
        pointer-events: none;
        transition: border-color 0.35s;
    }
    .hs-thumb.hs-ton::before { border-color: rgba(255,255,255,0.5); }

    .hs-thumb-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: brightness(0.35);
        transition: filter 0.4s ease, transform 0.55s ease;
    }
    .hs-thumb.hs-ton .hs-thumb-bg                   { filter: brightness(0.6); }
    .hs-thumb:not(.hs-ton):hover .hs-thumb-bg        { filter: brightness(0.5); transform: scale(1.04); }

    .hs-thumb-label {
        position: absolute;
        top: 12px;
        left: 0; right: 0;
        text-align: center;
        font-size: 9px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0);
        z-index: 4;
        padding: 0 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.3s;
    }
    .hs-thumb.hs-ton .hs-thumb-label { color: rgba(255,255,255,0.5); }

    .hs-thumb-num {
        position: absolute;
        bottom: 10px;
        left: 0; right: 0;
        text-align: center;
        font-size: 10px;
        letter-spacing: 0.1em;
        color: rgba(255,255,255,0.35);
        z-index: 4;
        transition: color 0.3s;
    }
    .hs-thumb.hs-ton .hs-thumb-num { color: rgba(255,255,255,0.85); }

    /* ── Mobile thumbnails ───────────────────────────────────────── */
    .hs-thumb-m {
        cursor: pointer;
        border-radius: 3px;
        overflow: hidden;
        position: relative;
        flex-shrink: 0;
        /* Fixed size — active gets a subtle white ring via box-shadow (no layout shift) */
        width: 40px;
        height: 40px;
        opacity: 0.45;
        transition:
            opacity   0.35s ease,
            box-shadow 0.35s ease,
            transform  0.35s ease;
    }
    .hs-thumb-m.hs-ton {
        opacity: 1;
        box-shadow: 0 0 0 1.5px rgba(255,255,255,0.6);
        transform: scale(1.1);
    }

    .hs-thumb-m-bg {
        width: 100%; height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(0.4);
        transition: filter 0.35s;
    }
    .hs-thumb-m.hs-ton .hs-thumb-m-bg { filter: brightness(0.65); }

    .hs-thumb-m-num {
        position: absolute;
        bottom: 2px; left: 0; right: 0;
        text-align: center;
        font-size: 8px;
        font-weight: 500;
        color: rgba(255,255,255,0.4);
        transition: color 0.3s;
    }
    .hs-thumb-m.hs-ton .hs-thumb-m-num { color: rgba(255,255,255,0.85); }

    /* ── Nav dots ────────────────────────────────────────────────── */
    .hs-dot {
        height: 2px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        padding: 0;
        background: rgba(255,255,255,0.25);
        width: 10px;
        transition: width 0.35s ease, background 0.35s ease;
    }
    .hs-dot.hs-don          { width: 24px; background: rgba(255,255,255,0.75); }
    .hs-dot:not(.hs-don):hover { background: rgba(255,255,255,0.45); }

    [x-cloak] { display: none !important; }
</style>


<script>
function heroCarousel() {
    return {
        slides:  [],
        cur:     0,
        busy:    false,
        loading: true,
        dur:     5.5,
        _timer:  null,

        get counter() {
            const n = this.slides.length;
            return n ? `${String(this.cur+1).padStart(2,'0')} / ${String(n).padStart(2,'0')}` : '–';
        },

        async init() {
            await this.fetchBanners();
            this.loading = false;
            this.$nextTick(() => {
                this._buildDOM();
                this._startProg();
                this._startTimer();
            });
        },

        async fetchBanners() {
            try {
                const res  = await fetch('/api/banners');
                const data = await res.json();
                if (data.length) {
                    this.slides = data.map((item, i) => ({
                        tag:     item.tag      || item.title?.split(' ')[0] || 'Collection',
                        title:   item.title    || 'Ja Lanka',
                        subtitle:item.subtitle || 'Premium Japanese Groceries',
                        image:   item.image    ? `/storage/${item.image}` : this._placeholderBg(i),
                        link:    item.button_link  || '/shop',
                        btnText: item.button_text  || 'Shop Now',
                    }));
                } else {
                    this._useDefaults();
                }
            } catch (e) {
                console.error('Hero banner fetch failed:', e);
                this._useDefaults();
            }
        },

        _useDefaults() {
            const items = [
                { tag:'New Collection',    title:'Taste of\nAuthentic Japan',        subtitle:'Curated groceries sourced directly from local Japanese producers — delivered to your door.' },
                { tag:'Pantry Essentials', title:'Premium Flavours\nFrom the East',  subtitle:'Sauces, spices, and pantry staples — hand-picked for quality and tradition.' },
                { tag:'Fresh & Frozen',    title:'Wholesome Meals\nStart Here',      subtitle:'Fresh produce and frozen favourites from Japan\'s finest farms and fisheries.' },
                { tag:'New Arrivals',      title:'Discover What\'s\nNew This Week',  subtitle:'Seasonal specials and limited-stock imports — refreshed every week.' },
            ];
            this.slides = items.map((d, i) => ({
                ...d,
                image:   this._placeholderBg(i),
                link:    '/shop',
                btnText: 'Shop Now',
            }));
        },

        _placeholderBg(i) {
            const palettes = [
                { bg:'#0f0812', accent:'#a78bfa' },
                { bg:'#060f08', accent:'#4ade80' },
                { bg:'#060810', accent:'#60a5fa' },
                { bg:'#100a04', accent:'#fb923c' },
            ];
            const p = palettes[i % palettes.length];
            let rects = '';
            for (let r = 0; r < 13; r++) {
                for (let c = 0; c < 15; c++) {
                    const op = (Math.random() * 0.13 + 0.03).toFixed(2);
                    rects += `<rect x="${c*56}" y="${r*54}" width="52" height="50" rx="5" fill="${p.accent}" opacity="${op}"/>`;
                }
            }
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='840' height='720' style='background:${p.bg}'>${rects}</svg>`;
            return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
        },

        _buildDOM() {
            const slideWrap  = document.getElementById('heroSlideWrap');
            const dotsWrap   = document.getElementById('heroDots');
            const stripWrap  = document.getElementById('heroThumbStrip');
            const mobileWrap = document.getElementById('heroThumbMobile');

            [slideWrap, dotsWrap, stripWrap, mobileWrap].forEach(el => { if (el) el.innerHTML = ''; });

            this.slides.forEach((s, i) => {

                /* ── Slide ── */
                const slide = document.createElement('div');
                slide.className = 'hs-slide' + (i === 0 ? ' hs-idle' : '');
                slide.id = 'hs-sl-' + i;
                const titleHtml = s.title.split('\n')
                    .map(l => `<span style="display:block">${l}</span>`)
                    .join('');
                slide.innerHTML = `
                    <div class="hs-bg"    style="background-image:url('${s.image}')"></div>
                    <div class="hs-noise"></div>
                    <div class="hs-scrim"></div>
                    <div class="hs-content">
                        <div class="hs-tag">
                            <span class="hs-tag-line"></span>
                            ${s.tag}
                        </div>
                        <div class="hs-title">${titleHtml}</div>
                        <div class="hs-sub">${s.subtitle}</div>
                        <div class="hs-btns">
                            <a href="${s.link}" class="hs-btn-primary">${s.btnText}</a>
                            <a href="<?php echo e(route('shop')); ?>" class="hs-btn-ghost">Explore More</a>
                        </div>
                    </div>`;
                slideWrap.appendChild(slide);

                /* ── Dot ── */
                const dot = document.createElement('button');
                dot.className = 'hs-dot' + (i === 0 ? ' hs-don' : '');
                dot.id = 'hs-dot-' + i;
                dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                dot.onclick = () => this.goTo(i, i >= this.cur ? 1 : -1);
                dotsWrap.appendChild(dot);

                /* ── Desktop thumb ── */
                const thumb = document.createElement('div');
                thumb.className = 'hs-thumb' + (i === 0 ? ' hs-ton' : '');
                thumb.id = 'hs-th-' + i;
                thumb.innerHTML = `
                    <div class="hs-thumb-bg"    style="background-image:url('${s.image}')"></div>
                    <div class="hs-thumb-label">${s.tag}</div>
                    <div class="hs-thumb-num">0${i + 1}</div>`;
                thumb.onclick = () => this.goTo(i, i >= this.cur ? 1 : -1);
                stripWrap.appendChild(thumb);

                /* ── Mobile thumb ── */
                const mThumb = document.createElement('div');
                mThumb.className = 'hs-thumb-m' + (i === 0 ? ' hs-ton' : '');
                mThumb.id = 'hs-thm-' + i;
                mThumb.innerHTML = `
                    <div class="hs-thumb-m-bg"  style="background-image:url('${s.image}')"></div>
                    <div class="hs-thumb-m-num">0${i + 1}</div>`;
                mThumb.onclick = () => this.goTo(i, i >= this.cur ? 1 : -1);
                mobileWrap.appendChild(mThumb);
            });
        },

        goTo(idx, dir) {
            if (this.busy || idx === this.cur) return;
            this.busy = true;

            const rev   = dir < 0;
            const outEl = document.getElementById('hs-sl-' + this.cur);
            const inEl  = document.getElementById('hs-sl-' + idx);

            outEl.className = 'hs-slide hs-out' + (rev ? ' hs-rev' : '');
            inEl.className  = 'hs-slide hs-in'  + (rev ? ' hs-rev' : '');

            this.cur = idx;
            this._updateUI();
            this._resetProg();
            this._resetTimer();

            setTimeout(() => {
                outEl.className = 'hs-slide';
                inEl.className  = 'hs-slide hs-idle';
                this.busy = false;
            }, 860);
        },

        next() { this.goTo((this.cur + 1) % this.slides.length,  1); },
        prev() { this.goTo((this.cur - 1 + this.slides.length) % this.slides.length, -1); },

        _updateUI() {
            const n = this.slides.length;
            for (let i = 0; i < n; i++) {
                const on = i === this.cur;
                document.getElementById('hs-dot-' + i)?.classList.toggle('hs-don', on);
                document.getElementById('hs-th-'  + i)?.classList.toggle('hs-ton', on);
                document.getElementById('hs-thm-' + i)?.classList.toggle('hs-ton', on);
            }
        },

        _startProg() {
            const bar = document.getElementById('heroProgress');
            if (!bar) return;
            bar.style.setProperty('--hs-dur', this.dur + 's');
            bar.classList.remove('go');
            bar.style.transition = 'none';
            bar.style.width = '0%';
            void bar.offsetWidth;
            bar.classList.add('go');
        },
        _resetProg() { this._startProg(); },

        _startTimer() {
            this._timer = setTimeout(() => { this.next(); }, (this.dur + 0.5) * 1000);
        },
        _resetTimer() {
            clearTimeout(this._timer);
            this._startTimer();
        },
    };
}
</script><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/partials/coverflow-hero.blade.php ENDPATH**/ ?>