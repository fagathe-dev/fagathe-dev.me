/**
 *
 * @param {string} selector
 * @returns {NodeList|Element|undefined}
 */
const $ = (selector) => {
  const elements = Array.from(document.querySelectorAll(selector));
  if (elements.length === 0) {
    return undefined;
  }
  return elements.length === 1 ? elements[0] : elements;
};

const dataHref = $("[data-href]");

if (dataHref instanceof Element) {
  dataHref.addEventListener(
    "click",
    (event) => (window.location = event.target.dataset.href)
  );
} else if (dataHref instanceof NodeList) {
  dataHref.forEach((a) => {
    a.addEventListener(
      "click",
      (event) => (window.location = event.target.dataset.href)
    );
  });
}