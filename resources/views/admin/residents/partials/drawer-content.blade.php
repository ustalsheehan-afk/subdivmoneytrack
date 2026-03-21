<div class="h-full flex flex-col bg-white shadow-2xl">

    {{-- ================= HEADER ================= --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
        <div>
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Resident Profile</h2>
            <p class="text-lg font-bold text-gray-900">Details Overview</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.residents.show', $resident->id) }}"
               class="text-sm font-semibold text-[#800020] hover:underline flex items-center gap-1">
                <i class="bi bi-box-arrow-up-right"></i> Full View
            </a>
            <button onclick="closeResidentDrawer()"
                    class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                <i class="bi bi-x-lg text-gray-500"></i>
            </button>
        </div>
    </div>

    {{-- ================= CONTENT ================= --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50 custom-scrollbar">

        {{-- ================= PROFILE ================= --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <div class="flex flex-col items-center text-center">
                <div class="relative p-1 rounded-full bg-gradient-to-tr from-[#800020]/30 to-transparent">
                    <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}"
                         onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                    <span class="absolute bottom-1 right-1 px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider shadow-sm
                        {{ $resident->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $resident->status }}
                    </span>
                </div>

                <h3 class="mt-3 text-xl font-bold text-gray-900">
                    {{ $resident->first_name }} {{ $resident->last_name }}
                </h3>
                <p class="text-sm text-gray-500">Block {{ $resident->block }}</p>

                <div class="flex gap-3 mt-4">
                    <a href="tel:{{ $resident->contact }}"
                       class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                        <i class="bi bi-telephone-fill"></i>
                    </a>
                    <a href="mailto:{{ $resident->email }}"
                       class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                        <i class="bi bi-envelope-fill"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= FINANCIAL OVERVIEW ================= --}}
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Financial Overview</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 text-center">
                    <p class="text-xs text-red-600 font-medium uppercase tracking-wide mb-1">Unpaid Dues</p>
                    <p class="text-lg font-bold text-red-700">
                        ₱{{ number_format($financials['outstandingDues'] ?? 0, 2) }}
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-green-50 border border-green-100 text-center">
                    <p class="text-xs text-green-600 font-medium uppercase tracking-wide mb-1">Total Paid</p>
                    <p class="text-lg font-bold text-green-700">
                        ₱{{ number_format($financials['totalPayments'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- ================= TABS ================= --}}
        <div>
            <div class="flex border-b border-gray-100 mb-4">
                <button onclick="showDrawerTab('dues')"
                        class="drawer-tab-btn flex-1 pb-2 text-sm font-semibold text-[#800020] border-b-2 border-[#800020]"
                        data-tab="dues">
                    Dues
                </button>
                <button onclick="showDrawerTab('payments')"
                        class="drawer-tab-btn flex-1 pb-2 text-sm font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent"
                        data-tab="payments">
                    Payments
                </button>
            </div>

            {{-- ===== DUES TAB ===== --}}
            <div id="drawer-tab-dues" class="drawer-tab-content space-y-3">
                @if($resident->dues && $resident->dues->count() > 0)
                    @foreach($resident->dues->take(5) as $due)
                        <div class="flex justify-between items-center p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $due->month }} {{ $due->year }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $due->due_date ? $due->due_date->format('M d, Y') : '—' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">
                                    ₱{{ number_format($due->amount, 2) }}
                                </p>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                                    {{ $due->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($due->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    @if($resident->dues->count() > 5)
                        <p class="text-center text-xs text-gray-400 mt-2">
                            Showing 5 of {{ $resident->dues->count() }} records
                        </p>
                    @endif
                @else
                    <p class="text-center text-sm text-gray-500 py-4">No dues records found.</p>
                @endif
            </div>

            {{-- ===== PAYMENTS TAB ===== --}}
            <div id="drawer-tab-payments" class="drawer-tab-content hidden space-y-3">
                @if($resident->payments && $resident->payments->count() > 0)
                    @foreach($resident->payments->take(5) as $payment)
                        <div class="flex justify-between items-center p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                            <div>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $payment->or_number ?? 'No OR Number' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $payment->date_paid ? $payment->date_paid->format('M d, Y') : '—' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-700">
                                    + ₱{{ number_format($payment->amount, 2) }}
                                </p>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                                    {{ $payment->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    @if($resident->payments->count() > 5)
                        <p class="text-center text-xs text-gray-400 mt-2">
                            Showing 5 of {{ $resident->payments->count() }} records
                        </p>
                    @endif
                @else
                    <p class="text-center text-sm text-gray-500 py-4">No payments found.</p>
                @endif
            </div>

        </div>

    </div>
</div>

{{-- ================= TAB SCRIPT ================= --}}
<script>
function showDrawerTab(tab) {
    document.querySelectorAll('.drawer-tab-content').forEach(el => {
        el.classList.add('hidden');
    });

    document.querySelectorAll('.drawer-tab-btn').forEach(btn => {
        btn.classList.remove('text-[#800020]', 'border-[#800020]');
        btn.classList.add('text-gray-500', 'border-transparent');
    });

    document.getElementById('drawer-tab-' + tab).classList.remove('hidden');

    const activeBtn = document.querySelector(`[data-tab="${tab}"]`);
    activeBtn.classList.add('text-[#800020]', 'border-[#800020]');
    activeBtn.classList.remove('text-gray-500', 'border-transparent');
}
</script>
