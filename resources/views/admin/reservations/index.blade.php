@extends('layouts.admin')

@section('title', 'Reservations')
@section('page-title', 'Amenity Reservations')

@section('content')
<div x-data="reservationCalendar()" @keydown.window="handleShortcuts($event)" class="h-[calc(100vh-8rem)] flex flex-col gap-6">

    {{-- HEADER & METRICS --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Amenity Reservations</h1>
            <p class="text-sm text-gray-500 mt-1">Manage bookings and verify payments</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-white rounded-lg p-1 border border-gray-200 shadow-sm">
                <button @click="changeDate(-1)" class="p-1.5 hover:bg-gray-50 rounded-md text-gray-500 transition-all">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <div class="relative">
                    <input type="date" x-model="currentDate" @change="fetchData" 
                           class="bg-transparent border-none text-sm font-semibold text-gray-700 focus:ring-0 cursor-pointer w-32 text-center py-1">
                </div>
                <button @click="changeDate(1)" class="p-1.5 hover:bg-gray-50 rounded-md text-gray-500 transition-all">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            </div>
            
            <button @click="activityLogOpen = true" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-50 hover:border-gray-300 shadow-sm flex items-center gap-2 transition-all">
                <i class="fas fa-history text-gray-400"></i>
                <span>Activity Log</span>
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="flex-1 grid grid-cols-12 gap-6 min-h-0">
        
        {{-- LEFT COLUMN: ACTION QUEUE (65%) --}}
        <div class="col-span-8 flex flex-col min-h-0 bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden">
            
            {{-- Queue Header --}}
            <div class="px-6 py-4 bg-white border-b border-gray-200 flex justify-between items-center shadow-sm z-10">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        Requires Action
                    </h2>
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-0.5 rounded-full" x-show="actionable.length > 0" x-text="actionable.length"></span>
                </div>
                
                {{-- Quick Filters --}}
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button @click="filterType = 'all'" :class="filterType === 'all' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1 rounded-md text-xs font-bold transition-all">All</button>
                    <button @click="filterType = 'verify_payment'" :class="filterType === 'verify_payment' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1 rounded-md text-xs font-bold transition-all">Payments</button>
                    <button @click="filterType = 'review'" :class="filterType === 'review' ? 'bg-white shadow-sm text-yellow-600' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1 rounded-md text-xs font-bold transition-all">Approvals</button>
                </div>
            </div>

            {{-- Queue List --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                
                <template x-for="req in filteredActionable" :key="req.id">
                    <div @click="selectRequest(req)" 
                         class="bg-white border rounded-xl p-5 cursor-pointer transition-all hover:shadow-md relative group select-none"
                         :class="selectedRequest?.id === req.id ? 'ring-2 ring-blue-500 border-transparent shadow-md z-10' : 'border-gray-200 shadow-sm'">
                         
                         <div class="flex justify-between items-start mb-4">
                             <div class="flex items-center gap-3">
                                 <div class="w-2.5 h-2.5 rounded-full ring-2 ring-offset-1" :class="'bg-' + req.status_color + '-500 ring-' + req.status_color + '-100'"></div>
                                 <span class="font-bold text-sm text-gray-900" x-text="req.status_reason"></span>
                                 <template x-if="req.is_overdue">
                                     <span class="bg-red-50 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded uppercase tracking-wide border border-red-100">Overdue</span>
                                 </template>
                             </div>
                             <span class="text-xs font-mono text-gray-400" x-text="req.created_at_formatted"></span>
                         </div>

                         <div class="grid grid-cols-4 gap-6">
                             {{-- Amenity --}}
                             <div class="col-span-1">
                                 <div class="flex items-center gap-3">
                                     <div class="w-10 h-10 rounded-lg bg-gray-50 flex-shrink-0 flex items-center justify-center border border-gray-100">
                                         <template x-if="req.amenity_image">
                                             <img :src="req.amenity_image" class="w-full h-full object-cover rounded-lg">
                                         </template>
                                         <template x-if="!req.amenity_image">
                                             <i class="fas fa-umbrella-beach text-gray-400"></i>
                                         </template>
                                     </div>
                                     <div>
                                         <div class="text-[10px] uppercase text-gray-400 font-bold tracking-wide leading-none mb-1">Amenity</div>
                                         <div class="font-bold text-sm text-gray-800 leading-tight" x-text="req.amenity_name"></div>
                                     </div>
                                 </div>
                             </div>

                             {{-- Resident --}}
                             <div class="col-span-1">
                                 <div class="text-[10px] uppercase text-gray-400 font-bold tracking-wide leading-none mb-1">Resident</div>
                                 <div class="font-bold text-sm text-gray-800 leading-tight" x-text="req.resident_name"></div>
                                 <div class="text-xs text-gray-500 mt-0.5" x-text="req.unit"></div>
                             </div>

                             {{-- Schedule --}}
                             <div class="col-span-1">
                                 <div class="text-[10px] uppercase text-gray-400 font-bold tracking-wide leading-none mb-1">Schedule</div>
                                 <div class="font-bold text-sm text-gray-800 leading-tight" x-text="req.date"></div>
                                 <div class="text-xs text-gray-500 mt-0.5" x-text="req.time_slot"></div>
                             </div>

                             {{-- Amount --}}
                             <div class="col-span-1 text-right">
                                 <div class="text-[10px] uppercase text-gray-400 font-bold tracking-wide leading-none mb-1">Total</div>
                                 <div class="font-bold text-lg text-gray-900 leading-tight" x-text="'₱' + req.total_price"></div>
                                 <div class="text-[10px] font-bold mt-1 uppercase" 
                                      :class="req.payment_method === 'gcash' ? 'text-blue-500' : 'text-orange-500'"
                                      x-text="req.payment_method"></div>
                             </div>
                         </div>

                         {{-- Inline Actions (Visible on Hover/Selection) --}}
                         <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end gap-3" x-show="selectedRequest?.id === req.id">
                            <span class="text-xs text-gray-400 italic self-center mr-auto">Select action in the details panel &rarr;</span>
                         </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="actionable.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check text-2xl text-green-500"></i>
                    </div>
                    <h3 class="text-gray-900 font-bold text-lg">All Caught Up!</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-xs">There are no pending reservations requiring your attention right now.</p>
                </div>

                {{-- Collapsible Timeline --}}
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <button @click="showTimeline = !showTimeline" class="flex items-center gap-2 text-gray-500 hover:text-gray-900 font-bold text-sm mb-4 w-full group">
                        <div class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                            <i class="fas text-xs" :class="showTimeline ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </div>
                        <span>Schedule Overview (Reference Only)</span>
                    </button>
                    
                    <div x-show="showTimeline" x-collapse>
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden h-96 relative flex flex-col">
                            {{-- Timeline Header --}}
                            <div class="flex border-b border-gray-200 overflow-hidden bg-gray-50 flex-shrink-0">
                                <div class="w-40 flex-shrink-0 border-r border-gray-200 p-3 flex items-center justify-center font-bold text-gray-500 text-xs bg-gray-50">Amenity</div>
                                <div class="flex-1 overflow-hidden" id="timeline-header">
                                    <div class="flex" :style="'width: ' + (hours.length * 100) + 'px'">
                                        <template x-for="hour in hours" :key="hour">
                                            <div class="w-[100px] flex-shrink-0 border-r border-gray-200 py-2 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider" x-text="formatHour(hour)"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Timeline Body --}}
                            <div class="flex-1 overflow-auto relative bg-white custom-scrollbar" id="timeline-body" @scroll="syncScroll">
                                <div class="relative" :style="'width: ' + (160 + (hours.length * 100)) + 'px'">
                                    <template x-for="amenity in amenities" :key="amenity.id">
                                        <div class="flex border-b border-gray-100 h-20 relative hover:bg-slate-50 transition-colors">
                                            {{-- Amenity Name --}}
                                            <div class="w-40 flex-shrink-0 border-r border-gray-200 p-3 flex flex-col justify-center sticky left-0 z-20 bg-white shadow-[4px_0_10px_-4px_rgba(0,0,0,0.05)]">
                                                <div class="font-bold text-gray-700 text-xs truncate" x-text="amenity.name"></div>
                                                <div class="text-[10px] text-gray-400 mt-1" x-text="amenity.capacity + ' bookings'"></div>
                                            </div>

                                            {{-- Grid --}}
                                            <div class="flex-1 relative">
                                                <div class="absolute inset-0 flex pointer-events-none">
                                                    <template x-for="hour in hours" :key="hour">
                                                        <div class="w-[100px] flex-shrink-0 border-r border-gray-100 h-full"></div>
                                                    </template>
                                                </div>
                                                
                                                {{-- Events --}}
                                                <template x-for="res in getReservationsForAmenity(amenity.id)" :key="res.id">
                                                    <div class="absolute top-1 bottom-1 rounded-md border p-1 text-[10px] overflow-hidden cursor-help transition-all hover:shadow-lg hover:z-30 flex flex-col justify-center select-none bg-blue-50 border-blue-200 text-blue-700"
                                                         :style="getEventStyle(res)"
                                                         :title="res.resident_name + ' (' + res.time_slot + ')'">
                                                        <div class="font-bold truncate" x-text="res.resident_name"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: STICKY DETAILS (35%) --}}
        <div class="col-span-4 relative h-full">
            <div x-show="selectedRequest" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-10"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="sticky top-0 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden flex flex-col h-full max-h-[calc(100vh-8rem)] z-30">
                
                {{-- Detail Header --}}
                <div class="p-5 border-b border-gray-100 bg-gray-50 flex-shrink-0">
                    <div class="flex justify-between items-start">
                        <h3 class="font-extrabold text-lg text-gray-900">Request Details</h3>
                        <button @click="selectedRequest = null" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide border"
                              :class="{
                                  'bg-blue-50 text-blue-700 border-blue-100': selectedRequest?.action_type === 'verify_payment',
                                  'bg-yellow-50 text-yellow-700 border-yellow-100': selectedRequest?.action_type === 'review',
                                  'bg-orange-50 text-orange-700 border-orange-100': selectedRequest?.action_type === 'collect_payment'
                              }" 
                              x-text="selectedRequest?.status_reason">
                        </span>
                    </div>
                </div>

                {{-- Scrollable Content --}}
                <div class="flex-1 overflow-y-auto p-5 space-y-6 custom-scrollbar">
                    
                    {{-- Resident Profile --}}
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xl border border-slate-200 shadow-inner">
                            <span x-text="selectedRequest?.resident_name.charAt(0)"></span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 text-base" x-text="selectedRequest?.resident_name"></div>
                            <div class="text-sm font-medium text-gray-500" x-text="selectedRequest?.unit"></div>
                            <div class="text-xs text-gray-400 mt-1 space-y-0.5">
                                <div class="flex items-center gap-2"><i class="fas fa-envelope w-3"></i> <span x-text="selectedRequest?.email"></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-phone w-3"></i> <span x-text="selectedRequest?.contact"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    {{-- Reservation Specs --}}
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-bold uppercase">Amenity</span>
                            <span class="text-sm font-bold text-gray-900" x-text="selectedRequest?.amenity_name"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-bold uppercase">Date</span>
                            <span class="text-sm font-bold text-gray-900" x-text="selectedRequest?.date"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-bold uppercase">Time</span>
                            <span class="text-sm font-bold text-gray-900" x-text="selectedRequest?.time_slot"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 font-bold uppercase">Guests</span>
                            <span class="text-sm font-bold text-gray-900" x-text="selectedRequest?.guest_count"></span>
                        </div>
                        
                        <template x-if="selectedRequest?.notes">
                            <div class="pt-2 border-t border-slate-200 mt-2">
                                <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Notes</span>
                                <p class="text-xs text-gray-600 italic" x-text="selectedRequest.notes"></p>
                            </div>
                        </template>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    {{-- Payment Verification --}}
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2">
                            <i class="fas fa-receipt text-gray-400"></i> Payment Details
                        </h4>
                        
                        <div class="rounded-xl border border-gray-200 overflow-hidden">
                            <div class="bg-gray-50 p-3 flex justify-between items-center border-b border-gray-200">
                                <span class="text-xs font-bold text-gray-500 uppercase">Total Due</span>
                                <span class="text-xl font-extrabold text-emerald-600" x-text="'₱' + selectedRequest?.total_price"></span>
                            </div>
                            
                            <div class="p-4 bg-white">
                                <template x-if="selectedRequest?.payment_method === 'gcash'">
                                    <div>
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-xs text-gray-500">Ref No.</span>
                                            <div class="flex items-center gap-2 bg-gray-100 px-2 py-1 rounded">
                                                <span class="font-mono text-xs font-bold text-gray-800" x-text="selectedRequest?.payment_reference_no || 'N/A'"></span>
                                                <button @click="navigator.clipboard.writeText(selectedRequest?.payment_reference_no)" class="text-gray-400 hover:text-blue-500 transition-colors"><i class="far fa-copy text-xs"></i></button>
                                            </div>
                                        </div>
                                        
                                        <template x-if="selectedRequest?.payment_proof">
                                            <div class="space-y-2">
                                                <span class="text-[10px] uppercase text-gray-400 font-bold">Proof of Payment</span>
                                                <a :href="selectedRequest?.payment_proof" target="_blank" class="block rounded-lg overflow-hidden border border-gray-200 relative group cursor-zoom-in">
                                                    <img :src="selectedRequest?.payment_proof" class="w-full h-auto object-contain bg-gray-50 max-h-64">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all flex items-center justify-center">
                                                        <span class="text-white opacity-0 group-hover:opacity-100 font-bold text-xs bg-black bg-opacity-60 px-3 py-1.5 rounded-full backdrop-blur-sm shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all">Click to Enlarge</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </template>
                                        <template x-if="!selectedRequest?.payment_proof">
                                            <div class="text-center py-4 bg-gray-50 rounded border border-dashed border-gray-300">
                                                <span class="text-xs text-gray-400 italic">No proof uploaded yet</span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <template x-if="selectedRequest?.payment_method === 'cash'">
                                    <div class="text-center py-6">
                                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-hand-holding-usd text-xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Cash Payment Required</p>
                                        <p class="text-xs text-gray-500 mt-1">Please collect cash from resident</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="p-5 bg-white border-t border-gray-200 shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.1)] flex-shrink-0 z-40">
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="openRejectionModal(selectedRequest.id)" 
                                :disabled="processingIds.includes(selectedRequest?.id)"
                                class="px-4 py-3 bg-white border-2 border-red-100 text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-200 transition-colors shadow-sm text-sm disabled:opacity-50">
                            Reject
                        </button>
                        
                        {{-- Dynamic Primary Action --}}
                        <template x-if="selectedRequest?.action_type === 'verify_payment'">
                            <button @click="verifyPayment(selectedRequest.id)" 
                                    :disabled="processingIds.includes(selectedRequest?.id)"
                                    class="px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200 text-sm disabled:opacity-50">
                                <span x-show="!processingIds.includes(selectedRequest?.id)">Verify & Approve</span>
                                <span x-show="processingIds.includes(selectedRequest?.id)"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                            </button>
                        </template>
                        
                        <template x-if="selectedRequest?.action_type === 'review'">
                            <button @click="quickAction(selectedRequest.id, 'approved')" 
                                    :disabled="processingIds.includes(selectedRequest?.id)"
                                    class="px-4 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200 text-sm disabled:opacity-50">
                                <span x-show="!processingIds.includes(selectedRequest?.id)">Approve Request</span>
                                <span x-show="processingIds.includes(selectedRequest?.id)"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                            </button>
                        </template>

                        <template x-if="selectedRequest?.action_type === 'collect_payment'">
                            <button @click="verifyPayment(selectedRequest.id)" 
                                    :disabled="processingIds.includes(selectedRequest?.id)"
                                    class="px-4 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-200 text-sm disabled:opacity-50">
                                <span x-show="!processingIds.includes(selectedRequest?.id)">Confirm Payment</span>
                                <span x-show="processingIds.includes(selectedRequest?.id)"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Empty Selection State --}}
            <div x-show="!selectedRequest" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 p-8 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50 m-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-mouse-pointer text-2xl text-gray-300"></i>
                </div>
                <h4 class="font-bold text-gray-600 text-lg">No Request Selected</h4>
                <p class="text-sm text-gray-400 text-center mt-2 max-w-xs">Select an item from the "Requires Action" list to view full details and manage the reservation.</p>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div x-show="rejectionModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition.opacity>
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" @click="closeRejectionModal"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden p-6" @click.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Reject Reservation</h3>
                <p class="text-sm text-gray-500 mb-4">Please provide a reason for rejecting this reservation. This will be sent to the resident.</p>
                
                <textarea x-model="rejectionReason" 
                          class="w-full border-gray-300 rounded-xl shadow-sm focus:border-red-500 focus:ring-red-500 text-sm mb-4" 
                          rows="3" 
                          placeholder="Reason for rejection..."></textarea>
                
                <div class="flex justify-end gap-3">
                    <button @click="closeRejectionModal" class="px-4 py-2 text-gray-700 font-bold text-sm hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                    <button @click="confirmRejection" 
                            :disabled="!rejectionReason.trim() || processingIds.includes(selectedRejectionId)"
                            class="px-4 py-2 bg-red-600 text-white font-bold text-sm rounded-lg hover:bg-red-700 transition-colors shadow-lg shadow-red-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirm Rejection
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Activity Log Modal --}}
    <div x-show="activityLogOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition.opacity>
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50" @click="activityLogOpen = false"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden max-h-[80vh] flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Activity Log</h3>
                    <button @click="activityLogOpen = false" class="text-gray-400 hover:text-gray-500"><i class="fas fa-times"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <template x-for="log in activities" :key="log.id">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                     :class="{
                                         'bg-green-500': log.status_color === 'green',
                                         'bg-red-500': log.status_color === 'red',
                                         'bg-yellow-500': log.status_color === 'yellow',
                                         'bg-gray-400': log.status_color === 'gray'
                                     }">
                                    <i class="fas" :class="{
                                        'fa-check': log.action.includes('approved') || log.action.includes('verified'),
                                        'fa-times': log.action.includes('rejected'),
                                        'fa-clock': log.action.includes('pending'),
                                        'fa-info': true
                                    }"></i>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">
                                    <span class="font-bold" x-text="log.admin_name"></span>
                                    <span x-text="log.action.replace('_', ' ')"></span>
                                    reservation
                                </p>
                                <p class="text-xs text-gray-500 mt-1" x-text="log.time_ago"></p>
                                <template x-if="log.details && log.details.reason">
                                    <p class="text-xs text-gray-600 mt-1 italic bg-gray-50 p-2 rounded" x-text="'Reason: ' + log.details.reason"></p>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function reservationCalendar() {
        return {
            currentDate: '{{ now()->format("Y-m-d") }}',
            amenities: [],
            reservations: [],
            actionable: [],
            activities: [],
            hours: Array.from({length: 17}, (_, i) => i + 6), // 6 AM to 10 PM
            
            // State
            selectedRequest: null,
            showTimeline: false,
            filterType: 'all', // all, verify_payment, review
            
            // Modals & Processing
            rejectionModalOpen: false,
            rejectionReason: '',
            selectedRejectionId: null,
            activityLogOpen: false,
            processingIds: [],

            init() {
                this.fetchData();
                // Auto-refresh every 30s
                setInterval(() => this.fetchData(), 30000);
            },

            get filteredActionable() {
                if (this.filterType === 'all') return this.actionable;
                return this.actionable.filter(req => req.action_type === this.filterType || (this.filterType === 'review' && req.action_type === 'collect_payment'));
            },

            async fetchData() {
                try {
                    const response = await fetch(`{{ route('admin.amenity-reservations.data') }}?date=${this.currentDate}`);
                    const data = await response.json();
                    
                    this.amenities = data.amenities;
                    this.reservations = data.reservations;
                    this.actionable = data.actionable;
                    this.activities = data.activities;

                    // Update selected request if it exists in new data, otherwise deselect
                    if (this.selectedRequest) {
                        const updated = this.actionable.find(r => r.id === this.selectedRequest.id);
                        this.selectedRequest = updated || null;
                    } else if (this.actionable.length > 0 && !this.selectedRequest) {
                        // Optional: Auto-select first item? Maybe better to let user choose to avoid confusion.
                        // this.selectedRequest = this.actionable[0]; 
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            },

            selectRequest(req) {
                this.selectedRequest = req;
            },

            // --- Actions ---

            async verifyPayment(id) {
                if (!confirm('Are you sure you want to verify this payment?')) return;
                
                this.processingIds.push(id);
                try {
                    const response = await fetch(`/admin/amenity-reservations/${id}/verify-payment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        await this.fetchData();
                        // If selected was processed, it will be removed from actionable, so deselect
                        if (this.selectedRequest?.id === id) {
                            this.selectedRequest = null;
                        }
                    }
                } catch (error) {
                    alert('Error verifying payment');
                } finally {
                    this.processingIds = this.processingIds.filter(pid => pid !== id);
                }
            },

            async quickAction(id, action) {
                if (!confirm(`Are you sure you want to ${action} this request?`)) return;
                
                this.processingIds.push(id);
                try {
                    const response = await fetch(`/admin/amenity-reservations/${id}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: action })
                    });
                    
                    if (response.ok) {
                        await this.fetchData();
                        if (this.selectedRequest?.id === id) {
                            this.selectedRequest = null;
                        }
                    }
                } catch (error) {
                    console.error(`Error ${action}ing reservation:`, error);
                } finally {
                    this.processingIds = this.processingIds.filter(pid => pid !== id);
                }
            },

            // --- Rejection Modal ---

            openRejectionModal(id) {
                this.selectedRejectionId = id;
                this.rejectionReason = '';
                this.rejectionModalOpen = true;
            },

            closeRejectionModal() {
                this.rejectionModalOpen = false;
                this.selectedRejectionId = null;
                this.rejectionReason = '';
            },

            async confirmRejection() {
                if (!this.selectedRejectionId || !this.rejectionReason) return;

                const id = this.selectedRejectionId;
                this.processingIds.push(id);

                try {
                    const response = await fetch(`/admin/amenity-reservations/${id}/reject-payment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ reason: this.rejectionReason })
                    });

                    if (response.ok) {
                        this.closeRejectionModal();
                        await this.fetchData();
                        if (this.selectedRequest?.id === id) {
                            this.selectedRequest = null;
                        }
                    }
                } catch (error) {
                    alert('Error rejecting payment');
                } finally {
                    this.processingIds = this.processingIds.filter(pid => pid !== id);
                }
            },

            // --- Timeline Helpers ---

            changeDate(days) {
                const date = new Date(this.currentDate);
                date.setDate(date.getDate() + days);
                this.currentDate = date.toISOString().split('T')[0];
                this.fetchData();
            },

            formatHour(hour) {
                const h = hour % 12 || 12;
                const ampm = hour < 12 ? 'AM' : 'PM';
                return `${h} ${ampm}`;
            },

            getReservationsForAmenity(amenityId) {
                return this.reservations.filter(r => r.amenity_id === amenityId);
            },

            getEventStyle(res) {
                // res.start_hour and res.duration are from backend
                let startOffset = (res.start_hour - this.hours[0]) * 100;
                let width = res.duration * 100;
                return `left: ${startOffset}px; width: ${width}px;`;
            },
            
            syncScroll(e) {
                const header = document.getElementById('timeline-header');
                if (header) {
                    header.scrollLeft = e.target.scrollLeft;
                }
            },

            handleShortcuts(e) {
                if (e.key === 'Escape') {
                    this.selectedRequest = null;
                    this.closeRejectionModal();
                    this.activityLogOpen = false;
                }
            }
        }
    }
</script>
@endsection
