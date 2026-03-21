@extends('layouts.admin')

@section('title', 'Edit Amenity')
@section('page-title', 'Edit Amenity')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST" enctype="multipart/form-data" 
              x-data="{ 
                  equipmentList: {{ json_encode($amenity->equipment ?? []) }},
                  addEquipment() { this.equipmentList.push({ name: '', price: '' }); },
                  removeEquipment(index) { this.equipmentList.splice(index, 1); },
                  imagePreview: {{ json_encode($amenity->image ? Storage::url($amenity->image) : null) }},
                  fileChosen(event) {
                      this.fileToDataUrl(event, src => this.imagePreview = src)
                  },
                  fileToDataUrl(event, callback) {
                      if (! event.target.files.length) return
                      let file = event.target.files[0],
                          reader = new FileReader()
                      reader.readAsDataURL(file)
                      reader.onload = e => callback(e.target.result)
                  }
              }">
            @csrf
            @method('PUT')
            
            <div class="p-8 space-y-8">
                
                <!-- Amenity Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Amenity Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $amenity->name) }}" required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm"
                           placeholder="e.g., Community Pool">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Days Available -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Days Available <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-2">
                        @php $selectedDays = old('days_available', $amenity->days_available ?? []); @endphp
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="days_available[]" value="{{ $day }}" class="peer sr-only"
                                    {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all text-center min-w-[3.5rem]">
                                    {{ $day }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('days_available') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Time Slots -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Time Slots <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        @php $selectedSlots = old('time_slots', $amenity->time_slots ?? []); @endphp
                        @foreach(['Morning', 'Afternoon', 'Evening'] as $slot)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="time_slots[]" value="{{ $slot }}" 
                                           class="peer h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           {{ in_array($slot, $selectedSlots) ? 'checked' : '' }}>
                                </div>
                                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $slot }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('time_slots') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Capacity & Price -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Max Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="max_capacity" value="{{ old('max_capacity', $amenity->max_capacity) }}" required min="1"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm"
                               placeholder="1">
                        @error('max_capacity') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price (₱)</label>
                        <input type="number" name="price" value="{{ old('price', $amenity->price) }}" step="0.01" min="0"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm"
                               placeholder="0">
                        @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Buffer Time -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Buffer Time (Minutes)</label>
                    <input type="number" name="buffer_minutes" value="{{ old('buffer_minutes', $amenity->buffer_minutes) }}" min="0" step="5"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm"
                           placeholder="e.g., 30">
                    <p class="text-xs text-gray-500 mt-1">Time to block after each reservation for cleaning/maintenance.</p>
                    @error('buffer_minutes') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm"
                              placeholder="Describe the amenity and its features...">{{ old('description', $amenity->description) }}</textarea>
                </div>

                <!-- Images (Drag & Drop Style) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Images</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="image" accept="image/*" @change="fileChosen"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div x-show="!imagePreview">
                            <i class="bi bi-upload text-3xl text-gray-400 mb-2 block"></i>
                            <p class="text-sm text-gray-600 font-medium">Click to upload images or drag and drop</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 5MB</p>
                        </div>
                        <div x-show="imagePreview" class="relative z-20">
                            <img :src="imagePreview" class="h-32 mx-auto rounded-lg object-cover shadow-sm">
                            <p class="text-xs text-gray-500 mt-2">Click area to change</p>
                        </div>
                    </div>
                    @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- PDF Rules -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">PDF Rules (Optional)</label>
                    <div class="flex items-center gap-3">
                        <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="bi bi-file-earmark-pdf mr-2"></i> Upload PDF
                            <input type="file" name="pdf_rules" accept="application/pdf" class="hidden">
                        </label>
                        @if($amenity->pdf_rules)
                            <a href="{{ Storage::url($amenity->pdf_rules) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                View Current Rules
                            </a>
                        @else
                            <span class="text-xs text-gray-500">No file chosen</span>
                        @endif
                    </div>
                    @error('pdf_rules') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Status & Highlight -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm">
                            <option value="active" {{ in_array(old('status', $amenity->status), ['active', '1', 1, true]) ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ old('status', $amenity->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="inactive" {{ in_array(old('status', $amenity->status), ['inactive', '0', 0, false]) ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center pt-8">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="highlight" value="0">
                            <input type="checkbox" name="highlight" value="1" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('highlight', $amenity->highlight) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Highlight Amenity</span>
                        </label>
                    </div>
                </div>

                <!-- Equipment & Add-ons (Collapsible or just below) -->
                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Equipment & Add-ons</h3>
                        <button type="button" @click="addEquipment()" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                            <i class="bi bi-plus-circle mr-1"></i> Add Item
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <template x-for="(item, index) in equipmentList" :key="index">
                            <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="grid grid-cols-3 gap-3 flex-1">
                                    <div class="col-span-2">
                                        <input type="text" :name="`equipment[${index}][name]`" x-model="item.name" placeholder="Item Name (e.g. Chair)" required
                                               class="w-full rounded border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <input type="number" :name="`equipment[${index}][price]`" x-model="item.price" placeholder="Price" step="0.01" min="0" required
                                               class="w-full rounded border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <button type="button" @click="removeEquipment(index)" class="text-red-500 hover:text-red-700 p-2">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </template>
                        <div x-show="equipmentList.length === 0" class="text-sm text-gray-500 italic">
                            No equipment added. Click "Add Item" to add rental items.
                        </div>
                    </div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('admin.amenities.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm transition-all transform hover:scale-[1.02]">
                    Update Amenity
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Simple script to update file name for PDF
    document.querySelector('input[name="pdf_rules"]').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'No file chosen';
        const label = this.parentElement.nextElementSibling;
        if (label && label.tagName === 'SPAN') {
             label.textContent = fileName;
        }
    });
</script>
@endsection
