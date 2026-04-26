@extends('app')

@section('title', 'Frequently Asked Questions - PawfectMatch')

@section('styles')
<style>
    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .faq-item {
        background: var(--surface-color, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .dark-mode .faq-item {
        background: var(--surface-color, #1e293b);
        border-color: var(--border-color, #334155);
    }
    .faq-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: var(--primary-color, #3b82f6);
    }
    .faq-question {
        padding: 20px 24px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-color, #1e293b);
    }
    .dark-mode .faq-question {
        color: #f1f5f9;
    }
    .faq-question i {
        transition: transform 0.3s ease;
        color: var(--primary-color, #3b82f6);
    }
    .faq-answer {
        padding: 0 24px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.4s ease;
        color: var(--text-muted, #64748b);
        line-height: 1.7;
    }
    .faq-item.active .faq-answer {
        padding: 0 24px 24px;
        max-height: 1000px;
    }
    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('content')
<section style="padding: 100px 0 50px; text-align: center;">
    <div class="container">
        <h1 data-aos="fade-up" style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-bottom: 20px;">Frequently Asked Questions</h1>
        <p data-aos="fade-up" data-aos-delay="100" style="max-width: 600px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem;">
            Everything you need to know about adopting and supporting PawfectMatch.
        </p>
    </div>
</section>

<section style="padding: 20px 0 100px;">
    <div class="container faq-container">
        @if($faqs->isEmpty())
            <div class="text-center" style="padding: 40px; background: var(--surface-color); border-radius: 12px;">
                <p>No FAQs available at the moment. Check back later!</p>
            </div>
        @else
            @foreach($faqs as $index => $faq)
                <div class="faq-item" data-aos="fade-up" data-aos-delay="{{ 100 * $index }}">
                    <div class="faq-question">
                        {{ $faq->question }}
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        @endif
        
        <div style="text-align: center; margin-top: 50px;" data-aos="fade-up">
            <p style="color: var(--text-muted); margin-bottom: 15px;">Still have questions?</p>
            <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentNode;
                const isActive = item.classList.contains('active');
                
                // Close all others
                document.querySelectorAll('.faq-item').forEach(faqItem => {
                    faqItem.classList.remove('active');
                });

                // Toggle current
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
    });
</script>
@endsection
