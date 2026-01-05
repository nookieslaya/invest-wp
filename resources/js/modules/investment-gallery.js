const initInvestmentGallery = () => {
  const cards = document.querySelectorAll('[data-gallery-card]');

  if (!cards.length) {
    return;
  }

  if (!('IntersectionObserver' in window)) {
    cards.forEach((card) => card.classList.add('is-in-view'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in-view');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.35,
      rootMargin: '0px 0px -10% 0px',
    },
  );

  cards.forEach((card) => observer.observe(card));
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initInvestmentGallery);
} else {
  initInvestmentGallery();
}
