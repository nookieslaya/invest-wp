const modules = document.querySelectorAll('[data-stacked-gallery]');

const applyOrder = (items, order) => {
  order.forEach((itemIndex, slotIndex) => {
    const item = items[itemIndex];
    if (!item) {
      return;
    }
    item.dataset.slot = String(slotIndex);
  });
};

modules.forEach((module) => {
  const items = Array.from(module.querySelectorAll('[data-stacked-item]'));
  if (items.length < 2) {
    return;
  }

  const prevButton = module.querySelector('[data-stacked-prev]');
  const nextButton = module.querySelector('[data-stacked-next]');

  const order = items.map((_, index) => index);
  applyOrder(items, order);

  const rotateForward = () => {
    order.push(order.shift());
    applyOrder(items, order);
  };

  const rotateBackward = () => {
    order.unshift(order.pop());
    applyOrder(items, order);
  };

  if (nextButton) {
    nextButton.addEventListener('click', rotateForward);
  }

  if (prevButton) {
    prevButton.addEventListener('click', rotateBackward);
  }
});
