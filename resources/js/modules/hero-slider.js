const initHeroSlider = () => {
  const sliders = document.querySelectorAll('[data-hero-slider]');

  sliders.forEach((slider) => {
    const track = slider.querySelector('[data-hero-track]');
    const slides = slider.querySelectorAll('[data-hero-slide]');
    const prevButton = slider.querySelector('[data-hero-prev]');
    const nextButton = slider.querySelector('[data-hero-next]');
    const currentEl = slider.querySelector('[data-hero-current]');
    const totalEl = slider.querySelector('[data-hero-total]');

    if (!track || slides.length < 2 || !prevButton || !nextButton) {
      return;
    }

    let index = 0;
    const formatNumber = (value) => String(value).padStart(2, '0');

    const goTo = (nextIndex) => {
      index = (nextIndex + slides.length) % slides.length;
      track.style.transform = `translateX(-${index * 100}%)`;

      if (currentEl) {
        currentEl.textContent = formatNumber(index + 1);
      }
    };

    if (totalEl) {
      totalEl.textContent = formatNumber(slides.length);
    }
    if (currentEl) {
      currentEl.textContent = formatNumber(index + 1);
    }

    prevButton.addEventListener('click', () => goTo(index - 1));
    nextButton.addEventListener('click', () => goTo(index + 1));

    window.addEventListener('resize', () => {
      goTo(index);
    });
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHeroSlider);
} else {
  initHeroSlider();
}
