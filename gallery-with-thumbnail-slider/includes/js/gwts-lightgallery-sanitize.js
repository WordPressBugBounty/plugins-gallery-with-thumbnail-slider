/**
 * GWTS - LightGallery DOM sanitization
 *
 * This script uses DOMPurify to sanitize any HTML captions or sub-html
 * content that LightGallery injects into the DOM, mitigating DOM based XSS
 * issues in the upstream lightbox library without changing its UI or API.
 */
(function ($, window) {
  'use strict';

  /**
   * Initialize sanitization when DOM and DOMPurify are ready.
   */
  function initSanitization() {
    // If DOMPurify is not available, fail safe and do nothing.
    if (!window.DOMPurify || typeof window.DOMPurify.sanitize !== 'function') {
      return;
    }

    /**
     * Sanitize the LightGallery sub-html area.
     *
     * The event `onAfterAppendSubHtml.lg` is triggered by LightGallery on the
     * gallery element every time it appends caption / sub-html content.
     * We listen for this event and sanitize the HTML that was just injected.
     */
    $(document).on('onAfterAppendSubHtml.lg', function () {
      // Double-check DOMPurify is available at event time
      if (!window.DOMPurify || typeof window.DOMPurify.sanitize !== 'function') {
        return;
      }

      var $outer = $('.lg-outer');

      if (!$outer.length) {
        return;
      }

      var $subHtml = $outer.find('.lg-sub-html');
      if (!$subHtml.length) {
        return;
      }

      var dirty = $subHtml.html();
      if (typeof dirty !== 'string') {
        return;
      }

      try {
        // Allow only a conservative set of elements and attributes typically used
        // for captions. This keeps existing visual behaviour while blocking
        // script execution and dangerous markup.
        var clean = window.DOMPurify.sanitize(dirty, {
          ALLOWED_TAGS: [
            'a',
            'b',
            'br',
            'em',
            'i',
            'strong',
            'u',
            'span',
            'p'
          ],
          ALLOWED_ATTR: [
            'href',
            'title',
            'target',
            'rel',
            'class'
          ]
        });

        $subHtml.html(clean);
      } catch (e) {
        // If sanitization fails, fail safe by removing the content
        console.warn('GWTS: DOMPurify sanitization failed', e);
        $subHtml.html('');
      }
    });
  }

  // Initialize when DOM is ready
  $(document).ready(function () {
    // Wait a bit for DOMPurify to be fully loaded if it's still loading
    if (window.DOMPurify && typeof window.DOMPurify.sanitize === 'function') {
      initSanitization();
    } else {
      // If DOMPurify isn't ready yet, wait a bit and try again
      setTimeout(function () {
        initSanitization();
      }, 100);
    }
  });
})(jQuery, window);


