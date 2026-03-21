@extends('layouts.admin')

@section('title', 'Create Reservation')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.amenities.index') }}" class="mr-4 text-gray-500 hover:text-gray-700">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Create Reservation</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" x-data="adminBookingForm()">
        <form action="{{ route('admin.amenity-reservations.store') }}" method="POST" id="adminBookingForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Resident Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resident</label>
                    <select name="resident_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                        <option value="" disabled selected>Select Resident</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}">{{ $resident->name }} (Block {{ $resident->block }} Lot {{ $resident->lot }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Amenity Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amenity</label>
                    <select name="amenity_id" x-model="selectedAmenityId" @change="updateAmenityDetails()" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                        <option value="" disabled selected>Select Amenity</option>
                        @foreach($amenities as $amenity)
                            <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Amenity Details (Dynamic) -->
                <div x-show="selectedAmenity" class="bg-blue-50 p-4 rounded-lg border border-blue-100 flex justify-between items-center text-sm text-blue-800">
                    <div>
                        <span class="font-bold">Price:</span> <span x-text="'₱' + (selectedAmenity?.price || 0)"></span>/hour
                    </div>
                    <div>
                        <span class="font-bold">Capacity:</span> <span x-text="(selectedAmenity?.max_capacity || 0) + ' Pax'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" x-model="date" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                        <select name="start_time" x-model="startTime" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                            <option value="" disabled>Select time</option>
                            <template x-for="time in generatedTimes" :key="time.value">
                                <option :value="time.value" x-text="time.label"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (hours)</label>
                        <select name="duration" x-model="duration" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <option value="1">1 hour</option>
                            <option value="2">2 hours</option>
                            <option value="3">3 hours</option>
                            <option value="4">4 hours</option>
                        </select>
                    </div>

                    <!-- Guest Count -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guest Count</label>
                        <input type="number" name="guest_count" min="1" :max="selectedAmenity?.max_capacity || 100" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 rounded-xl border cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                            <span class="ml-3 font-medium text-gray-900">Cash</span>
                        </label>
                        <label class="relative flex items-center p-4 rounded-xl border cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="gcash" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-900">GCash</span>
                        </label>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm"></textarea>
                </div>

                <!-- Total Summary -->
                <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center" x-show="startTime && selectedAmenity">
                    <span class="text-gray-700 font-medium">Total Price:</span>
                    <span class="text-xl font-bold text-blue-600" x-text="'₱' + totalPrice.toFixed(2)"></span>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm transition-colors">
                        Create Reservation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function adminBookingForm() {
        return {
            selectedAmenityId: '',
            selectedAmenity: null,
            date: '',
            startTime: '',
            duration: 2,
            paymentMethod: 'cash',
            
            amenities: {!! json_encode($amenities) !!},
            generatedTimes: [],

            init() {
                // Generate times
                let times = [];
                for(let h=8; h<=20; h++) {
                    let hour = h < 10 ? '0'+h : h;
                    let time = `${hour}:00`;
                    let ampm = h >= 12 ? 'PM' : 'AM';
                    let h12 = h % 12 || 12;
                    let label = `${h12}:00 ${ampm}`;
                    times.push({ value: time, label: label });
                }
                this.generatedTimes = times;
            },

            updateAmenityDetails() {
                this.selectedAmenity = this.amenities.find(a => a.id == this.selectedAmenityId);
            },

            get totalPrice() {
                if (!this.selectedAmenity) return 0;
                return parseFloat(this.selectedAmenity.price) * parseInt(this.duration);
            }
        }
    }
</script>
@endsection