# Project: Pet Adoption Management System (PawfectMatch)
# Source of Truth & AI Coding Guidelines

## 🎨 Design System
## sample_UI is available for reference (review the .mp4 file)

### Color Palette
- **Primary Navy**: `#1A2332` - Deep navy for reliability and trust
- **Cream Background**: `#FAF8F6` - Soft, warm off-white background
- **Coral CTA**: `#FF6B6B` to `#FF8E8E` - Vibrant coral gradient for call-to-action
- **Mint Accent**: `#4ECDC4` - Refreshing mint green for secondary actions
- **Muted Gray**: `#6B7280` - Professional gray for secondary text

### Typography
- **Headings**: Playfair Display (Elegant serif)
- **Body Text**: Inter (Clean geometric sans-serif)
- **Font Sizes**: Responsive scaling from mobile to desktop

## ✨ Premium Features Implemented

### 1. Immersive Homepage

#### Full-Bleed Video Hero
- Auto-playing background video with smooth fade-in
- Sophisticated gradient overlay
- Animated text with staggered reveal (0.8s duration, custom easing)
- Prominent CTA button with hover scale effect

#### Animated Statistics Section
- Custom counting animation hook (`useCountUp`)
- Intersection Observer for scroll-triggered animations
- Smooth ease-out-quart easing function
- Three key metrics: Successful Adoptions, Available Pets, Happy Families

#### Process Section
- Three-step adoption process with icons
- Hover effects: scale + shadow + blur glow
- Staggered fade-in animations on scroll
- Icon rotation on hover (500ms duration)

#### Featured Pets Carousel
- Grid layout with smooth scroll animations
- Individual card animations with delay staggering
- AOS (Animate On Scroll) for all animations
- Viewport-based scroll animation triggers

### 2. Interactive Pet Gallery (Browse Page)

#### Advanced Filter Sidebar
- **Multi-select filters**: Species, Size, Temperament (checkboxes & tags)
- **Range sliders**: Age (0-180 months), Energy Level (0-10)
- **Search input**: Real-time filtering by name/breed
- **Mobile-responsive**: Slide-up modal on mobile with spring animations

#### Premium Pet Cards
- **Hover Effects**:
  - Vertical lift (-8px translateY)
  - Image scale (1.1x) with blur
  - Gradient overlay reveal
  - Stats display (Energy Level, Temperament tags)
- **Heart Icon**: Scale animation on hover/tap
- **Status Badges**: Color-coded (Available/Pending)

#### Infinite Scrolling
- Load 6 pets initially
- "Load More" button with loading state
- Lottie-style spinner (rotating border animation)
- Smooth content insertion

### 3. High-Class Pet Detail Page

#### Photo Gallery
- Large main image with aspect-square ratio
- **360° View Button**: Rotation animation on click (placeholder for future 360 implementation)
- **Thumbnail Grid**: 4-column layout with active state rings
- Heart favorite button with filled icon

#### Expandable Information Sections
- **Accordion-style panels**: Health, Personality, Requirements
- **Smooth animations**: Height auto-expand with opacity fade
- **ChevronDown rotation**: 180° on toggle (0.3s duration)
- **Color-coded indicators**: Green checkmarks, gray X's

#### Sticky Application Button
- Fixed positioning at bottom center
- Gradient background with shadow glow
- Scale-up hover effect
- Animated entry from bottom (y: 100 → 0)

#### Modal Application Form
- Backdrop blur overlay
- Spring animation on open (scale 0.9 → 1)
- Two-column responsive grid
- Custom styled inputs with focus states

### 4. Glassmorphism Admin Dashboard

#### Glassmorphic Design
- Gradient background: Purple to pink (`#667eea` → `#764ba2` → `#f093fb`)
- **Animated background orbs**: Floating elements with 20-25s loops
- **Card styling**: `backdrop-blur-xl`, white/10 opacity, border white/20

#### Animated Charts (Recharts)
- **Pie Chart**: Status distribution with custom colors
  - Available: Mint `#4ECDC4`
  - Pending: Amber `#FFB84D`
  - Adopted: Coral `#FF6B6B`
  - 800ms animation duration
- **Line Chart**: Monthly adoption trends
  - 1000ms animation
  - Animated dots and lines
  - Custom grid styling

#### Inline Editing
- **Click to edit**: Name and Breed fields
- **Status dropdown**: Drag-style instant update
- **Save/Cancel buttons**: Icon-based actions
- **Visual feedback**: Input styling with blur background

#### Status Management
- **Animated toggle**: Select dropdown with gradient backgrounds
- **Live updates**: Instant status change reflection
- **Color transitions**: Smooth background color shifts

## 🎭 Animation Details

### AOS (Animate On Scroll) Implementation
- All scroll-triggered animations use AOS (`aos.js`)
- **Easing**: Custom CSS cubic-bezier or `ease-in-out`
- **Durations**: 300ms (quick), 600ms (medium), 800ms (slow)
- Initialization in `app.js` with `once: true` to prevent re-triggering

### Scroll Animations
- `useInView` hook with Intersection Observer
- Threshold: 0.3 for early trigger
- Margin: -50px to -100px for pre-animation
- Viewport: `once: true` to prevent re-trigger

### Hover States
- Card lift: `whileHover={{ y: -8 }}`
- Image blur: `filter: blur(sm)` on hover
- Scale: 1.05-1.1 for emphasis
- Shadow: md → 2xl transitions

### Loading States
- Skeleton screens with pulse animation
- Custom spinner with rotating border
- Shimmer effect on load states

## 🎯 Accessibility Features

1. **ARIA Labels**: All interactive elements
2. **Keyboard Navigation**: Focus states with ring utilities
3. **Color Contrast**: WCAG AA compliant (navy on cream, white on coral)
4. **Focus Indicators**: 2px ring-[#FF6B6B]
5. **Screen Reader Text**: Hidden labels for icons

## 📱 Responsive Design

- **Mobile-first**: Base styles for mobile, enhanced for desktop
- **Breakpoints**: sm (640px), md (768px), lg (1024px), xl (1280px)
- **Touch targets**: Minimum 44x44px for mobile
- **Flexible grids**: 1 col mobile → 2-3 cols desktop

## 🚀 Performance Optimizations

1. **Lazy loading**: Images with fallback component
2. **Memoization**: useMemo for filtered pet lists
3. **Debouncing**: Search input filtering
4. **Code splitting**: React Router with lazy components
5. **Animation batching**: RequestAnimationFrame for count-up

## 🎨 Custom CSS Utilities

Located in `/src/styles/animations.css`:
- `.glass` - Glassmorphism effect
- `.gradient-text` - Gradient text fill
- `.animate-float` - Floating animation
- `.animate-glow` - Glowing shadow pulse
- Custom scrollbar styling

## 🔧 Technical Stack & Architecture

* **Backend:** PHP 8.3+, Laravel 11.x
* **Database:** MySQL (via XAMPP). Eloquent ORM used for all database interactions.
* **Frontend Rendering:** HTML5, CSS3, Vanilla JavaScript, Laravel Blade templating.
* **CSS Framework:** Tailwind CSS 4.x
* **Animation:** CSS transitions/keyframes for hover states, AOS (Animate On Scroll) for scroll animations.
* **Icons:** Lucide Icons (SVG) or Heroicons.
* **Build System:** Vite

## Development Rules for Copilot
1.  **Strict MVC:** Keep business logic in Controllers, data structures in Models, and markup in Blade Views.
2.  **No Page Reloads for Interactions:** Use AJAX/Fetch API for filtering pets and submitting forms.
3.  **Accessibility First:** All video elements must include mandatory subtitle `<track>` tags. Ensure semantic HTML, high color contrast, and proper ARIA labels throughout the application.
4.  **Visual Polish:** Apply cinematic image styling (e.g., dynamic contrast, warm color grading overlays on hover) to pet photography to maintain a premium feel.

## System Modules (6 Core Modules)

### 1. Homepage Module (`resources/views/home.blade.php`)
* **Video Hero Section:** Full-bleed background video with clear text overlay and mandatory closed captions (VTT) for accessibility.
* **Animated Statistics Counter:** Dynamic counting animation for metrics like "Pets Rescued" and "Happy Homes."
* **Adoption Process Showcase:** Step-by-step visual guide using iconography.
* **Featured Pets Carousel:** Smooth-scrolling, touch-friendly slider highlighting priority adoptions.

### 2. Pet Gallery/Browse Module (`resources/views/pets/index.blade.php`)
* **Advanced Filtering System:** Sidebar with sliders (age, size) and multi-select tags (species, temperament, energy level) that update results via AJAX.
* **Interactive Pet Cards:** Grid layout. On hover, apply cinematic color grading to the image, scale up slightly, and smoothly overlay detailed stats.
* **Search Functionality:** Real-time text search by breed or name.
* **Infinite Scroll:** Load more pets dynamically as the user scrolls to the bottom of the grid.

### 3. Pet Detail Module (`resources/views/pets/show.blade.php`)
* **Photo Gallery:** Large primary image with a grid of clickable thumbnails.
* **Expandable Information:** Tabulated or accordion sections for Health, Personality, and Requirements with smooth ease-in-out transitions.
* **Application Form Modal:** A clean, multi-step form that opens over the page without redirecting.
* **Sticky Adoption Button:** A call-to-action button that remains visible on the screen as the user scrolls down the details.

### 4. Admin Dashboard Module (`resources/views/admin/dashboard.blade.php`)
* **Glassmorphism UI:** Transparent, blurred panels over a soft gradient background.
* **Data Visualization:** Chart.js integration showing pending applications and shelter capacity.
* **Inline Editing:** Data tables where admins can click a field (like 'Name' or 'Status') to edit it directly without opening a new page (requires Livewire or AJAX).
* **Status Management:** Drag-and-drop or animated toggle switches to change a pet's status from "Available" to "Adopted."

### 5. Adopter Authentication Module (`resources/views/auth/`)
* **Split-Screen Design:** Left side features an emotional, high-quality pet image/video; right side is the form.
* **User Login/Signup:** Smooth sliding toggle to switch between creating an account and signing in.
* **Floating Input Labels:** Clean, modern form inputs where the placeholder text elegantly slides up to become the label when focused.

### 6. Admin Login Modal (`resources/views/admin/login.blade.php`)
* **Secure Dark-Mode Design:** Visually distinct from the public site using a dark, frosted-glass modal centered on the screen.
* **Micro-interactions:** A padlock icon that animates to an unlocked state upon successful credential entry.
* **Isolated Flow:** Separate authentication logic using Laravel Guards to ensure admins are routed to the dashboard and standard users to the homepage.