// Google Tag Manager - Buton Tracking
(function(){
  'use strict';
  
  // GTM DataLayer kontrolü
  window.dataLayer = window.dataLayer || [];
  
  function pushEvent(eventName, eventCategory, eventLabel, eventValue) {
    window.dataLayer.push({
      'event': eventName,
      'eventCategory': eventCategory,
      'eventLabel': eventLabel,
      'eventValue': eventValue
    });
  }
  
  // Tüm butonlara click event listener ekle
  document.addEventListener('click', function(e) {
    const button = e.target.closest('button, .btn, a[role="button"]');
    if (!button) return;
    
    // Buton bilgilerini topla
    const buttonText = button.textContent.trim() || button.getAttribute('aria-label') || button.getAttribute('title') || 'Unknown Button';
    const buttonId = button.id || 'no-id';
    const buttonClass = button.className || 'no-class';
    const pagePath = window.location.pathname;
    
    // Özel kategoriler
    let category = 'Button Click';
    
    if (buttonId.includes('export') || buttonText.includes('İndir') || buttonText.includes('Excel') || buttonText.includes('Word')) {
      category = 'Export Action';
    } else if (buttonId.includes('upload') || buttonText.includes('Yükle') || buttonText.includes('Seç')) {
      category = 'Upload Action';
    } else if (buttonId.includes('search') || buttonText.includes('Ara')) {
      category = 'Search Action';
    } else if (buttonId.includes('clear') || buttonText.includes('Temizle')) {
      category = 'Clear Action';
    } else if (buttonId.includes('calculate') || buttonId.includes('hesap') || buttonText.includes('Hesap')) {
      category = 'Calculate Action';
    } else if (buttonId.includes('modal') || button.hasAttribute('data-bs-toggle')) {
      category = 'Modal Action';
    } else if (buttonId.includes('theme') || buttonId.includes('desaturate')) {
      category = 'Theme Action';
    }
    
    // GTM'e event gönder
    pushEvent(
      'button_click',
      category,
      `${buttonText} (${buttonId})`,
      pagePath
    );
    
    // Console'a log (development için)
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
      console.log('GTM Event:', {
        event: 'button_click',
        category: category,
        label: `${buttonText} (${buttonId})`,
        page: pagePath
      });
    }
  });
  
  // Form submit tracking
  document.addEventListener('submit', function(e) {
    const form = e.target;
    const formId = form.id || 'no-id';
    const formAction = form.action || 'no-action';
    const pagePath = window.location.pathname;
    
    pushEvent(
      'form_submit',
      'Form Submission',
      `Form: ${formId}`,
      pagePath
    );
  });
  
  // File input tracking
  document.addEventListener('change', function(e) {
    if (e.target.type === 'file') {
      const fileInput = e.target;
      const fileCount = fileInput.files.length;
      const inputId = fileInput.id || 'no-id';
      const pagePath = window.location.pathname;
      
      pushEvent(
        'file_selected',
        'File Input',
        `${inputId} (${fileCount} file${fileCount > 1 ? 's' : ''})`,
        pagePath
      );
    }
  });
  
  // Page view tracking (SPA için)
  let lastPath = window.location.pathname;
  setInterval(function() {
    const currentPath = window.location.pathname;
    if (currentPath !== lastPath) {
      pushEvent(
        'page_view',
        'Navigation',
        currentPath,
        currentPath
      );
      lastPath = currentPath;
    }
  }, 1000);
  
})();
