// Initialisation des icônes Lucide
lucide.createIcons();
// Mobile Menu Overlays
const menuToggle = document.getElementById('menuToggle'); // Top Burger
const openCategories = document.getElementById('openCategories'); // Bottom Categories
const openProfile = document.getElementById('openProfile'); // Bottom Profile

const overlayPages = document.getElementById('mobileMenuPages');
const overlayCategories = document.getElementById('mobileMenuCategories');
const overlayProfile = document.getElementById('mobileMenuProfile');
const overlayLogin = document.getElementById('loginOverlay');
const overlayCart = document.getElementById('cartOverlay');

function openMenu(menu) {
    if (menu) {
        menu.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeAllMenus() {
    const overlays = document.querySelectorAll('.mobile-menu-overlay');
    overlays.forEach(menu => menu.classList.remove('active'));
    document.body.style.overflow = '';
}

// Event Listeners for Opening Menus
if (menuToggle) menuToggle.addEventListener('click', () => openMenu(overlayPages));
if (openCategories) openCategories.addEventListener('click', (e) => { e.preventDefault(); openMenu(overlayCategories); });
if (openProfile) openProfile.addEventListener('click', (e) => { e.preventDefault(); openMenu(overlayProfile); });

const loginOverlayBtn = document.getElementById('openLoginOverlay');
if (loginOverlayBtn) loginOverlayBtn.addEventListener('click', (e) => {
    e.preventDefault();
    openMenu(overlayLogin);
});

const cartOverlayBtn = document.getElementById('openCartOverlay');
if (cartOverlayBtn) cartOverlayBtn.addEventListener('click', (e) => {
    e.preventDefault();
    openMenu(overlayCart);
});

// Mobile bottom nav cart button
const cartOverlayMobileBtn = document.getElementById('openCartOverlayMobile');
if (cartOverlayMobileBtn) cartOverlayMobileBtn.addEventListener('click', (e) => {
    e.preventDefault();
    openMenu(overlayCart);
});

// Close buttons inside all overlays
document.querySelectorAll('.close-menu').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeAllMenus();
    });
});

// Close when clicking overlay background
document.querySelectorAll('.mobile-menu-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeAllMenus();
        }
    });
});

// Submenu Toggle Logic (Categories)
const submenuLinks = document.querySelectorAll('.has-submenu > a');
submenuLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const parent = link.parentElement;
        parent.classList.toggle('active');
        // Chevron animation handled via CSS rotate
    });
});

// User Icon Logic (Header) - Desktop Hover / Mobile opens Login
const userAccount = document.getElementById('userAccount');
if (userAccount) {
    userAccount.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            // On mobile, open login overlay instead of dropdown
            e.preventDefault();
            openMenu(overlayLogin);
        }
    });
}

// Mega Menu Toggle Logic for Tablets (where hover isn't ideal)
document.querySelectorAll('.mega-menu-trigger').forEach(trigger => {
    trigger.addEventListener('click', (e) => {
        if (window.innerWidth <= 1024) { // Tablet and below
            // If clicking the link itself, we might want to toggle instead of navigating
            // But usually, the menu has its own links. 
            // Let's toggle a class.
            const isOpened = trigger.classList.contains('mega-menu-open');

            // Close others
            document.querySelectorAll('.mega-menu-trigger').forEach(t => t.classList.remove('mega-menu-open'));

            if (!isOpened) {
                trigger.classList.add('mega-menu-open');
                e.preventDefault(); // Prevent navigation on first click to show menu
            }
        }
    });
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.mega-menu-trigger')) {
        document.querySelectorAll('.mega-menu-trigger').forEach(t => t.classList.remove('mega-menu-open'));
    }
});

/*--------------------------------------------------------------
# Countdown Timer Logic
--------------------------------------------------------------*/
function initCountdown() {
    const timers = document.querySelectorAll('.countdown-timer');

    timers.forEach(timer => {
        const deadline = new Date(timer.getAttribute('data-deadline')).getTime();

        const updateTimer = () => {
            const now = new Date().getTime();
            const distance = deadline - now;

            if (distance < 0) {
                timer.innerHTML = "<span class='deal-expired'>Deal Expired</span>";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const daysEl = timer.querySelector('.days');
            const hoursEl = timer.querySelector('.hours');
            const minutesEl = timer.querySelector('.minutes');
            const secondsEl = timer.querySelector('.seconds');

            if (daysEl) daysEl.textContent = days.toString().padStart(2, '0');
            if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
            if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
            if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
        };

        updateTimer();
        setInterval(updateTimer, 1000);
    });
}

initCountdown();

/*--------------------------------------------------------------
# Video Modal Logic
--------------------------------------------------------------*/
const openVideoBtn = document.getElementById('openVideoModal');
const videoModal = document.getElementById('videoModal');
const closeVideoBtn = document.querySelector('.close-video-modal');
const youtubeIframe = document.getElementById('youtubeIframe');
const videoModalOverlay = document.querySelector('.video-modal-overlay');

// Use the high-quality video provided by the user
const youtubeUrl = "https://www.youtube.com/embed/Btf4mN37OsU?autoplay=1";

if (openVideoBtn && videoModal && youtubeIframe) {
    openVideoBtn.addEventListener('click', () => {
        youtubeIframe.src = youtubeUrl;
        videoModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
}

function closeVideo() {
    if (videoModal) {
        videoModal.classList.remove('active');
        if (youtubeIframe) youtubeIframe.src = "";
        document.body.style.overflow = '';
    }
}

if (closeVideoBtn) closeVideoBtn.addEventListener('click', closeVideo);
if (videoModalOverlay) videoModalOverlay.addEventListener('click', closeVideo);

// Close on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeVideo();
});
