import './bootstrap';

// Intersection Observer for fade-in animations
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
});

// Livewire event handling
document.addEventListener('livewire:initialized', () => {
    Livewire.on('generation-started', () => {
        console.log('Generation queued');
    });
});
