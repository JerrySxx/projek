<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Violet'); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #050505;
            color: #e7e9ea;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(236,72,153,0.07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            right: -5%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139,92,246,0.05) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #252525; }

        .glass {
            background: rgba(10,10,10,0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .border-subtle { border-color: rgba(255,255,255,0.06); }

        .card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .card:hover {
            background: rgba(255,255,255,0.035);
            border-color: rgba(255,255,255,0.08);
        }

        .card-glow {
            position: relative;
            overflow: hidden;
        }
        .card-glow::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(236,72,153,0.3), rgba(139,92,246,0.2), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .card-glow:hover::before { opacity: 1; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 500;
            color: #8b8b8b;
            transition: all 0.2s ease;
            position: relative;
        }
        .nav-item:hover {
            color: #e7e9ea;
            background: rgba(255,255,255,0.04);
        }
        .nav-item.active {
            color: #f472b6;
            background: rgba(236,72,153,0.08);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: linear-gradient(180deg, #ec4899, #a855f7);
            border-radius: 0 4px 4px 0;
        }

        .search-input {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .search-input:focus {
            background: rgba(255,255,255,0.06);
            border-color: rgba(236,72,153,0.4);
            box-shadow: 0 0 0 4px rgba(236,72,153,0.06), 0 0 20px rgba(236,72,153,0.05);
            outline: none;
        }

        .search-dropdown {
            background: rgba(14,14,14,0.97);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 24px 80px rgba(0,0,0,0.6), 0 0 40px rgba(236,72,153,0.03);
        }
        .search-dropdown-item { transition: all 0.15s ease; }
        .search-dropdown-item:hover { background: rgba(236,72,153,0.06); }

        .suggestion-highlight { color: #ec4899; font-weight: 700; }

        .hashtag-link {
            color: #ec4899;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hashtag-link:hover {
            color: #f9a8d4;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .post-image-square {
            aspect-ratio: 1 / 1;
            object-fit: cover !important;
            width: 100%;
            border-radius: 16px;
            background: #0e0e0e;
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), filter 0.3s ease;
        }
        .post-image-square:hover {
            transform: scale(1.01);
            filter: brightness(1.05);
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 13px;
            color: #6b7280;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border: none;
        }
        .action-btn:hover { background: rgba(255,255,255,0.04); }
        .action-btn.liked { color: #ec4899; }
        .action-btn.liked:hover { background: rgba(236,72,153,0.08); }

        main input,
        main textarea,
        main select {
            background-color: rgba(255,255,255,0.04) !important;
            border: 1px solid rgba(255,255,255,0.07) !important;
            color: #e7e9ea !important;
            caret-color: #ec4899;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 14px;
            line-height: 1.6;
            transition: all 0.25s ease;
        }
        main input:focus,
        main textarea:focus,
        main select:focus {
            background-color: rgba(255,255,255,0.06) !important;
            border-color: rgba(236,72,153,0.35) !important;
            box-shadow: 0 0 0 4px rgba(236,72,153,0.06);
            outline: none;
        }
        main input::placeholder,
        main textarea::placeholder { color: #3f3f46 !important; }
        main input:invalid,
        main textarea:invalid { box-shadow: none !important; }

        main .rounded-full {
            object-fit: cover !important;
            width: 44px !important;
            height: 44px !important;
            background: none !important;
        }
        aside img { object-fit: contain !important; }

        .pulse-dot {
            width: 8px; height: 8px;
            background: #22c55e;
            border-radius: 50%;
            position: relative;
        }
        .pulse-dot::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: rgba(34,197,94,0.3);
            animation: pulse-ring 2s ease-out infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(2.2); opacity: 0; }
        }

        .gradient-text {
            background: linear-gradient(135deg, #ec4899, #a855f7, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeSlideUp 0.4s ease forwards; }
    </style>
</head>

<body class="min-h-screen flex relative" style="z-index:1;">

<!-- LEFT SIDEBAR -->
<aside class="w-[272px] h-screen sticky top-0 border-r border-subtle flex flex-col justify-between p-5 shrink-0" style="z-index:20;">
    <div>
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, rgba(236,72,153,0.2), rgba(168,85,247,0.15));">
                <img src="<?php echo e(asset('storage/profiles/logo.jpg')); ?>" alt="Violet" class="w-6 h-6 rounded-lg">
            </div>
            <span class="text-lg font-extrabold tracking-tight gradient-text">Violet</span>
        </div>

        <div class="card card-glow p-4 mb-8 cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" class="w-11 h-11 rounded-full ring-2 ring-pink-500/20">
                    <div class="pulse-dot absolute -bottom-0.5 -right-0.5 ring-2 ring-[#050505]"></div>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="font-semibold text-sm truncate"><?php echo e(Auth::user()->name); ?></div>
                    <div class="text-xs text-gray-600 truncate flex items-center gap-1">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Online
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-700 shrink-0"></i>
            </div>
        </div>

        <nav class="flex flex-col gap-1">
            <a href="<?php echo e(route('home')); ?>" class="nav-item active">
                <i data-lucide="home" class="w-[18px] h-[18px]"></i>
                Home
            </a>
            <a href="<?php echo e(route('profile.edit')); ?>" class="nav-item">
                <i data-lucide="user-circle" class="w-[18px] h-[18px]"></i>
                Profile
            </a>
        </nav>
    </div>

    <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button class="w-full flex items-center gap-3 p-3 rounded-xl text-red-500/70 text-sm transition-colors hover:text-red-400 hover:bg-red-500/5">
            <i data-lucide="log-out" class="w-[18px] h-[18px]"></i>
            Logout
        </button>
    </form>
</aside>


<!-- MAIN CONTENT -->
<main class="flex-1 min-w-0 border-r border-subtle" style="z-index:1;">
    <div class="sticky top-0 glass border-b border-subtle z-30">
        <div class="p-4 pb-3">
            <div class="flex items-center justify-between mb-3">
                <h1 class="text-lg font-bold">Home</h1>
                <button class="w-9 h-9 rounded-xl bg-white/[0.03] border border-white/[0.06] flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/[0.06] transition-all">
                    <i data-lucide="settings-2" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="relative" id="searchContainer">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600 pointer-events-none"></i>
                    <input type="text" id="hashtagSearch" class="search-input w-full pl-10 pr-10 py-2.5 rounded-xl text-sm text-white placeholder-gray-600" placeholder="Cari hashtag #..." autocomplete="off">
                    <button id="clearSearch" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 hover:text-white transition-colors hidden">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="searchDropdown" class="search-dropdown absolute top-full left-0 right-0 mt-2 rounded-2xl overflow-hidden z-50 hidden">
                    <div class="p-3.5 border-b border-subtle">
                        <div class="text-[11px] text-gray-600 font-semibold uppercase tracking-widest">Hashtag</div>
                    </div>
                    <div id="searchResults" class="max-h-72 overflow-y-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="p-4 text-pink-400 text-sm text-center border-b border-subtle bg-pink-500/[0.03]">
            <div class="flex items-center justify-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<script>
lucide.createIcons();

/* =========================
   HASHTAG SEARCH
========================= */
const searchInput = document.getElementById('hashtagSearch');
const clearBtn = document.getElementById('clearSearch');
const searchDropdown = document.getElementById('searchDropdown');
const searchResults = document.getElementById('searchResults');
let searchDebounce = null;

searchInput.addEventListener('input', () => {
    const q = searchInput.value.trim();
    clearBtn.classList.toggle('hidden', q === '');
    if (searchDebounce) clearTimeout(searchDebounce);
    if (!q) { searchDropdown.classList.add('hidden'); return; }
    searchDebounce = setTimeout(() => performSearch(q), 250);
});
searchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); const q = searchInput.value.trim(); if(q){ searchByTag(q); searchDropdown.classList.add('hidden'); } }
    if (e.key === 'Escape') { searchDropdown.classList.add('hidden'); searchInput.blur(); }
});
clearBtn.addEventListener('click', () => { searchInput.value=''; clearBtn.classList.add('hidden'); searchDropdown.classList.add('hidden'); searchInput.focus(); });
document.addEventListener('click', (e) => { if(!document.getElementById('searchContainer').contains(e.target)) searchDropdown.classList.add('hidden'); });

async function performSearch(query) {
    const cq = query.startsWith('#') ? query.substring(1) : query;
    try {
        const t = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const r = await fetch(`/tags/search?q=${encodeURIComponent(cq)}`, { headers:{'X-CSRF-TOKEN':t,'Accept':'application/json'} });
        renderSearchResults(r.ok ? (await r.json()).tags||[] : [], cq);
    } catch { renderSearchResults([], cq); }
}

function renderSearchResults(tags, query) {
    if (!tags.length) {
        searchResults.innerHTML = `<div class="p-6 text-center"><div class="w-12 h-12 rounded-2xl bg-white/[0.03] flex items-center justify-center mx-auto mb-3"><i data-lucide="hash" class="w-5 h-5 text-gray-700"></i></div><div class="text-sm text-gray-500">Tidak ditemukan tag untuk</div><div class="text-sm font-bold text-white mt-1">#${escapeHtml(query)}</div><button onclick="searchByTag('#${escapeHtml(query)}')" class="mt-4 px-5 py-2 rounded-xl text-xs font-semibold bg-pink-500/10 text-pink-400 hover:bg-pink-500/20 transition-all border border-pink-500/10">Cari anyway</button></div>`;
        searchDropdown.classList.remove('hidden'); lucide.createIcons(); return;
    }
    searchResults.innerHTML = tags.map(item => {
        const tag = item.tag.startsWith('#') ? item.tag.trim() : '#'+item.tag.trim();
        const fc = (item.count??0)>=1e3 ? ((item.count??0)/1e3).toFixed(1)+'K' : item.count??0;
        const hl = highlightMatch(escapeHtml(tag), escapeHtml(query));
        return `<div class="search-dropdown-item flex items-center justify-between p-3.5 cursor-pointer" onclick="searchByTag('${escapeHtml(tag)}')"><div class="flex items-center gap-3 min-w-0"><div class="w-9 h-9 rounded-xl bg-gradient-to-br from-pink-500/10 to-purple-500/10 flex items-center justify-center shrink-0 border border-pink-500/10"><i data-lucide="hash" class="w-4 h-4 text-pink-500"></i></div><div class="min-w-0"><div class="text-sm font-semibold truncate">${hl}</div><div class="text-[11px] text-gray-600 mt-0.5">${fc} post</div></div></div><i data-lucide="chevron-right" class="w-4 h-4 text-gray-700 shrink-0"></i></div>`;
    }).join('');
    searchDropdown.classList.remove('hidden'); lucide.createIcons();
}

function searchByTag(tag) {
    if(!tag||!tag.trim()) return;
    const n = tag.trim().startsWith('#') ? tag.trim() : '#'+tag.trim();
    searchInput.value=n; clearBtn.classList.remove('hidden'); searchDropdown.classList.add('hidden');
    window.location.href=`/search?tag=${encodeURIComponent(n.substring(1))}`;
}
function highlightMatch(t,q) { if(!q) return t; return t.replace(new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')})`,'gi'),'<span class="suggestion-highlight">$1</span>'); }
function escapeHtml(s) { const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }

/* =========================
   AUTO-LINK HASHTAG
========================= */
function parseHashtagsInPosts() {
    document.querySelectorAll('.post-body').forEach(el => {
        if(el.dataset.parsed||el.closest('a,script,style')) return;
        el.dataset.parsed='true';
        Array.from(el.childNodes).forEach(node => {
            if(node.nodeType!==Node.TEXT_NODE) return;
            const rx=/#\w+/g; if(!rx.test(node.textContent)) return; rx.lastIndex=0;
            const frag=document.createDocumentFragment(); let last=0,m;
            while((m=rx.exec(node.textContent))!==null){
                if(m.index>last) frag.appendChild(document.createTextNode(node.textContent.substring(last,m.index)));
                const s=document.createElement('span'); s.className='hashtag-link'; s.textContent=m[0];
                s.onclick=()=>searchByTag(m[0]); frag.appendChild(s); last=m.index+m[0].length;
            }
            if(last>0){if(last<node.textContent.length) frag.appendChild(document.createTextNode(node.textContent.substring(last))); node.replaceWith(frag);}
        });
    });
}
new MutationObserver(parseHashtagsInPosts).observe(document.querySelector('main'),{childList:true,subtree:true});
parseHashtagsInPosts();

/* =========================
   POST LIKE
========================= */
async function togglePostLike(btn, postId) {
    try {
        const t=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const r=await fetch(`/posts/${postId}/like`,{method:'POST',headers:{'X-CSRF-TOKEN':t,'Accept':'application/json','Content-Type':'application/json'}});
        const d=await r.json();
        const icon=btn.querySelector('svg'), cs=btn.querySelector('.like-count');
        if(d.liked){btn.classList.add('liked');if(icon)icon.style.fill='currentColor';btn.style.transform='scale(1.2)';setTimeout(()=>btn.style.transform='scale(1)',200);}
        else{btn.classList.remove('liked');if(icon)icon.style.fill='none';}
        if(cs) cs.innerText=d.count??0;
    } catch(e){console.error('Like error:',e);}
}

/* =========================
   COMMENT LIKE
========================= */
async function toggleCommentLike(btn, commentId) {
    try {
        const t=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const r=await fetch(`/comments/${commentId}/like`,{method:'POST',headers:{'X-CSRF-TOKEN':t,'Accept':'application/json','Content-Type':'application/json'}});
        const d=await r.json();
        const icon=btn.querySelector('svg'), cs=btn.querySelector('.like-count');
        if(d.liked){btn.classList.add('liked');if(icon)icon.style.fill='currentColor';btn.style.transform='scale(1.2)';setTimeout(()=>btn.style.transform='scale(1)',200);}
        else{btn.classList.remove('liked');if(icon)icon.style.fill='none';}
        if(cs) cs.innerText=d.count??0;
    } catch(e){console.error('Comment like error:',e);}
}
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\laragon\www\violet\resources\views/layout/app.blade.php ENDPATH**/ ?>