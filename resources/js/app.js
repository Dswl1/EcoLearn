import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import './sweetalert';

// Particle Generation
const particleContainer = document.getElementById('particle-container');

if (particleContainer) {
    const particleCount = 15;
    const fragment = document.createDocumentFragment();

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';

        const size = Math.random() * 3 + 1;
        const x = Math.random() * 100;

        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.left = x + '%';
        particle.style.top = Math.random() * 100 + '%';

        particle.animate([
            { transform: 'translateY(0) translateX(0)', opacity: 0.3 },
            { transform: 'translateY(-100px) translateX(' + (Math.random() * 50 - 25) + 'px)', opacity: 0 }
        ], {
            duration: (Math.random() * 20 + 10) * 1000,
            iterations: Infinity,
            delay: Math.random() * -20 * 1000,
            easing: 'linear'
        });

        fragment.appendChild(particle);
    }

    particleContainer.appendChild(fragment);
}

// Mouse interaction for card tilt (hero card only) - throttled
const heroCard = document.querySelector('.hero-card-tilt');
if (heroCard) {
    let ticking = false;
    document.addEventListener('mousemove', function(e) {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                const xAxis = (window.innerWidth / 2 - e.pageX) / 30;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 30;
                heroCard.style.transform = 'rotateY(' + xAxis + 'deg) rotateX(' + yAxis + 'deg)';
                ticking = false;
            });
            ticking = true;
        }
    });
}
