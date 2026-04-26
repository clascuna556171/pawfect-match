# 🐾 PawfectMatch - Pet Adoption Management System

PawfectMatch is a premium, modern, and highly interactive Pet Adoption Management System built with **Laravel 11**. It is designed with a focus on delivering a high-quality user experience through beautiful glassmorphism design, immersive animations, and robust management capabilities.

## ✨ Key Features

### 🏡 Public Platform
* **Immersive Homepage**: Full-bleed background video hero, scroll-triggered animated statistics, and a featured pets carousel with smooth interactions.
* **Interactive Pet Gallery**: Advanced sidebar filtering (species, size, temperament, age, energy levels) with real-time AJAX search and infinite scrolling.
* **High-Class Pet Details**: Photo galleries, expandable accordion information panels (Health, Personality, Requirements), and a modal application form with backdrop blur.
* **Adopter Authentication**: Split-screen design with sliding toggles, floating input labels, and premium UI elements.
* **Smart Natural Language Chatbot**: A custom-built, keyword-based chatbot assistant that allows users to search for pets using natural language.

### 🛡️ Admin Dashboard
* **Glassmorphism UI**: Beautiful, semi-transparent admin interface with animated background orbs and sleek data visualization using Recharts.
* **Application Management**: Intuitive filtering and tracking of adoption applications (Submitted, Under Review, Rejected, Withdrawn) with read-only badges and clear statuses.
* **Inline Editing**: Live updates to pet information and drag-and-drop or select-dropdown status management without full page reloads.

## 🚀 Tech Stack

* **Backend**: PHP 8.3+, Laravel 11.x
* **Database**: MySQL (Eloquent ORM)
* **Frontend**: HTML5, Vanilla JavaScript, Blade Templating
* **Styling**: Tailwind CSS 4.x, Custom CSS (Glassmorphism, Animations)
* **Build Tools**: Vite, Composer, NPM
* **Animations**: AOS (Animate On Scroll), CSS Keyframes

## 🛠️ Installation & Setup (Local Development)

Follow these steps to set up PawfectMatch on your local machine:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/pet-adoption.git
   cd pet-adoption
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install and compile frontend assets:**
   ```bash
   npm install
   npm run dev
   ```

4. **Environment Setup:**
   * Copy the example `.env` file:
     ```bash
     cp .env.example .env
     ```
   * Generate an application key:
     ```bash
     php artisan key:generate
     ```
   * Update your `.env` file with your local MySQL database credentials:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=pawfectmatch
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Start the local development server:**
   ```bash
   php artisan serve
   ```
   * The application will be available at `http://localhost:8000`.

## ☁️ Production Deployment

This project is configured for deployment using:
* **Web Hosting**: [Render](https://render.com) using Docker. Assets and videos are optimized to fit within Render's 1GB Docker image limit.
* **Database Hosting**: [Clever Cloud](https://www.clever-cloud.com) for MySQL database hosting.

To deploy, ensure your `APP_KEY` and production `DB_*` variables are correctly set in the Render Dashboard environment settings.

## 🤝 Contributing

Contributions are welcome! If you'd like to improve the UI, fix bugs, or optimize the backend, please fork the repository, make your changes, and submit a pull request.

## 📄 License

This project is proprietary and built for educational/demonstration purposes. All design mockups and concepts belong to their respective creators.
