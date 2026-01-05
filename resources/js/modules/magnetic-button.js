const magneticButtons = document.querySelectorAll('[data-magnetic]');

const applyMagnet = (event, element, strength) => {
  const rect = element.getBoundingClientRect();
  const offsetX = event.clientX - rect.left - rect.width / 2;
  const offsetY = event.clientY - rect.top - rect.height / 2;
  const moveX = (-offsetX / rect.width) * strength;
  const moveY = (-offsetY / rect.height) * strength;

  element.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
};

const resetMagnet = (element) => {
  element.style.transform = 'translate3d(0, 0, 0)';
};

const initMagneticButton = (element) => {
  const strength = Number(element.dataset.magneticStrength || 10);

  element.addEventListener('mousemove', (event) => applyMagnet(event, element, strength));
  element.addEventListener('mouseleave', () => resetMagnet(element));
  element.addEventListener('blur', () => resetMagnet(element));
};

magneticButtons.forEach(initMagneticButton);
