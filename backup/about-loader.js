/* ===============================
 * HAKKINDA KARTI - README.MD LOADER
 * Gelişmiş Markdown Parser (marked.js ile)
 * =============================== */
(function(){
  'use strict';

  function loadAboutContent() {
    const aboutContent = document.getElementById('aboutContent');
    if (!aboutContent) return;

    // Önce marked.js kütüphanesini yükle
    loadMarkedLibrary(function() {
      // README.md dosyasını fetch ile oku
      fetch('/README.md')
        .then(function(response) {
          if (!response.ok) throw new Error('README.md bulunamadı');
          return response.text();
        })
        .then(function(markdown) {
          // Markdown -> HTML dönüşümü
          const html = parseMarkdown(markdown);
          aboutContent.innerHTML = html;
          
          // Scroll-to-top butonu ekle
          addScrollToTop();
        })
        .catch(function(error) {
          /* README yükleme hatası - sessizce ele al */
          aboutContent.innerHTML = '<p class="muted">Hakkında bilgisi yüklenemedi.</p>';
        });
    });
  }

  // marked.js kütüphanesini CDN'den yükle
  function loadMarkedLibrary(callback) {
    if (window.marked) {
      callback();
      return;
    }

    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/marked@11.1.1/marked.min.js';
    script.onload = callback;
    script.onerror = function() {
      /* Marked.js yüklenemedi, basit parser kullanılacak */
      callback();
    };
    document.head.appendChild(script);
  }

  // Markdown parser (marked.js varsa kullan, yoksa basit parser)
  function parseMarkdown(md) {
    if (!md) return '';

    // Marked.js yüklüyse onu kullan
    if (window.marked) {
      return parseMarkdownWithMarked(md);
    }

    // Yoksa basit parser
    return parseMarkdownSimple(md);
  }

  // Gelişmiş markdown parser (marked.js ile)
  function parseMarkdownWithMarked(md) {
    // Marked.js konfigürasyonu
    marked.setOptions({
      breaks: true,
      gfm: true,
      headerIds: true,
      mangle: false
    });

    // Custom renderer
    const renderer = new marked.Renderer();
    
    // Başlıklar
    renderer.heading = function(text, level) {
      const sizes = ['2rem', '1.5rem', '1.25rem', '1.1rem'];
      const margins = ['40px 0 20px 0', '32px 0 16px 0', '24px 0 12px 0', '20px 0 10px 0'];
      return '<h' + level + ' style="margin:' + margins[level-1] + ';font-size:' + sizes[level-1] + ';">' + text + '</h' + level + '>';
    };

    // Kod blokları
    renderer.code = function(code, language) {
      const escaped = code.replace(/</g, '&lt;').replace(/>/g, '&gt;');
      return '<pre style="background:#2d2d2d;color:#f8f8f2;padding:12px;border-radius:6px;overflow-x:auto;margin:16px 0;"><code style="font-family:monospace;font-size:0.9em;" class="language-' + (language || '') + '">' + escaped + '</code></pre>';
    };

    // Inline kod
    renderer.codespan = function(code) {
      return '<code style="background:var(--bg-secondary,#f5f5f5);color:var(--text,inherit);padding:2px 6px;border-radius:4px;font-family:monospace;font-size:0.9em;">' + code + '</code>';
    };

    // Linkler
    renderer.link = function(href, title, text) {
      return '<a href="' + href + '" target="_blank" rel="noopener noreferrer" style="color:var(--primary,#4f46e5);text-decoration:none;" title="' + (title || '') + '">' + text + '</a>';
    };

    // Liste öğeleri
    renderer.listitem = function(text) {
      return '<li style="margin:4px 0;">' + text + '</li>';
    };

    // Listeler
    renderer.list = function(body, ordered) {
      const tag = ordered ? 'ol' : 'ul';
      return '<' + tag + ' style="margin:12px 0;padding-left:24px;">' + body + '</' + tag + '>';
    };

    // Paragraflar
    renderer.paragraph = function(text) {
      return '<p style="margin:12px 0;line-height:1.6;">' + text + '</p>';
    };

    // Yatay çizgi
    renderer.hr = function() {
      return '<hr style="margin:24px 0;border:0;border-top:1px solid var(--border-color,#ddd);">';
    };

    // Blockquote
    renderer.blockquote = function(quote) {
      return '<blockquote style="border-left:4px solid var(--primary,#4f46e5);padding-left:16px;margin:16px 0;color:var(--text-muted,#666);">' + quote + '</blockquote>';
    };

    // Tablo
    renderer.table = function(header, body) {
      return '<table style="width:100%;border-collapse:collapse;margin:16px 0;"><thead>' + header + '</thead><tbody>' + body + '</tbody></table>';
    };

    renderer.tablecell = function(content, flags) {
      const type = flags.header ? 'th' : 'td';
      const style = 'border:1px solid var(--border-color,#ddd);padding:8px;text-align:' + (flags.align || 'left');
      return '<' + type + ' style="' + style + '">' + content + '</' + type + '>';
    };

    marked.use({ renderer: renderer });

    return marked.parse(md);
  }

  // Basit markdown parser (fallback)
  function parseMarkdownSimple(md) {
    const html = md
      // Kod blokları (```)
      .replace(/```(\w+)?\n([\s\S]*?)```/gim, function(match, lang, code) {
        return '<pre style="background:#2d2d2d;color:#f8f8f2;padding:12px;border-radius:6px;overflow-x:auto;margin:16px 0;"><code style="font-family:monospace;font-size:0.9em;">' +
          code.replace(/</g, '&lt;').replace(/>/g, '&gt;') +
          '</code></pre>';
      })

      // Başlıklar (#### önce, sonra ###, ##, #)
      .replace(/^#### (.*$)/gim, '<h4 style="margin:20px 0 10px 0;font-size:1.1rem;">$1</h4>')
      .replace(/^### (.*$)/gim, '<h3 style="margin:24px 0 12px 0;font-size:1.25rem;">$1</h3>')
      .replace(/^## (.*$)/gim, '<h2 style="margin:32px 0 16px 0;font-size:1.5rem;">$1</h2>')
      .replace(/^# (.*$)/gim, '<h1 style="margin:40px 0 20px 0;font-size:2rem;">$1</h1>')

      // Kalın metin
      .replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>')

      // İtalik metin
      .replace(/\*(.*?)\*/gim, '<em>$1</em>')

      // Satır içi kod
      .replace(/`(.*?)`/gim, '<code style="background:var(--bg-secondary,#f5f5f5);color:var(--text,inherit);padding:2px 6px;border-radius:4px;font-family:monospace;font-size:0.9em;">$1</code>')

      // Linkler
      .replace(/\[([^\]]+)\]\(([^)]+)\)/gim, '<a href="$2" target="_blank" rel="noopener noreferrer" style="color:var(--primary,#4f46e5);text-decoration:none;">$1</a>')

      // Liste öğeleri (unordered)
      .replace(/^\- (.*$)/gim, '<li style="margin:4px 0;">$1</li>')

      // Yatay çizgi
      .replace(/^---$/gim, '<hr style="margin:24px 0;border:0;border-top:1px solid var(--border-color,#ddd);">')

      // Paragraflar (iki satır arası boşluk)
      .split('\n\n')
      .map(function(para) {
        para = para.trim();
        if (!para) return '';

        // Liste öğelerini algıla
        if (para.indexOf('<li') !== -1) {
          return '<ul style="margin:12px 0;padding-left:24px;list-style-type:disc;">' + para + '</ul>';
        }

        // Başlık, HR, veya pre ise olduğu gibi bırak
        if (para.indexOf('<h') === 0 || para.indexOf('<hr') === 0 || para.indexOf('<pre') === 0) {
          return para;
        }

        // Diğer durumlarda paragraf yap
        return '<p style="margin:12px 0;line-height:1.6;">' + para + '</p>';
      })
      .join('\n');

    return html;
  }

  // Scroll-to-top butonu ekle
  function addScrollToTop() {
    const button = document.createElement('button');
    button.textContent = '↑ Yukarı';
    button.style.cssText = 'position:fixed;bottom:80px;right:20px;padding:12px 20px;background:var(--primary,#4f46e5);color:white;border:none;border-radius:6px;cursor:pointer;display:none;z-index:1000;font-size:14px;box-shadow:0 4px 6px rgba(0,0,0,0.1);transition:opacity 0.3s ease;';
    
    button.onclick = function() {
      // Smooth scroll - modern yaklaşım
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    };

    // Scroll olayını dinle
    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) {
        button.style.display = 'block';
      } else {
        button.style.display = 'none';
      }
    });

    document.body.appendChild(button);
  }

  // Sayfa yüklendiğinde çalıştır
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAboutContent);
  } else {
    loadAboutContent();
  }
})();
