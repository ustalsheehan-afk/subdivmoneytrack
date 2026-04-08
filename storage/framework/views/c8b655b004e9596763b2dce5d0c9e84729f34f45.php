

<?php $__env->startSection('title', 'Book ' . $amenity->name); ?>
<?php $__env->startSection('page-title', 'Book Amenity'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="bookingWizard()">
        
        <!-- Navigation Back -->
        <a href="<?php echo e(route('resident.amenities.index')); ?>" class="inline-flex items-center gap-3 text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-emerald-600 transition-all group w-fit">
            <div class="w-8 h-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center group-hover:border-emerald-100 group-hover:bg-emerald-50 transition-all">
                <i class="bi bi-arrow-left"></i>
            </div>
            <span>Back to Amenities</span>
        </a>

        <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fade-in max-w-3xl mx-auto">
            
            <!-- Header / Stepper -->
            <div class="bg-[#081412] p-6 md:p-7 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-accent/10 rounded-full blur-3xl group-hover:bg-brand-accent/20 transition-all duration-1000"></div>
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                            <i class="bi bi-calendar-check-fill text-emerald-400 text-xs"></i>
                            <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em]">Reservation Wizard</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight leading-none">Book <?php echo e($amenity->name); ?></h1>
                    </div>
                </div>
                
                <!-- Progress Stepper -->
                <div class="relative z-10 flex items-center justify-between max-w-xl mx-auto px-1">
                    <div class="absolute left-0 top-4 w-full h-1 bg-white/10 rounded-full"></div>
                    <div class="absolute left-0 top-4 h-1 bg-emerald-500 transition-all duration-700 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)]" :style="'width: ' + ((step - 1) / 3 * 100) + '%'"></div>

                    <!-- Step 1 -->
                    <div class="relative flex flex-col items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs transition-all duration-500 border-2"
                             :class="step >= 1 ? 'bg-emerald-500 border-emerald-400 text-black shadow-lg shadow-emerald-500/20' : 'bg-[#081412] border-white/10 text-white/40'">
                            1
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="step >= 1 ? 'text-emerald-400' : 'text-white/20'">Details</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative flex flex-col items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs transition-all duration-500 border-2"
                             :class="step >= 2 ? 'bg-emerald-500 border-emerald-400 text-black shadow-lg shadow-emerald-500/20' : 'bg-[#081412] border-white/10 text-white/40'">
                            2
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="step >= 2 ? 'text-emerald-400' : 'text-white/20'">Review</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative flex flex-col items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs transition-all duration-500 border-2"
                             :class="step >= 3 ? 'bg-emerald-500 border-emerald-400 text-black shadow-lg shadow-emerald-500/20' : 'bg-[#081412] border-white/10 text-white/40'">
                            3
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest transition-colors duration-500" :class="step >= 3 ? 'text-emerald-400' : 'text-white/20'">Payment</span>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative flex flex-col items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs transition-all duration-500 border-2 bg-[#081412] border-white/10 text-white/40">
                            4
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/20">Confirm</span>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form action="<?php echo e(route('resident.amenities.reserve', $amenity)); ?>" method="POST" class="p-5 md:p-7 bg-white" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="equipment_addons" :value="JSON.stringify(getSelectedEquipmentObjects())">
                
                <!-- STEP 1: Booking Details -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <!-- Left: Inputs -->
                        <div class="lg:col-span-7 space-y-6">
                            <!-- Date Selection -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                    <span class="w-8 h-px bg-gray-200"></span>
                                    01. Select Date
                                </label>
                                <div class="relative group">
                                    <input type="date" name="date" x-model="date" @change="checkAvailability()"
                                           min="<?php echo e(date('Y-m-d')); ?>"
                                           class="w-full p-5 text-lg font-black border-2 border-gray-100 rounded-[24px] focus:border-emerald-500 focus:ring-0 transition-all cursor-pointer bg-gray-50 hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 tabular-nums">
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 text-emerald-500 group-hover:scale-110 transition-transform pointer-events-none">
                                        <i class="bi bi-calendar-week text-xl"></i>
                                    </div>
                                </div>
                                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest flex items-center gap-2 mt-2" x-show="!isDateValid && date">
                                    <i class="bi bi-exclamation-triangle-fill"></i> This amenity is closed on this day.
                                </p>
                            </div>

                            <!-- Time & Duration -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8" x-show="isDateValid && date">
                                <div class="space-y-4">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                        <span class="w-8 h-px bg-gray-200"></span>
                                        02. Start Time
                                    </label>
                                    <div class="relative">
                                        <select name="start_time" x-model="startTime" class="w-full p-5 text-base font-black border-2 border-gray-100 rounded-[24px] focus:border-emerald-500 focus:ring-0 bg-gray-50 hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 cursor-pointer appearance-none transition-all tabular-nums">
                                            <option value="" disabled>Select Time Slot</option>
                                            <template x-for="time in generatedTimes" :key="time.value">
                                                <option :value="time.value" x-text="time.label"></option>
                                            </template>
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                        <span class="w-8 h-px bg-gray-200"></span>
                                        03. Duration
                                    </label>
                                    <div class="relative">
                                        <select name="duration" x-model="duration" class="w-full p-5 text-base font-black border-2 border-gray-100 rounded-[24px] focus:border-emerald-500 focus:ring-0 bg-gray-50 hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 cursor-pointer appearance-none transition-all tabular-nums">
                                            <?php for($i=1; $i<=10; $i++): ?>
                                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?> Hour<?php echo e($i > 1 ? 's' : ''); ?> Session</option>
                                            <?php endfor; ?>
                                        </select>
                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Guest Count -->
                            <div class="space-y-4">
                                <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                    <span class="w-8 h-px bg-gray-200"></span>
                                    04. Guest Count
                                </label>
                                <div class="flex items-center gap-4 bg-gray-50 p-5 rounded-[24px] border-2 border-gray-100 group hover:bg-white hover:shadow-xl transition-all">
                                    <button type="button" @click="guestCount > 1 ? guestCount-- : null" class="w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-xl font-black text-gray-900 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 hover:shadow-lg transition-all">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <div class="flex-1 text-center">
                                        <input type="number" name="guest_count" x-model="guestCount" 
                                               class="w-full text-center p-0 text-2xl font-black border-none bg-transparent text-gray-900 focus:ring-0 tabular-nums">
                                        <p class="text-[9px] font-black uppercase tracking-widest mt-1 transition-colors"
                                           :class="guestCount > maxCapacity ? 'text-red-500 animate-pulse' : 'text-gray-400'">
                                            <template x-if="guestCount > maxCapacity">
                                                <span><i class="bi bi-exclamation-circle-fill"></i> Exceeds Max Capacity (<?php echo e($amenity->max_capacity); ?>)</span>
                                            </template>
                                            <template x-if="guestCount <= maxCapacity">
                                                <span>Maximum Capacity: <?php echo e($amenity->max_capacity); ?> PAX</span>
                                            </template>
                                        </p>
                                    </div>
                                    <button type="button" @click="guestCount++" class="w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-xl font-black text-gray-900 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 hover:shadow-lg transition-all">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Equipment Add-ons -->
                            <div x-show="equipmentList.length > 0" class="space-y-4">
                                <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                    <span class="w-8 h-px bg-gray-200"></span>
                                    05. Optional Add-ons
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="(item, index) in equipmentList" :key="index">
                                        <label class="flex items-center p-5 border-2 border-gray-100 rounded-[24px] cursor-pointer hover:bg-white hover:shadow-lg transition-all relative overflow-hidden group"
                                               :class="selectedEquipmentIndices.includes(index) ? 'border-emerald-500 bg-emerald-50/30' : 'bg-gray-50'">
                                            <input type="checkbox" :value="index" x-model="selectedEquipmentIndices" class="sr-only">
                                            <div class="flex-1 flex justify-between items-center relative z-10">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center border transition-all"
                                                         :class="selectedEquipmentIndices.includes(index) ? 'bg-emerald-500 border-emerald-400 text-black' : 'bg-white border-gray-100 text-gray-400'">
                                                        <i class="bi" :class="selectedEquipmentIndices.includes(index) ? 'bi-check-lg' : 'bi-plus'"></i>
                                                    </div>
                                                    <span class="font-black text-sm text-gray-900 tracking-tight" x-text="item.name"></span>
                                                </div>
                                                <span class="font-black text-sm text-emerald-600 tabular-nums" x-text="'+₱' + parseFloat(item.price).toFixed(0)"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Summary & Notes -->
                        <div class="lg:col-span-5">
                            <div class="sticky top-6 space-y-6">
                                <div class="glass-card p-6 space-y-6">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-black text-gray-900 tracking-tight">Reservation Summary</div>
                                        <div class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Estimated</div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700">
                                                    <i class="bi bi-calendar-week"></i>
                                                </div>
                                                <div class="leading-tight">
                                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</div>
                                                    <div class="text-[12px] font-black text-gray-900 tabular-nums" x-text="date ? formatDate(date) : '—'"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700">
                                                    <i class="bi bi-clock"></i>
                                                </div>
                                                <div class="leading-tight">
                                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Time</div>
                                                    <div class="text-[12px] font-black text-gray-900 tabular-nums" x-text="startTime ? formatTimeRange() : '—'"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="leading-tight">
                                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Guests</div>
                                                    <div class="text-[12px] font-black text-gray-900 tabular-nums" x-text="guestCount ? (guestCount + ' People') : '—'"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="selectedEquipmentIndices.length > 0" class="pt-5 border-t border-gray-100 space-y-3">
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Add-ons</div>
                                        <template x-for="index in selectedEquipmentIndices" :key="index">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="text-[12px] font-bold text-gray-700" x-text="equipmentList[index].name"></div>
                                                <div class="text-[12px] font-black text-gray-900 tabular-nums" x-text="'₱' + parseFloat(equipmentList[index].price).toFixed(0)"></div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="pt-5 border-t border-gray-100">
                                        <div class="flex items-end justify-between gap-4">
                                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Estimated Cost</div>
                                            <div class="text-right">
                                                <div class="text-2xl font-black text-emerald-700 tracking-tight tabular-nums" x-text="'₱' + calculateTotal().toFixed(0)"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-5 border-t border-gray-100 space-y-2">
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Special Requests / Notes</div>
                                        <textarea name="notes" rows="3" class="w-full p-4 rounded-2xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-0 transition-all text-sm font-medium leading-relaxed" placeholder="Optional..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="button" @click="validateStep1() && (step = 2)" 
                                :disabled="!isValidStep1()"
                                class="px-8 py-4 bg-[#081412] text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_25px_rgba(182,255,92,0.2)] transition-all disabled:opacity-30 disabled:cursor-not-allowed flex items-center gap-3 group/btn border border-white/5">
                            <span>Review Booking Details</span>
                            <i class="bi bi-arrow-right text-brand-accent group-hover/btn:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Review -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="space-y-12">
                        <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-[32px] p-8 flex items-start gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 shrink-0">
                                <i class="bi bi-info-circle-fill text-2xl"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-lg font-black text-gray-900 tracking-tight">Review your information</h4>
                                <p class="text-sm font-medium text-gray-500">Please verify all booking details before proceeding to payment. Once confirmed, your reservation will be sent for review.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div class="space-y-8">
                                <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
                                    <span class="w-8 h-px bg-gray-200"></span>
                                    Facility Info
                                </h3>
                                <div class="flex items-start gap-6 bg-gray-50 p-6 rounded-[32px] border border-gray-100">
                                    <div class="w-32 h-32 rounded-[24px] overflow-hidden shrink-0 shadow-xl border-4 border-white">
                                        <?php if($amenity->image): ?>
                                            <img src="<?php echo e(Storage::url($amenity->image)); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-white flex items-center justify-center">
                                                <i class="bi bi-building text-4xl text-gray-200"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="space-y-2">
                                        <h4 class="text-2xl font-black text-gray-900 tracking-tight"><?php echo e($amenity->name); ?></h4>
                                        <p class="text-sm font-medium text-gray-500 leading-relaxed"><?php echo e(Str::limit($amenity->description, 100)); ?></p>
                                        <div class="pt-2">
                                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-500/20">Active Facility</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
                                    <span class="w-8 h-px bg-gray-200"></span>
                                    Reservation Data
                                </h3>
                                <div class="bg-white rounded-[32px] border border-gray-100 p-8 space-y-6 shadow-sm">
                                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</span>
                                        <span class="text-sm font-black text-gray-900 tracking-tight"><?php echo e(Auth::user()->name); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</span>
                                        <span class="text-sm font-black text-emerald-600 tracking-tight tabular-nums" x-text="formatDate(date)"></span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Time Slot</span>
                                        <span class="text-sm font-black text-gray-900 tracking-tight tabular-nums" x-text="formatTimeRange()"></span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Guests</span>
                                        <span class="text-sm font-black text-gray-900 tracking-tight tabular-nums" x-text="guestCount + ' PAX'"></span>
                                    </div>
                                    <div class="flex justify-between items-center py-3" x-show="selectedEquipmentIndices.length > 0">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Add-ons</span>
                                        <span class="text-sm font-black text-gray-900 tracking-tight" x-text="selectedEquipmentIndices.length + ' Items'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-16 pt-8 border-t border-gray-100 flex justify-between items-center">
                        <button type="button" @click="step = 1" class="px-8 py-4 text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-all flex items-center gap-2 group">
                            <i class="bi bi-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Modify Details
                        </button>
                        <button type="button" @click="step = 3" class="px-10 py-5 bg-[#081412] text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_25px_rgba(182,255,92,0.2)] transition-all flex items-center gap-3 group/btn border border-white/5">
                            <span>Proceed to Payment</span>
                            <i class="bi bi-arrow-right text-brand-accent group-hover/btn:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Payment -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="max-w-3xl mx-auto space-y-12">
                        
                        <div class="text-center space-y-3">
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Total Reservation Fee</p>
                            <h2 class="text-6xl font-black text-emerald-600 tracking-tighter tabular-nums" x-text="'₱' + calculateTotal().toFixed(0)"></h2>
                        </div>

                        <div class="space-y-6">
                            <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                <span class="w-8 h-px bg-gray-200"></span>
                                Select Payment Method
                            </label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Cash Option -->
                                <label class="relative flex flex-col p-8 rounded-[32px] border-2 cursor-pointer transition-all group overflow-hidden"
                                       :class="paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl'">
                                    <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="sr-only">
                                    
                                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-emerald-500 shadow-sm border border-gray-100 group-hover:scale-110 transition-transform mb-6">
                                        <i class="bi bi-cash-stack text-2xl"></i>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <span class="block font-black text-xl text-gray-900 tracking-tight">On-site Payment</span>
                                        <span class="block text-xs font-medium text-gray-500">Pay directly at the office</span>
                                    </div>
                                    
                                    <div class="absolute top-8 right-8 w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center transition-all"
                                         :class="paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-500 shadow-lg shadow-emerald-500/20' : ''">
                                        <i class="bi bi-check text-white font-black" x-show="paymentMethod === 'cash'"></i>
                                    </div>
                                </label>

                                <!-- GCash Option -->
                                <label class="relative flex flex-col p-8 rounded-[32px] border-2 cursor-pointer transition-all group overflow-hidden"
                                       :class="paymentMethod === 'gcash' ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl'">
                                    <input type="radio" name="payment_method" value="gcash" x-model="paymentMethod" class="sr-only">
                                    
                                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-blue-500 shadow-sm border border-gray-100 group-hover:scale-110 transition-transform mb-6">
                                        <i class="bi bi-wallet2 text-2xl"></i>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <span class="block font-black text-xl text-gray-900 tracking-tight">GCash Mobile</span>
                                        <span class="block text-xs font-medium text-gray-500">Fast & secure digital payment</span>
                                    </div>
                                    
                                    <div class="absolute top-8 right-8 w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center transition-all"
                                         :class="paymentMethod === 'gcash' ? 'border-emerald-500 bg-emerald-500 shadow-lg shadow-emerald-500/20' : ''">
                                        <i class="bi bi-check text-white font-black" x-show="paymentMethod === 'gcash'"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- GCash Details -->
                        <div x-show="paymentMethod === 'gcash'" x-collapse class="space-y-8 animate-fade-in">
                             <div class="bg-[#081412] rounded-[40px] p-10 shadow-2xl relative overflow-hidden text-center group">
                                <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-1000"></div>
                                
                                <div class="relative z-10 space-y-8">
                                    <div class="space-y-2">
                                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-[0.3em]">Scan to Pay</p>
                                        <h4 class="text-2xl font-black text-white tracking-tight">GCash Digital Transfer</h4>
                                    </div>
                                    
                                    <div class="bg-white p-6 rounded-[32px] inline-block shadow-2xl relative group/qr">
                                        <img src="<?php echo e(asset('images/gcash-qr.jpg')); ?>" alt="GCash QR" class="w-56 h-56 object-contain">
                                        <a href="<?php echo e(asset('images/gcash-qr.jpg')); ?>" download="GCash-QR.jpg" 
                                           class="absolute inset-0 bg-[#081412]/80 backdrop-blur-sm flex flex-col items-center justify-center opacity-0 group-hover/qr:opacity-100 transition-all duration-500 rounded-[32px]">
                                            <i class="bi bi-download text-3xl text-white mb-2"></i>
                                            <span class="text-[10px] font-black text-white uppercase tracking-widest">Download QR</span>
                                        </a>
                                    </div>

                                    <div class="space-y-6 pt-4">
                                        <div class="bg-white/5 border border-white/5 p-6 rounded-[24px] inline-block">
                                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.3em] mb-2">Account Number</p>
                                            <p class="text-4xl font-black text-white tracking-[0.1em] font-mono tabular-nums">0905 530 3469</p>
                                            <p class="text-[11px] font-black text-blue-400 uppercase tracking-widest mt-3">Mussah Ustal</p>
                                        </div>
                                    </div>
                                </div>
                             </div>

                             <!-- Payment Proof Upload -->
                             <div class="bg-white rounded-[40px] p-10 border border-gray-100 shadow-sm space-y-8">
                                <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                                    <span class="w-8 h-px bg-gray-200"></span>
                                    Verification Details
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest pl-2">Reference Number</p>
                                        <input type="text" name="payment_reference_no" placeholder="Enter 13-digit code" 
                                               x-model="referenceNo"
                                               class="w-full p-6 rounded-[24px] border-2 border-gray-50 focus:border-emerald-500 focus:ring-0 bg-gray-50 hover:bg-white transition-all font-mono text-lg font-black tracking-widest tabular-nums">
                                    </div>

                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest pl-2">Payment Receipt</p>
                                        <div class="relative h-20">
                                            <input type="file" name="payment_proof" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                                                   @change="fileSelected($event)" accept="image/*">
                                            <div class="absolute inset-0 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[24px] flex items-center justify-between px-6 transition-all group hover:bg-white hover:border-emerald-500/30"
                                                 :class="fileName ? 'border-emerald-500 bg-emerald-50/50' : ''">
                                                <div class="flex items-center gap-4">
                                                    <i class="bi text-xl" :class="fileName ? 'bi-file-earmark-check-fill text-emerald-500' : 'bi-cloud-upload text-gray-400 group-hover:text-emerald-500'"></i>
                                                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest truncate max-w-[150px]" x-text="fileName || 'Upload Screenshot'"></span>
                                                </div>
                                                <span class="text-[9px] font-black text-emerald-600 uppercase" x-show="fileName">Change File</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <div class="mt-16 pt-8 border-t border-gray-100 flex justify-between items-center">
                            <button type="button" @click="step = 2" class="px-8 py-4 text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-all flex items-center gap-2 group">
                                <i class="bi bi-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                                Back
                            </button>
                            <button type="submit" 
                                    :disabled="paymentMethod === 'gcash' && (!referenceNo || !fileName)"
                                    class="px-10 py-5 bg-emerald-500 text-black text-[11px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-400 hover:shadow-[0_0_25px_rgba(182,255,92,0.4)] transition-all flex items-center gap-3 group/btn border border-emerald-400 disabled:opacity-30 disabled:cursor-not-allowed shadow-xl shadow-emerald-500/20">
                                <i class="bi bi-shield-check text-lg"></i>
                                <span>Confirm & Submit Reservation</span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function bookingWizard() {
        return {
            step: 1,
            date: '',
            startTime: '',
            duration: 1,
            guestCount: 1,
            paymentMethod: 'cash',
            referenceNo: '',
            fileName: '',
            
            // Constants
            maxCapacity: <?php echo e($amenity->max_capacity); ?>,
            pricePerHour: <?php echo e($amenity->price); ?>,
            daysAvailable: <?php echo json_encode($amenity->days_available ?? []); ?>,
            equipmentList: <?php echo json_encode($amenity->equipment ?? []); ?>,
            
            // State
            isDateValid: true,
            isLoading: false,
            generatedTimes: [],
            selectedEquipmentIndices: [],
            unavailableSlots: [],

            init() {
                this.$watch('date', () => this.checkAvailability());
                this.$watch('duration', () => this.generateTimes()); // Re-generate if duration changes to filter
                this.$watch('guestCount', (value) => {
                    if (value > this.maxCapacity) {
                        // Keep it at max for calculation but show warning in UI
                    }
                    if (value < 1) this.guestCount = 1;
                });
            },

            fileSelected(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                } else {
                    this.fileName = '';
                }
            },

            isValidStep1() {
                return this.date && this.isDateValid && this.startTime && this.guestCount > 0 && this.guestCount <= this.maxCapacity && !this.isLoading;
            },

            validateStep1() {
                if (!this.isValidStep1()) {
                    alert('Please complete all required fields correctly.');
                    return false;
                }
                return true;
            },

            async checkAvailability() {
                this.startTime = '';
                this.unavailableSlots = [];
                
                if (!this.date) return;

                const dateObj = new Date(this.date);
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                const dayName = days[dateObj.getUTCDay()];
                
                // 1. Check day of week
                const shortDayName = dayName; 
                const availableDaysShort = this.daysAvailable.map(d => d.substring(0, 3));

                if (!availableDaysShort.includes(shortDayName)) {
                    this.isDateValid = false;
                    this.generatedTimes = [];
                    return;
                }
                this.isDateValid = true;

                // 2. Fetch unavailable slots from backend
                this.isLoading = true;
                try {
                    const response = await fetch(`<?php echo e(route('resident.amenities.unavailable-slots', $amenity->id)); ?>?date=${this.date}`);
                    if (response.ok) {
                        this.unavailableSlots = await response.json();
                    }
                } catch (e) {
                    console.error('Failed to fetch availability', e);
                } finally {
                    this.isLoading = false;
                    this.generateTimes();
                }
            },

            generateTimes() {
                if (!this.isDateValid || this.isLoading) {
                    this.generatedTimes = [];
                    return;
                }

                // Generate times (8 AM to 8 PM) - Simplified range, should be dynamic based on amenity hours ideally
                // Assuming 8:00 to 20:00 for now as per original code
                let times = [];
                for(let h=8; h<=20; h++) {
                    // Start of this slot
                    let startH = h;
                    let startM = 0;
                    
                    // End of this slot (based on duration)
                    let endH = h + parseInt(this.duration);
                    let endM = 0; // Assuming hourly slots

                    // Check if this slot + duration fits within operational hours (e.g. closes at 22:00?)
                    // Assuming operational until 22:00 (10 PM) for safety, or just allow last booking at 20:00
                    if (endH > 22) continue; 

                    // Check overlap with unavailable slots
                    // Logic: (StartA < EndB) AND (EndA > StartB)
                    // Request: [startH:00, endH:00]
                    // Unavailable: [u.start, u.end]
                    
                    let isConflict = false;
                    
                    // Convert request time to minutes for easier comparison
                    let reqStartMin = startH * 60 + startM;
                    let reqEndMin = endH * 60 + endM;

                    for (let slot of this.unavailableSlots) {
                        let [uStartH, uStartM] = slot.start.split(':').map(Number);
                        let [uEndH, uEndM] = slot.end.split(':').map(Number);
                        
                        let uStartMin = uStartH * 60 + uStartM;
                        let uEndMin = uEndH * 60 + uEndM;

                        if (reqStartMin < uEndMin && reqEndMin > uStartMin) {
                            isConflict = true;
                            break;
                        }
                    }

                    if (!isConflict) {
                        let hour = h < 10 ? '0'+h : h;
                        let time = `${hour}:00`;
                        let ampm = h >= 12 ? 'PM' : 'AM';
                        let h12 = h % 12 || 12;
                        let label = `${h12}:00 ${ampm}`;
                        times.push({ value: time, label: label });
                    }
                }
                this.generatedTimes = times;
            },

            calculateTotal() {
                let total = this.pricePerHour * this.duration;
                // Add equipment cost
                this.selectedEquipmentIndices.forEach(index => {
                    const item = this.equipmentList[index];
                    if(item && item.price) {
                        total += parseFloat(item.price);
                    }
                });
                return total;
            },

            getSelectedEquipmentObjects() {
                return this.selectedEquipmentIndices.map(index => this.equipmentList[index]);
            },

            formatDate(dateStr) {
                if(!dateStr) return '';
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateStr).toLocaleDateString('en-US', options);
            },

            formatTimeRange() {
                if(!this.startTime) return '';
                
                let startH = parseInt(this.startTime.split(':')[0]);
                let endH = startH + parseInt(this.duration);
                
                const formatH = (h) => {
                    let ampm = h >= 12 ? 'PM' : 'AM';
                    let h12 = h % 12 || 12;
                    return `${h12}:00 ${ampm}`;
                };

                return `${formatH(startH)} - ${formatH(endH)}`;
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\amenities\show.blade.php ENDPATH**/ ?>