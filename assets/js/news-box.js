// Haber Kutusu (News Box) - Rastgele zamanlarda gösterim
(function(){
  'use strict';
  
  const MIN_INTERVAL = 60000;  // 60 saniye
  const MAX_INTERVAL = 200000; // 200 saniye
  const NEWS_URL = '/api/news.php';
  
  let newsData = [];
  let currentNewsIndex = 0;
  let autoShowTimer = null;
  let isManuallyOpened = false;
  
  const newsPanel = document.getElementById('newsPanel');
  const newsPanelBody = document.getElementById('newsPanelBody');
  const newsToggle = document.getElementById('newsToggle');
  const newsCloseBtn = document.getElementById('newsCloseBtn');
  
  // Haber ikonu eşleşmeleri
  const iconMap = {
    feature: 'new_releases',
    improvement: 'speed',
    update: 'update',
    info: 'info',
    tip: 'lightbulb'
  };
  
  const colorMap = {
    feature: 'primary',
    improvement: 'success',
    update: 'info',
    info: 'warning',
    tip: 'secondary'
  };
  
  // Haberleri yükle
  async function loadNews() {
    try {
      const response = await fetch(NEWS_URL + '?v=' + Date.now(), { cache: 'no-store' });
      if (!response.ok) throw new Error('Haberler yüklenemedi');
      newsData = await response.json();
      if (newsData.length > 0) {
        startAutoShow();
      }
    } catch (error) {
      console.error('Haber yükleme hatası:', error);
      newsData = [];
    }
  }
  
  // Rastgele bir haber seç
  function getRandomNews() {
    if (newsData.length === 0) return null;
    currentNewsIndex = Math.floor(Math.random() * newsData.length);
    return newsData[currentNewsIndex];
  }
  
  // Haberi göster
  function showNews(news) {
    if (!news) return;
    
    const icon = iconMap[news.type] || news.icon || 'campaign';
    const color = colorMap[news.type] || 'primary';
    
    // Link varsa tıklanabilir kart, yoksa normal kart
    const cardContent = `
      <div class="d-flex align-items-start gap-3">
        <div class="flex-shrink-0">
          <span class="material-symbols-rounded text-${color}" style="font-size: 2.5rem;">${icon}</span>
        </div>
        <div class="flex-grow-1">
          <h6 class="card-title fw-bold mb-2">${news.title}</h6>
          <p class="card-text text-muted mb-0">${news.content}</p>
          ${news.link ? `<a href="${news.link}" target="_blank" class="btn btn-sm btn-outline-primary mt-2" onclick="event.stopPropagation();">
            <span class="material-symbols-rounded" style="font-size: 1rem; vertical-align: middle;">open_in_new</span>
            Uygulamayı Aç
          </a>` : ''}
        </div>
      </div>
    `;
    
    if (news.link) {
      newsPanelBody.innerHTML = `
        <div class="card border-0 shadow-sm" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
          <div class="card-body" style="cursor: pointer;" 
               onclick="window.open('${news.link}', '_blank')"
               onmouseover="this.parentElement.style.transform='translateY(-2px)'; this.parentElement.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'"
               onmouseout="this.parentElement.style.transform='translateY(0)'; this.parentElement.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
            ${cardContent}
          </div>
        </div>
      `;
    } else {
      newsPanelBody.innerHTML = `
        <div class="card border-0 shadow-sm">
          <div class="card-body" style="cursor: pointer;" onclick="closePanel()">
            ${cardContent}
          </div>
        </div>
      `;
    }
    
    openPanel();
  }
  
  // Panel aç
  function openPanel() {
    if (newsPanel) {
      newsPanel.style.display = 'block';
      newsPanel.hidden = false;
      setTimeout(() => {
        newsPanel.style.opacity = '1';
        newsPanel.style.transform = 'translateY(0)';
      }, 10);
    }
  }
  
  // Panel kapat
  function closePanel() {
    if (newsPanel) {
      newsPanel.style.opacity = '0';
      newsPanel.style.transform = 'translateY(-20px)';
      setTimeout(() => {
        newsPanel.style.display = 'none';
        newsPanel.hidden = true;
      }, 300);
    }
  }
  
  // Otomatik gösterim başlat
  function startAutoShow() {
    if (autoShowTimer) {
      clearTimeout(autoShowTimer);
    }
    
    const randomInterval = Math.floor(Math.random() * (MAX_INTERVAL - MIN_INTERVAL + 1)) + MIN_INTERVAL;
    
    autoShowTimer = setTimeout(() => {
      if (!isManuallyOpened && newsPanel && newsPanel.hidden) {
        const news = getRandomNews();
        if (news) {
          showNews(news);
          // Otomatik kapatma - 10 saniye sonra
          setTimeout(() => {
            if (!isManuallyOpened) {
              closePanel();
            }
          }, 10000);
        }
      }
      startAutoShow(); // Bir sonraki gösterim için zamanlayıcıyı yeniden başlat
    }, randomInterval);
  }
  
  // Event listeners
  if (newsToggle) {
    newsToggle.addEventListener('click', function(e) {
      e.preventDefault();
      isManuallyOpened = true;
      
      if (newsPanel.hidden) {
        const news = getRandomNews();
        if (news) {
          showNews(news);
        }
      } else {
        closePanel();
        isManuallyOpened = false;
      }
    });
  }
  
  if (newsCloseBtn) {
    newsCloseBtn.addEventListener('click', function(e) {
      e.preventDefault();
      closePanel();
      isManuallyOpened = false;
    });
  }
  
  // Panel dışına tıklandığında kapat
  document.addEventListener('click', function(e) {
    if (newsPanel && !newsPanel.hidden && !newsPanel.contains(e.target) && !newsToggle.contains(e.target)) {
      closePanel();
      isManuallyOpened = false;
    }
  });
  
  // ESC tuşu ile kapat
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && newsPanel && !newsPanel.hidden) {
      closePanel();
      isManuallyOpened = false;
    }
  });
  
  // Panel için geçiş efektleri
  if (newsPanel) {
    newsPanel.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    newsPanel.style.opacity = '0';
    newsPanel.style.transform = 'translateY(-20px)';
  }
  
  // Başlangıçta haberleri yükle
  loadNews();
})();
