<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       GLOBAL STATE
    =============================== */
    let currentPage = <?php echo e($residents->currentPage()); ?>;
    let hasMorePages = <?php echo e($residents->hasMorePages() ? 'true' : 'false'); ?>;
    let isLoading = false;
    let currentBlock = "<?php echo e(request('block')); ?>";
    let currentView = '<?php echo e(request("view", "list")); ?>';
    let isSelectionMode = false;
    let searchTimeout;
    let activeResidentId = "<?php echo e(request('active_id')); ?>";

    /* ===============================
       HELPER: HIGHLIGHT ACTIVE ROW
    =============================== */
    window.highlightActiveRow = function () {
        // 1. Remove active class from ALL rows
        document.querySelectorAll('tr[data-id]').forEach(row => {
            row.classList.remove('bg-blue-100', 'border-l-4', 'border-blue-600'); 
            // Optional: reset to default border if needed, but standard border is fine
        });

        // 2. Add active class to the CURRENT active row
        if (activeResidentId) {
            const row = document.querySelector(`tr[data-id="${activeResidentId}"]`);
            if (row) {
                row.classList.add('bg-blue-100', 'border-l-4', 'border-blue-600');
            }
        }
    }

    /* ===============================
       FILTERS & SORT
    =============================== */

    window.filterByBlock = function (block) {
        currentBlock = block;
        applyAllFilters();
    }

    window.clearFilters = function () {
        document.querySelectorAll('input[name="status"]').forEach(el => el.checked = false);

        const lot = document.getElementById('popoverLot');
        const month = document.getElementById('popoverMonth');
        const year = document.getElementById('popoverYear');

        if (lot) lot.value = '';
        if (month) month.value = '';
        if (year) year.value = '';

        applyAllFilters();
    }

    window.applyAllFilters = function () {
        const params = new URLSearchParams();

        // Search
        const searchInput = document.getElementById('searchInput');
        if (searchInput && searchInput.value.trim() !== '') {
            params.append('search', searchInput.value.trim());
        }

        // Block
        if (currentBlock) {
            params.append('block', currentBlock);
        }

        // Status
        const statusEl = document.querySelector('input[name="status"]:checked');
        if (statusEl && statusEl.value !== '') {
            params.append('status', statusEl.value);
        }

        // Lot
        const lot = document.getElementById('popoverLot');
        if (lot && lot.value.trim() !== '') {
            params.append('lot', lot.value.trim());
        }

        // Month
        const month = document.getElementById('popoverMonth');
        if (month && month.value !== '') {
            params.append('month', month.value);
        }

        // Year
        const year = document.getElementById('popoverYear');
        if (year && year.value !== '') {
            params.append('year', year.value);
        }

        // View
        if (currentView) {
            params.append('view', currentView);
        }

        // Sort
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('sort_option')) {
            params.append('sort_option', urlParams.get('sort_option'));
        }

        // Active ID (Persist Selection)
        if (activeResidentId) {
            params.append('active_id', activeResidentId);
        }

        window.location.href = `<?php echo e(route('admin.residents.index')); ?>?${params.toString()}`;
    }

    window.updateSort = function (option) {
        const url = new URL(window.location);
        url.searchParams.set('sort_option', option);
        window.location.href = url.toString();
    }

    /* ===============================
       SEARCH DEBOUNCE
    =============================== */

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyAllFilters, 500);
        });
    }

    /* ===============================
       VIEW TOGGLE
    =============================== */

    window.toggleView = function (view) {
        currentView = view;

        const listView = document.getElementById('listView');
        const gridView = document.getElementById('gridView');
        const listBtn = document.getElementById('listViewBtn');
        const gridBtn = document.getElementById('gridViewBtn');

        if (view === 'grid') {
            if (listView) listView.classList.add('hidden');
            if (gridView) gridView.classList.remove('hidden');

            if (listBtn) listBtn.className = "w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:bg-white hover:shadow-sm transition-all";
            if (gridBtn) gridBtn.className = "w-9 h-9 flex items-center justify-center rounded-lg text-blue-600 bg-white shadow-sm transition-all";
        } else {
            if (listView) listView.classList.remove('hidden');
            if (gridView) gridView.classList.add('hidden');

            if (listBtn) listBtn.className = "w-9 h-9 flex items-center justify-center rounded-lg text-blue-600 bg-white shadow-sm transition-all";
            if (gridBtn) gridBtn.className = "w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:bg-white hover:shadow-sm transition-all";
        }

        const url = new URL(window.location);
        url.searchParams.set('view', view);
        window.history.pushState({}, '', url);
    }

    toggleView(currentView);
    
    // Initial highlight on load
    highlightActiveRow();

    /* ===============================
       SELECTION MODE + BULK
    =============================== */

    window.toggleSelectionMode = function () {
        isSelectionMode = !isSelectionMode;

        const btn = document.getElementById('selectModeBtn');
        const checkboxCols = document.querySelectorAll('.resident-checkbox-col');
        const bulkForm = document.getElementById('bulkDeleteForm');

        if (isSelectionMode) {
            if (btn) {
                btn.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
                btn.innerHTML = '<i class="bi bi-x-lg"></i> Cancel';
            }
            checkboxCols.forEach(el => el.classList.remove('hidden'));
        } else {
            if (btn) {
                btn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                btn.innerHTML = '<i class="bi bi-check2-square"></i> Select';
            }
            checkboxCols.forEach(el => el.classList.add('hidden'));
            if (bulkForm) bulkForm.classList.add('hidden');

            document.querySelectorAll('.resident-checkbox').forEach(cb => cb.checked = false);
            const selectAll = document.getElementById('selectAll');
            if (selectAll) selectAll.checked = false;

            updateBulkAction();
        }
    }

    window.toggleSelectAll = function () {
        const checkboxes = document.querySelectorAll('.resident-checkbox');
        const selectAll = document.getElementById('selectAll');
        if (!selectAll) return;

        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkAction();
    }

    window.updateBulkAction = function () {
        const checked = document.querySelectorAll('.resident-checkbox:checked');
        const form = document.getElementById('bulkDeleteForm');
        const inputContainer = document.getElementById('bulkDeleteInputs');
        const countSpan = document.getElementById('selectedCount');

        if (countSpan) countSpan.innerText = checked.length;
        if (inputContainer) inputContainer.innerHTML = '';

        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            inputContainer.appendChild(input);
        });

        if (form) {
            if (checked.length > 0) form.classList.remove('hidden');
            else form.classList.add('hidden');
        }

        const allCheckboxes = document.querySelectorAll('.resident-checkbox');
        const selectAll = document.getElementById('selectAll');

        if (selectAll) {
            if (checked.length === allCheckboxes.length && allCheckboxes.length > 0) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else if (checked.length > 0) {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
        }
    }

    /* ===============================
       LOAD MORE + INFINITE SCROLL
    =============================== */

    window.loadMore = async function () {
        if (isLoading || !hasMorePages) return;

        isLoading = true;

        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const spinner = document.getElementById('loadingSpinner');

        if (loadMoreBtn) loadMoreBtn.classList.add('hidden');
        if (spinner) spinner.classList.remove('hidden');

        const nextPage = currentPage + 1;
        const url = new URL(window.location.href);
        url.searchParams.set('page', nextPage);
        url.searchParams.set('load_more', '1');

        try {
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();

            const body = document.getElementById('residentsBody');
            if (body) body.insertAdjacentHTML('beforeend', html);

            currentPage = nextPage;

            if (html.trim().length === 0) {
                hasMorePages = false;
                const container = document.getElementById('loadMoreContainer');
                if (container) {
                    container.innerHTML = '<p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-2">End of List</p>';
                }
            } else {
                if (loadMoreBtn) loadMoreBtn.classList.remove('hidden');
                if (spinner) spinner.classList.add('hidden');
            }
        } catch (err) {
            console.error('Load more failed:', err);
        } finally {
            isLoading = false;
        }
    }

    const scrollContainer = document.getElementById('scrollContainer');
    if (scrollContainer) {
        scrollContainer.addEventListener('scroll', () => {
            if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight - 100) {
                loadMore();
            }
        });
    }

    /* ===============================
       DRAWER
    =============================== */

    const drawer = document.getElementById('residentDrawer');
    const overlay = document.getElementById('drawerOverlay');
    const content = document.getElementById('drawerContent');

    window.openResidentDrawer = async function (id) {
        if (!drawer || !overlay || !content) return;

        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        drawer.classList.remove('translate-x-full');

        content.innerHTML = `
            <div class="h-full flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            </div>
        `;

        try {
            const url = `<?php echo e(route('admin.residents.show', ':id')); ?>`.replace(':id', id);
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            content.innerHTML = html;
        } catch (err) {
            console.error(err);
            content.innerHTML = `
                <div class="p-6 text-red-500 text-center">
                    <p class="font-bold">Failed to load details.</p>
                    <button onclick="openResidentDrawer(${id})" class="mt-2 text-sm text-blue-600 underline">Retry</button>
                </div>
            `;
        }
    }

    window.closeResidentDrawer = function () {
        if (!drawer || !overlay || !content) return;

        drawer.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.add('hidden');
            content.innerHTML = '';
        }, 300);
    }

    window.showDrawerTab = function (tab) {
        document.querySelectorAll('.drawer-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.drawer-tab-btn').forEach(el => {
            el.classList.remove('text-[#800020]', 'border-[#800020]');
            el.classList.add('text-gray-500', 'border-transparent');
        });

        const activeContent = document.getElementById('drawer-tab-' + tab);
        if (activeContent) activeContent.classList.remove('hidden');

        const activeBtn = document.querySelector(`button[data-tab="${tab}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('text-gray-500', 'border-transparent');
            activeBtn.classList.add('text-[#800020]', 'border-[#800020]');
        }
    }

});
</script>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

#residentsBody tr:hover {
    background-color: #f9fafb;
    transition: background-color 0.3s;
}
</style>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\partials\scripts.blade.php ENDPATH**/ ?>