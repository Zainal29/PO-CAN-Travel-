import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

window.cityAutocomplete = () => ({
    activeField: null,
    suggestions: [],
    requestId: 0,
    debounceTimer: null,

    searchCities(field, value) {
        this.activeField = field;
        const query = value.trim();
        clearTimeout(this.debounceTimer);
        const requestId = ++this.requestId;

        if (query.length < 2) {
            this.suggestions = [];
            return;
        }

        this.debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(
                    `/api/cities/suggestion?q=${encodeURIComponent(query)}`,
                    { headers: { Accept: "application/json" } },
                );

                if (!response.ok || requestId !== this.requestId) {
                    return;
                }

                this.suggestions = await response.json();
            } catch {
                if (requestId === this.requestId) {
                    this.suggestions = [];
                }
            }
        }, 300);
    },

    selectCity(field, city) {
        document.getElementById(field).value = city;
        this.suggestions = [];
        this.activeField = null;
    },

    closeSuggestions(field) {
        if (this.activeField === field) {
            this.suggestions = [];
            this.activeField = null;
        }
    },
});

Alpine.start();
