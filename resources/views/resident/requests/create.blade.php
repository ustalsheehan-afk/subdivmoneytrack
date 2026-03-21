@extends('resident.layouts.app')
@section('title','Submit Request')

@section('content')

<div class="p-6 lg:p-8 bg-gray-50/50 min-h-screen">

<div class="max-w-4xl mx-auto space-y-6">

{{-- Header --}}
<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">

<div class="text-center md:text-left">
<h1 class="text-2xl font-bold text-gray-900">Submit a Request</h1>
<p class="text-sm text-gray-600 mt-1">
Report an issue or request assistance from the subdivision administration.
</p>
</div>

<a href="{{ route('resident.requests.index') }}"
class="px-4 py-2 bg-gray-100 border border-gray-200 text-gray-700 rounded-lg font-medium text-sm hover:bg-gray-200 transition flex items-center gap-2">

<i class="bi bi-arrow-left"></i>
Back to List

</a>

</div>



<form action="{{ route('resident.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
@csrf

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-6">

{{-- Request Type --}}
<div class="space-y-2">

<label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
Request Type
</label>

<input type="text"
name="type"
id="type"
placeholder="e.g. Plumbing, Electrical, Noise Complaint"
required
class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">

</div>



{{-- Description --}}
<div class="space-y-2">

<label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
Detailed Description
</label>

<textarea
name="description"
rows="5"
required
placeholder="Please describe your request in detail..."
class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"></textarea>

</div>



<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

{{-- Priority --}}
<div class="space-y-2">

<label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
Priority Level
</label>

<select name="priority"
class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">

<option value="Low">Low - Not Urgent</option>
<option value="Medium" selected>Medium - Normal</option>
<option value="High">High - Urgent</option>

</select>

</div>



{{-- Photo Upload --}}
<div class="space-y-2">

<label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
Attach Photo (Optional)
</label>

<input type="file"
name="photo"
id="photo"
accept="image/*"
onchange="previewImage(event)"
class="hidden">

<label for="photo"
class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer">

<i class="bi bi-camera"></i>
<span id="photoLabel">Upload Reference Photo</span>

</label>

</div>

</div>



{{-- Image Preview --}}
<div id="imagePreviewContainer" class="hidden pt-4 border-t border-gray-100 text-center">

<p class="text-xs text-gray-500 mb-3 font-medium">
Photo Preview
</p>

<div class="relative inline-block group">

<img id="imagePreview"
class="max-h-64 rounded-lg border border-gray-200 shadow">

<button type="button"
onclick="clearImage()"
class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow hover:bg-red-600">

<i class="bi bi-x"></i>

</button>

</div>

</div>

</div>



{{-- Submit Button --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

<button type="submit"
class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition flex items-center justify-center gap-2">

<i class="bi bi-send-fill"></i>
Submit Request

</button>

</div>

</form>

</div>

</div>



<script>

function previewImage(event) {

const input = event.target;
const preview = document.getElementById('imagePreview');
const container = document.getElementById('imagePreviewContainer');
const label = document.getElementById('photoLabel');

if (input.files && input.files[0]) {

const reader = new FileReader();

reader.onload = function(e) {

preview.src = e.target.result;
container.classList.remove('hidden');
label.textContent = input.files[0].name;

}

reader.readAsDataURL(input.files[0]);

}

}



function clearImage(){

const input = document.getElementById('photo');
const preview = document.getElementById('imagePreview');
const container = document.getElementById('imagePreviewContainer');
const label = document.getElementById('photoLabel');

input.value = '';
preview.src = '#';
container.classList.add('hidden');
label.textContent = 'Upload Reference Photo';

}

</script>

@endsection