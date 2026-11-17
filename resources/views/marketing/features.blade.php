@extends('layouts.marketing')

@section('title', 'Features — AI Dental Receptionist')
@section('meta_description', 'Full feature set for dental front-desk automation: AI booking, patient intake, insurance eligibility, billing estimates, and recall reminders.')

@section('content')
<section class="hero">
    <span class="pill">Product features</span>
    <h1>Everything a high-volume clinic front desk needs</h1>
    <p>Designed as a complete business solution — not a chatbot toy. Multi-role users, Filament admin, modular toggles, and dental-specific workflows.</p>
</section>

<section class="section">
    <div class="grid">
        <article class="panel">
            <h3>AI receptionist</h3>
            <p class="muted">Handles booking intents, FAQs, after-hours coverage, and escalations with full transcripts.</p>
        </article>
        <article class="panel">
            <h3>Chair-time scheduling</h3>
            <p class="muted">Provider, chair, procedure, and waitlist-ready appointment board for today and beyond.</p>
        </article>
        <article class="panel">
            <h3>Digital intake</h3>
            <p class="muted">Patient charts, medical alerts, preferred language, and intake status tracking.</p>
        </article>
        <article class="panel">
            <h3>Insurance eligibility</h3>
            <p class="muted">Primary policy capture, benefits summary, and AI-assisted verification workflow.</p>
        </article>
        <article class="panel">
            <h3>Billing & estimates</h3>
            <p class="muted">Treatment estimates, patient responsibility, co-pay tracking, and invoice statuses.</p>
        </article>
        <article class="panel">
            <h3>Reminder engine</h3>
            <p class="muted">SMS/email queues for confirmations, recalls, and no-show recovery campaigns.</p>
        </article>
        <article class="panel">
            <h3>Multi-role access</h3>
            <p class="muted">Super Admin, Admin, Manager, Staff, Client, and Demo roles with Filament panel gates.</p>
        </article>
        <article class="panel">
            <h3>Module control plane</h3>
            <p class="muted">Turn appointments, patients, insurance, AI, billing, reminders, or attendance on/off.</p>
        </article>
    </div>
</section>
@endsection
