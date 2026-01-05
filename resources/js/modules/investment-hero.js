const clamp = (value, min = 0, max = 1) => Math.min(Math.max(value, min), max);
const lerp = (start, end, progress) => start + (end - start) * progress;

const getHeroLayout = () => {
  const width = window.innerWidth;
  const height = window.innerHeight;

  if (width < 768) {
    return 'mobile';
  }

  const isMediumDesktop = width <= 1600;
  const isSmallDesktop = width <= 1200;
  const ratio = (height / width) * 100;
  const threshold = isSmallDesktop ? 70 : isMediumDesktop ? 65 : 62.5;

  return ratio >= threshold ? 'tall' : 'wide';
};

const initInvestmentHero = () => {
  const heroes = document.querySelectorAll('[data-investment-hero]');

  if (!heroes.length) {
    return;
  }

  const reduceMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

  const updateLayout = () => {
    const layout = getHeroLayout();
    heroes.forEach((hero) => {
      hero.dataset.heroLayout = layout;
    });
  };

  const updateHero = (hero) => {
    const layout = hero.dataset.heroLayout || 'wide';
    const viewport = window.innerHeight || document.documentElement.clientHeight;
    const scrollTop = (document.scrollingElement || document.documentElement).scrollTop;
    const start = hero.offsetTop;
    const height = hero.offsetHeight || 1;
    const scrollRange = Math.max(height - viewport, 1);
    const progressRaw = (scrollTop - start) / scrollRange;
    const progress = reduceMotionQuery.matches ? 1 : clamp(progressRaw);
    if (layout === 'mobile') {
      hero.style.setProperty('--hero-investment-y', '0%');
      hero.style.setProperty('--hero-heading-left-x', '0%');
      hero.style.setProperty('--hero-heading-right-x', '0%');
      hero.style.setProperty('--hero-clip', 'polygon(100% 0, 100% 100%, 0 100%, 0 0)');
      hero.style.setProperty('--hero-image-top', '0%');
      hero.style.setProperty('--hero-image-scale', '1');
      hero.style.setProperty('--hero-image-wrapper-scale', '1');
      hero.style.setProperty('--hero-caption-y', '0%');
      hero.style.setProperty('--hero-caption-opacity', '1');
      hero.style.setProperty('--hero-wrapper-y', '0%');
      return;
    }

    const revealSplit = 0.6;
    const revealProgress = clamp(progress / revealSplit);
    const moveProgress = clamp((progress - revealSplit) / (1 - revealSplit));
    const headingStart = 0.25;
    const headingDuration = 0.75;
    const headingProgress = clamp((progress - headingStart) / headingDuration);
    const headingEase = Math.pow(headingProgress, 1.2);

    const headingLeftRange = layout === 'tall' ? -650 : -550;
    const headingRightRange = layout === 'tall' ? 650 : 550;
    const investmentRange = -100;

    const investmentY = lerp(0, investmentRange, moveProgress);
    const headingLeftX = lerp(0, headingLeftRange, headingEase);
    const headingRightX = lerp(0, headingRightRange, headingEase);

    const clipTop = lerp(12.91666667, 0, revealProgress);
    const clipRight = lerp(64.16666667, 100, revealProgress);
    const clipBottom = lerp(87.08333333, 100, revealProgress);
    const clipLeft = lerp(35.83333333, 0, revealProgress);
    const clipPath = `polygon(${clipRight}% ${clipTop}%, ${clipRight}% ${clipBottom}%, ${clipLeft}% ${clipBottom}%, ${clipLeft}% ${clipTop}%)`;

    const imageTopStart = -6.45833335;
    const imageScaleStart = 1;
    const imageScaleEnd = 1;
    const imageWrapperScaleStart = 0.9;
    const imageTop = lerp(imageTopStart, 0, revealProgress);
    const imageScale = lerp(imageScaleStart, imageScaleEnd, moveProgress);
    const imageWrapperScale = lerp(imageWrapperScaleStart, 1, revealProgress);

    const captionY = layout === 'wide'
      ? lerp(0, 100, moveProgress)
      : 0;
    const captionOpacity = layout === 'tall' ? lerp(1, 0, moveProgress) : 1;
    const wrapperY = lerp(0, 12, moveProgress);

    hero.style.setProperty('--hero-investment-y', `${investmentY}%`);
    hero.style.setProperty('--hero-heading-left-x', `${headingLeftX}%`);
    hero.style.setProperty('--hero-heading-right-x', `${headingRightX}%`);
    hero.style.setProperty('--hero-clip', clipPath);
    hero.style.setProperty('--hero-image-top', `${imageTop}%`);
    hero.style.setProperty('--hero-image-scale', imageScale.toFixed(3));
    hero.style.setProperty('--hero-image-wrapper-scale', imageWrapperScale.toFixed(3));
    hero.style.setProperty('--hero-caption-y', `${captionY}%`);
    hero.style.setProperty('--hero-caption-opacity', captionOpacity.toFixed(3));
    hero.style.setProperty('--hero-wrapper-y', `${wrapperY}%`);
  };

  const updateAll = () => {
    heroes.forEach(updateHero);
  };

  updateLayout();
  updateAll();

  let rafId = null;
  const tick = () => {
    updateAll();
    rafId = window.requestAnimationFrame(tick);
  };

  rafId = window.requestAnimationFrame(tick);

  window.addEventListener('resize', () => {
    updateLayout();
    updateAll();
  });

  window.addEventListener('load', updateAll);
  window.addEventListener('pagehide', () => {
    if (rafId) {
      window.cancelAnimationFrame(rafId);
      rafId = null;
    }
  });

  if (reduceMotionQuery.addEventListener) {
    reduceMotionQuery.addEventListener('change', updateAll);
  } else if (reduceMotionQuery.addListener) {
    reduceMotionQuery.addListener(updateAll);
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initInvestmentHero);
} else {
  initInvestmentHero();
}
