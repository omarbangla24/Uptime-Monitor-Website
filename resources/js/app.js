import './bootstrap';

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
// resources/js/app.js
import './bootstrap';
import './globals/tiny-global'; // ✅ সব পেজে TinyMCE অটো-ইনিট



// Alpine plugins
Alpine.plugin(persist);

// Global Alpine data
document.addEventListener('alpine:init', () => {
    // Theme management
    Alpine.data('theme', () => ({
        darkMode: Alpine.$persist(false).as('darkMode'),

        init() {
            // Set initial theme based on system preference if not set
            if (!localStorage.getItem('darkMode')) {
                this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }

            // Watch for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('darkMode')) {
                    this.darkMode = e.matches;
                }
            });
        },

        toggle() {
            this.darkMode = !this.darkMode;
        }
    }));

    // Notification system
    Alpine.data('notifications', () => ({
        notifications: [],

        add(type, message, duration = 5000) {
            const id = Date.now();
            const notification = { id, type, message };

            this.notifications.push(notification);

            if (duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, duration);
            }

            return id;
        },

        remove(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications.splice(index, 1);
            }
        },

        success(message, duration) {
            return this.add('success', message, duration);
        },

        error(message, duration) {
            return this.add('error', message, duration);
        },

        warning(message, duration) {
            return this.add('warning', message, duration);
        },

        info(message, duration) {
            return this.add('info', message, duration);
        }
    }));

    // Modal management
    Alpine.data('modal', (initialOpen = false) => ({
        open: initialOpen,

        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },

        hide() {
            this.open = false;
            document.body.style.overflow = '';
        },

        toggle() {
            this.open ? this.hide() : this.show();
        }
    }));

    // Form handling
    Alpine.data('form', () => ({
        loading: false,
        errors: {},

        async submit(url, data, options = {}) {
            this.loading = true;
            this.errors = {};

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        ...options.headers
                    },
                    body: JSON.stringify(data),
                    ...options
                });

                const result = await response.json();

                if (!response.ok) {
                    if (result.errors) {
                        this.errors = result.errors;
                    }
                    throw new Error(result.message || 'An error occurred');
                }

                return result;
            } catch (error) {
                console.error('Form submission error:', error);
                throw error;
            } finally {
                this.loading = false;
            }
        },

        hasError(field) {
            return this.errors[field] && this.errors[field].length > 0;
        },

        getError(field) {
            return this.errors[field] ? this.errors[field][0] : '';
        }
    }));
});

window.Alpine = Alpine;
Alpine.start();

// Global utility functions
window.utils = {
    // Format bytes to human readable format
    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    },

    // Format duration in milliseconds to human readable format
    formatDuration(ms) {
        if (ms < 1000) return `${Math.round(ms)}ms`;
        if (ms < 60000) return `${(ms / 1000).toFixed(1)}s`;
        if (ms < 3600000) return `${Math.round(ms / 60000)}m`;
        return `${Math.round(ms / 3600000)}h`;
    },

    // Format date to relative time
    formatRelativeTime(date) {
        const now = new Date();
        const diff = now - new Date(date);

        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (days > 0) return `${days}d ago`;
        if (hours > 0) return `${hours}h ago`;
        if (minutes > 0) return `${minutes}m ago`;
        return `${seconds}s ago`;
    },

    // Copy text to clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            console.error('Failed to copy text: ', err);
            return false;
        }
    },

    // Debounce function
    debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }
};

// Real-time updates using Server-Sent Events or WebSockets
class RealtimeUpdates {
    constructor() {
        this.eventSource = null;
        this.callbacks = new Map();
    }

    connect(url = '/api/realtime') {
        if (this.eventSource) {
            this.eventSource.close();
        }

        this.eventSource = new EventSource(url);

        this.eventSource.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                this.handleMessage(data);
            } catch (error) {
                console.error('Error parsing realtime message:', error);
            }
        };

        this.eventSource.onerror = (error) => {
            console.error('Realtime connection error:', error);
        };
    }

    disconnect() {
        if (this.eventSource) {
            this.eventSource.close();
            this.eventSource = null;
        }
    }

    on(event, callback) {
        if (!this.callbacks.has(event)) {
            this.callbacks.set(event, []);
        }
        this.callbacks.get(event).push(callback);
    }

    off(event, callback) {
        if (this.callbacks.has(event)) {
            const callbacks = this.callbacks.get(event);
            const index = callbacks.indexOf(callback);
            if (index > -1) {
                callbacks.splice(index, 1);
            }
        }
    }

    handleMessage(data) {
        const { event, payload } = data;
        if (this.callbacks.has(event)) {
            this.callbacks.get(event).forEach(callback => callback(payload));
        }
    }
}

window.realtime = new RealtimeUpdates();

// Initialize realtime updates for authenticated users
if (document.querySelector('meta[name="user-id"]')) {
    window.realtime.connect();
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    window.realtime.disconnect();
});
