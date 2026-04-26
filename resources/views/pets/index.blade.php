@extends('app')

@section('title', 'Browse Pets — PawfectMatch')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/modules.css') }}">
@endsection

@section('content')
<div class="browse-container">
    
    {{-- ============================================
        ADVANCED FILTER SIDEBAR
        ============================================ --}}
    <aside class="filter-sidebar" aria-label="Filter Pets">
        <form id="filter-form">
            <div class="filter-header">
                <h2>Filters</h2>
                <button type="reset" class="filter-reset" id="reset-filters">Clear All</button>
            </div>

            {{-- Real-time Search --}}
            <div class="search-wrapper">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="search" id="search-input" class="search-input" placeholder="Search by name or breed" aria-label="Search by name or breed">
            </div>

            {{-- Species Filter --}}
            <div class="filter-group">
                <span class="filter-label">Species</span>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" name="species[]" value="Dog"> Dog
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="species[]" value="Cat"> Cat
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="species[]" value="Other"> Others (Rabbit, Bird, etc.)
                    </label>
                </div>
            </div>

            {{-- Age Range Slider (Max 180 months) --}}
            <div class="filter-group">
                <span class="filter-label">Max Age (Months)</span>
                <input type="range" name="age_months" id="age-slider" class="range-slider" min="1" max="180" value="180" aria-label="Filter by maximum age in months">
                <div class="range-value">
                    <span>1 mo</span>
                    <span id="age-value-display">180 mo</span>
                </div>
            </div>

            {{-- Energy Level Range Slider (Max 10) --}}
            <div class="filter-group">
                <span class="filter-label">Max Energy Level</span>
                <input type="range" name="energy_level" id="energy-slider" class="range-slider" min="1" max="10" value="10" aria-label="Filter by maximum energy level">
                <div class="range-value">
                    <span>Calm (1)</span>
                    <span id="energy-value-display">Active (10)</span>
                </div>
            </div>

            {{-- Size Filter --}}
            <div class="filter-group">
                <span class="filter-label">Size</span>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" name="size[]" value="Small"> Small
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="size[]" value="Medium"> Medium
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="size[]" value="Large"> Large
                    </label>
                </div>
            </div>
        </form>
    </aside>

    {{-- ============================================
        PET CARDS GRID
        ============================================ --}}
    <main class="pets-main">
        <div class="pets-header">
            <div>
                <h1>Find Your Companion</h1>
                <p class="pets-count">Showing <span id="total-results">{{ $totalResults }}</span> exceptional pets</p>
                <p id="filter-feedback" style="margin-top:0.5rem; color:#64748b; font-size:0.9rem;" aria-live="polite"></p>
                <p id="filter-sr-status" style="position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0 0 0 0); white-space:nowrap;" aria-live="polite"></p>
            </div>
        </div>

        <div class="pets-grid" id="pets-grid" aria-live="polite" aria-busy="false">
            @include('pets.partials.pet-grid', ['pets' => $pets])
        </div>

        <div style="display:flex; justify-content:center; margin-top:1rem;">
            <button type="button" id="load-more-btn" class="btn-primary" style="display: {{ $pets->hasMorePages() ? 'inline-flex' : 'none' }}; align-items:center; gap:0.5rem;">
                Load More
            </button>
        </div>
        
        <div class="loader-container" id="pagination-loader" aria-hidden="true">
            <div class="spinner"></div>
        </div>
    </main>
</div>

{{-- Vanilla JS Fetch API for advanced filtering without page reload --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('filter-form');
    const petsGrid = document.getElementById('pets-grid');
    const totalResultsDisplay = document.getElementById('total-results');
    const loader = document.getElementById('pagination-loader');
    const loadMoreButton = document.getElementById('load-more-btn');
    const feedback = document.getElementById('filter-feedback');
    const srStatus = document.getElementById('filter-sr-status');

    let nextPageUrl = @json($pets->nextPageUrl());
    let isLoading = false;
    
    // Range displays
    const ageSlider = document.getElementById('age-slider');
    const ageDisplay = document.getElementById('age-value-display');
    const energySlider = document.getElementById('energy-slider');
    const energyDisplay = document.getElementById('energy-value-display');
    
    // Update displays dynamically
    ageSlider.addEventListener('input', (e) => ageDisplay.textContent = e.target.value + ' mo');
    energySlider.addEventListener('input', (e) => energyDisplay.textContent = e.target.value);
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const getFilterQueryString = () => {
        const formData = new FormData(filterForm);
        return new URLSearchParams(formData).toString();
    };

    const setLoadMoreVisibility = () => {
        loadMoreButton.style.display = nextPageUrl ? 'inline-flex' : 'none';
    };

    const setFeedback = (message, isError = false) => {
        feedback.textContent = message;
        feedback.style.color = isError ? '#b91c1c' : '#64748b';
        srStatus.textContent = message;
    };

    // Fetch filtered data
    const fetchPets = async ({ append = false, url = null } = {}) => {
        if (isLoading) {
            return;
        }

        isLoading = true;
        loadMoreButton.disabled = true;
        loadMoreButton.setAttribute('aria-disabled', 'true');
        loader.classList.add('active');
        petsGrid.setAttribute('aria-busy', 'true');
        setFeedback('Loading pets...');
        if (!append) {
            petsGrid.style.opacity = '0.5';
        }
        
        try {
            const endpoint = url || `{{ route('pets.index') }}?${getFilterQueryString()}`;
            
            const response = await fetch(endpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            if (append) {
                petsGrid.insertAdjacentHTML('beforeend', data.html);
            } else {
                petsGrid.innerHTML = data.html;
            }

            nextPageUrl = data.next_page_url;
            totalResultsDisplay.textContent = data.total;
            setLoadMoreVisibility();

            if (Number(data.total) === 0) {
                setFeedback('No pets match your current filters. Try widening your search.');
            } else if (append) {
                setFeedback(`Loaded more pets. ${data.total} total results.`);
            } else {
                setFeedback(`Showing ${data.total} results.`);
            }

            // Re-apply favorite heart states to newly loaded cards
            if (typeof window.applyFavoriteHearts === 'function') {
                window.applyFavoriteHearts();
            }
            
        } catch (error) {
            console.error('Error fetching filtered pets:', error);
            setFeedback('Unable to load pets right now. Please try again.', true);
        } finally {
            isLoading = false;
            loadMoreButton.disabled = false;
            loadMoreButton.setAttribute('aria-disabled', 'false');
            loader.classList.remove('active');
            petsGrid.setAttribute('aria-busy', 'false');
            petsGrid.style.opacity = '1';
        }
    };

    const debouncedFetch = debounce(fetchPets, 400);

    // Listen to all inputs changing
    filterForm.addEventListener('input', (e) => {
        if (e.target.type === 'text') {
            debouncedFetch();
            return;
        }

        fetchPets();
    });

    loadMoreButton.addEventListener('click', () => {
        if (!nextPageUrl) {
            return;
        }

        fetchPets({ append: true, url: nextPageUrl });
    });
    
    // Reset filters
    filterForm.addEventListener('reset', () => {
        setTimeout(() => {
            ageDisplay.textContent = '180 mo';
            energyDisplay.textContent = 'Active (10)';
            fetchPets();
        }, 10);
    });

    setLoadMoreVisibility();
    setFeedback(`Showing ${totalResultsDisplay.textContent} results.`);
});
</script>
@endsection
