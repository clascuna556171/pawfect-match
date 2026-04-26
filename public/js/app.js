// PawfectMatch App JS
document.addEventListener('DOMContentLoaded', function() {
	if (typeof AOS !== 'undefined') {
		AOS.init({ duration: 800, once: true });
	}

	const adminLoginLink = document.getElementById('adminLoginLink');
	if (adminLoginLink) {
		adminLoginLink.addEventListener('click', function(event) {
			event.preventDefault();
			window.location.assign('/admin-access');
		});
	}

	// Public navbar mobile toggle
	const mobileToggle = document.getElementById('mobileToggle');
	const navbarMenu = document.getElementById('navbarMenu');
	if (mobileToggle && navbarMenu) {
		mobileToggle.addEventListener('click', function() {
			navbarMenu.classList.toggle('active');
			this.textContent = navbarMenu.classList.contains('active') ? '✕' : '☰';
		});
	}

	// Admin navbar mobile toggle
	const mobileToggleAdmin = document.getElementById('mobileToggleAdmin');
	const navbarMenuAdmin = document.getElementById('navbarMenuAdmin');
	if (mobileToggleAdmin && navbarMenuAdmin) {
		mobileToggleAdmin.addEventListener('click', function() {
			navbarMenuAdmin.classList.toggle('active');
			this.textContent = navbarMenuAdmin.classList.contains('active') ? '✕' : '☰';
		});
	}

	// Initialize favorite hearts on page load
	initFavoriteHearts();
});

// Favorite toggle used by heart buttons in browse/home cards.
window.toggleFavorite = async function(petId, button) {
	const icon = button ? button.querySelector('svg') : null;

	try {
		const response = await fetch('/favorites/toggle/' + petId, {
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json'
			}
		});

		if (!response.ok) {
			if (response.status === 401 || response.status === 403) {
				window.location.href = '/login';
				return;
			}
			throw new Error('Request failed');
		}

		const data = await response.json();

		if (!data.success) {
			throw new Error('Toggle failed');
		}

		if (icon) {
			if (data.favorited) {
				icon.style.fill = '#FF6B6B';
				icon.style.stroke = '#FF6B6B';
				button.classList.add('is-favorited');
				// Update local cache
				if (window._favoritedPetIds) {
					window._favoritedPetIds.add(petId);
				}
			} else {
				icon.style.fill = 'none';
				icon.style.stroke = 'currentColor';
				button.classList.remove('is-favorited');
				if (window._favoritedPetIds) {
					window._favoritedPetIds.delete(petId);
				}
			}
		}
	} catch (error) {
		window.alert('Unable to update favorites right now. Please try again.');
	}
};

/**
 * Fetch the user's favorited pet IDs and apply red hearts on page load.
 */
function initFavoriteHearts() {
	fetch('/favorites/ids', {
		headers: { 'Accept': 'application/json' }
	})
	.then(function(res) {
		if (!res.ok) return null;
		return res.json();
	})
	.then(function(data) {
		if (!data || !data.ids) return;
		window._favoritedPetIds = new Set(data.ids.map(function(id) { return Number(id); }));
		applyFavoriteHearts();
	})
	.catch(function() {
		// Silently fail — user is not logged in
	});
}

/**
 * Apply red fill to all .card-fav-btn whose pet ID is in the favorited set.
 * Can be called after dynamic content loads (AJAX pet grid).
 */
function applyFavoriteHearts() {
	if (!window._favoritedPetIds || window._favoritedPetIds.size === 0) return;

	document.querySelectorAll('.card-fav-btn').forEach(function(btn) {
		var onclickAttr = btn.getAttribute('onclick') || '';
		var match = onclickAttr.match(/toggleFavorite\((\d+)/);
		if (!match) return;

		var petId = Number(match[1]);
		if (window._favoritedPetIds.has(petId)) {
			var icon = btn.querySelector('svg');
			if (icon) {
				icon.style.fill = '#FF6B6B';
				icon.style.stroke = '#FF6B6B';
			}
			btn.classList.add('is-favorited');
		}
	});
}

window.applyFavoriteHearts = applyFavoriteHearts;