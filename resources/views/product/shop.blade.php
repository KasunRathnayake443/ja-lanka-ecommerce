@extends('layouts.desktop')

@section('title', 'Shop - All Products')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap');

:root {
  --white:        #FFFFFF;
  --off-white:    #F9F8F6;
  --surface:      #F3F1ED;
  --surface-2:    #EAE7E1;
  --border:       #E2DDD5;
  --border-soft:  #EEECE8;
  --ink:          #1A1814;
  --ink-mid:      #3D3830;
  --ash:          #8C857A;
  --ash-light:    #B5AFA7;
  --spice:        #C0392B;
  --amber:        #B8860B;
  --amber-light:  #D4A017;
  --amber-tint:   #FDF8EE;
  --font-display: 'Cormorant Garamond', Georgia, serif;
  --font-body:    'Plus Jakarta Sans', sans-serif;
  --ease-out:     cubic-bezier(0.16, 1, 0.3, 1);
  --shadow-card:  0 2px 16px rgba(26,24,20,0.07);
  --shadow-hover: 0 12px 40px rgba(26,24,20,0.13);
}

*, *::before, *::after { box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--off-white); color: var(--ink-mid); -webkit-font-smoothing: antialiased; }

/* ── Page header ── */
.shop-header {
  background: var(--white);
  border-bottom: 1px solid var(--border-soft);
  padding: 2.5rem 0 2rem;
}
.shop-header-inner {
  max-width: 1320px; margin: 0 auto; padding: 0 5vw;
  display: flex; align-items: flex-end; justify-content: space-between;
}
.shop-eyebrow {
  font-size: 0.65rem; font-weight: 600; letter-spacing: 0.22em;
  text-transform: uppercase; color: var(--amber);
  display: flex; align-items: center; gap: 8px; margin-bottom: 0.4rem;
}
.shop-eyebrow::before { content:''; width:22px; height:1px; background:var(--amber-light); }
.shop-title {
  font-family: var(--font-display); font-size: clamp(1.8rem,3.5vw,2.6rem);
  font-weight: 300; color: var(--ink); line-height: 1.1;
}
.shop-title em { font-style: italic; color: var(--amber); }
.shop-count {
  font-size: 0.75rem; color: var(--ash); letter-spacing: 0.06em;
  font-weight: 400;
}

/* ── Layout ── */
.shop-layout {
  max-width: 1320px; margin: 0 auto; padding: 2.5rem 5vw;
  display: grid; grid-template-columns: 260px 1fr; gap: 2.5rem;
  align-items: start;
}

/* ── Sidebar ── */
.sidebar {
  position: sticky; top: 100px;
  background: var(--white);
  border: 1px solid var(--border-soft);
}
.sidebar-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border-soft);
  display: flex; align-items: center; justify-content: space-between;
}
.sidebar-title {
  font-size: 0.67rem; font-weight: 700; letter-spacing: 0.2em;
  text-transform: uppercase; color: var(--ink);
}
.btn-reset {
  font-size: 0.62rem; font-weight: 600; letter-spacing: 0.12em;
  text-transform: uppercase; color: var(--ash);
  background: none; border: none; cursor: pointer; padding: 0;
  transition: color 0.2s;
}
.btn-reset:hover { color: var(--spice); }

.filter-section {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border-soft);
}
.filter-section:last-child { border-bottom: none; }
.filter-label {
  font-size: 0.62rem; font-weight: 700; letter-spacing: 0.18em;
  text-transform: uppercase; color: var(--ash);
  margin-bottom: 0.9rem; display: block;
}

/* Radio pills */
.radio-group { display: flex; flex-direction: column; gap: 4px; }
.radio-pill { display: flex; align-items: center; }
.radio-pill input[type="radio"] { display: none; }
.radio-pill label {
  display: flex; align-items: center; gap: 7px;
  font-size: 0.82rem; font-weight: 400; color: var(--ink-mid);
  padding: 6px 10px; cursor: pointer; width: 100%;
  transition: background 0.15s, color 0.15s;
  border-radius: 2px;
}
.radio-pill input[type="radio"]:checked + label {
  background: var(--amber-tint); color: var(--amber);
  font-weight: 600;
}
.radio-pill label:hover { background: var(--surface); }

/* Checkbox list */
.check-list { max-height: 150px; overflow-y: auto; display: flex; flex-direction: column; gap: 3px; }
.check-list::-webkit-scrollbar { width: 3px; }
.check-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }
.check-item { display: flex; align-items: center; gap: 8px; }
.check-item input[type="checkbox"] {
  width: 14px; height: 14px; accent-color: var(--amber);
  flex-shrink: 0; cursor: pointer;
}
.check-item label {
  font-size: 0.82rem; color: var(--ink-mid); cursor: pointer;
  transition: color 0.15s;
}
.check-item label:hover { color: var(--ink); }
.check-item input:checked + label { color: var(--amber); font-weight: 500; }

/* Price inputs */
.price-row { display: flex; gap: 8px; align-items: center; }
.price-input {
  flex: 1; border: 1px solid var(--border); background: var(--off-white);
  padding: 8px 10px; font-family: var(--font-body); font-size: 0.82rem;
  color: var(--ink); outline: none; transition: border-color 0.2s;
}
.price-input:focus { border-color: var(--amber); }
.price-input::placeholder { color: var(--ash-light); }
.price-sep { font-size: 0.75rem; color: var(--ash-light); flex-shrink: 0; }

/* Sort select */
.sort-select {
  width: 100%; border: 1px solid var(--border); background: var(--off-white);
  padding: 8px 10px; font-family: var(--font-body); font-size: 0.82rem;
  color: var(--ink); outline: none; cursor: pointer; appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238C857A'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 10px center;
  transition: border-color 0.2s;
}
.sort-select:focus { border-color: var(--amber); }

/* Active filter tags */
#active-tags {
  display: flex; flex-wrap: wrap; gap: 6px;
  padding: 0 1.5rem 1rem; min-height: 0;
}
.filter-tag {
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--amber-tint); border: 1px solid rgba(184,134,11,0.2);
  color: var(--amber); font-size: 0.65rem; font-weight: 600;
  letter-spacing: 0.1em; text-transform: uppercase; padding: 3px 9px;
  cursor: pointer; transition: background 0.15s;
}
.filter-tag:hover { background: #fcefd0; }
.filter-tag span { font-size: 0.8rem; line-height: 1; }

/* ── Products area ── */
.products-topbar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 1.75rem;
}
.topbar-left {
  font-family: var(--font-display); font-size: 1.5rem; font-weight: 300;
  color: var(--ink);
}
.topbar-left em { font-style: italic; color: var(--amber); }
.topbar-count { font-size: 0.75rem; color: var(--ash); margin-top: 2px; }

/* Grid */
#productsGrid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
}

/* Product Card */
.p-card {
  background: var(--white);
  border: 1px solid var(--border-soft);
  overflow: hidden;
  transition: transform 0.35s var(--ease-out), box-shadow 0.35s var(--ease-out);
}
.p-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-hover);
}
.p-card-img {
  position: relative; height: 210px; overflow: hidden; background: var(--surface);
}
.p-card-img img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s var(--ease-out);
}
.p-card:hover .p-card-img img { transform: scale(1.06); }
.badge-pct {
  position: absolute; top: 10px; right: 10px; z-index: 2;
  background: var(--amber-tint); color: var(--amber);
  border: 1px solid rgba(184,134,11,0.2);
  font-size: 0.6rem; font-weight: 700; padding: 3px 8px;
}
.badge-sale-strip {
  position: absolute; top: 10px; left: 10px; z-index: 2;
  background: var(--spice); color: #fff;
  font-size: 0.58rem; font-weight: 700; letter-spacing: 0.1em;
  text-transform: uppercase; padding: 3px 8px;
}
.p-card-body { padding: 1.1rem; }
.p-card-origin {
  font-size: 0.62rem; color: var(--ash-light); letter-spacing: 0.07em;
  text-transform: uppercase; margin-bottom: 0.3rem;
}
.p-card-name {
  font-family: var(--font-display); font-size: 1rem; font-weight: 400;
  color: var(--ink); line-height: 1.35;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
  overflow: hidden; min-height: 2.7em;
}
.p-card-prices {
  margin-top: 0.6rem; display: flex; align-items: baseline; gap: 7px;
}
.p-price-main {
  font-family: var(--font-display); font-size: 1.15rem;
  font-weight: 600; color: var(--ink);
}
.p-price-sale { color: var(--spice); }
.p-price-old { font-size: 0.8rem; color: var(--ash-light); text-decoration: line-through; }
.btn-atc {
  margin-top: 0.9rem; width: 100%; padding: 9px;
  background: transparent; border: 1.5px solid var(--border);
  cursor: pointer; font-family: var(--font-body); font-size: 0.67rem;
  font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
  color: var(--ink-mid);
  transition: background 0.25s, border-color 0.25s, color 0.25s;
}
.btn-atc:hover { background: var(--ink); border-color: var(--ink); color: var(--white); }

/* Empty / loading states */
.state-box {
  grid-column: 1/-1; text-align: center; padding: 5rem 0;
}
.state-box .loader {
  width: 34px; height: 34px;
  border: 2px solid var(--border); border-top-color: var(--amber);
  border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 1rem;
}
.state-box p { font-size: 0.85rem; color: var(--ash); }
@keyframes spin { to { transform: rotate(360deg); } }

/* Load more / end strip */
.load-more-strip {
  margin-top: 2.5rem; text-align: center;
  padding: 1.5rem 0; border-top: 1px solid var(--border-soft);
  font-size: 0.75rem; color: var(--ash-light); letter-spacing: 0.1em; text-transform: uppercase;
}
.load-spinner {
  display: inline-flex; align-items: center; gap: 10px; color: var(--ash);
}
.load-spinner .mini-spin {
  width: 18px; height: 18px;
  border: 1.5px solid var(--border); border-top-color: var(--amber);
  border-radius: 50%; animation: spin 0.7s linear infinite;
}

/* Toast */
.gfm-toast {
  position: fixed; bottom: 2rem; right: 2rem;
  background: var(--ink); color: var(--white);
  padding: 12px 20px; font-size: 0.78rem; font-weight: 500;
  border-left: 3px solid var(--amber-light); z-index: 99999;
  transform: translateY(10px); opacity: 0;
  transition: opacity 0.3s, transform 0.3s;
  box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}
.gfm-toast.show { opacity: 1; transform: translateY(0); }
.gfm-toast.error { border-left-color: var(--spice); }

/* Responsive */
@media (max-width: 1100px) { #productsGrid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 860px)  { .shop-layout { grid-template-columns: 1fr; } .sidebar { position: static; } }
@media (max-width: 540px)  { #productsGrid { grid-template-columns: repeat(2,1fr); gap: 0.9rem; } }
</style>

<!-- Page Header -->
<div class="shop-header">
  <div class="shop-header-inner">
    <div>
      <div class="shop-eyebrow">Global Flavors Mart</div>
      <h1 class="shop-title">All <em>Products</em></h1>
    </div>
    <div id="productsCount" class="shop-count">Loading…</div>
  </div>
</div>

<div class="shop-layout">

  <!-- ─── Sidebar ─── -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <span class="sidebar-title">Filters</span>
      <button class="btn-reset" id="resetFilters">Clear All</button>
    </div>

    <!-- Active filter tags -->
    <div id="active-tags"></div>

    <!-- Product Type -->
    <div class="filter-section">
      <span class="filter-label">Product Type</span>
      <div class="radio-group">
        <div class="radio-pill">
          <input type="radio" name="type" id="type-all" value="" checked class="filter-radio">
          <label for="type-all">All Products</label>
        </div>
        <div class="radio-pill">
          <input type="radio" name="type" id="type-food" value="food" class="filter-radio">
          <label for="type-food">🍜 Food Items</label>
        </div>
        <div class="radio-pill">
          <input type="radio" name="type" id="type-appliance" value="appliance" class="filter-radio">
          <label for="type-appliance">🔌 Appliances</label>
        </div>
      </div>
    </div>

    <!-- Categories -->
    <div class="filter-section">
      <span class="filter-label">Category</span>
      <div id="categories-filter" class="check-list">
        <div class="state-mini">Loading…</div>
      </div>
    </div>

    <!-- Brands -->
    <div class="filter-section">
      <span class="filter-label">Brand</span>
      <div id="brands-filter" class="check-list">
        <div class="state-mini">Loading…</div>
      </div>
    </div>

    <!-- Origins -->
    <div class="filter-section">
      <span class="filter-label">Country of Origin</span>
      <div id="origins-filter" class="check-list">
        <div class="state-mini">Loading…</div>
      </div>
    </div>

    <!-- Price Range -->
    <div class="filter-section">
      <span class="filter-label">Price Range (LKR)</span>
      <div class="price-row">
        <input type="number" id="minPrice" placeholder="Min" class="price-input">
        <span class="price-sep">–</span>
        <input type="number" id="maxPrice" placeholder="Max" class="price-input">
      </div>
    </div>

    <!-- Sort -->
    <div class="filter-section">
      <span class="filter-label">Sort By</span>
      <select id="sortBy" class="sort-select">
        <option value="newest">Newest First</option>
        <option value="price_low">Price: Low to High</option>
        <option value="price_high">Price: High to Low</option>
        <option value="oldest">Oldest First</option>
      </select>
    </div>
  </aside>

  <!-- ─── Products ─── -->
  <div>
    <div class="products-topbar">
      <div>
        <div class="topbar-left">The <em>Collection</em></div>
        <div class="topbar-count" id="topbarCount">Loading products…</div>
      </div>
    </div>

    <div id="productsGrid">
      <div class="state-box">
        <div class="loader"></div>
        <p>Loading products…</p>
      </div>
    </div>

    <div id="loadMoreStrip" class="load-more-strip hidden">
      <div class="load-spinner">
        <div class="mini-spin"></div>
        Loading more
      </div>
    </div>
    <div id="noMoreProducts" class="load-more-strip hidden">You've seen it all</div>
  </div>

</div>

<!-- Toast -->
<div id="gfm-toast" class="gfm-toast"></div>

<script>
/* ─── State ─── */
let currentPage = 1, isLoading = false, hasMore = true;
let currentFilters = { type:'', categories:[], brands:[], origins:[], min_price:'', max_price:'', sort:'newest' };

/* ─── Init ─── */
loadFilters();
loadProducts();
applyUrlFilters();

/* ─── URL filters ─── */
function applyUrlFilters() {
  const p = new URLSearchParams(window.location.search);
  if (p.get('type'))     { currentFilters.type = p.get('type'); }
  if (p.get('sort'))     { currentFilters.sort = p.get('sort'); document.getElementById('sortBy').value = p.get('sort'); }
  if (p.get('has_sale')) { /* handled server-side */ }
}

/* ─── Filters ─── */
async function loadFilters() {
  try {
    const data = await fetch('/api/products/filters').then(r=>r.json());

    document.getElementById('categories-filter').innerHTML =
      data.categories.length
        ? data.categories.map(c=>`
          <div class="check-item">
            <input type="checkbox" id="cat-${c.id}" value="${c.id}" class="category-filter">
            <label for="cat-${c.id}">${escHtml(c.name)}</label>
          </div>`).join('')
        : '<p style="font-size:0.78rem;color:var(--ash-light)">None available</p>';

    document.getElementById('brands-filter').innerHTML =
      data.brands.length
        ? data.brands.map(b=>`
          <div class="check-item">
            <input type="checkbox" id="br-${b.id}" value="${b.id}" class="brand-filter">
            <label for="br-${b.id}">${escHtml(b.name)}</label>
          </div>`).join('')
        : '<p style="font-size:0.78rem;color:var(--ash-light)">None available</p>';

    document.getElementById('origins-filter').innerHTML =
      data.origins.length
        ? data.origins.map(o=>`
          <div class="check-item">
            <input type="checkbox" id="or-${o.id}" value="${o.id}" class="origin-filter">
            <label for="or-${o.id}">${o.flag_icon||'🌏'} ${escHtml(o.country_name)}</label>
          </div>`).join('')
        : '<p style="font-size:0.78rem;color:var(--ash-light)">None available</p>';

    attachListeners();
  } catch(e) { console.error(e); }
}

function attachListeners() {
  document.querySelectorAll('.filter-radio').forEach(r => r.addEventListener('change', ()=>{
    if (r.checked) { currentFilters.type = r.value; resetAndLoad(); renderTags(); }
  }));
  document.querySelectorAll('.category-filter').forEach(cb => cb.addEventListener('change', ()=>{
    currentFilters.categories = [...document.querySelectorAll('.category-filter:checked')].map(c=>c.value);
    resetAndLoad(); renderTags();
  }));
  document.querySelectorAll('.brand-filter').forEach(cb => cb.addEventListener('change', ()=>{
    currentFilters.brands = [...document.querySelectorAll('.brand-filter:checked')].map(c=>c.value);
    resetAndLoad(); renderTags();
  }));
  document.querySelectorAll('.origin-filter').forEach(cb => cb.addEventListener('change', ()=>{
    currentFilters.origins = [...document.querySelectorAll('.origin-filter:checked')].map(c=>c.value);
    resetAndLoad(); renderTags();
  }));
  document.getElementById('minPrice').addEventListener('input', function() {
    currentFilters.min_price = this.value; resetAndLoad(); renderTags();
  });
  document.getElementById('maxPrice').addEventListener('input', function() {
    currentFilters.max_price = this.value; resetAndLoad(); renderTags();
  });
  document.getElementById('sortBy').addEventListener('change', function() {
    currentFilters.sort = this.value; resetAndLoad();
  });
  document.getElementById('resetFilters').addEventListener('click', resetAllFilters);
}

/* ─── Active filter tags ─── */
function renderTags() {
  const wrap = document.getElementById('active-tags');
  const tags = [];
  if (currentFilters.type) tags.push({ label: currentFilters.type, clear: ()=>{ currentFilters.type=''; document.querySelector('.filter-radio[value=""]').checked=true; }});
  if (currentFilters.min_price) tags.push({ label: `Min LKR ${Number(currentFilters.min_price).toLocaleString()}`, clear:()=>{ currentFilters.min_price=''; document.getElementById('minPrice').value=''; }});
  if (currentFilters.max_price) tags.push({ label: `Max LKR ${Number(currentFilters.max_price).toLocaleString()}`, clear:()=>{ currentFilters.max_price=''; document.getElementById('maxPrice').value=''; }});
  document.querySelectorAll('.category-filter:checked').forEach(c => tags.push({ label: c.nextElementSibling?.textContent||'', clear:()=>{ c.checked=false; currentFilters.categories=currentFilters.categories.filter(v=>v!==c.value); }}));
  document.querySelectorAll('.brand-filter:checked').forEach(c => tags.push({ label: c.nextElementSibling?.textContent||'', clear:()=>{ c.checked=false; currentFilters.brands=currentFilters.brands.filter(v=>v!==c.value); }}));
  document.querySelectorAll('.origin-filter:checked').forEach(c => tags.push({ label: c.nextElementSibling?.textContent||'', clear:()=>{ c.checked=false; currentFilters.origins=currentFilters.origins.filter(v=>v!==c.value); }}));

  if (!tags.length) { wrap.innerHTML = ''; return; }
  wrap.innerHTML = tags.map((_,i)=>`<div class="filter-tag" data-idx="${i}">${_.label} <span>×</span></div>`).join('');
  wrap.querySelectorAll('.filter-tag').forEach((el, i) => {
    el.addEventListener('click', ()=>{ tags[i].clear(); resetAndLoad(); renderTags(); });
  });
}

function resetAllFilters() {
  document.querySelectorAll('.filter-radio').forEach(r => r.checked = (r.value === ''));
  document.querySelectorAll('.category-filter,.brand-filter,.origin-filter').forEach(c => c.checked = false);
  document.getElementById('minPrice').value = '';
  document.getElementById('maxPrice').value = '';
  document.getElementById('sortBy').value = 'newest';
  currentFilters = { type:'', categories:[], brands:[], origins:[], min_price:'', max_price:'', sort:'newest' };
  document.getElementById('active-tags').innerHTML = '';
  resetAndLoad();
}

function resetAndLoad() {
  currentPage = 1; hasMore = true;
  document.getElementById('productsGrid').innerHTML = `<div class="state-box"><div class="loader"></div><p>Loading…</p></div>`;
  document.getElementById('noMoreProducts').classList.add('hidden');
  loadProducts();
}

/* ─── Load products ─── */
async function loadProducts() {
  if (isLoading || !hasMore) return;
  isLoading = true;
  const pageToLoad = currentPage;

  if (pageToLoad > 1) {
    document.getElementById('loadMoreStrip').classList.remove('hidden');
  }

  try {
    const params = new URLSearchParams();
    params.append('page', pageToLoad);
    params.append('sort', currentFilters.sort);
    if (currentFilters.type)      params.append('type', currentFilters.type);
    if (currentFilters.min_price) params.append('min_price', currentFilters.min_price);
    if (currentFilters.max_price) params.append('max_price', currentFilters.max_price);
    currentFilters.categories.forEach(id => params.append('categories[]', id));
    currentFilters.brands.forEach(id     => params.append('brands[]', id));
    currentFilters.origins.forEach(id    => params.append('origins[]', id));

    const data = await fetch(`/api/products?${params}`).then(r=>r.json());

    if (pageToLoad === 1) document.getElementById('productsGrid').innerHTML = '';
    renderProducts(data.data, pageToLoad);

    hasMore = data.current_page < data.last_page;
    currentPage++;

    const from = data.from || 0, to = data.to || 0, total = data.total || 0;
    const countText = total ? `Showing ${from}–${to} of ${total} products` : '0 products found';
    document.getElementById('productsCount').textContent = countText;
    document.getElementById('topbarCount').textContent   = countText;

    if (!hasMore) document.getElementById('noMoreProducts').classList.remove('hidden');

  } catch(e) {
    console.error(e);
    document.getElementById('productsGrid').innerHTML =
      `<div class="state-box"><p style="color:var(--spice)">Failed to load products. Please refresh.</p></div>`;
  } finally {
    isLoading = false;
    document.getElementById('loadMoreStrip').classList.add('hidden');
  }
}

function renderProducts(products, pageToLoad) {
  const grid = document.getElementById('productsGrid');

  if (!products.length && pageToLoad === 1) {
    grid.innerHTML = `<div class="state-box"><p>No products match your filters.</p></div>`;
    return;
  }

  products.forEach(p => {
    const img = p.images?.[0]?.image_path ? `/storage/${p.images[0].image_path}` : '/images/placeholder.jpg';
    const hasSale = p.sale_price && parseFloat(p.sale_price) < parseFloat(p.regular_price);
    const disc = p.discount_percent > 0 ? p.discount_percent
      : (hasSale ? Math.round((p.regular_price - p.sale_price)/p.regular_price*100) : 0);

    const originHtml = p.origin
      ? `<div class="p-card-origin">${p.origin.flag_icon||'🌏'} ${escHtml(p.origin.country_name)}</div>` : '';

    const priceHtml = hasSale
      ? `<span class="p-price-main p-price-sale">LKR ${parseFloat(p.sale_price).toLocaleString()}</span>
         <span class="p-price-old">LKR ${parseFloat(p.regular_price).toLocaleString()}</span>`
      : `<span class="p-price-main">LKR ${parseFloat(p.regular_price).toLocaleString()}</span>`;

    const saleBadge = hasSale ? `<span class="badge-sale-strip">Sale</span>` : '';
    const pctBadge  = disc > 0 ? `<span class="badge-pct">-${disc}%</span>` : '';

    const card = document.createElement('div');
    card.className = 'p-card';
    card.innerHTML = `
      <a href="/product/${p.slug}" style="text-decoration:none">
        <div class="p-card-img">
          ${saleBadge}${pctBadge}
          <img src="${img}" alt="${escHtml(p.name)}" loading="lazy" onerror="this.src='/images/placeholder.jpg'">
        </div>
        <div class="p-card-body">
          ${originHtml}
          <div class="p-card-name">${escHtml(p.name)}</div>
          <div class="p-card-prices">${priceHtml}</div>
          <button class="btn-atc" onclick="event.preventDefault();addToCart(${p.id})">Add to Cart</button>
        </div>
      </a>`;
    grid.appendChild(card);
  });
}

function escHtml(t) {
  if (!t) return '';
  const d = document.createElement('div'); d.textContent = t; return d.innerHTML;
}

/* ─── Cart ─── */
async function addToCart(id) {
  try {
    const data = await fetch('/api/cart/add', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
      body: JSON.stringify({ product_id:id, quantity:1 })
    }).then(r=>r.json());
    if (data.success) { updateCartCount(data.cart_count); showToast('Added to cart'); }
  } catch(e) { showToast('Error adding to cart', true); }
}
function updateCartCount(n) {
  const el = document.getElementById('cartCount');
  if (!el) return;
  n > 0 ? (el.textContent=n, el.classList.remove('hidden')) : el.classList.add('hidden');
}
function showToast(msg, err=false) {
  const t = document.getElementById('gfm-toast');
  t.textContent = msg;
  t.className = 'gfm-toast'+(err?' error':'');
  requestAnimationFrame(()=>t.classList.add('show'));
  setTimeout(()=>t.classList.remove('show'), 3200);
}

/* ─── Infinite scroll ─── */
window.addEventListener('scroll', ()=>{
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 600) loadProducts();
}, { passive:true });
</script>

@endsection