// Loading Overlay Script
document.addEventListener('DOMContentLoaded', function() {
    // Create and inject CSS
    const style = document.createElement('style');
    style.textContent = `
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        #loadingOverlay .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
        }

        .spinner-border {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            vertical-align: -0.125em;
            border: 0.25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }

        .text-primary {
            color: #0d6efd !important;
        }
    `;
    document.head.appendChild(style);

    // Create and inject HTML
    const overlay = document.createElement('div');
    overlay.id = 'loadingOverlay';
    overlay.innerHTML = `
        <div class="loading-content">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0">Loading...</p>
        </div>
    `;
    document.body.appendChild(overlay);

    // Get all links that should trigger loading
    const links = document.querySelectorAll('a:not([href^="#"]):not([href=""]):not([data-bs-toggle]):not(.no-loading)');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't show loading for external links opening in new tab
            if (this.getAttribute('target') === '_blank') {
                return;
            }
            
            // Don't show loading for mailto: or tel: links
            const href = this.getAttribute('href');
            if (href && (href.startsWith('mailto:') || href.startsWith('tel:'))) {
                return;
            }
            
            // Show loading overlay
            overlay.style.display = 'flex';
            
            // Hide loading after 10 seconds as fallback
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 10000);
        });
    });
    
    // Hide loading on page show (when using back button)
    window.addEventListener('pageshow', function() {
        overlay.style.display = 'none';
    });
});