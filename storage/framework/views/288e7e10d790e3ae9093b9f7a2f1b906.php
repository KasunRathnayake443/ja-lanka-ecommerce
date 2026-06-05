

<?php $__env->startSection('title', 'Sale Items - Ja Lanka'); ?>

<?php $__env->startSection('content'); ?>

<!-- Page Header -->
<div class="bg-white border-b border-gray-100 py-10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-10">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <div class="text-[0.72rem] font-semibold tracking-[0.14em] uppercase text-red-700 mb-1">Limited Time</div>
                <h1 class="font-['Cormorant_Garamond'] text-3xl md:text-4xl font-light text-gray-900">
                    <em class="italic text-red-700 not-italic">Sale</em> Collection
                </h1>
            </div>
            <div id="productsCount" class="text-xs text-gray-400 tracking-wide">Loading…</div>
        </div>
    </div>
</div>

<div class="max-w-[1400px] mx-auto px-5 md:px-10 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8">

        <!-- Sidebar Filters -->
        <aside class="lg:sticky lg:top-24 h-fit">
            <div class="bg-white border border-gray-100 rounded overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
                    <span class="text-[0.67rem] font-bold tracking-[0.2em] uppercase text-gray-900">Filters</span>
                    <button id="resetFilters" class="text-[0.62rem] font-semibold tracking-[0.12em] uppercase text-gray-400 hover:text-red-700 transition">Clear All</button>
                </div>

                <!-- Active Filter Tags -->
                <div id="active-tags" class="flex flex-wrap gap-1.5 p-5 border-b border-gray-100 min-h-[50px]"></div>

                <!-- Product Type -->
                <div class="p-5 border-b border-gray-100">
                    <div class="text-[0.62rem] font-bold tracking-[0.18em] uppercase text-gray-400 mb-3">Product Type</div>
                    <div class="space-y-1">
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type-all" value="" checked class="hidden peer/type-all">
                            <label for="type-all" class="flex items-center gap-2 text-sm text-gray-800 px-2 py-1.5 cursor-pointer w-full rounded hover:bg-gray-50 peer-checked/type-all:bg-amber-50 peer-checked/type-all:text-red-700 peer-checked/type-all:font-semibold">All Sale Items</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type-food" value="food" class="hidden peer/type-food">
                            <label for="type-food" class="flex items-center gap-2 text-sm text-gray-800 px-2 py-1.5 cursor-pointer w-full rounded hover:bg-gray-50 peer-checked/type-food:bg-amber-50 peer-checked/type-food:text-red-700 peer-checked/type-food:font-semibold">🍜 Food Items</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type-appliance" value="appliance" class="hidden peer/type-appliance">
                            <label for="type-appliance" class="flex items-center gap-2 text-sm text-gray-800 px-2 py-1.5 cursor-pointer w-full rounded hover:bg-gray-50 peer-checked/type-appliance:bg-amber-50 peer-checked/type-appliance:text-red-700 peer-checked/type-appliance:font-semibold">🔌 Appliances</label>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="p-5 border-b border-gray-100">
                    <div class="text-[0.62rem] font-bold tracking-[0.18em] uppercase text-gray-400 mb-3">Category</div>
                    <div id="categories-filter" class="max-h-48 overflow-y-auto space-y-1 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-gray-200"></div>
                </div>

                <!-- Brands -->
                <div class="p-5 border-b border-gray-100">
                    <div class="text-[0.62rem] font-bold tracking-[0.18em] uppercase text-gray-400 mb-3">Brand</div>
                    <div id="brands-filter" class="max-h-48 overflow-y-auto space-y-1 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-gray-200"></div>
                </div>

                <!-- Origins -->
                <div class="p-5 border-b border-gray-100">
                    <div class="text-[0.62rem] font-bold tracking-[0.18em] uppercase text-gray-400 mb-3">Country of Origin</div>
                    <div id="origins-filter" class="max-h-48 overflow-y-auto space-y-1 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-gray-200"></div>
                </div>

                <!-- Price Range -->
                <div class="p-5 border-b border-gray-100">
                    <div class="text-[0.62rem] font-bold tracking-[0.18em] uppercase text-gray-400 mb-3">Price Range (LKR)</div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <div class="text-[0.65rem] text-gray-400 uppercase tracking-wide mb-1">Min</div>
                                <input type="number" id="minPrice" placeholder="0" class="w-full border border-gray-100 bg-gray-50 px-3 py-2 text-sm text-gray-900 rounded focus:border-red-700 focus:outline-none">
                            </div>
                            <span class="text-gray-300 self-end mb-2">—</span>
                            <div class="flex-1">
                                <div class="text-[0.65rem] text-gray-400 uppercase tracking-wide mb-1">Max</div>
                                <input type="number" id="maxPrice" placeholder="Any" class="w-full border border-gray-100 bg-gray-50 px-3 py-2 text-sm text-gray-900 rounded focus:border-red-700 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sort -->
                <div class="p-5">
                    <div class="text-[0.62rem] font-bold tracking-[0.18em] uppercase text-gray-400 mb-3">Sort By</div>
                    <select id="sortBy" class="w-full border border-gray-100 bg-gray-50 px-3 py-2 text-sm text-gray-900 rounded focus:border-red-700 focus:outline-none appearance-none bg-[url('data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2210%22%20height%3D%226%22%3E%3Cpath%20d%3D%22M0%200l5%206%205-6z%22%20fill%3D%22%238C857A%22%2F%3E%3C%2Fsvg%3E')] bg-[right:10px_center] bg-no-repeat">
                        <option value="discount_desc">Biggest Discount First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
            </div>
        </aside>

        <!-- Products Area -->
        <div>
            <div class="flex flex-wrap items-center justify-between gap-4 mb-7">
                <div class="font-['Cormorant_Garamond'] text-2xl font-light text-gray-900">
                    The <em class="italic text-red-700 not-italic">Sale</em> Collection
                </div>
                <div id="topbarCount" class="text-xs text-gray-400">Loading sale items…</div>
            </div>

            <div id="productsGrid" class="grid grid-cols-2 md:grid-cols-3 gap-5">
                <div class="col-span-full text-center py-20">
                    <div class="w-8 h-8 border-2 border-gray-100 border-t-red-700 rounded-full animate-spin mx-auto mb-4"></div>
                    <p class="text-sm text-gray-400">Loading sale items…</p>
                </div>
            </div>

            <div id="loadMoreStrip" class="hidden text-center py-6 border-t border-gray-100 mt-6">
                <div class="inline-flex items-center gap-2 text-xs text-gray-400 uppercase tracking-wider">
                    <div class="w-4 h-4 border border-gray-200 border-t-red-700 rounded-full animate-spin"></div>
                    Loading more
                </div>
            </div>
            <div id="noMoreProducts" class="hidden text-center py-6 text-xs text-gray-400 uppercase tracking-wider">You've seen it all</div>
        </div>

    </div>
</div>

<script>
/* ─── State ─── */
let currentPage = 1, isLoading = false, hasMore = true;
let currentFilters = { type:'', categories:[], brands:[], origins:[], min_price:'', max_price:'', sort:'discount_desc' };

/* ─── Init ─── */
loadFilters();
loadProducts();

/* ─── Filters ─── */
async function loadFilters() {
  try {
    const data = await fetch('/api/products/filters').then(r=>r.json());

    document.getElementById('categories-filter').innerHTML = data.categories.length
      ? data.categories.map(c=>`
        <div class="check-item flex items-center gap-2">
          <input type="checkbox" id="cat-${c.id}" value="${c.id}" class="category-filter w-3.5 h-3.5 accent-amber-600">
          <label for="cat-${c.id}" class="text-sm text-gray-700 cursor-pointer hover:text-gray-900">${escapeHtml(c.name)}</label>
        </div>`).join('')
      : '<div class="text-xs text-gray-300 py-1">No categories</div>';

    document.getElementById('brands-filter').innerHTML = data.brands.length
      ? data.brands.map(b=>`
        <div class="check-item flex items-center gap-2">
          <input type="checkbox" id="br-${b.id}" value="${b.id}" class="brand-filter w-3.5 h-3.5 accent-amber-600">
          <label for="br-${b.id}" class="text-sm text-gray-700 cursor-pointer hover:text-gray-900">${escapeHtml(b.name)}</label>
        </div>`).join('')
      : '<div class="text-xs text-gray-300 py-1">No brands</div>';

    document.getElementById('origins-filter').innerHTML = data.origins.length
      ? data.origins.map(o=>`
        <div class="check-item flex items-center gap-2">
          <input type="checkbox" id="or-${o.id}" value="${o.id}" class="origin-filter w-3.5 h-3.5 accent-amber-600">
          <label for="or-${o.id}" class="text-sm text-gray-700 cursor-pointer hover:text-gray-900">${o.flag_icon||'🌏'} ${escapeHtml(o.country_name)}</label>
        </div>`).join('')
      : '<div class="text-xs text-gray-300 py-1">No origins</div>';

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

function renderTags() {
  const wrap = document.getElementById('active-tags');
  const tags = [];
  if (currentFilters.type) tags.push({ label: currentFilters.type === 'food' ? 'Food' : (currentFilters.type === 'appliance' ? 'Appliances' : currentFilters.type), clear: ()=>{ currentFilters.type=''; document.querySelector('.filter-radio[value=""]').checked=true; }});
  if (currentFilters.min_price) tags.push({ label: `Min LKR ${Number(currentFilters.min_price).toLocaleString()}`, clear:()=>{ currentFilters.min_price=''; document.getElementById('minPrice').value=''; }});
  if (currentFilters.max_price) tags.push({ label: `Max LKR ${Number(currentFilters.max_price).toLocaleString()}`, clear:()=>{ currentFilters.max_price=''; document.getElementById('maxPrice').value=''; }});
  document.querySelectorAll('.category-filter:checked').forEach(c => tags.push({ label: c.nextElementSibling?.textContent||'', clear:()=>{ c.checked=false; currentFilters.categories=currentFilters.categories.filter(v=>v!==c.value); }}));
  document.querySelectorAll('.brand-filter:checked').forEach(c => tags.push({ label: c.nextElementSibling?.textContent||'', clear:()=>{ c.checked=false; currentFilters.brands=currentFilters.brands.filter(v=>v!==c.value); }}));
  document.querySelectorAll('.origin-filter:checked').forEach(c => tags.push({ label: c.nextElementSibling?.textContent||'', clear:()=>{ c.checked=false; currentFilters.origins=currentFilters.origins.filter(v=>v!==c.value); }}));

  if (!tags.length) { wrap.innerHTML = ''; return; }
  wrap.innerHTML = tags.map((t,i)=>`<div class="filter-tag inline-flex items-center gap-1 bg-amber-50 border border-amber-200 text-red-700 text-[0.62rem] font-semibold tracking-wide uppercase px-2 py-1 rounded cursor-pointer hover:bg-amber-100" data-idx="${i}">${t.label} <span class="text-sm">×</span></div>`).join('');
  wrap.querySelectorAll('.filter-tag').forEach((el, i) => {
    el.addEventListener('click', ()=>{ tags[i].clear(); resetAndLoad(); renderTags(); });
  });
}

function resetAllFilters() {
  document.querySelectorAll('.filter-radio').forEach(r => r.checked = (r.value === ''));
  document.querySelectorAll('.category-filter,.brand-filter,.origin-filter').forEach(c => c.checked = false);
  document.getElementById('minPrice').value = '';
  document.getElementById('maxPrice').value = '';
  document.getElementById('sortBy').value = 'discount_desc';
  currentFilters = { type:'', categories:[], brands:[], origins:[], min_price:'', max_price:'', sort:'discount_desc' };
  renderTags();
  resetAndLoad();
}

function resetAndLoad() {
  currentPage = 1; hasMore = true;
  document.getElementById('productsGrid').innerHTML = `<div class="col-span-full text-center py-20"><div class="w-8 h-8 border-2 border-gray-100 border-t-red-700 rounded-full animate-spin mx-auto mb-4"></div><p class="text-sm text-gray-400">Loading sale items…</p></div>`;
  document.getElementById('noMoreProducts').classList.add('hidden');
  loadProducts();
}

/* ─── Load products using flash-sales API ─── */
async function loadProducts() {
    if (isLoading || !hasMore) return;
    isLoading = true;
    const pageToLoad = currentPage;

    if (pageToLoad > 1) {
        document.getElementById('loadMoreStrip').classList.remove('hidden');
    }

    try {
        // Use flash-sales endpoint which works
        const response = await fetch('/api/flash-sales');
        const data = await response.json();
        
        // Transform the data to product format
        const products = data.map(item => ({
            id: item.product.id,
            name: item.product.name,
            slug: item.product.slug,
            regular_price: item.product.regular_price,
            sale_price: item.product.sale_price,
            discount_percent: item.product.discount_percent || Math.round((item.product.regular_price - item.product.sale_price) / item.product.regular_price * 100),
            images: item.product.images,
            origin: item.product.origin
        }));

        if (pageToLoad === 1) {
            document.getElementById('productsGrid').innerHTML = '';
        }
        
        renderProducts(products, pageToLoad);

        // Since flash-sales returns all at once, set hasMore to false after first load
        hasMore = false;
        currentPage++;

        const countText = products.length > 0 ? `Showing ${products.length} sale items` : '0 sale items found';
        document.getElementById('productsCount').textContent = countText;
        document.getElementById('topbarCount').textContent = countText;

        if (!hasMore) {
            document.getElementById('noMoreProducts').classList.remove('hidden');
        }

    } catch(e) {
        console.error(e);
        document.getElementById('productsGrid').innerHTML = `<div class="col-span-full text-center py-20"><p class="text-sm text-red-600">Failed to load sale items. Please refresh.</p></div>`;
    } finally {
        isLoading = false;
        document.getElementById('loadMoreStrip').classList.add('hidden');
    }
}


function renderProducts(products, pageToLoad) {
  const grid = document.getElementById('productsGrid');

  if (!products.length && pageToLoad === 1) {
    grid.innerHTML = `<div class="col-span-full text-center py-20"><p class="text-sm text-gray-400">No sale items match your filters.</p></div>`;
    return;
  }

  products.forEach(p => {
    const img = p.images?.[0]?.image_path ? `/storage/${p.images[0].image_path}` : '/images/placeholder.jpg';
    const hasSale = p.sale_price && parseFloat(p.sale_price) < parseFloat(p.regular_price);
    const disc = p.discount_percent > 0 ? p.discount_percent
      : (hasSale ? Math.round((p.regular_price - p.sale_price)/p.regular_price*100) : 0);

    const originHtml = p.origin
      ? `<div class="text-[0.72rem] text-gray-400 uppercase tracking-wide mb-1">${p.origin.flag_icon||'🌏'} ${escapeHtml(p.origin.country_name)}</div>` : '';

    const priceHtml = hasSale
      ? `<span class="text-base font-bold text-red-700">LKR ${parseFloat(p.sale_price).toLocaleString()}</span>
         <span class="text-xs text-gray-400 line-through ml-2">LKR ${parseFloat(p.regular_price).toLocaleString()}</span>`
      : `<span class="text-base font-bold text-gray-900">LKR ${parseFloat(p.regular_price).toLocaleString()}</span>`;

    const saleBadge = hasSale ? `<span class="absolute top-2 right-2 bg-red-700 text-white text-[0.68rem] font-bold px-2 py-0.5 rounded">-${disc}%</span>` : '';

    const card = document.createElement('div');
    card.className = 'product-card bg-white rounded-lg overflow-hidden shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col';
    card.innerHTML = `
      <a href="/product/${p.slug}" class="block flex-1">
        <div class="product-img-wrap relative h-56 overflow-hidden bg-gray-50">
          ${saleBadge}
          <img src="${img}" alt="${escapeHtml(p.name)}" class="product-img w-full h-full object-cover transition-transform duration-500 hover:scale-105" loading="lazy" onerror="this.src='/images/placeholder.jpg'">
        </div>
        <div class="p-4 flex-1 flex flex-col">
          ${originHtml}
          <div class="product-name text-sm font-semibold text-gray-900 mb-2 line-clamp-2">${escapeHtml(p.name)}</div>
          <div class="product-prices flex items-center gap-2 flex-wrap">${priceHtml}</div>
        </div>
      </a>
      <button class="add-to-cart-btn mx-4 mb-4 py-2 bg-gray-900 hover:bg-red-700 text-white text-xs font-semibold tracking-wide uppercase rounded transition" onclick="event.preventDefault();addToCart(${p.id})">Add to Cart</button>`;
    grid.appendChild(card);
  });
}

function escapeHtml(t) {
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
    if (data.success) { updateCartCount(data.cart_count); showNotification('Added to cart', 'success'); }
  } catch(e) { showNotification('Error adding to cart', 'error'); }
}

function updateCartCount(n) {
  const el = document.getElementById('cartCount');
  if (!el) return;
  n > 0 ? (el.textContent=n, el.classList.remove('hidden')) : el.classList.add('hidden');
}

function showNotification(msg, type = 'success') {
  const n = document.createElement('div');
  n.className = `fixed bottom-6 right-6 px-5 py-3 rounded text-sm font-semibold text-white z-[9999] transition-opacity shadow-lg ${type === 'success' ? 'bg-emerald-700' : 'bg-red-700'}`;
  n.textContent = msg;
  document.body.appendChild(n);
  setTimeout(() => { n.style.opacity = '0'; setTimeout(() => n.remove(), 300); }, 2800);
}

/* ─── Infinite scroll ─── */
window.addEventListener('scroll', ()=>{
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) loadProducts();
}, { passive:true });
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.desktop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ja-lanka-ecommerce\resources\views/product/sale.blade.php ENDPATH**/ ?>