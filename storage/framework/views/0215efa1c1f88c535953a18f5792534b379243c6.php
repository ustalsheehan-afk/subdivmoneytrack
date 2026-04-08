<?php $__env->startSection('title', 'Create Reservation'); ?>
<?php $__env->startSection('page-title', 'Create Reservation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-6" x-data="adminReservationWizard()">

    <div class="rounded-3xl overflow-hidden" style="background: #FFFFFF; border: 1px solid rgba(16, 185, 129, 0.2); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);">
        <div class="px-8 py-7 md:px-10 md:py-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div class="space-y-2">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-600">Reservation Wizard</p>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900">Create Amenity Reservation</h1>
                    <p class="text-sm text-gray-700">Create a reservation for a resident or non-resident using a structured wizard flow.</p>
                </div>
                <div class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-white/70 bg-white/5 rounded-full px-3 py-2">
                    <i class="bi bi-clock-history"></i> <span>Admin panel</span>
                </div>
            </div>

            <div class="mt-8">
                <div class="relative h-2 bg-white/10 rounded-full">
                    <div class="absolute inset-y-0 left-0 bg-emerald-400 rounded-full transition-all" :style="{ width: step >= 4 ? '100%' : (step - 1) / 3 * 100 + '%' }"></div>
                </div>

                <div class="mt-4 grid grid-cols-4 gap-3">
                    <template x-for="(label, index) in ['Details', 'Review', 'Payment', 'Confirm']" :key="label">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all"
                                 :class="step >= index + 1 ? 'bg-emerald-400 text-black shadow-lg' : 'bg-[#152a28] text-white/40 border border-white/10'">
                                <span x-text="index + 1"></span>
                            </div>
                            <p class="text-[10px] uppercase tracking-widest" :class="step >= index + 1 ? 'text-emerald-300' : 'text-white/20'" x-text="label"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <a href="<?php echo e(route('admin.amenity-reservations.index')); ?>" class="inline-flex items-center gap-3 text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-emerald-600 transition-all">
        <span class="w-8 h-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center"><i class="bi bi-arrow-left"></i></span>
        <span>Back to Reservations</span>
    </a>

    <div class="bg-white rounded-[32px] shadow-xl border border-gray-100 overflow-hidden">
        <form method="POST" action="<?php echo e(route('admin.amenity-reservations.store')); ?>" class="p-8 space-y-8">
            <?php echo csrf_field(); ?>

            <section x-show="step === 1" class="space-y-8">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <div class="xl:col-span-2 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <label class="rounded-3xl border-2 p-5 cursor-pointer transition-all" :class="customerType === 'resident' ? 'border-emerald-500 bg-emerald-50/40' : 'border-gray-100 bg-gray-50'">
                                <input type="radio" name="customer_type" value="resident" x-model="customerType" class="sr-only">
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Resident</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Use an existing resident account</p>
                            </label>
                            <label class="rounded-3xl border-2 p-5 cursor-pointer transition-all" :class="customerType === 'non_resident' ? 'border-emerald-500 bg-emerald-50/40' : 'border-gray-100 bg-gray-50'">
                                <input type="radio" name="customer_type" value="non_resident" x-model="customerType" class="sr-only">
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">Non-Resident</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Walk-in or external booking</p>
                            </label>
                        </div>

                        <div x-show="customerType === 'resident'" class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Resident</label>
                            <div class="relative">
                                <input type="text" x-model="residentSearch" placeholder="Search resident..." class="w-full p-4 rounded-3xl border-2 border-gray-100 bg-white text-sm font-black focus:border-emerald-500 focus:ring-0" />
                                <div class="absolute inset-x-0 top-full mt-2 z-40 max-h-56 overflow-y-auto rounded-2xl border border-gray-200 bg-white shadow-lg" x-show="residentSearch || filteredResidents.length > 0" x-cloak>
                                    <template x-for="resident in filteredResidents" :key="resident.user_id">
                                        <button type="button" @click="selectResident(resident)" class="w-full text-left px-4 py-3 hover:bg-emerald-50 transition-colors focus:outline-none" :class="selectedResidentId === resident.user_id ? 'bg-emerald-100' : 'bg-white'">
                                            <div class="font-black text-gray-900" x-text="resident.name"></div>
                                            <div class="text-xs text-gray-500" x-text="`Block ${resident.block} Lot ${resident.lot}`"></div>
                                        </button>
                                    </template>
                                    <div x-show="filteredResidents.length === 0" class="px-4 py-3 text-xs text-gray-500">No resident matches.</div>
                                </div>
                            </div>
                            <input type="hidden" name="resident_id" :value="selectedResidentId" />
                            <p class="text-xs text-emerald-600" x-show="selectedResidentId">Selected: <span x-text="selectedResident?.name"></span> (<span x-text="`Block ${selectedResident?.block} Lot ${selectedResident?.lot}`"></span>)</p>
                        </div>

                        <div x-show="customerType === 'non_resident'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="guest_name" x-model="guest.name" placeholder="Enter full name" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                                <p class="mt-2 text-xs text-red-500" x-show="guestNameError" x-text="guestNameError"></p>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Contact Number <span class="text-red-500">*</span></label>
                                <input type="text" name="guest_contact" x-model="guest.contact" placeholder="Enter contact number" @input="formatContact()" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                                <p class="mt-2 text-xs text-red-500" x-show="guestContactError" x-text="guestContactError"></p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Email</label>
                                <input type="email" name="guest_email" x-model="guest.email" placeholder="Enter email address" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Amenity</label>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <template x-for="amenity in amenities" :key="amenity.id">
                                        <button type="button" @click="selectAmenity(amenity)" class="text-left p-4 border rounded-2xl transition-all hover:shadow-lg" :class="selectedAmenityId == amenity.id ? 'border-emerald-500 bg-emerald-50 shadow-lg' : 'border-gray-200 bg-white'">
                                            <p class="font-black text-sm text-gray-900" x-text="amenity.name"></p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="amenity.description || 'No description available'" ></p>
                                            <p class="text-[10px] uppercase tracking-widest mt-3 text-gray-400">₱<span x-text="Number(amenity.price || 0).toFixed(2)"></span> • <span x-text="amenity.max_capacity + ' Pax'"></span></p>
                                        </button>
                                    </template>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Click an amenity card to choose; or use the time and duration fields below.</p>
                                <input type="hidden" name="amenity_id" :value="selectedAmenityId" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Date</label>
                                <input type="date" name="date" x-model="date" @change="checkAvailability()" min="<?php echo e(now()->format('Y-m-d')); ?>" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Time Slot</label>
                                <select name="start_time" x-model="startTime" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                                    <option value="">Choose time</option>
                                    <template x-for="time in generatedTimes" :key="time.value">
                                        <option :value="time.value" x-text="time.label"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Duration</label>
                                <select name="duration" x-model="duration" @change="generateTimes()" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                                    <template x-for="hour in [1,2,3,4,5,6]" :key="hour">
                                        <option :value="hour" x-text="hour + ' Hour' + (hour > 1 ? 's' : '')"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Time Slot</label>
                                <select name="start_time" x-model="startTime" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                                    <option value="">Choose time</option>
                                    <template x-for="time in generatedTimes" :key="time.value">
                                        <option :value="time.value" x-text="time.label"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Duration</label>
                                <select name="duration" x-model="duration" @change="generateTimes()" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                                    <template x-for="hour in [1,2,3,4,5,6]" :key="hour">
                                        <option :value="hour" x-text="hour + ' Hour' + (hour > 1 ? 's' : '')"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Guest Count</label>
                                <input type="number" name="guest_count" x-model="guestCount" min="1" :max="selectedAmenity?.max_capacity || 1" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-black focus:border-emerald-500 focus:ring-0">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Notes</label>
                                <textarea name="notes" x-model="notes" rows="4" class="w-full p-5 rounded-3xl border-2 border-gray-100 bg-gray-50 font-medium focus:border-emerald-500 focus:ring-0 resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <aside class="space-y-4">
                        <div class="rounded-3xl border border-gray-100 bg-gray-50 p-6">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Amenity Card</p>
                            <template x-if="selectedAmenity">
                                <div class="space-y-4 mt-4">
                                    <template x-if="selectedAmenity.image_url">
                                        <img :src="selectedAmenity.image_url" :alt="selectedAmenity.name" class="w-full h-48 rounded-3xl object-cover border border-gray-100">
                                    </template>
                                    <div>
                                        <h3 class="text-xl font-black text-gray-900" x-text="selectedAmenity.name"></h3>
                                        <p class="text-sm text-gray-600 mt-2" x-text="selectedAmenity.description || 'Amenity details will appear here.'"></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Rate</p>
                                            <p class="text-lg font-black text-emerald-600" x-text="'₱' + Number(selectedAmenity.price || 0).toFixed(2)"></p>
                                        </div>
                                        <div class="rounded-2xl bg-white border border-gray-100 p-4">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Capacity</p>
                                            <p class="text-lg font-black text-gray-900" x-text="selectedAmenity.max_capacity + ' Pax'"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="!selectedAmenity">
                                <div class="rounded-3xl border border-dashed border-gray-200 p-8 text-center text-gray-400 mt-4">
                                    <i class="bi bi-building text-3xl"></i>
                                    <p class="text-[10px] font-black uppercase tracking-widest mt-3">Choose an amenity to preview</p>
                                </div>
                            </template>
                        </div>
                    </aside>
                </div>

                <div class="border-t border-gray-100 pt-6 flex justify-end">
                    <button type="button" @click="goToReview()" class="px-8 py-4 rounded-2xl bg-emerald-500 text-black text-[11px] font-black uppercase tracking-widest border border-emerald-400">Review Reservation</button>
                </div>
            </section>

            <section x-show="step === 2" style="display:none;" class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="rounded-3xl border border-gray-100 p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Review</h3>
                            <button type="button" @click="step = 1" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Edit Details</button>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Amenity</span><span class="font-black text-gray-900" x-text="selectedAmenity?.name"></span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Customer Type</span><span class="font-black text-gray-900" x-text="customerType === 'non_resident' ? 'Non-Resident' : 'Resident'"></span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Customer</span><span class="font-black text-gray-900" x-text="customerDisplayName()"></span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Contact</span><span class="font-black text-gray-900" x-text="customerDisplayContact()"></span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Date</span><span class="font-black text-gray-900" x-text="formattedDate()"></span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Time</span><span class="font-black text-gray-900" x-text="formatTimeRange()"></span></div>
                            <div class="flex justify-between gap-4"><span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Guests</span><span class="font-black text-gray-900" x-text="guestCount + ' Pax'"></span></div>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-100 bg-gray-50 p-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Reservation Fee</p>
                        <p class="text-5xl font-black text-emerald-600 mt-4" x-text="'₱' + calculateTotal().toFixed(2)"></p>
                        <p class="text-sm text-gray-600 mt-4">Review the booking details, then continue to payment handling.</p>
                    </div>
                </div>
                <div class="border-t border-gray-100 pt-6 flex justify-between">
                    <button type="button" @click="step = 1" class="px-8 py-4 rounded-2xl border border-gray-200 text-[11px] font-black uppercase tracking-widest text-gray-500">Back</button>
                    <button type="button" @click="step = 3" class="px-8 py-4 rounded-2xl bg-emerald-500 text-black text-[11px] font-black uppercase tracking-widest border border-emerald-400">Continue to Payment</button>
                </div>
            </section>

            <section x-show="step === 3" style="display:none;" class="space-y-8">
                <div class="max-w-3xl mx-auto space-y-12">
                    
                    <div class="text-center space-y-3">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Total Reservation Fee</p>
                        <h2 class="text-6xl font-black text-emerald-600 tracking-tighter tabular-nums" x-text="'₱' + calculateTotal().toFixed(2)"></h2>
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

                    <div class="space-y-6">
                        <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3">
                            <span class="w-8 h-px bg-gray-200"></span>
                            Payment Status
                        </label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Paid Option -->
                            <label class="relative flex flex-col p-8 rounded-[32px] border-2 cursor-pointer transition-all group overflow-hidden"
                                   :class="paymentStatus === 'paid' ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl'">
                                <input type="radio" name="payment_status" value="paid" x-model="paymentStatus" class="sr-only">
                                
                                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-emerald-500 shadow-sm border border-gray-100 group-hover:scale-110 transition-transform mb-6">
                                    <i class="bi bi-check-circle-fill text-2xl"></i>
                                </div>
                                
                                <div class="space-y-1">
                                    <span class="block font-black text-xl text-gray-900 tracking-tight">Paid</span>
                                    <span class="block text-xs font-medium text-gray-500">Payment already received</span>
                                </div>
                                
                                <div class="absolute top-8 right-8 w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center transition-all"
                                     :class="paymentStatus === 'paid' ? 'border-emerald-500 bg-emerald-500 shadow-lg shadow-emerald-500/20' : ''">
                                    <i class="bi bi-check text-white font-black" x-show="paymentStatus === 'paid'"></i>
                                </div>
                            </label>

                            <!-- Pending Option -->
                            <label class="relative flex flex-col p-8 rounded-[32px] border-2 cursor-pointer transition-all group overflow-hidden"
                                   :class="paymentStatus === 'pending' ? 'border-amber-400 bg-amber-50/30' : 'border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl'">
                                <input type="radio" name="payment_status" value="pending" x-model="paymentStatus" class="sr-only">
                                
                                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-amber-500 shadow-sm border border-gray-100 group-hover:scale-110 transition-transform mb-6">
                                    <i class="bi bi-clock-fill text-2xl"></i>
                                </div>
                                
                                <div class="space-y-1">
                                    <span class="block font-black text-xl text-gray-900 tracking-tight">Pending</span>
                                    <span class="block text-xs font-medium text-gray-500">Payment to be collected later</span>
                                </div>
                                
                                <div class="absolute top-8 right-8 w-6 h-6 rounded-full border-2 border-gray-200 flex items-center justify-center transition-all"
                                     :class="paymentStatus === 'pending' ? 'border-amber-400 bg-amber-400 shadow-lg shadow-amber-400/20' : ''">
                                    <i class="bi bi-check text-white font-black" x-show="paymentStatus === 'pending'"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- GCash Reference Number -->
                    <div x-show="paymentMethod === 'gcash'" x-collapse class="space-y-6 animate-fade-in">
                        <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                            <label class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-3 mb-6">
                                <span class="w-8 h-px bg-gray-200"></span>
                                GCash Reference Number
                            </label>
                            <input type="text" name="payment_reference_no" x-model="referenceNo" 
                                   placeholder="Enter 13-digit reference code" 
                                   class="w-full p-6 rounded-[24px] border-2 border-gray-50 focus:border-emerald-500 focus:ring-0 bg-gray-50 hover:bg-white transition-all font-mono text-lg font-black tracking-widest tabular-nums">
                        </div>
                    </div>

                    <!-- Admin Controls (Subtle) -->
                    <div class="bg-gray-50 rounded-[24px] p-6 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Admin Override</p>
                                <p class="text-sm text-gray-600">Allow booking even if slot conflict exists</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="override" value="1" x-model="override" class="sr-only peer">
                                <div class="w-12 h-7 bg-gray-200 rounded-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-1 after:left-1 after:w-5 after:h-5 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-16 pt-8 border-t border-gray-100 flex justify-between items-center">
                        <button type="button" @click="step = 2" class="px-8 py-4 text-[11px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-all flex items-center gap-2 group">
                            <i class="bi bi-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Back
                        </button>
                        <button type="button" @click="step = 4" class="px-10 py-5 bg-emerald-500 text-black text-[11px] font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-400 hover:shadow-[0_0_25px_rgba(182,255,92,0.4)] transition-all flex items-center gap-3 group/btn border border-emerald-400 shadow-xl shadow-emerald-500/20">
                            <i class="bi bi-shield-check text-lg"></i>
                            <span>Finalize Booking</span>
                        </button>
                    </div>
                </div>
            </section>

            <section x-show="step === 4" style="display:none;" class="space-y-8">
                <div class="rounded-3xl bg-white p-8 text-gray-900 shadow-lg border border-gray-200">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em]">Confirmation</p>
                    <h2 class="text-3xl font-black tracking-tight mt-2">Ready to Create Reservation</h2>
                    <p class="text-sm text-gray-700 mt-3">The system will create the booking and generate the reference number on the next screen.</p>
                </div>
                <div class="rounded-3xl border border-gray-100 p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amenity</p><p class="text-lg font-black text-gray-900" x-text="selectedAmenity?.name"></p></div>
                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer</p><p class="text-lg font-black text-gray-900" x-text="customerDisplayName()"></p></div>
                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Time</p><p class="text-lg font-black text-gray-900" x-text="formattedDate()"></p><p class="text-sm font-bold text-gray-500 mt-1" x-text="formatTimeRange()"></p></div>
                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Payment</p><p class="text-lg font-black text-gray-900" x-text="paymentMethod === 'gcash' ? 'GCash' : 'On-site'"></p><p class="text-sm font-bold mt-1" :class="paymentStatus === 'paid' ? 'text-emerald-600' : 'text-amber-600'" x-text="paymentStatus === 'paid' ? 'Marked as Paid' : 'Pending Collection'"></p></div>
                    </div>
                    <div class="rounded-3xl bg-gray-50 border border-gray-100 p-5 flex items-center justify-between">
                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Fee</p><p class="text-3xl font-black text-emerald-600" x-text="'₱' + calculateTotal().toFixed(2)"></p></div>
                        <div class="text-right"><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Source</p><p class="text-sm font-black text-gray-900">Admin-Created</p></div>
                    </div>
                </div>
                <div class="border-t border-gray-100 pt-6 flex justify-between">
                    <button type="button" @click="step = 3" class="px-8 py-4 rounded-2xl border border-gray-200 text-[11px] font-black uppercase tracking-widest text-gray-500">Back</button>
                    <button type="submit" class="px-8 py-4 rounded-2xl bg-emerald-500 text-black text-[11px] font-black uppercase tracking-widest border border-emerald-400">Create Reservation</button>
                </div>
            </section>
        </form>
    </div>
</div>

<script>
function adminReservationWizard() {
    return {
        step: 1,
        customerType: 'resident',
        residentSearch: '',
        selectedResidentId: '',
        selectedAmenityId: '',
        date: '',
        startTime: '',
        duration: 1,
        guestCount: 1,
        paymentMethod: 'cash',
        paymentStatus: 'pending',
        referenceNo: '',
        notes: '',
        override: false,
        isDateValid: true,
        unavailableSlots: [],
        generatedTimes: [],
        guest: { name: '', contact: '', email: '' },
        guestNameError: '',
        guestContactError: '',
        amenities: <?php echo json_encode($amenities->map(fn ($amenity) => ['id' => $amenity->id, 'name' => $amenity->name, 'description' => $amenity->description, 'price' => $amenity->price, 'max_capacity' => $amenity->max_capacity, 'days_available' => $amenity->days_available ?? [], 'image_url' => $amenity->image ? asset('storage/' . $amenity->image) : null])->values()); ?>,
        residents: <?php echo json_encode($residents->map(fn ($resident) => ['user_id' => $resident->user_id, 'name' => $resident->full_name, 'contact' => $resident->contact_number, 'email' => $resident->email, 'block' => $resident->block, 'lot' => $resident->lot])->values()); ?>,
        get filteredResidents() {
            if (!this.residentSearch) return this.residents;
            const search = this.residentSearch.toLowerCase();
            return this.residents.filter(resident => {
                return resident.name.toLowerCase().includes(search) ||
                    resident.block.toLowerCase().includes(search) ||
                    resident.lot.toLowerCase().includes(search);
            });
        },
        get selectedAmenity() { return this.amenities.find(a => String(a.id) === String(this.selectedAmenityId)) || null; },
        get selectedResident() { return this.residents.find(r => String(r.user_id) === String(this.selectedResidentId)) || null; },
        init() { this.generateTimes(); this.$watch('date', () => this.checkAvailability()); },
        selectResident(resident) {
            this.selectedResidentId = resident.user_id;
            this.residentSearch = resident.name;
            this.guestNameError = '';
            this.guestContactError = '';
        },
        selectAmenity(amenity) {
            this.selectedAmenityId = amenity.id;
            this.onAmenityChange();
        },
        formatContact() {
            let raw = this.guest.contact.replace(/[^0-9]/g, '');
            if (raw.startsWith('09')) {
                if (raw.length > 4) raw = raw.slice(0, 4) + '-' + raw.slice(4);
                if (raw.length > 8) raw = raw.slice(0, 8) + '-' + raw.slice(8);
            }
            this.guest.contact = raw;
            this.guestContactError = raw.replace(/[^0-9]/g, '').length >= 10 ? '' : 'Contact number must be at least 10 digits';
        },
        async onAmenityChange() { this.startTime = ''; await this.checkAvailability(); },
        async checkAvailability() {
            this.startTime = '';
            this.unavailableSlots = [];
            if (!this.selectedAmenity || !this.date) { this.generateTimes(); return; }
            const dayIndex = new Date(this.date + 'T00:00:00').getDay();
            const dayCode = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][dayIndex];
            const days = (this.selectedAmenity.days_available || []).map(day => day.substring(0, 3));
            if (days.length && !days.includes(dayCode)) { this.isDateValid = false; this.generatedTimes = []; return; }
            this.isDateValid = true;
            try {
                const response = await fetch(`<?php echo e(url('admin/amenity-reservations')); ?>/${this.selectedAmenityId}/unavailable-slots?date=${this.date}`);
                if (response.ok) this.unavailableSlots = await response.json();
            } catch (error) { console.error('Failed to fetch slots', error); }
            this.generateTimes();
        },
        generateTimes() {
            if (!this.selectedAmenity || !this.isDateValid) { this.generatedTimes = []; return; }
            const times = [];
            for (let hour = 8; hour <= 20; hour++) {
                const endHour = hour + Number(this.duration);
                if (endHour > 22) continue;
                const requestStart = hour * 60;
                const requestEnd = endHour * 60;
                const conflict = this.unavailableSlots.some(slot => {
                    const [sh, sm] = slot.start.split(':').map(Number);
                    const [eh, em] = slot.end.split(':').map(Number);
                    const slotStart = sh * 60 + sm;
                    const slotEnd = eh * 60 + em;
                    return requestStart < slotEnd && requestEnd > slotStart;
                });
                if (!conflict) {
                    const labelHour = hour % 12 || 12;
                    times.push({ value: `${String(hour).padStart(2, '0')}:00`, label: `${labelHour}:00 ${hour >= 12 ? 'PM' : 'AM'}` });
                }
            }
            this.generatedTimes = times;
        },
        calculateTotal() { return this.selectedAmenity ? Number(this.selectedAmenity.price || 0) * Number(this.duration || 1) : 0; },
        formattedDate() { return this.date ? new Date(this.date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '—'; },
        formatTimeRange() {
            if (!this.startTime) return '—';
            const startHour = Number(this.startTime.split(':')[0]);
            const endHour = startHour + Number(this.duration || 1);
            const fmt = hour => `${hour % 12 || 12}:00 ${hour >= 12 ? 'PM' : 'AM'}`;
            return `${fmt(startHour)} - ${fmt(endHour)}`;
        },
        customerDisplayName() { return this.customerType === 'resident' ? (this.selectedResident?.name || 'Select a resident') : (this.guest.name || 'Enter guest name'); },
        customerDisplayContact() { return this.customerType === 'resident' ? (this.selectedResident?.contact || 'N/A') : (this.guest.contact || 'N/A'); },
        goToReview() {
            if (!this.selectedAmenityId || !this.date || !this.startTime || Number(this.guestCount) < 1) {
                alert('Please complete all reservation details before continuing.');
                return;
            }

            if (this.customerType === 'resident' && !this.selectedResidentId) {
                alert('Please select a resident before continuing.');
                return;
            }

            if (this.customerType === 'non_resident') {
                this.guestNameError = this.guest.name.trim() ? '' : 'Full name is required';
                this.guestContactError = this.guest.contact.trim().length >= 10 ? '' : 'Contact number is required and should be at least 10 digits';

                if (this.guestNameError || this.guestContactError) {
                    return;
                }
            }

            if (!this.isDateValid) {
                alert('The selected amenity is not available on that date.');
                return;
            }
            this.step = 2;
        },
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\reservations\create.blade.php ENDPATH**/ ?>