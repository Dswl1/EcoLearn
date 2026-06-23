// window.addEventListener('DOMContentLoaded', () => {
//     const toast = document.getElementById('toast');

//     if (toast) {
//         setTimeout(() => {
//             toast.classList.add('active');
//         }, 100);

//         setTimeout(() => {
//             toast.classList.remove('active');
//         }, 3000);
//     }
// });

// // Particle Generation
// const container = document.getElementById('particle-container');
// const particleCount = 40;

// for (let i = 0; i < particleCount; i++) {
//     createParticle();
// }

// function createParticle() {
//     const particle = document.createElement('div');
//     particle.className = 'particle';

//     const size = Math.random() * 3 + 1;
//     const x = Math.random() * 100;
//     const y = Math.random() * 100;
//     const duration = Math.random() * 20 + 10;
//     const delay = Math.random() * -20;

//     particle.style.width = `${size}px`;
//     particle.style.height = `${size}px`;
//     particle.style.left = `${x}%`;
//     particle.style.top = `${y}%`;

//     particle.animate([{
//         transform: 'translateY(0) translateX(0)',
//         opacity: 0.3
//     },
//     {
//         transform: `translateY(-100px) translateX(${Math.random() * 50 - 25}px)`,
//         opacity: 0
//     },
//     ], {
//         duration: duration * 1000,
//         iterations: Infinity,
//         delay: delay * 1000,
//         easing: 'linear'
//     });

//     container.appendChild(particle);
// }

// // Form Logic
// const form = document.getElementById('reset-form');
// const submitBtn = document.getElementById('submit-btn');
// const btnText = document.getElementById('btn-text');
// const btnLoader = document.getElementById('btn-loader');
// const btnIcon = document.getElementById('btn-icon');
// const toast = document.getElementById('toast');
// const emailInput = document.getElementById('email');


// // Mouse interaction for card tilt
// const card = document.querySelector('.glass-card');
// document.addEventListener('mousemove', (e) => {
//     const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
//     const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
//     card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
// });