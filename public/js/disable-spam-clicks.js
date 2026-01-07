document.addEventListener("DOMContentLoaded", () => {

    const handled = new WeakSet();

    function disableElement(el) {
        if (el.tagName === "BUTTON" || el.tagName === "INPUT") {
            el.disabled = true;
        } else {
            el.style.pointerEvents = "none";
        }

        el.classList.add("disabled");
        el.style.opacity = "0.65";

        const loadingText = el.dataset.loadingText;
        if (loadingText) {
            el.dataset.originalText = el.innerHTML;
            el.innerHTML = loadingText;
        }
    }

    function handleAction(el, event) {
        if (handled.has(el)) {
            event.preventDefault();
            return;
        }

        // Confirmation (if required)
        if (el.dataset.confirm) {
            const ok = confirm(el.dataset.confirm);
            if (!ok) {
                event.preventDefault();
                return;
            }
        }

        handled.add(el);
        disableElement(el);
    }

    // Action links
    document.querySelectorAll("a[data-action]").forEach(el => {
        el.addEventListener("click", (e) => {
            handleAction(el, e);
        });
    });

    // Action buttons
    document.querySelectorAll("button[data-action], input[data-action]").forEach(el => {
        el.addEventListener("click", (e) => {
            handleAction(el, e);
        });
    });

    // Forms: protect against double submit
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            const btn = form.querySelector("[data-action]");
            if (btn) disableElement(btn);
        });
    });

});
