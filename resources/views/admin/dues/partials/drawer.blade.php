<x-drawer id="dueDrawer" width="max-w-lg">
    <div class="h-full flex flex-col bg-white">
        
        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch Details</h2>
                <p class="text-lg font-bold text-gray-900" id="drawerTitle">Batch Title</p>
            </div>
            <div class="flex items-center gap-2">
                 <span id="drawerStatusBadge" class="hidden px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 uppercase tracking-wide">
                    Active
                </span>
                <button onclick="closeDueDrawer()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-gray-500">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50 custom-scrollbar">
            
            {{-- Section: Financial Overview --}}
            <div>
                 <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Financial Overview</p>
                    <span id="drawerType" class="text-xs font-bold text-blue-600 uppercase tracking-wide bg-blue-50 px-2 py-0.5 rounded-full">
                        Monthly HOA
                    </span>
                 </div>
                <div class="grid grid-cols-2 gap-3">
                    {{-- Amount Per Resident --}}
                    <div class="p-4 rounded-xl bg-white border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Fee Per Resident</p>
                        <p class="text-lg font-bold text-gray-900" id="drawerAmount">₱0.00</p>
                    </div>
                    {{-- Total Collected --}}
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-center">
                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide mb-1">Total Collected</p>
                        <p class="text-lg font-bold text-blue-700" id="drawerTotalCollected">₱0.00</p>
                    </div>
                </div>
            </div>

            {{-- Section: Collection Progress --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-end mb-3">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        Collection Progress
                    </h3>
                    <span class="text-lg font-bold text-blue-600" id="drawerProgressText">0%</span>
                </div>
                
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden mb-4">
                    <div id="drawerProgressBar" class="bg-blue-600 h-full rounded-full transition-all duration-700 ease-out relative" style="width: 0%">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <p class="text-gray-500 text-xs mb-0.5 uppercase tracking-wide">Paid Residents</p>
                        <p class="font-bold text-gray-900 text-lg" id="drawerPaidCount">0</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-xs mb-0.5 uppercase tracking-wide">Target Amount</p>
                        <p class="font-bold text-gray-900 text-lg" id="drawerTotalExpected">₱0.00</p>
                    </div>
                </div>
            </div>

            {{-- Section: Details --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Batch Information</p>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Description</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" id="drawerDescription">No description provided.</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Residents</p>
                            <p class="text-sm font-bold text-gray-900" id="drawerTotalResidents">0</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Created Date</p>
                            <p class="text-sm font-bold text-gray-900" id="drawerCreatedDate">-</p>
                        </div>
                         <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Due Date</p>
                            <p class="text-sm font-bold text-gray-900" id="drawerDate">-</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="p-6 border-t border-gray-100 bg-white sticky bottom-0 z-10">
            <button class="w-full py-3 bg-[#800020] text-white font-bold rounded-xl hover:bg-[#600018] transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group active:scale-[0.98]">
                <span>View Full Report</span>
                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>

    </div>
</x-drawer>
