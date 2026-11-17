@extends('layouts.marketing')

@section('title', 'Demo Access — AI Dental Receptionist')
@section('meta_description', 'Demo login for AI Dental Receptionist / Admin Agent Filament panel.')

@section('content')
<section class="hero">
    <span class="pill">Demo</span>
    <h1>Explore the clinic command center</h1>
    <p>Seeded demo users cover Super Admin, Admin, Manager, Staff, Client, and Demo roles. Use the Filament admin to toggle modules and walk the appointment board.</p>
</section>

<section class="section">
    <article class="panel" style="max-width:640px;">
        <h3 style="margin-top:0;">Demo credentials</h3>
        <p class="muted"><strong>Email:</strong> demo@dental-ai.test</p>
        <p class="muted"><strong>Password:</strong> password</p>
        <p class="muted"><strong>Role:</strong> Demo (full module visibility for walkthroughs)</p>
        <div class="cta-row">
            <a class="btn" href="/admin/login">Open Filament Admin</a>
            <a class="btn btn-outline" href="{{ route('modules') }}">View modules</a>
        </div>
    </article>
</section>
@endsection
