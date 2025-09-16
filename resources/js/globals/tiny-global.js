// resources/js/globals/tiny-global.js
import { initTiny } from '../editors/tinymce';

function mountEditors(root = document) {
  const nodes = root.querySelectorAll('.js-tinymce, [data-editor="tinymce"]');
  if (!nodes.length) return;

  // প্রতিটি textarea আলাদা selector দিয়ে init করুন
  nodes.forEach((el) => {
    if (el.dataset.tinyInitialized === '1') return; // already mounted
    const id = el.id || `tiny-${Math.random().toString(36).slice(2)}`;
    if (!el.id) el.id = id;

    // আগে থেকে কোনো instance আছে কিনা (edge-case)
    if (window.tinymce?.get(id)) {
      window.tinymce.get(id).remove();
    }

    initTiny(`#${id}`);
    el.dataset.tinyInitialized = '1';
  });
}

// DOM ready
document.addEventListener('DOMContentLoaded', () => {
  mountEditors();

  // Optional: Livewire/Alpine/HTMX ইত্যাদির ডাইনামিক DOM আপডেট ধরতে চাইলে
  // MutationObserver দিয়ে নতুন textarea পেলেই init করুন:
  const mo = new MutationObserver((muts) => {
    for (const m of muts) {
      m.addedNodes.forEach((n) => {
        if (n.nodeType === 1) {
          if (n.matches?.('.js-tinymce, [data-editor="tinymce"]')) {
            mountEditors(n.parentNode || document);
          } else if (n.querySelectorAll) {
            mountEditors(n);
          }
        }
      });
    }
  });
  mo.observe(document.documentElement, { childList: true, subtree: true });

  // Optional: Turbo/Livewire navigation events (ব্যবহার করলে আনকমেন্ট)
  // document.addEventListener('turbo:load', () => mountEditors());
  // document.addEventListener('livewire:navigated', () => mountEditors());
});
