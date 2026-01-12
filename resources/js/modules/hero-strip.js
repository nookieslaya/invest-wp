// const clamp = (value, min = 0, max = 1) => Math.min(Math.max(value, min), max);

// const initHeroStrip = () => {
//   const heroes = document.querySelectorAll('[data-hero-strip]');

//   if (!heroes.length) {
//     return;
//   }

//   const reduceMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

//   const updateHero = (hero) => {
//     if (reduceMotionQuery.matches) {
//       hero.style.setProperty('--hero-strip-parallax', '0px');
//       return;
//     }

//     const rect = hero.getBoundingClientRect();
//     const viewport = window.innerHeight || document.documentElement.clientHeight;
//     const scrollRange = viewport + rect.height || 1;
//     const progress = clamp((viewport - rect.top) / scrollRange);
//     const offset = (progress - 0.5) * 80;

//     hero.style.setProperty('--hero-strip-parallax', `${offset.toFixed(1)}px`);
//   };

//   const updateAll = () => {
//     heroes.forEach(updateHero);
//   };

//   updateAll();
//   let ticking = false;
//   const scheduleUpdate = () => {
//     if (ticking) {
//       return;
//     }

//     ticking = true;
//     window.requestAnimationFrame(() => {
//       updateAll();
//       ticking = false;
//     });
//   };

//   window.addEventListener('scroll', scheduleUpdate, { passive: true });
//   window.addEventListener('resize', updateAll);

//   if (reduceMotionQuery.addEventListener) {
//     reduceMotionQuery.addEventListener('change', updateAll);
//   } else if (reduceMotionQuery.addListener) {
//     reduceMotionQuery.addListener(updateAll);
//   }
// };

// if (document.readyState === 'loading') {
//   document.addEventListener('DOMContentLoaded', initHeroStrip);
// } else {
//   initHeroStrip();
// }
