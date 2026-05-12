function filterProducts(category, el) {
  document
    .querySelectorAll(".category-item")
    .forEach((i) => i.classList.remove("active"));
  if (el) el.classList.add("active");
  document.querySelectorAll("#product-grid .product-card").forEach((card) => {
    card.style.display =
      category === "all" || card.dataset.category === category
        ? "block"
        : "none";
  });
}

function filterTab(category, el) {
  document
    .querySelectorAll(".filter-tab")
    .forEach((b) => b.classList.remove("active"));
  el.classList.add("active");
  document.querySelectorAll("#product-grid .product-card").forEach((card) => {
    card.style.display =
      category === "all" || card.dataset.category === category
        ? "block"
        : "none";
  });
}
