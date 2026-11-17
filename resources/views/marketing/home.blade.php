@extends('layouts.marketing')

@section('title', 'AI Front Desk for Modern Dental Clinics')
@section('meta_description', 'AI dental receptionist and admin agent for appointment booking, patient intake, insurance checks, and front-desk automation that saves clinics hours daily.')

@section('content')
<section class="hero">
    <span class="pill">Dental / Healthcare · Modular Laravel + Filament</span>
    <h1>AI Dental Receptionist / Admin Agent</h1>
    <p>
        Replace phone tag and clipboard chaos with an AI front desk that books appointments,
        completes intake, verifies insurance, and keeps billing reminders on rails —
        so your hygienists stay in chairs, not on hold.
    </p>
    <div class="cta-row">
        <a class="btn" href="{{ route('demo') }}">Try demo credentials</a>
        <a class="btn btn-outline" href="{{ route('features') }}">Explore features</a>
    </div>
</section>

<section class="section">
    <img class="dash-shot" src="{{ asset('docs/screenshots/dashboard.png') }}" alt="Dental AI Desk clinic command center dashboard">
    <p class="muted" style="margin-top:0.75rem;">Clinic Command Center — appointments, patients, insurance queue, and live AI receptionist.</p>
</section>

<section class="section">
    <h2>Toggle only what your clinic needs</h2>
    <p class="muted">Every module can run on or off. Start lean, then enable insurance, billing, or attendance when you are ready.</p>
    <div class="grid" style="margin-top:1.25rem;">
        @foreach($modules as $module)
            <article class="panel">
                <span class="pill">{{ $module['enabled'] ? 'Enabled' : 'Optional' }}</span>
                <h3 style="margin:0.6rem 0 0.35rem;font-family:Fraunces,Georgia,serif;">{{ $module['name'] }}</h3>
                <p class="muted" style="margin:0;">{{ $module['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
