/**
 * ISSM CMS - Summernote Global Configuration
 * Portuguese (pt-BR) localization and security hardening
 */

(function () {
  'use strict';

  if (typeof $.fn === 'undefined' || typeof $.fn.summernote === 'undefined') {
    console.warn('Summernote or jQuery not loaded. Config not applied.');
    return;
  }

  if (typeof $.summernote !== 'undefined' && $.summernote) {
    $.summernote.options.lang = 'pt-BR';
  }

  $.extend($.summernote.options, {
    lang: 'pt-BR',
    height: 300,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['link', ['link']],
      ['picture', ['picture']],
      ['video', ['video']],
      ['codeview', ['codeview']],
    ],
    popover: {
      image: [],
      link: [],
      table: [],
      air: [],
    },
    codeviewFilter: true,
    codeviewFilterRegex: /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>|<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>|<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>|<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>|<svg\s+on\w+[\s\S]*?<\/svg>|<[^>]*\bon\w+\s*=[^>]*>/gi,
    disableDragAndDrop: false,
    shortcuts: true,
    spellCheck: true,
    tabDisable: false,
    dialogsInBody: true,
    dialogsFade: true,
    maximumImageFileSize: 5120 * 1024,
    acceptExternalLink: false,
    followingLink: false,
  });

  var originalOnImageUpload = $.summernote.options.callbacks
    ? $.summernote.options.callbacks.onImageUpload
    : null;

  $.extend($.summernote.options.callbacks || {}, {
    onImageUpload: function (files) {
      if (files && files[0]) {
        if (typeof CMS !== 'undefined' && typeof CMS.uploadSummernoteImage === 'function') {
          CMS.uploadSummernoteImage(files[0], this);
        } else {
          var formData = new FormData();
          formData.append('image', files[0]);
          formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

          var baseUrl =
            (document.querySelector('meta[name="base-url"]')
              ? document.querySelector('meta[name="base-url"]').getAttribute('content')
              : '') || '';

          fetch(baseUrl + '/admin/media/upload', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json',
            },
            body: formData,
          })
            .then(function (res) {
              if (!res.ok) throw new Error('Upload falhou');
              return res.json();
            })
            .then(function (data) {
              if (data.url) {
                $(files[0].name || 'Imagem');
              }
            })
            .catch(function (err) {
              if (typeof Toastify !== 'undefined') {
                Toastify({
                  text: 'Erro ao enviar imagem: ' + err.message,
                  duration: 0,
                  gravity: 'top',
                  position: 'right',
                  style: { background: '#dc3545' },
                  close: true,
                }).showToast();
              }
            });
        }
      }
    },
    onMediaDelete: function ($target) {
      var src = $target.attr('src') || '';
      if (src && src.indexOf('/storage/') > -1) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var baseUrl =
          (document.querySelector('meta[name="base-url"]')
            ? document.querySelector('meta[name="base-url"]').getAttribute('content')
            : '') || '';

        fetch(baseUrl + '/admin/media/delete-by-url', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ url: src }),
        }).catch(function () {});
      }
    },
    onPaste: function (e) {
      var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
      e.preventDefault();
      document.execCommand('insertText', false, bufferText);
    },
  });

  if (typeof $.summernote !== 'undefined') {
    var lang = $.summernote.lang['pt-BR'];
    if (lang) {
      lang.toolbar = lang.toolbar || {};
      lang.toolbar.style = 'Estilo';
      lang.toolbar.bold = 'Negrito';
      lang.toolbar.italic = 'Itálio';
      lang.toolbar.underline = 'Sublinhado';
      lang.toolbar.clear = 'Limpar formatação';
      lang.toolbar.paragraph = 'Parágrafo';
      lang.toolbar.ul = 'Lista não ordenada';
      lang.toolbar.ol = 'Lista ordenada';
      lang.toolbar.table = 'Tabela';
      lang.toolbar.link = 'Link';
      lang.toolbar.picture = 'Imagem';
      lang.toolbar.video = 'Vídeo';
      lang.toolbar.codeview = 'Código Fonte';
      lang.toolbar.help = 'Ajuda';

      lang.font = lang.font || {};
      lang.font.bold = 'Negrito';
      lang.font.italic = 'Itálio';
      lang.font.underline = 'Sublinhado';
      lang.font.clear = 'Remover formatação';
      lang.font.height = 'Altura da linha';
      lang.font.name = 'Fonte';
      lang.font.strikethrough = 'Riscado';
      lang.font.superscript = 'Superescrito';
      lang.font.subscript = 'Subescrito';

      lang.image = lang.image || {};
      lang.image.image = 'Imagem';
      lang.image.insert = 'Inserir imagem';
      lang.image.resizeFull = 'Redimensionar original';
      lang.image.resizeHalf = 'Redimensionar metade';
      lang.image.resizeQuarter = 'Redimensionar quarto';
      lang.image.resizeNone = 'Tamanho original';
      lang.image.floatLeft = 'Flutuar à esquerda';
      lang.image.floatRight = 'Flutuar à direita';
      lang.image.floatNone = 'Sem flutuação';
      lang.image.dragImageHere = 'Arraste uma imagem aqui';
      lang.image.selectFromFiles = 'Selecione do computador';
      lang.image.url = 'URL da imagem';
      lang.image.remove = 'Remover imagem';

      lang.link = lang.link || {};
      lang.link.link = 'Link';
      lang.link.insert = 'Inserir link';
      lang.link.unlink = 'Remover link';
      lang.link.edit = 'Editar';
      lang.link.textToDisplay = 'Texto a exibir';
      lang.link.url = 'URL';
      lang.link.openInNewWindow = 'Abrir em nova janela';

      lang.table = lang.table || {};
      lang.table.table = 'Tabela';
      lang.table.insertRowAbove = 'Inserir linha acima';
      lang.table.insertRowBelow = 'Inserir linha abaixo';
      lang.table.insertColumnLeft = 'Inserir coluna à esquerda';
      lang.table.insertColumnRight = 'Inserir coluna à direita';
      lang.table.deleteRow = 'Excluir linha';
      lang.table.deleteColumn = 'Excluir coluna';
      lang.table.deleteTable = 'Excluir tabela';

      lang.video = lang.video || {};
      lang.video.video = 'Vídeo';
      lang.video.videoLink = 'Link do vídeo';
    }
  }

  console.log('Summernote global config applied: lang=pt-BR, security filters active.');
})();
