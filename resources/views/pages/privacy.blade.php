@extends('app')

@section('title', 'Privacy Policy - PawfectMatch')

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
    .legal-content ul {
        margin-bottom: 20px;
        padding-left: 20px;
        color: var(--text-muted);
    }
    .legal-content li {
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<section style="padding: 100px 0 50px; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 20px;">Privacy Policy</h1>
        <p data-aos="fade-up" data-aos-delay="100" style="color: var(--text-muted); font-size: 1.1rem;">
            Last Updated: {{ date('F d, Y') }}
        </p>
    </div>
</section>

<section style="padding: 20px 0 100px;">
    <div class="container">
        <div class="legal-content" data-aos="fade-up">
            <h2>1. Introduction</h2>
            <p>Welcome to PawfectMatch. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>
            
            <h2>2. The Data We Collect</h2>
            <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped together follows:</p>
            <ul>
                <li><strong>Identity Data</strong> includes first name, last name, username or similar identifier.</li>
                <li><strong>Contact Data</strong> includes billing address, delivery address, email address and telephone numbers.</li>
                <li><strong>Financial Data</strong> includes payment processing details used for donations.</li>
                <li><strong>Profile Data</strong> includes your application preferences, favorites, and adoption history.</li>
            </ul>

            <h2>3. How We Use Your Data</h2>
            <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
            <ul>
                <li>Where we need to perform the contract we are about to enter into or have entered into with you (such as processing an adoption application).</li>
                <li>Where it is necessary for our legitimate interests (or those of a third party) and your interests and fundamental rights do not override those interests.</li>
                <li>Where we need to comply with a legal or regulatory obligation.</li>
            </ul>

            <h2>4. Data Security</h2>
            <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors and other third parties who have a business need to know.</p>

            <h2>5. Contact Us</h2>
            <p>If you have any questions about this privacy policy, please contact us via our <a href="{{ route('contact') }}">Contact Page</a>.</p>
        </div>
    </div>
</section>
@endsection
