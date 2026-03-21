@extends('resident.layouts.app')

@section('title', 'Book ' . $amenity->name)
@section('page-title', 'Book Amenity')

@section('content')
<div class="max-w-4xl mx-auto p-4 md:p-8" x-data="bookingWizard()">
    
    <!-- Navigation Back -->
    <a href="{{ route('resident.amenities.index') }}" class="inline-flex items-center text-gray-500 hover:text-blue-600 font-medium mb-8 transition-colors">
        <i class="bi bi-arrow-left mr-2"></i> Back to Amenities
    </a>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        <!-- Header / Stepper -->
        <div class="bg-gray-50 border-b border-gray-100 p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Book {{ $amenity->name }}</h1>
            
            <!-- Progress Stepper -->
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 -z-0"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-blue-600 transition-all duration-500 -z-0" :style="'width: ' + ((step - 1) / 3 * 100) + '%'"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300"
                         :class="step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                        1
                    </div>
                    <span class="text-xs font-semibold mt-2 uppercase tracking-wide" :class="step >= 1 ? 'text-blue-700' : 'text-gray-400'">Details</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300"
                         :class="step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                        2
                    </div>
                    <span class="text-xs font-semibold mt-2 uppercase tracking-wide" :class="step >= 2 ? 'text-blue-700' : 'text-gray-400'">Review</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300"
                         :class="step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'">
                        3
                    </div>
                    <span class="text-xs font-semibold mt-2 uppercase tracking-wide" :class="step >= 3 ? 'text-blue-700' : 'text-gray-400'">Payment</span>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 bg-gray-200 text-gray-500">
                        4
                    </div>
                    <span class="text-xs font-semibold mt-2 uppercase tracking-wide text-gray-400">Confirm</span>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form action="{{ route('resident.amenities.reserve', $amenity) }}" method="POST" class="p-6 md:p-10" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="equipment_addons" :value="JSON.stringify(getSelectedEquipmentObjects())">
            
            <!-- STEP 1: Booking Details -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left: Inputs -->
                    <div class="space-y-6">
                        <!-- Date -->
                        <div>
                            <label class="block text-lg font-bold text-gray-800 mb-2">Select Date</label>
                            <input type="date" name="date" x-model="date" @change="checkAvailability()"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full p-4 text-lg border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors cursor-pointer bg-gray-50 hover:bg-white">
                            <p class="mt-2 text-sm text-red-600 font-medium" x-show="!isDateValid && date">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i> Sorry, this amenity is closed on this day.
                            </p>
                        </div>

                        <!-- Time & Duration -->
                        <div class="grid grid-cols-2 gap-4" x-show="isDateValid && date">
                            <div>
                                <label class="block text-lg font-bold text-gray-800 mb-2">Start Time</label>
                                <select name="start_time" x-model="startTime" class="w-full p-4 text-lg border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-gray-50 hover:bg-white cursor-pointer">
                                    <option value="" disabled>Select...</option>
                                    <template x-for="time in generatedTimes" :key="time.value">
                                        <option :value="time.value" x-text="time.label"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-lg font-bold text-gray-800 mb-2">Duration</label>
                                <select name="duration" x-model="duration" class="w-full p-4 text-lg border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-gray-50 hover:bg-white cursor-pointer">
                                    <option value="1">1 Hour</option>
                                    <option value="2">2 Hours</option>
                                    <option value="3">3 Hours</option>
                                    <option value="4">4 Hours</option>
                                </select>
                            </div>
                        </div>

                        <!-- Guest Count -->
                        <div>
                            <label class="block text-lg font-bold text-gray-800 mb-2">Number of Guests</label>
                            <div class="flex items-center gap-4">
                                <button type="button" @click="guestCount > 1 ? guestCount-- : null" class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-xl font-bold transition-colors">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="guest_count" x-model="guestCount" readonly
                                       class="w-20 text-center p-3 text-xl font-bold border-2 border-gray-200 rounded-xl bg-white">
                                <button type="button" @click="guestCount < maxCapacity ? guestCount++ : null" class="w-12 h-12 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-xl font-bold transition-colors">
                                    <i class="bi bi-plus"></i>
                                </button>
                                <span class="text-gray-500 text-sm">Max {{ $amenity->max_capacity }}</span>
                            </div>
                        </div>

                        <!-- Equipment Add-ons -->
                        <div x-show="equipmentList.length > 0">
                            <label class="block text-lg font-bold text-gray-800 mb-2">Optional Add-ons</label>
                            <div class="space-y-3">
                                <template x-for="(item, index) in equipmentList" :key="index">
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors"
                                           :class="selectedEquipmentIndices.includes(index) ? 'border-blue-500 bg-blue-50' : ''">
                                        <input type="checkbox" :value="index" x-model="selectedEquipmentIndices" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 mr-3">
                                        <div class="flex-1 flex justify-between items-center">
                                            <span class="font-medium text-gray-900" x-text="item.name"></span>
                                            <span class="font-bold text-blue-600" x-text="'+₱' + parseFloat(item.price).toFixed(2)"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary & Notes -->
                    <div class="bg-blue-50 rounded-2xl p-6 md:p-8 flex flex-col h-full">
                        <h3 class="text-xl font-bold text-blue-900 mb-4">Reservation Summary</h3>
                        
                        <div class="space-y-4 flex-1">
                            <div class="flex justify-between items-center text-blue-800">
                                <span class="text-blue-600"><i class="bi bi-calendar-event mr-2"></i>Date</span>
                                <span class="font-bold" x-text="date ? formatDate(date) : 'Select Date'"></span>
                            </div>
                            <div class="flex justify-between items-center text-blue-800">
                                <span class="text-blue-600"><i class="bi bi-clock mr-2"></i>Time</span>
                                <span class="font-bold" x-text="startTime ? formatTimeRange() : 'Select Time'"></span>
                            </div>
                            <div class="flex justify-between items-center text-blue-800">
                                <span class="text-blue-600"><i class="bi bi-people mr-2"></i>Guests</span>
                                <span class="font-bold" x-text="guestCount + ' People'"></span>
                            </div>

                            <template x-for="index in selectedEquipmentIndices" :key="index">
                                <div class="flex justify-between items-center text-blue-800 text-sm mt-2 pl-4 border-l-2 border-blue-200">
                                    <span><i class="bi bi-plus-circle mr-2"></i><span x-text="equipmentList[index].name"></span></span>
                                    <span class="font-bold" x-text="'₱' + parseFloat(equipmentList[index].price).toFixed(2)"></span>
                                </div>
                            </template>
                            
                            <hr class="border-blue-200 my-4">
                            
                            <div class="flex justify-between items-center text-lg">
                                <span class="text-blue-800 font-medium">Estimated Cost</span>
                                <span class="text-2xl font-bold text-blue-700" x-text="'₱' + calculateTotal().toFixed(2)"></span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label class="block text-sm font-bold text-blue-800 mb-2">Special Requests / Notes</label>
                            <textarea name="notes" rows="2" class="w-full p-3 rounded-xl border border-blue-200 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="button" @click="validateStep1() && (step = 2)" 
                            :disabled="!isValidStep1()"
                            class="px-8 py-4 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        Review Details <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 2: Review -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div class="space-y-8">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
                        <i class="bi bi-info-circle-fill text-yellow-600 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="font-bold text-yellow-800">Please review your booking details</h4>
                            <p class="text-yellow-700 text-sm">Ensure all information is correct before proceeding to payment.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Amenity Information</h3>
                            <div class="flex items-start gap-4">
                                @if($amenity->image)
                                    <img src="{{ Storage::url($amenity->image) }}" class="w-24 h-24 rounded-lg object-cover bg-gray-100">
                                @else
                                    <div class="w-24 h-24 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="bi bi-image text-3xl text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-xl text-gray-900">{{ $amenity->name }}</h4>
                                    <p class="text-gray-500 text-sm mt-1">{{ $amenity->description }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Your Details</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Name</span>
                                    <span class="font-bold text-gray-900">{{ Auth::user()->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Email</span>
                                    <span class="font-bold text-gray-900">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Date Selected</span>
                                    <span class="font-bold text-gray-900" x-text="formatDate(date)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Time Slot</span>
                                    <span class="font-bold text-gray-900" x-text="formatTimeRange()"></span>
                                </div>
                            </div>

                            <!-- Add-ons Review -->
                            <div class="mt-6" x-show="selectedEquipmentIndices.length > 0">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-3">Selected Add-ons</h4>
                                <ul class="space-y-2">
                                    <template x-for="index in selectedEquipmentIndices" :key="index">
                                        <li class="flex justify-between text-gray-900">
                                            <span x-text="equipmentList[index].name"></span>
                                            <span class="font-bold" x-text="'₱' + parseFloat(equipmentList[index].price).toFixed(2)"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-between">
                    <button type="button" @click="step = 1" class="px-6 py-3 text-gray-600 font-bold hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors">
                        <i class="bi bi-arrow-left mr-2"></i> Edit Details
                    </button>
                    <button type="button" @click="step = 3" class="px-8 py-4 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                        Proceed to Payment <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- STEP 3: Payment -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div class="max-w-2xl mx-auto space-y-8">
                    
                    <div class="text-center mb-8">
                        <p class="text-gray-500 mb-2">Total Amount to Pay</p>
                        <h2 class="text-4xl font-extrabold text-blue-600" x-text="'₱' + calculateTotal().toFixed(2)"></h2>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-lg font-bold text-gray-900 mb-4">Choose Payment Method</label>
                        
                        <!-- Cash Option -->
                        <label class="relative flex items-center p-6 rounded-2xl border-2 cursor-pointer transition-all group"
                               :class="paymentMethod === 'cash' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:border-blue-200 hover:bg-gray-50'">
                            <input type="radio" name="payment_method" value="cash" x-model="paymentMethod" class="sr-only">
                            <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-4">
                                <i class="bi bi-cash-stack text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <span class="block font-bold text-lg text-gray-900">Cash Payment</span>
                                <span class="block text-sm text-gray-500">Pay at the administration office</span>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                 :class="paymentMethod === 'cash' ? 'border-blue-600 bg-blue-600' : ''">
                                <i class="bi bi-check text-white text-sm" x-show="paymentMethod === 'cash'"></i>
                            </div>
                        </label>

                        <!-- GCash Option -->
                        <label class="relative flex items-center p-6 rounded-2xl border-2 cursor-pointer transition-all group"
                               :class="paymentMethod === 'gcash' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:border-blue-200 hover:bg-gray-50'">
                            <input type="radio" name="payment_method" value="gcash" x-model="paymentMethod" class="sr-only">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                <i class="bi bi-wallet2 text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <span class="block font-bold text-lg text-gray-900">GCash</span>
                                <span class="block text-sm text-gray-500">Scan QR or send to number</span>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                 :class="paymentMethod === 'gcash' ? 'border-blue-600 bg-blue-600' : ''">
                                <i class="bi bi-check text-white text-sm" x-show="paymentMethod === 'gcash'"></i>
                            </div>
                        </label>
                    </div>

                    <!-- GCash Details -->
                    <div x-show="paymentMethod === 'gcash'" x-collapse class="mt-6">
                         <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-2xl p-8 shadow-lg text-center relative overflow-hidden mb-8">
                            <div class="relative z-10">
                                <h4 class="text-xl font-bold mb-6">Send Payment Here</h4>
                                <div class="bg-white p-4 rounded-xl inline-block mb-4 shadow-sm">
                                    <img src="{{ asset('images/gcash-qr.jpg') }}" alt="GCash QR" class="w-48 h-48 object-contain">
                                </div>

                                {{-- Download Button --}}
                                <div class="mb-6">
                                    <a href="{{ asset('images/gcash-qr.jpg') }}" download="GCash-QR.jpg" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-lg transition-all border border-white/20 backdrop-blur-sm">
                                        <i class="bi bi-download"></i>
                                        Download QR
                                    </a>
                                </div>

                                <div class="text-lg">
                                    <p class="opacity-80 text-sm uppercase tracking-widest mb-1">GCash Number</p>
                                    <p class="text-3xl font-bold tracking-widest font-mono">0905 530 3469</p>
                                    <p class="opacity-80 mt-2">Mussah Ustal</p>
                                </div>
                            </div>
                            <!-- Decor -->
                            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                         </div>

                         <!-- Payment Proof Upload -->
                         <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 space-y-6">
                            <h4 class="font-bold text-gray-900 border-b border-gray-200 pb-2">Submit Payment Proof</h4>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Reference Number <span class="text-gray-400 font-normal">(Optional if paying later)</span></label>
                                <input type="text" name="payment_reference_no" placeholder="e.g. 1234 567 890" 
                                       x-model="referenceNo"
                                       class="w-full p-3 rounded-xl border border-gray-300 focus:ring-blue-500 focus:border-blue-500 font-mono text-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Upload Screenshot <span class="text-gray-400 font-normal">(Optional if paying later)</span></label>
                                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-100 transition-colors cursor-pointer group">
                                    <input type="file" name="payment_proof" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                           @change="fileSelected($event)" accept="image/*">
                                    
                                    <div x-show="!fileName" class="space-y-2">
                                        <i class="bi bi-cloud-upload text-4xl text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                        <p class="text-gray-500 font-medium">Click to upload image</p>
                                        <p class="text-xs text-gray-400">JPG, PNG up to 5MB</p>
                                    </div>
                                    
                                    <div x-show="fileName" class="space-y-2">
                                        <i class="bi bi-file-earmark-image text-4xl text-blue-600"></i>
                                        <p class="text-blue-800 font-bold" x-text="fileName"></p>
                                        <p class="text-xs text-green-600">File selected</p>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="mt-10 flex justify-between items-center">
                        <button type="button" @click="step = 2" class="px-6 py-3 text-gray-600 font-bold hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors">
                            <i class="bi bi-arrow-left mr-2"></i> Back
                        </button>
                        <button type="submit" 
                                :disabled="paymentMethod === 'gcash' && (!referenceNo || !fileName)"
                                class="px-8 py-4 bg-green-600 text-white font-bold text-lg rounded-xl hover:bg-green-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="bi bi-check-lg"></i> Confirm & Pay
                        </button>
                    </div>
                </div>
            </div>

        </form>
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
            maxCapacity: {{ $amenity->max_capacity }},
            pricePerHour: {{ $amenity->price }},
            daysAvailable: {!! json_encode($amenity->days_available ?? []) !!},
            equipmentList: {!! json_encode($amenity->equipment ?? []) !!},
            
            // State
            isDateValid: true,
            isLoading: false,
            generatedTimes: [],
            selectedEquipmentIndices: [],
            unavailableSlots: [],

            init() {
                this.$watch('date', () => this.checkAvailability());
                this.$watch('duration', () => this.generateTimes()); // Re-generate if duration changes to filter
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
                return this.date && this.isDateValid && this.startTime && this.guestCount > 0 && !this.isLoading;
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
                    const response = await fetch(`{{ route('resident.amenities.unavailable-slots', $amenity->id) }}?date=${this.date}`);
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
@endsection
