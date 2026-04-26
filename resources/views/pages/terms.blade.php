@extends('app')

@section('title', 'Terms of Service - PawfectMatch')

@section('styles')
<style>
    .legal-content {
        max-width: 800px;
        margin: 0 auto;
        color: var(--text-color);
        line-height: 1.8;
    }
    .legal-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        margin: 40px 0 20px;
    }
    .legal-content p {
        margin-bottom: 20px;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<section style="padding: 100px 0 50px; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 20px;">Terms of Service</h1>
        <p data-aos="fade-up" data-aos-delay="100" style="color: var(--text-muted); font-size: 1.1rem;">
            Last Updated: {{ date('F d, Y') }}
        </p>
    </div>
</section>

<section style="padding: 20px 0 100px;">
    <div class="container">
        <div class="legal-content" data-aos="fade-up">
            <h2>1. Agreement to Terms</h2>
            <p>By accessing or using PawfectMatch, you agree to be bound by these Terms. If you disagree with any part of the terms, then you may not access the Service.</p>
            
            <h2>2. Pet Adoption Applications</h2>
            <p>Submitting an adoption application does not guarantee approval. PawfectMatch reserves the right to approve or deny any application at our sole discretion. Adoption fees are non-refundable once an adoption is finalized.</p>

            <h2>3. User Accounts</h2>
            <p>When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.</p>

            <h2>4. Intellectual Property</h2>
            <p>The Service and its original content, features and functionality are and will remain the exclusive property of PawfectMatch and its licensors. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of PawfectMatch.</p>

            <h2>5. Changes to Terms</h2>
            <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. What constitutes a material change will be determined at our sole discretion.</p>
        </div>
    </div>
</section>
@endsection
