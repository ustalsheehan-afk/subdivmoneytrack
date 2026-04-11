@extends('resident.layouts.app')

@section('title', 'Message Thread - ' . $thread->subject)
@section('page-title', 'Message Thread')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    {{-- Thread Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('resident.messages.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:bg-emerald-50 transition-all">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div class="flex-1">
            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">{{ $thread->subject }}</h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase tracking-widest">{{ $thread->category }}</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ID: #{{ str_pad($thread->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
        <div class="text-right">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Status</span>
            <span class="px-4 py-1.5 bg-gray-100 text-gray-700 text-xs font-black rounded-xl uppercase tracking-widest">{{ $thread->status }}</span>
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[500px] mb-8">
        <div class="flex-1 p-8 overflow-y-auto space-y-8 bg-gray-50/30" id="chatContainer">
            @foreach($thread->messages as $message)
                <div class="flex {{ $message->isFromResident() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] space-y-2">
                        <div class="flex items-center gap-2 {{ $message->isFromResident() ? 'flex-row-reverse' : '' }}">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                {{ $message->isFromResident() ? 'You' : 'Administration' }}
                            </span>
                            <span class="text-[9px] text-gray-300">{{ $message->created_at->format('h:i A') }}</span>
                        </div>
                        <div class="px-6 py-4 rounded-[2rem] text-sm font-medium shadow-sm {{ $message->isFromResident() ? 'bg-[#0D1F1C] text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none' }}">
                            {{ $message->body }}
                            @if($message->attachment)
                                <div class="mt-3 pt-3 border-t {{ $message->isFromResident() ? 'border-white/10' : 'border-gray-50' }}">
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest {{ $message->isFromResident() ? 'text-[#B6FF5C]' : 'text-emerald-600' }}">
                                        <i class="bi bi-paperclip"></i> View Attachment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reply Area --}}
        <div class="p-6 bg-white border-t border-gray-50"
             x-data="{
                attachmentName: '',
                attachmentPreview: '',
                attachmentType: '',
                setAttachment(event) {
                    const file = event.target.files[0];

                    if (!file) {
                        this.clearAttachment();
                        return;
                    }

                    if (this.attachmentPreview) {
                        URL.revokeObjectURL(this.attachmentPreview);
                    }

                    this.attachmentName = file.name;
                    this.attachmentType = file.type || '';
                    this.attachmentPreview = URL.createObjectURL(file);
                },
                clearAttachment() {
                    if (this.attachmentPreview) {
                        URL.revokeObjectURL(this.attachmentPreview);
                    }

                    this.attachmentName = '';
                    this.attachmentPreview = '';
                    this.attachmentType = '';
                }
             }">
            @if($thread->status !== 'closed')
                <form action="{{ route('resident.messages.reply', $thread->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="relative">
                        <textarea name="body" rows="2" placeholder="Write your reply here..." 
                            class="w-full px-8 py-5 rounded-[2rem] bg-gray-50 border border-gray-100 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all outline-none resize-none pr-32" required></textarea>
                        
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">
                            <label for="resident-thread-attachment" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-100 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 cursor-pointer transition-all">
                                <input id="resident-thread-attachment" x-ref="attachmentInput" type="file" name="attachment" accept="image/*,.pdf" class="sr-only" @change="setAttachment($event)">
                                <i class="bi bi-paperclip text-lg"></i>
                            </label>
                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#0D1F1C] text-[#B6FF5C] hover:shadow-[0_0_15px_rgba(182,255,92,0.3)] transition-all">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-3 space-y-3" x-show="attachmentName" x-cloak>
                        <div class="rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
                            <template x-if="attachmentType.startsWith('image/')">
                                <img :src="attachmentPreview" alt="Attachment preview" class="w-full max-h-56 object-cover rounded-xl border border-gray-100">
                            </template>
                            <template x-if="!attachmentType.startsWith('image/')">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        <i class="bi bi-paperclip text-lg"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-900 truncate" x-text="attachmentName"></p>
                                        <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Ready to upload</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-red-500" @click="clearAttachment(); $refs.attachmentInput.value = ''">Remove file</button>
                    </div>
                </form>
            @else
                <div class="p-6 text-center bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">This conversation has been closed.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
@endsection
