@extends('app')

@section('title', $pet->name . ' — PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
<style>
    .pet-application-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        padding: 1rem;
    }

    .pet-application-overlay.open {
        display: flex;
    }

    .noscroll {
        overflow: hidden !important;
    }

    .pet-application-content {
        width: 100%;
        max-width: 600px;
        max-height: 88vh;
        overflow-y: auto;
        overflow-x: hidden;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.2);
    }

    .application-modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .application-modal-header h3 {
        margin: 0;
        color: #0f172a;
        font-size: 1.8rem;
        font-family: var(--font-serif);
        text-align: center;
    }

    .modal-close {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #475569;
        font-size: 1.75rem;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s ease;
    }

    .modal-close:hover {
        color: #0f172a;
    }

    .application-modal-body {
        padding: 1.8rem;
    }

    .modal-application-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .modal-application-grid .field {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .modal-application-grid .field.full {
        grid-column: 1 / -1;
    }

    .modal-application-grid .field label {
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.2rem;
    }

    .modal-application-grid .field label i {
        color: #64748b;
        font-size: 1rem;
        width: 20px;
        text-align: center;
    }

    .modal-application-grid input,
    .modal-application-grid select,
    .modal-application-grid textarea {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        font-family: var(--font-sans);
        background-color: #f8fafc;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #0f172a;
    }

    .modal-application-grid input:focus,
    .modal-application-grid select:focus,
    .modal-application-grid textarea:focus {
        outline: none;
        border-color: var(--coral, #FF6B6B);
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.15);
    }

    .modal-application-grid textarea {
        min-height: 120px;
        resize: vertical;
    }

    /* Custom Radio Cards */
    .custom-radio-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .radio-card {
        position: relative;
        cursor: pointer;
        font-weight: normal !important;
        margin: 0 !important;
    }

    .radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .radio-card .radio-content {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 500;
        color: #475569;
        transition: all 0.3s ease;
    }

    .radio-card:hover .radio-content {
        border-color: #cbd5e1;
        background: #f1f5f9;
        color: #334155;
    }

    .radio-card input[type="radio"]:checked + .radio-content {
        border-color: var(--coral, #FF6B6B);
        background: rgba(255, 107, 107, 0.08);
        color: var(--coral, #FF6B6B);
        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.15);
        font-weight: 600;
    }

    .modal-actions {
        display: flex;
        justify-content: center;
        gap: 0.7rem;
        margin-top: 1rem;
    }

    .modal-error-box {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
        border-radius: 10px;
        padding: 0.8rem 0.95rem;
        margin-bottom: 0.9rem;
    }

    @media (max-width: 768px) {
        .modal-application-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <div class="back-link-wrapper" style="grid-column: 1 / -1; margin-bottom: 0rem; margin-top: -1.5rem;">
        <a href="{{ route('pets.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; text-decoration:none; color:#64748b; font-weight:500; transition:color 0.2s ease;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Browse
        </a>
    </div>

    @if(session('success'))
        <div style="grid-column: 1 / -1; margin-bottom: 1rem; padding: 0.85rem 1rem; border-radius: 10px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="grid-column: 1 / -1; margin-bottom: 1rem; padding: 0.85rem 1rem; border-radius: 10px; background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
            {{ session('error') }}
        </div>
    @endif
    
    {{-- ============================================
        PHOTO GALLERY
        ============================================ --}}
    <section class="gallery-section">
        <div class="main-image-wrapper cinematic-zoom" style="position: relative;">
            @if($pet->featured_video)
                <video src="{{ asset($pet->featured_video) }}" autoplay loop muted playsinline id="main-video" style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0; z-index:10; transition: opacity 0.3s ease;"></video>
            @endif
            @if($pet->image_url)
                <img src="{{ asset('images/pets/' . $pet->image_url) }}" alt="{{ $pet->name }}'s Photo" id="main-image" style="transition: all 0.3s ease; {{ $pet->featured_video ? 'opacity:0; z-index:1;' : 'opacity:1; z-index:10;' }}">
            @else
                <img src="{{ asset('images/auth-pet.png') }}" alt="Placeholder for {{ $pet->name }}" id="main-image" style="transition: all 0.3s ease; {{ $pet->featured_video ? 'opacity:0; z-index:1;' : 'opacity:1; z-index:10;' }}">
            @endif
        </div>
        
        <div class="gallery-thumbnails">
            @if($pet->featured_video)
            <div class="thumbnail active" onclick="updateMainMedia(this, 'video')">
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#1A2332; color:white; border-radius:12px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                </div>
            </div>
            @endif
            
            {{-- Main Image Thumbnail --}}
            <div class="thumbnail {{ $pet->featured_video ? '' : 'active' }}" onclick="updateMainMedia(this, 'image')">
                @if($pet->image_url)
                    <img src="{{ asset('images/pets/' . $pet->image_url) }}" alt="{{ $pet->name }} Main Photo">
                @else
                    <img src="{{ asset('images/auth-pet.png') }}" alt="Placeholder for {{ $pet->name }}">
                @endif
            </div>
            
            {{-- Extra Gallery Thumbnails --}}
            @if(is_array($pet->gallery) || is_object($pet->gallery))
                @foreach(collect($pet->gallery)->take(2) as $index => $galleryImage)
                    <div class="thumbnail" onclick="updateMainMedia(this, 'image')">
                        <img src="{{ asset('images/pets/' . $galleryImage) }}" alt="{{ $pet->name }} Gallery Photo {{ $index + 1 }}">
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    {{-- ============================================
        INFO & ACCORDIONS
        ============================================ --}}
    <section class="info-section">
        <div class="pet-meta-header">
            <h1 class="pet-title">{{ $pet->name }}</h1>
            <p class="pet-subtitle">{{ $pet->breed }}</p>
            
            <div class="card-traits" style="margin-bottom: 0;">
                <span class="trait"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg> {{ $pet->species }}</span>
                <span class="trait"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg> {{ $pet->size }}</span>
                <span class="trait"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> {{ $pet->age_months < 12 ? $pet->age_months . ' mo' : intdiv($pet->age_months, 12) . ' yr' }}</span>
                <span class="trait"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Level {{ $pet->energy_level }} Energy</span>
            </div>
        </div>

        <div class="pet-description" style="margin-bottom: 2.5rem; font-family: var(--font-sans); color: var(--navy); line-height: 1.8; font-size: 1.1rem;">
            {{ $pet->description ?? "$pet->name is a wonderful {$pet->breed} waiting for a loving home. They are looking for a family to share endless moments of joy and companionship." }}
        </div>

        <div class="accordion-wrapper">
            {{-- Health Accordion --}}
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false" aria-controls="accordion-health" id="accordion-trigger-health">
                    <span>Health & Medical</span>
                    <svg class="accordion-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-health" role="region" aria-labelledby="accordion-trigger-health" aria-hidden="true">
                    <p><strong>Status:</strong> {{ $pet->health_status }}</p>
                    <p><strong>Requirements:</strong> {{ $pet->medical_notes ?? "No special medical requirements noted." }}</p>
                    <p>All pets are spayed/neutered, microchipped, and up-to-date on standard vaccinations prior to adoption.</p>
                </div>
            </div>

            {{-- Personality Accordion --}}
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false" aria-controls="accordion-personality" id="accordion-trigger-personality">
                    <span>Personality Traits</span>
                    <svg class="accordion-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-personality" role="region" aria-labelledby="accordion-trigger-personality" aria-hidden="true">
                    @if($pet->temperament)
                        <ul style="list-style: disc; margin-left: 1.5rem; margin-bottom: 1rem;">
                        @foreach(is_string($pet->temperament) ? json_decode($pet->temperament, true) ?? [] : $pet->temperament as $tag)
                            <li>{{ $tag }}</li>
                        @endforeach
                        </ul>
                    @else
                        <p>Loving, affectionate, and ready to meet you!</p>
                    @endif
                </div>
            </div>

            {{-- Requirements Accordion --}}
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false" aria-controls="accordion-requirements" id="accordion-trigger-requirements">
                    <span>Home Requirements</span>
                    <svg class="accordion-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="accordion-content" id="accordion-requirements" role="region" aria-labelledby="accordion-trigger-requirements" aria-hidden="true">
                    <ul style="list-style: disc; margin-left: 1.5rem;">
                        <li>Best in a home with: {{ $pet->energy_level > 7 ? 'Active family, fenced yard preferable' : 'Any loving environment' }}</li>
                        <li>Good with kids? {{ $pet->good_with_kids ? 'Yes' : 'Not recommended' }}</li>
                        <li>Good with other pets? {{ $pet->good_with_pets ? 'Yes' : 'Case-by-case basis' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Sticky Action Bar --}}
@if($pet->adoption_status === 'Available')
<div class="sticky-action-bar">
    <a
        href="{{ route('donations.create', ['pet_id' => $pet->id]) }}"
        class="btn-secondary"
        style="display:inline-flex; align-items:center; justify-content:center; gap:0.4rem; text-decoration:none;"
    >
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
        Donate for {{ $pet->name }}
    </a>
    <button
        class="btn-apply"
        type="button"
        id="open-application-modal"
        data-form-url="{{ route('applications.create', ['pet_id' => $pet->id, 'partial' => 1]) }}"
        style="border: none; cursor: pointer;"
    >
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
        Apply to Adopt {{ $pet->name }}
    </button>
</div>

<div class="pet-application-overlay" id="pet-application-overlay" aria-hidden="true">
    <div class="pet-application-content" role="dialog" aria-modal="true" aria-labelledby="application-modal-title">
        <div class="application-modal-header">
            <h3 id="application-modal-title">Apply to Adopt {{ $pet->name }}</h3>
            <button type="button" class="modal-close" id="close-application-modal" aria-label="Close application form">&times;</button>
        </div>
        <div class="application-modal-body" id="application-modal-body">
            <p style="margin: 0; color: #64748b;">Loading form...</p>
        </div>
        <p id="modal-status" style="position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0 0 0 0); white-space:nowrap;" aria-live="polite"></p>
    </div>
</div>
@endif

{{-- Vanilla JS for Interactions --}}
<script>
    // Gallery Media Swapping
    function updateMainMedia(thumbnail, type) {
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
        
        const mainImg = document.getElementById('main-image');
        const mainVid = document.getElementById('main-video');
        
        if (type === 'video' && mainVid) {
            mainImg.style.opacity = '0';
            mainImg.style.zIndex = '1';
            
            mainVid.style.opacity = '1';
            mainVid.style.zIndex = '10';
            mainVid.play();
        } else {
            if (mainVid) {
                mainVid.style.opacity = '0';
                mainVid.style.zIndex = '1';
                mainVid.pause();
            }
            
            mainImg.style.zIndex = '10';
            const thumbImg = thumbnail.querySelector('img');
            
            mainImg.style.opacity = '0.5';
            mainImg.style.transform = 'scale(0.98)';
            
            setTimeout(() => {
                mainImg.src = thumbImg.src;
                mainImg.style.opacity = '1';
                mainImg.style.transform = 'scale(1)';
            }, 150);
        }
    }

    document.querySelectorAll('.accordion-trigger').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const item = trigger.parentElement;
            const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
            const contentId = trigger.getAttribute('aria-controls');
            const content = contentId ? document.getElementById(contentId) : null;

            item.classList.toggle('active');
            trigger.setAttribute('aria-expanded', !isExpanded);
            if (content) {
                content.setAttribute('aria-hidden', isExpanded ? 'true' : 'false');
            }
        });
    });

    const modal = document.getElementById('pet-application-overlay');
    const modalBody = document.getElementById('application-modal-body');
    const openModalButton = document.getElementById('open-application-modal');
    const closeModalButton = document.getElementById('close-application-modal');
    const modalStatus = document.getElementById('modal-status');
    let previousFocusedElement = null;

    function announceModalStatus(message) {
        if (modalStatus) {
            modalStatus.textContent = message;
        }
    }

    function trapModalFocus(event) {
        if (!modal || !modal.classList.contains('open') || event.key !== 'Tab') {
            return;
        }

        const focusables = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (!focusables.length) {
            return;
        }

        const first = focusables[0];
        const last = focusables[focusables.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    }

    function closeApplicationModal() {
        if (!modal) return;
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('noscroll');
        document.documentElement.classList.remove('noscroll');
        document.removeEventListener('keydown', trapModalFocus);
        announceModalStatus('Application form closed.');

        if (previousFocusedElement && typeof previousFocusedElement.focus === 'function') {
            previousFocusedElement.focus();
        }
    }

    function renderValidationErrors(errors) {
        const errorBox = document.getElementById('modal-form-errors');
        if (!errorBox) return;

        const messages = Object.values(errors || {}).flat();
        if (!messages.length) {
            errorBox.style.display = 'none';
            errorBox.innerHTML = '';
            return;
        }

        errorBox.className = 'modal-error-box';
        errorBox.style.display = 'block';
        errorBox.innerHTML = `<ul style="margin:0; padding-left:1rem;">${messages.map(message => `<li>${message}</li>`).join('')}</ul>`;
    }

    async function submitModalApplicationForm(event) {
        event.preventDefault();
        const form = event.currentTarget;
        const submitButton = document.getElementById('modal-submit-btn');

        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';
        renderValidationErrors({});
        announceModalStatus('Submitting application...');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            const payload = await response.json();

            if (response.status === 422) {
                renderValidationErrors(payload.errors || {});
                announceModalStatus('There are validation errors in the form.');
                return;
            }

            if (!response.ok || !payload.success) {
                renderValidationErrors({ general: [payload.message || 'Unable to submit your application right now.'] });
                announceModalStatus('Unable to submit application right now.');
                return;
            }

            announceModalStatus('Application submitted successfully.');

            // Show success message inside the modal
            const modalHeader = document.querySelector('.application-modal-header');
            if (modalHeader) modalHeader.style.display = 'none';

            modalBody.innerHTML = `
                <div style="text-align:center; padding:2.5rem 1rem;">
                    <div style="font-size:3.5rem; margin-bottom:1rem;">🎉</div>
                    <h3 style="font-family:var(--font-serif); font-size:1.6rem; color:#0f172a; margin:0 0 0.6rem;">Application Submitted!</h3>
                    <p style="color:#64748b; font-size:1rem; line-height:1.6; margin:0;">Thank you for applying. We will review your application and get back to you soon.</p>
                </div>`;

            // Auto-close modal after 2.5s and reload page
            setTimeout(() => {
                closeApplicationModal();
                window.location.reload();
            }, 2500);
        } catch (error) {
            renderValidationErrors({ general: ['Network error while submitting the application. Please try again.'] });
            announceModalStatus('Network error while submitting application.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Application';
        }
    }

    async function openApplicationModal() {
        if (!modal || !modalBody || !openModalButton) return;

        // Move modal to body to prevent transform/filter containment issues
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        previousFocusedElement = document.activeElement;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('noscroll');
        document.documentElement.classList.add('noscroll');
        modalBody.innerHTML = '<p style="margin:0; color:#64748b;">Loading form...</p>';
        announceModalStatus('Loading application form.');
        document.addEventListener('keydown', trapModalFocus);

        try {
            const response = await fetch(openModalButton.dataset.formUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (response.redirected) {
                window.location.href = response.url;
                return;
            }

            if (!response.ok) {
                throw new Error('Failed to load form');
            }

            modalBody.innerHTML = await response.text();
            announceModalStatus('Application form loaded.');

            const form = document.getElementById('modal-application-form');
            const cancelButton = document.getElementById('modal-cancel-btn');
            const firstInput = modalBody.querySelector('input, select, textarea, button');

            if (form) {
                form.addEventListener('submit', submitModalApplicationForm);
            }

            if (cancelButton) {
                cancelButton.addEventListener('click', closeApplicationModal);
            }

            if (firstInput) {
                firstInput.focus();
            }
        } catch (error) {
            modalBody.innerHTML = '<div class="modal-error-box">Unable to load the application form right now. Please try again.</div>';
            announceModalStatus('Unable to load application form.');
        }
    }

    if (openModalButton) {
        openModalButton.addEventListener('click', openApplicationModal);
    }

    if (closeModalButton) {
        closeModalButton.addEventListener('click', closeApplicationModal);
    }

    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeApplicationModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.classList.contains('open')) {
                closeApplicationModal();
            }
        });
    }

    const accordionTriggers = Array.from(document.querySelectorAll('.accordion-trigger'));
    accordionTriggers.forEach((trigger, index) => {
        trigger.addEventListener('keydown', (event) => {
            if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) {
                return;
            }

            event.preventDefault();
            let targetIndex = index;

            if (event.key === 'ArrowDown') {
                targetIndex = (index + 1) % accordionTriggers.length;
            } else if (event.key === 'ArrowUp') {
                targetIndex = (index - 1 + accordionTriggers.length) % accordionTriggers.length;
            } else if (event.key === 'Home') {
                targetIndex = 0;
            } else if (event.key === 'End') {
                targetIndex = accordionTriggers.length - 1;
            }

            accordionTriggers[targetIndex].focus();
        });
    });



</script>
@endsection
