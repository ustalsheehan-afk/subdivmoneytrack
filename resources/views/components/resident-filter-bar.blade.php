@props([
    'searchName' => 'search',
    'searchValue' => request('search'),
    'searchPlaceholder' => 'Search...',
    'statusName' => 'status',
    'statusOptions' => [],
    'statusValue' => request('status'),
    'dateName' => 'date',
    'dateOptions' => [],
    'dateValue' => request('date'),
    'clearHref' => url()->current(),
    'primary' => null,
    'method' => 'GET',
    'action' => url()->current(),
])
<form method="{{ $method }}" action="{{ $action }}" class="glass-card p-4 sm:p-5">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
        <div class="lg:col-span-4">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="{{ $searchName }}" value="{{ $searchValue }}"
                       placeholder="{{ $searchPlaceholder }}"
                       class="input pl-9" />
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="relative">
                <select name="{{ $statusName }}" class="select">
                    <option value="">All Status</option>
                    @foreach($statusOptions as $val => $label)
                        <option value="{{ $val }}" {{ (string)$statusValue === (string)$val ? 'selected' : '' }}>{{ strtoupper($label) }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="relative">
                <select name="{{ $dateName }}" class="select">
                    <option value="">Any Date</option>
                    @foreach($dateOptions as $val => $label)
                        <option value="{{ $val }}" {{ (string)$dateValue === (string)$val ? 'selected' : '' }}>{{ strtoupper($label) }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
            </div>
        </div>
        <div class="lg:col-span-2 hidden lg:block"></div>
        <div class="lg:col-span-2 flex items-center gap-2 justify-end">
            <a href="{{ $clearHref }}" class="btn-secondary">
                Clear
            </a>
            @if($primary)
                <a href="{{ $primary['href'] }}" class="btn-premium">
                    @if(isset($primary['icon'])) <i class="bi {{ $primary['icon'] }}"></i> @endif
                    {{ $primary['label'] }}
                </a>
            @endif
            <button type="submit" class="btn-secondary">
                Apply
            </button>
        </div>
    </div>
</form>
