<?php $__env->startSection('title', 'Reservations'); ?>
<?php $__env->startSection('page-title', 'Amenity Reservations'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="reservationCalendar()" @keydown.window="handleShortcuts($event)" class="space-y-8 animate-fade-in pb-20">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Reservations
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage facility bookings, verify payments, and monitor community schedules.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.amenity-reservations.create')); ?>" class="btn-premium flex items-center gap-3 group">
                    <i class="bi bi-plus-circle text-brand-accent group-hover:rotate-12 transition-transform"></i>
                    <span>Create Reservation</span>
                </a>
                <div class="flex items-center bg-white rounded-2xl p-1.5 border border-gray-100 shadow-sm ring-4 ring-gray-50/50">
                    <button @click="changeDate(-1)" class="w-10 h-10 flex items-center justify-center hover:bg-emerald-50 text-gray-400 hover:text-emerald-600 rounded-xl transition-all">
                        <i class="bi bi-chevron-left text-sm"></i>
                    </button>
                    <div class="relative px-3">
                        <input type="date" x-model="currentDate" @change="fetchData" 
                               class="bg-transparent border-none text-[11px] font-black uppercase tracking-widest text-gray-900 focus:ring-0 cursor-pointer w-36 text-center py-1">
                    </div>
                    <button @click="changeDate(1)" class="w-10 h-10 flex items-center justify-center hover:bg-emerald-50 text-gray-400 hover:text-emerald-600 rounded-xl transition-all">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </button>
                </div>
                
                <button @click="activityLogOpen = true" class="btn-premium flex items-center gap-3 group">
                    <i class="bi bi-clock-history text-brand-accent group-hover:rotate-12 transition-transform"></i>
                    <span>Activity Log</span>
                </button>
            </div>
        </div>
    </div>

    
    
    
    <div class="grid grid-cols-12 gap-8 items-start">
        
        
        <div class="col-span-12 lg:col-span-8 space-y-8">
            
            
            <div class="glass-card p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4 px-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                        <i class="bi bi-lightning-charge-fill text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Action Queue</h2>
                        <div class="flex items-center gap-2 mt-0.5" x-show="actionable.length > 0">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-xs font-black text-red-500 uppercase tracking-tighter" x-text="actionable.length + ' Pending'"></span>
                        </div>
                    </div>
                </div>
                
                
                <div class="flex bg-gray-50 rounded-2xl p-1 border border-gray-100">
                    <button @click="filterType = 'all'" 
                            :class="filterType === 'all' ? 'bg-brand-darker text-brand-accent shadow-lg' : 'text-gray-500 hover:text-brand-accent hover:bg-brand-darker hover:shadow-lg'" 
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">All</button>
                    <button @click="filterType = 'verify_payment'" 
                            :class="filterType === 'verify_payment' ? 'bg-brand-darker text-brand-accent shadow-lg' : 'text-gray-500 hover:text-brand-accent hover:bg-brand-darker hover:shadow-lg'" 
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Payments</button>
                    <button @click="filterType = 'review'" 
                            :class="filterType === 'review' ? 'bg-brand-darker text-brand-accent shadow-lg' : 'text-gray-500 hover:text-brand-accent hover:bg-brand-darker hover:shadow-lg'" 
                            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Approvals</button>
                </div>
            </div>

            
            <div class="space-y-4">
                <template x-for="req in filteredActionable" :key="req.id">
                    <div @click="selectRequest(req)" 
                         class="glass-card p-6 cursor-pointer transition-all hover:bg-emerald-50/20 group relative overflow-hidden"
                         :class="selectedRequest?.id === req.id ? 'border-emerald-500 ring-4 ring-emerald-500/5 bg-emerald-50/30' : ''">
                         
                         <div class="flex justify-between items-start mb-6">
                             <div class="flex items-center gap-3">
                                 <div class="w-2 h-2 rounded-full ring-4" 
                                      :class="{
                                         'bg-emerald-500 ring-emerald-100': req.status_color === 'emerald' || req.status_color === 'green',
                                         'bg-red-500 ring-red-100': req.status_color === 'red',
                                         'bg-orange-500 ring-orange-100': req.status_color === 'orange' || req.status_color === 'yellow',
                                         'bg-gray-400 ring-gray-100': !req.status_color
                                      }"></div>
                                 <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest" x-text="req.status_reason"></span>
                                 <template x-if="req.is_overdue">
                                     <span class="bg-red-50 text-red-600 text-[9px] font-black px-2 py-1 rounded-lg uppercase tracking-widest border border-red-100">Overdue</span>
                                 </template>
                             </div>
                             <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest tabular-nums" x-text="req.created_at_formatted"></span>
                         </div>

                         <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                             
                             <div class="col-span-1">
                                 <div class="flex items-center gap-4">
                                     <div class="w-12 h-12 rounded-2xl bg-gray-50 flex-shrink-0 flex items-center justify-center border border-gray-100 shadow-sm group-hover:scale-110 transition-transform duration-500">
                                         <template x-if="req.amenity_image">
                                             <img :src="req.amenity_image" class="w-full h-full object-cover rounded-2xl">
                                         </template>
                                         <template x-if="!req.amenity_image">
                                             <i class="bi bi-building text-gray-300 text-xl"></i>
                                         </template>
                                     </div>
                                     <div class="min-w-0">
                                         <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Facility</p>
                                         <p class="font-black text-sm text-gray-900 truncate" x-text="req.amenity_name"></p>
                                     </div>
                                 </div>
                             </div>

                             
                             <div class="col-span-1">
                                 <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Customer</p>
                                 <p class="font-black text-sm text-gray-900 truncate" x-text="req.resident_name"></p>
                                 <div class="flex items-center gap-2 mt-0.5">
                                     <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-tight" x-text="req.unit"></p>
                                     <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest border"
                                           :class="req.customer_type === 'Non-Resident' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100'"
                                           x-text="req.customer_type"></span>
                                 </div>
                             </div>

                             
                             <div class="col-span-1">
                                 <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Schedule</p>
                                 <p class="font-black text-sm text-gray-900 truncate tabular-nums" x-text="req.date"></p>
                                 <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight mt-0.5 tabular-nums" x-text="req.time_slot"></p>
                             </div>

                             
                             <div class="col-span-1 md:text-right">
                                 <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Price</p>
                                 <p class="font-black text-lg text-gray-900 tabular-nums" x-text="'₱' + req.total_price"></p>
                                 <span class="inline-block mt-1 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest border border-gray-100 bg-gray-50 text-gray-600" x-text="req.payment_method"></span>
                             </div>
                         </div>

                         
                         <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-12 bg-emerald-500 rounded-l-full transform translate-x-full transition-transform duration-300"
                              :class="selectedRequest?.id === req.id ? 'translate-x-0' : ''"></div>
                    </div>
                </template>

                
                <div x-show="actionable.length === 0" class="glass-card py-20 text-center animate-zoom-in">
                    <div class="w-20 h-20 bg-emerald-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 text-emerald-500 shadow-inner">
                        <i class="bi bi-check-all text-4xl"></i>
                    </div>
                    <h3 class="text-gray-900 font-black text-xl uppercase tracking-tight">All Caught Up!</h3>
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mt-2 max-w-xs mx-auto">No pending reservations requiring your attention</p>
                </div>

                
                <div x-show="cancelledReservations.length > 0" class="glass-card p-6 border border-red-100 bg-red-50/20">
                    <h4 class="text-sm font-black text-red-700 uppercase tracking-widest mb-4">Cancelled Reservations</h4>
                    <template x-for="cancel in cancelledReservations" :key="cancel.id">
                        <div class="p-3 mb-3 rounded-xl border border-red-100 bg-white/80 flex justify-between items-start gap-3">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">"<span x-text="cancel.amenity_name"></span>"</p>
                                <p class="text-xs text-gray-800 font-black" x-text="cancel.resident_name + ' - ' + cancel.time_slot"></p>
                                <p class="text-[9px] text-red-600 font-semibold" x-text="cancel.cancellation_reason || 'cancelled'"></p>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-red-700" x-text="cancel.date"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        
        <div class="col-span-12 lg:col-span-4 sticky top-8">
            <div x-show="selectedRequest" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-10"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="glass-card overflow-hidden flex flex-col max-h-[calc(100vh-8rem)] relative border-none shadow-2xl">
                
                
                <div class="p-8 bg-gray-900 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-48 h-48 bg-brand-accent/10 rounded-full blur-3xl"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-1">Reservation Info</p>
                            <h3 class="font-black text-2xl text-white tracking-tight">Details</h3>
                        </div>
                        <button @click="selectedRequest = null" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all border border-white/10 shadow-lg">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                    
                    <div class="mt-8 relative z-10 flex items-center gap-3">
                        <span class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border shadow-xl inline-flex items-center gap-2"
                              :class="{
                                  'bg-emerald-500 text-white border-emerald-400 shadow-emerald-500/20': selectedRequest?.action_type === 'verify_payment',
                                  'bg-[#B6FF5C] text-[#081412] border-[#B6FF5C]/50 shadow-[#B6FF5C]/20': selectedRequest?.action_type === 'review',
                                  'bg-orange-500 text-white border-orange-400 shadow-orange-500/20': selectedRequest?.action_type === 'collect_payment',
                                  'bg-gray-700 text-white border-gray-600 shadow-black/20': !['verify_payment', 'review', 'collect_payment'].includes(selectedRequest?.action_type)
                              }">
                            <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                            <span x-text="selectedRequest?.status_reason"></span>
                        </span>

                        <template x-if="selectedRequest?.is_overdue">
                            <span class="px-4 py-2.5 rounded-xl bg-red-500 text-white text-[10px] font-black uppercase tracking-widest border border-red-400 shadow-lg shadow-red-500/20">
                                Overdue
                            </span>
                        </template>
                    </div>
                </div>

                
                <div class="flex-1 overflow-y-auto p-8 space-y-8 custom-scrollbar">
                    
                    
                    <template x-if="selectedRequest && selectedRequest.status === 'cancelled'">
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center flex-shrink-0 text-lg">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Cancellation Details</p>
                                    <p class="text-sm font-medium text-gray-800 leading-relaxed break-words" x-text="selectedRequest.cancellation_reason || 'No reason provided'"></p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-3" x-text="'Cancelled ' + (selectedRequest.created_at_formatted || '')"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-2xl shadow-sm">
                            <span x-text="(selectedRequest?.resident_name || '?').charAt(0)"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-black text-gray-900 text-lg truncate tracking-tight mb-0.5" x-text="selectedRequest?.resident_name"></p>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-100" x-text="selectedRequest?.unit"></span>
                                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[9px] font-black uppercase tracking-widest rounded-lg border border-amber-100" x-show="selectedRequest?.customer_type === 'Non-Resident'">Walk-in</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100/50">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Amenity</p>
                            <p class="text-[11px] font-black text-gray-900 uppercase truncate" x-text="selectedRequest?.amenity_name"></p>
                        </div>
                        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100/50">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Date</p>
                            <p class="text-[11px] font-black text-gray-900 uppercase tabular-nums" x-text="selectedRequest?.date"></p>
                        </div>
                        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100/50">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Time Slot</p>
                            <p class="text-[11px] font-black text-gray-900 uppercase tabular-nums" x-text="selectedRequest?.time_slot"></p>
                        </div>
                        <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100/50">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Guest Count</p>
                            <p class="text-[11px] font-black text-gray-900 uppercase tabular-nums" x-text="selectedRequest?.guest_count + ' PAX'"></p>
                        </div>
                    </div>

                    <template x-if="selectedRequest?.notes">
                        <div class="bg-emerald-50/30 rounded-2xl p-5 border border-emerald-50">
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2">Special Notes</p>
                            <p class="text-xs font-bold text-emerald-900 italic leading-relaxed" x-text="'&ldquo;' + selectedRequest.notes + '&rdquo;'"></p>
                        </div>
                    </template>

                    
                    <div class="space-y-4">
                        <div class="rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                            <div class="bg-emerald-50/50 p-5 flex justify-between items-center border-b border-emerald-50">
                                <span class="text-[9px] font-black text-emerald-900 uppercase tracking-widest">Total Amount</span>
                                <span class="text-xl font-black text-emerald-600 tabular-nums" x-text="'₱' + selectedRequest?.total_price"></span>
                            </div>
                            
                            <div class="p-5 bg-white">
                                <template x-if="selectedRequest?.payment_method === 'gcash'">
                                    <div class="space-y-5">
                                        <div class="flex justify-between items-center">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Ref No.</span>
                                            <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                                <span class="font-black text-xs text-gray-900 tabular-nums" x-text="selectedRequest?.payment_reference_no || 'N/A'"></span>
                                                <button @click="navigator.clipboard.writeText(selectedRequest?.payment_reference_no)" class="text-gray-300 hover:text-emerald-500 transition-all"><i class="bi bi-files text-xs"></i></button>
                                            </div>
                                        </div>
                                        
                                        <template x-if="selectedRequest?.payment_proof">
                                            <div class="space-y-3">
                                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Proof of Payment</p>
                                                <a :href="selectedRequest?.payment_proof" target="_blank" class="block rounded-xl overflow-hidden border border-gray-100 relative group cursor-zoom-in">
                                                    <img :src="selectedRequest?.payment_proof" class="w-full h-auto object-contain bg-gray-50 max-h-48 transition-transform duration-700 group-hover:scale-105">
                                                </a>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <template x-if="selectedRequest?.payment_method === 'cash'">
                                    <div class="text-center py-6">
                                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-orange-100">
                                            <i class="bi bi-wallet2 text-xl"></i>
                                        </div>
                                        <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Cash Collection</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="p-8 bg-gray-50/50 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <button @click="openRejectionModal(selectedRequest.id)" 
                            :disabled="processingIds.includes(selectedRequest?.id)"
                            class="px-6 py-4 bg-white border border-red-100 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-red-50 transition-all disabled:opacity-50">
                        Reject
                    </button>
                    
                    <template x-if="selectedRequest?.action_type === 'verify_payment'">
                        <button @click="verifyPayment(selectedRequest.id)" 
                                :disabled="processingIds.includes(selectedRequest?.id)"
                                class="px-6 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-600 transition-all disabled:opacity-50 shadow-lg">
                            <span x-show="!processingIds.includes(selectedRequest?.id)">Approve</span>
                            <span x-show="processingIds.includes(selectedRequest?.id)"><i class="bi bi-arrow-repeat animate-spin"></i></span>
                        </button>
                    </template>
                    
                    <template x-if="selectedRequest?.action_type === 'review'">
                        <button @click="quickAction(selectedRequest.id, 'approved')" 
                                :disabled="processingIds.includes(selectedRequest?.id)"
                                class="px-6 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-600 transition-all disabled:opacity-50 shadow-lg">
                            <span x-show="!processingIds.includes(selectedRequest?.id)">Approve</span>
                            <span x-show="processingIds.includes(selectedRequest?.id)"><i class="bi bi-arrow-repeat animate-spin"></i></span>
                        </button>
                    </template>

                    <template x-if="selectedRequest?.action_type === 'collect_payment'">
                        <button @click="verifyPayment(selectedRequest.id)" 
                                :disabled="processingIds.includes(selectedRequest?.id)"
                                class="px-6 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-600 transition-all disabled:opacity-50 shadow-lg">
                            <span x-show="!processingIds.includes(selectedRequest?.id)">Confirm Cash</span>
                            <span x-show="processingIds.includes(selectedRequest?.id)"><i class="bi bi-arrow-repeat animate-spin"></i></span>
                        </button>
                    </template>
                </div>

                
                <div x-show="selectedRequest && selectedRequest.status !== 'cancelled' && (selectedRequest.status === 'approved' || selectedRequest.status === 'pending')" 
                     class="p-8 bg-red-50/30 border-t border-red-100">
                    <button @click="openCancelModal(selectedRequest.id)" 
                            class="w-full px-6 py-4 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-red-600 transition-all shadow-lg">
                        Cancel Reservation
                    </button>
                </div>
            </div>

            
            <div x-show="!selectedRequest" class="glass-card border-2 border-dashed border-gray-100 flex flex-col items-center justify-center p-12 text-center h-[500px]">
                <div class="w-20 h-20 bg-gray-50 rounded-[32px] flex items-center justify-center mb-6 text-gray-200 border border-gray-50 shadow-inner">
                    <i class="bi bi-cursor text-4xl"></i>
                </div>
                <h4 class="font-black text-gray-900 text-lg uppercase tracking-tight">No Request Selected</h4>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-3 max-w-[200px] leading-relaxed mx-auto">Select an item from the queue to manage details</p>
            </div>
        </div>
    </div>

    
    <div class="glass-card overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-900 text-brand-accent flex items-center justify-center shadow-lg">
                    <i class="bi bi-calendar3 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Schedule Timeline</h3>
                    <p class="text-sm font-black text-gray-900 uppercase tracking-tighter mt-0.5">Visual Availability Map</p>
                </div>
            </div>
            <button @click="showTimeline = !showTimeline" class="btn-secondary py-2 px-5 text-[10px]">
                <span x-text="showTimeline ? 'Hide View' : 'Show View'"></span>
            </button>
        </div>
        
        <div x-show="showTimeline" x-collapse>
            <div class="p-8 h-[500px] relative flex flex-col">
                <div class="bg-white border border-gray-100 rounded-3xl shadow-inner overflow-hidden flex flex-col h-full relative">
                    
                    <div class="flex border-b border-gray-100 overflow-hidden bg-gray-50 flex-shrink-0">
                        <div class="w-48 flex-shrink-0 border-r border-gray-100 p-4 flex items-center justify-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Facility</div>
                        <div class="flex-1 overflow-hidden" id="timeline-header">
                            <div class="flex" :style="'width: ' + (hours.length * 120) + 'px'">
                                <template x-for="hour in hours" :key="hour">
                                    <div class="w-[120px] flex-shrink-0 border-r border-gray-100 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest tabular-nums" x-text="formatHour(hour)"></div>
                                </template>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex-1 overflow-auto relative bg-white custom-scrollbar" id="timeline-body" @scroll="syncScroll">
                        <div class="relative" :style="'width: ' + (192 + (hours.length * 120)) + 'px'">
                            <template x-for="amenity in amenities" :key="amenity.id">
                                <div class="flex border-b border-gray-50 h-24 relative hover:bg-emerald-50/10 transition-colors group">
                                    
                                    <div class="w-48 flex-shrink-0 border-r border-gray-100 p-4 flex flex-col justify-center sticky left-0 z-20 bg-white group-hover:bg-emerald-50/30 transition-colors shadow-[8px_0_15px_-10px_rgba(0,0,0,0.05)]">
                                        <div class="font-black text-gray-900 text-xs truncate uppercase tracking-tighter" x-text="amenity.name"></div>
                                        <div class="text-[9px] font-black text-emerald-600 mt-1 uppercase tracking-widest" x-text="amenity.capacity + ' bookings'"></div>
                                    </div>

                                    
                                    <div class="flex-1 relative">
                                        <div class="absolute inset-0 flex pointer-events-none">
                                            <template x-for="hour in hours" :key="hour">
                                                <div class="w-[120px] flex-shrink-0 border-r border-gray-50 h-full"></div>
                                            </template>
                                        </div>
                                        
                                        
                                        <template x-for="res in getReservationsForAmenity(amenity.id)" :key="res.id">
                                            <div class="absolute top-2 bottom-2 rounded-2xl border p-3 text-[9px] overflow-hidden cursor-help transition-all hover:shadow-xl hover:z-30 flex flex-col justify-center select-none shadow-sm"
                                                 :class="res.status === 'cancelled' ? 'bg-red-50 border-red-100 text-red-700' : 'bg-emerald-50 border-emerald-100 text-emerald-700'"
                                                 :style="getEventStyle(res)"
                                                 :title="res.resident_name + ' (' + res.time_slot + ')'">
                                                <div class="font-black truncate uppercase tracking-widest mb-0.5" x-text="res.resident_name"></div>
                                                <div class="font-bold opacity-60 tabular-nums" x-text="res.time_slot + (res.status === 'cancelled' ? ' (CANCELLED)' : '')"></div>
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






<div x-show="rejectionModalOpen" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;"
     x-transition.opacity>
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="closeRejectionModal"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-md rounded-[32px] shadow-2xl overflow-hidden p-10 animate-zoom-in" @click.stop>
            <div class="w-20 h-20 bg-red-50 rounded-[24px] flex items-center justify-center mx-auto mb-8 text-red-500 border border-red-100 shadow-sm">
                <i class="bi bi-x-circle text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight text-center uppercase">Reject Reservation</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2 mb-10 text-center">State the reason for rejection. This will be sent to the resident.</p>
            
            <textarea x-model="rejectionReason" 
                      class="w-full px-6 py-5 rounded-2xl border border-gray-100 bg-gray-50 text-sm font-medium focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 focus:bg-white transition-all placeholder-gray-300 resize-none" 
                      rows="4" 
                      placeholder="e.g. Invalid payment proof, maintenance required..."></textarea>
            
            <div class="grid grid-cols-2 gap-4 mt-10">
                <button @click="closeRejectionModal" class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:bg-gray-50 rounded-2xl transition-all">Cancel</button>
                <button @click="confirmRejection" 
                        :disabled="!rejectionReason.trim() || processingIds.includes(selectedRejectionId)"
                        class="px-6 py-4 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:shadow-xl hover:shadow-red-500/20 transition-all disabled:opacity-50">
                    Reject Now
                </button>
            </div>
        </div>
    </div>
</div>


<div x-show="activityLogOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" @click="activityLogOpen = false"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden max-h-[85vh] flex flex-col relative animate-zoom-in">
            <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 text-brand-accent flex items-center justify-center shadow-xl">
                        <i class="bi bi-clock-history text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Reservation Log</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter mt-0.5">Real-time Activity Audit</p>
                    </div>
                </div>
                <button @click="activityLogOpen = false" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-all shadow-sm">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-10 space-y-8 custom-scrollbar">
                <template x-for="log in activities" :key="log.id">
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0 mt-1 relative">
                            <div class="w-1 h-full bg-gray-100 absolute left-1/2 -translate-x-1/2 top-10 group-last:hidden"></div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg relative z-10"
                                 :class="{
                                     'bg-emerald-500 shadow-emerald-200': log.status_color === 'green' || log.status_color === 'emerald',
                                     'bg-red-500 shadow-red-200': log.status_color === 'red',
                                     'bg-brand-accent text-[#081412] shadow-brand-accent/20': log.status_color === 'yellow' || log.status_color === 'orange',
                                     'bg-gray-400 shadow-gray-200': log.status_color === 'gray'
                                 }">
                                <i class="bi text-lg" :class="{
                                    'bi-check-lg': log.action.includes('approved') || log.action.includes('verified'),
                                    'bi-x-lg': log.action.includes('rejected'),
                                    'bi-clock': log.action.includes('pending'),
                                    'bi-info-circle': true
                                }"></i>
                            </div>
                        </div>
                        <div class="flex-1 pb-8 border-b border-gray-50 group-last:border-0">
                            <p class="text-base text-gray-900 leading-relaxed">
                                <span class="font-black" x-text="log.admin_name"></span>
                                <span class="text-gray-500 font-bold lowercase" x-text="log.action.replace('_', ' ')"></span>
                                <span class="font-black uppercase tracking-tighter text-xs">reservation</span>
                            </p>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2 tabular-nums flex items-center gap-2">
                                <i class="bi bi-clock"></i>
                                <span x-text="log.time_ago"></span>
                            </p>
                            <template x-if="log.details && log.details.reason">
                                <div class="mt-5 p-5 bg-gray-50 rounded-2xl border border-gray-100 relative">
                                    <div class="absolute -left-1 top-5 w-2.5 h-2.5 bg-gray-50 border-l border-b border-gray-100 rotate-45"></div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Reason</p>
                                    <p class="text-sm font-bold text-gray-600 italic leading-relaxed" x-text="log.details.reason"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in {
        animation: zoomIn 0.2s ease-out forwards;
    }
</style>

<script>
    function reservationCalendar() {
        return {
            currentDate: '<?php echo e(now()->format("Y-m-d")); ?>',
            amenities: [],
            reservations: [],
            actionable: [],
            cancelledReservations: [],
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
            cancelModalOpen: false,
            cancelReservationId: null,
            cancelReason: '',
            cancelNotes: '',
            canceling: false,

            init() {
                this.fetchData();
                setInterval(() => this.fetchData(), 30000);
            },

            get filteredActionable() {
                if (this.filterType === 'all') return this.actionable;
                return this.actionable.filter(req => req.action_type === this.filterType || (this.filterType === 'review' && req.action_type === 'collect_payment'));
            },

            async fetchData() {
                try {
                    const response = await fetch(`<?php echo e(route('admin.amenity-reservations.data')); ?>?date=${this.currentDate}`);
                    const data = await response.json();
                    
                    this.amenities = data.amenities;
                    this.reservations = data.reservations;
                    this.actionable = data.actionable;
                    this.cancelledReservations = data.cancelled_reservations || [];
                    this.activities = data.activities;

                    if (this.selectedRequest) {
                        const updated = this.actionable.find(r => r.id === this.selectedRequest.id);
                        this.selectedRequest = updated || null;
                    } else {
                        const requestedId = new URLSearchParams(window.location.search).get('active_id');
                        if (requestedId) {
                            const allReservations = [...this.actionable, ...this.reservations];
                            this.selectedRequest = allReservations.find(r => String(r.id) === String(requestedId)) || null;
                        }
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            },

            selectRequest(req) {
                this.selectedRequest = req;
            },

            async verifyPayment(id) {
                if (!confirm('Are you sure you want to verify this payment?')) return;
                
                this.processingIds.push(id);
                try {
                    const response = await fetch(`/admin/amenity-reservations/${id}/verify-payment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        if (data.success) {
                            alert('Payment verified successfully!');
                            
                            // Open receipt in new window
                            if (data.receipt_url) {
                                window.open(data.receipt_url, '_blank');
                            }
                            
                            // Refresh data and clear selection
                            await this.fetchData();
                            if (this.selectedRequest?.id === id) {
                                this.selectedRequest = null;
                            }
                        }
                    } else {
                        const data = await response.json();
                        alert(data.message || 'Error verifying payment');
                    }
                } catch (error) {
                    console.error('Payment verification error:', error);
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
                let startOffset = (res.start_hour - this.hours[0]) * 120;
                let width = res.duration * 120;
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
                    this.closeCancelModal();
                    this.activityLogOpen = false;
                }
            },

            openCancelModal(id) {
                this.cancelReservationId = id;
                this.cancelModalOpen = true;
                // Update form action
                document.getElementById('adminCancelForm').action = `/admin/amenity-reservations/${id}/cancel`;
            },

            closeCancelModal() {
                this.cancelModalOpen = false;
                this.cancelReservationId = null;
                this.cancelReason = '';
                this.cancelNotes = '';
                this.canceling = false;
            },

            async submitCancel() {
                if (!this.cancelReason || !this.cancelReservationId) return;

                this.canceling = true;
                try {
                    const response = await fetch(`/admin/amenity-reservations/${this.cancelReservationId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            cancellation_reason_id: this.cancelReason,
                            notes: this.cancelNotes
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.closeCancelModal();
                        await this.fetchData();
                        alert('Reservation cancelled successfully.');
                    } else {
                        alert(data.message || 'An error occurred while cancelling.');
                    }
                } catch (error) {
                    console.error('Cancel submission error', error);
                    alert('An error occurred. Please try again.');
                } finally {
                    this.canceling = false;
                }
            }
        }
    }
</script>


<div x-show="cancelModalOpen" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Cancel Reservation</h3>
                <button @click="closeCancelModal()" class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-all">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>

            <form id="adminCancelForm" method="POST" @submit.prevent="submitCancel">
                <?php echo csrf_field(); ?>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Reason for Cancellation</label>
                        <select x-model="cancelReason" required class="w-full p-4 border-2 border-gray-50 bg-gray-50 rounded-[16px] text-sm font-medium focus:ring-0 focus:border-red-500 focus:bg-white transition-all outline-none">
                            <option value="">Select a reason</option>
                            <?php $__currentLoopData = \App\Models\ReservationCancellationReason::where('active', true)->where(function($q) { $q->where('scope', 'admin')->orWhere('scope', 'both'); })->orderBy('sort_order')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($reason->id); ?>"><?php echo e($reason->label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Additional Notes (Optional)</label>
                        <textarea x-model="cancelNotes" rows="3" class="w-full p-4 border-2 border-gray-50 bg-gray-50 rounded-[16px] text-sm font-medium focus:ring-0 focus:border-red-500 focus:bg-white transition-all outline-none resize-none" placeholder="Any additional details..."></textarea>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-[16px] p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm mt-0.5"></i>
                            <p class="text-red-700 text-xs font-medium leading-relaxed">This action cannot be undone. The reservation will be permanently cancelled.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" @click="closeCancelModal()" class="flex-1 py-4 bg-gray-50 text-gray-700 text-sm font-black uppercase tracking-widest rounded-[16px] hover:bg-gray-100 transition-all">
                        Back
                    </button>
                    <button type="submit" id="adminConfirmCancelBtn" :disabled="canceling || !cancelReason" class="flex-1 py-4 bg-red-500 text-white text-sm font-black uppercase tracking-widest rounded-[16px] hover:bg-red-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!canceling">Confirm Cancellation</span>
                        <span x-show="canceling">Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\reservations\index.blade.php ENDPATH**/ ?>