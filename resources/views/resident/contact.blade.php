@extends('resident.layouts.app')

@section('title', 'Contact Us')
@section('page-title', 'Contact Us')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 relative overflow-hidden">

    {{-- Decorative Blurs --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-300/30 rounded-full blur-3xl"></div>
    <div class="absolute top-1/3 -right-32 w-96 h-96 bg-indigo-300/30 rounded-full blur-3xl"></div>

    {{-- HEADER --}}
    <section class="relative z-10">
        <div class="max-w-7xl mx-auto px-6 md:px-12 py-20">
            <h1 class="text-5xl md:text-6xl font-semibold text-slate-900"
                style="font-family: 'Brush Script MT', cursive;">
                Vistabella Subdivision
            </h1>
            <p class="mt-4 text-slate-600 max-w-xl text-lg">
                We’d love to hear from you. Connect with our management team through the contact details below.
            </p>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

            {{-- CONTACT INFO --}}
            <div class="space-y-10">
                <h2 class="text-3xl font-semibold text-slate-900">
                    Contact Information
                </h2>

                <div class="space-y-6">

                    {{-- Item --}}
                    <div class="flex items-center gap-5 p-6 rounded-2xl bg-white/80 backdrop-blur
                                border border-slate-200 hover:border-blue-500
                                hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 flex items-center justify-center">
                            <i class="bi bi-facebook text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Facebook</p>
                            <p class="text-lg font-medium text-slate-800">
                                Vistabella Subdivision
                            </p>
                        </div>
                    </div>

                    {{-- Item --}}
                    <div class="flex items-center gap-5 p-6 rounded-2xl bg-white/80 backdrop-blur
                                border border-slate-200 hover:border-blue-500
                                hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 flex items-center justify-center">
                            <i class="bi bi-telephone-fill text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Phone</p>
                            <p class="text-lg font-medium text-slate-800">
                                +63 907 654 38756
                            </p>
                        </div>
                    </div>

                    {{-- Item --}}
                    <div class="flex items-center gap-5 p-6 rounded-2xl bg-white/80 backdrop-blur
                                border border-slate-200 hover:border-blue-500
                                hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 flex items-center justify-center">
                            <i class="bi bi-envelope-fill text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Email</p>
                            <p class="text-lg font-medium text-slate-800">
                                vistabella@gmail.com
                            </p>
                        </div>
                    </div>

                    {{-- Item --}}
                    <div class="flex items-center gap-5 p-6 rounded-2xl bg-white/80 backdrop-blur
                                border border-slate-200 hover:border-blue-500
                                hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-xl bg-blue-600/10 flex items-center justify-center">
                            <i class="bi bi-clock-fill text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Office Hours</p>
                            <p class="text-lg font-medium text-slate-800">
                                Monday – Friday, 9:00 AM – 5:00 PM
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- MAP --}}
            <div class="relative w-full h-[520px] rounded-3xl overflow-hidden
                        border border-slate-200 shadow-xl bg-white">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.666324637766!2d121.04358877590234!3d14.561081578044733!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c860ad20d919%3A0xb3650228d7164893!2sVistabella%20Subdivision!5e0!3m2!1sen!2sph!4v1706000000000!5m2!1sen!2sph"
                    class="w-full h-full"
                    style="border:0;"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>

                {{-- Map Label --}}
                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-4 py-2 rounded-xl shadow">
                    <p class="text-sm font-medium text-slate-800">
                        📍 Vistabella Subdivision
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="relative z-10 border-t border-slate-200 bg-white/80 backdrop-blur py-6">
        <div class="max-w-7xl mx-auto px-6 md:px-12 text-center text-sm text-slate-500">
            © {{ date('Y') }} Vistabella Subdivision. All rights reserved.
        </div>
    </footer>

</div>
@endsection
