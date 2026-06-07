/**
 * ISSM CMS - Admin JavaScript Module
 * Vanilla JS with fetch() AJAX, CSRF from meta tag
 */

(function () {
  'use strict';

  const CMS = {
    csrfToken: document.querySelector('meta[name="csrf-token"]')
      ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      : '',

    baseUrl: document.querySelector('meta[name="base-url"]')
      ? document.querySelector('meta[name="base-url"]').getAttribute('content')
      : '',

    init: function () {
      this.autoSlug();
      this.initDataTable();
      this.initSortable();
      this.initMediaPicker();
      this.initDropZone();
      this.initSummernote();
      this.bindStatusToggles();
      this.bindDeleteButtons();
      this.initToastify();
    },

    getHeaders: function () {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': this.csrfToken,
        'Accept': 'application/json',
      };
    },

    getFormDataHeaders: function () {
      return {
        'X-CSRF-TOKEN': this.csrfToken,
        'Accept': 'application/json',
      };
    },

    initSummernote: function (selector, options) {
      var elements = document.querySelectorAll(selector || '.summernote');
      var defaults = {
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
        },
        codeviewFilter: true,
        codeviewFilterRegex: /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
        disableDragAndDrop: false,
        callbacks: {
          onImageUpload: function (files) {
            CMS.uploadSummernoteImage(files[0], this);
          },
        },
      };

      var settings = Object.assign({}, defaults, options || {});

      if (typeof $.fn !== 'undefined' && typeof $.fn.summernote !== 'undefined') {
        elements.forEach(function (el) {
          if (el && typeof el.summernote !== 'function') {
            $(el).summernote(settings);
          } else if (el) {
            try {
              $(el).summernote(settings);
            } catch (e) {
              console.warn('Summernote init error:', e);
            }
          }
        });
      }
    },

    uploadSummernoteImage: function (file, editor) {
      var self = this;
      var formData = new FormData();
      formData.append('image', file);
      formData.append('_token', this.csrfToken);

      fetch(this.baseUrl + '/admin/media/upload', {
        method: 'POST',
        headers: this.getFormDataHeaders(),
        body: formData,
      })
        .then(function (response) {
          if (!response.ok) {
            return response.json().then(function (err) {
              throw new Error(err.message || 'Upload falhou');
            });
          }
          return response.json();
        })
        .then(function (data) {
          if (data.url) {
            $(editor).summernote('insertImage', data.url, data.filename || 'Imagem');
          }
          if (typeof self.showSuccess === 'function') {
            self.showSuccess('Imagem enviada com sucesso.');
          }
        })
        .catch(function (error) {
          if (typeof self.showError === 'function') {
            self.showError(error.message || 'Erro ao enviar imagem.');
          }
        });
    },

    confirmDelete: function (message, callback) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Confirmar Exclusão',
          text: message || 'Tem certeza que deseja excluir este item?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Sim, excluir!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true,
        }).then(function (result) {
          if (result.isConfirmed && typeof callback === 'function') {
            callback();
          }
        });
      } else if (typeof callback === 'function') {
        console.warn('Confirmação não disponível: ' + (message || 'Tem certeza que deseja excluir este item?'));
        callback();
      }
    },

    showSuccess: function (message) {
      if (typeof Toastify !== 'undefined') {
        Toastify({
          text: message || 'Operação realizada com sucesso.',
          duration: 4500,
          gravity: 'top',
          position: 'right',
          style: {
            background: '#28a745',
          },
          close: true,
        }).showToast();
      } else {
        console.warn(message);
      }
    },

    showError: function (message) {
      if (typeof Toastify !== 'undefined') {
        Toastify({
          text: message || 'Erro ao realizar operação.',
          duration: 0,
          gravity: 'top',
          position: 'right',
          style: {
            background: '#dc3545',
          },
          close: true,
        }).showToast();
      } else {
        console.warn('Erro: ' + message);
      }
    },

    toggleStatus: function (url, button) {
      var self = this;

      fetch(url, {
        method: 'POST',
        headers: this.getHeaders(),
        body: JSON.stringify({ _method: 'PATCH' }),
      })
        .then(function (response) {
          if (!response.ok) {
            return response.json().then(function (err) {
              throw new Error(err.message || 'Erro ao alternar status');
            });
          }
          return response.json();
        })
        .then(function (data) {
          if (data.status === 'success') {
            if (data.active !== undefined) {
              var icon = button.querySelector('i') || button;
              if (data.active) {
                button.classList.remove('text-muted', 'text-danger');
                button.classList.add('text-success');
                icon.className = 'fas fa-toggle-on fa-lg';
                button.title = 'Ativo';
              } else {
                button.classList.remove('text-success');
                button.classList.add('text-muted');
                icon.className = 'fas fa-toggle-off fa-lg';
                button.title = 'Inativo';
              }
            }
            if (typeof self.showSuccess === 'function') {
              self.showSuccess(data.message || 'Status alterado com sucesso.');
            }
          } else {
            if (typeof self.showError === 'function') {
              self.showError(data.message || 'Erro ao alterar status.');
            }
          }
        })
        .catch(function (error) {
          if (typeof self.showError === 'function') {
            self.showError(error.message || 'Erro de comunicação com o servidor.');
          }
        });
    },

    reorderItems: function (url, items) {
      var self = this;
      var payload = {
        items: items.map(function (item, index) {
          return { id: item.id, order: index + 1 };
        }),
      };

      fetch(url, {
        method: 'POST',
        headers: this.getHeaders(),
        body: JSON.stringify(payload),
      })
        .then(function (response) {
          if (!response.ok) {
            return response.json().then(function (err) {
              throw new Error(err.message || 'Erro ao reordenar');
            });
          }
          return response.json();
        })
        .then(function (data) {
          if (data.status === 'success') {
            if (typeof self.showSuccess === 'function') {
              self.showSuccess(data.message || 'Ordem atualizada com sucesso.');
            }
          } else {
            if (typeof self.showError === 'function') {
              self.showError(data.message || 'Erro ao reordenar.');
            }
          }
        })
        .catch(function (error) {
          if (typeof self.showError === 'function') {
            self.showError(error.message || 'Erro de comunicação com o servidor.');
          }
        });
    },

    initMediaPicker: function (buttonSelector, inputSelector) {
      var self = this;
      var buttons = document.querySelectorAll(buttonSelector || '[data-media-picker]');
      var input = document.querySelector(inputSelector || '[data-media-input]');

      buttons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          var targetInput = document.querySelector(
            this.getAttribute('data-media-input') || inputSelector || '[data-media-input]'
          );
          var mediaModal = document.getElementById('mediaPickerModal');
          if (mediaModal) {
            if (typeof $.fn !== 'undefined' && typeof $.fn.modal !== 'undefined') {
              $(mediaModal).modal('show');
            } else {
              mediaModal.classList.add('show');
              mediaModal.style.display = 'block';
              document.body.classList.add('modal-open');
            }
            mediaModal.dataset.targetInput =
              targetInput ? targetInput.getAttribute('name') || targetInput.id : '';
          } else {
            self.openMediaPickerWindow(targetInput);
          }
        });
      });
    },

    openMediaPickerWindow: function (inputElement) {
      var self = this;
      var width = 900;
      var height = 600;
      var left = window.screenX + (window.outerWidth - width) / 2;
      var top = window.screenY + (window.outerHeight - height) / 2;
      var popup = window.open(
        self.baseUrl + '/admin/media/picker',
        'MediaPicker',
        'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',scrollbars=yes'
      );
      window.addEventListener('message', function (event) {
        if (event.origin !== window.location.origin) return;
        if (event.data && event.data.type === 'media-selected') {
          if (inputElement) {
            inputElement.value = event.data.url;
            var preview = document.getElementById(
              inputElement.getAttribute('data-preview') || 'media-preview'
            );
            if (preview) {
              preview.src = event.data.url;
              preview.style.display = 'block';
            }
          }
          if (popup && !popup.closed) {
            popup.close();
          }
        }
      });
    },

    selectMedia: function (url, filename) {
      var modal = document.getElementById('mediaPickerModal');
      var inputName = modal ? modal.dataset.targetInput : '';
      var input = inputName
        ? document.querySelector('[name="' + inputName + '"], #' + inputName)
        : null;
      if (input) {
        input.value = url;
        var preview = document.querySelector(
          '[data-preview-for="' + (input.getAttribute('name') || input.id) + '"]'
        );
        if (preview) {
          preview.src = url;
          preview.style.display = 'block';
        }
      }
      if (modal && typeof $.fn !== 'undefined' && typeof $.fn.modal !== 'undefined') {
        $(modal).modal('hide');
      } else if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
      }
    },

    autoSlug: function (titleSelector, slugSelector) {
      var titleEl = document.querySelector(titleSelector || '[data-slug-source]');
      var slugEl = document.querySelector(slugSelector || '[data-slug-target]');

      if (!titleEl || !slugEl) return;

      titleEl.addEventListener('keyup', function () {
        var slug = CMS.slugify(this.value);
        slugEl.value = slug;
      });

      titleEl.addEventListener('change', function () {
        var slug = CMS.slugify(this.value);
        slugEl.value = slug;
      });
    },

    slugify: function (text) {
      if (!text) return '';
      var map = {
        á: 'a', à: 'a', ã: 'a', â: 'a', ä: 'a',
        é: 'e', è: 'e', ê: 'e', ë: 'e',
        í: 'i', ì: 'i', î: 'i', ï: 'i',
        ó: 'o', ò: 'o', õ: 'o', ô: 'o', ö: 'o',
        ú: 'u', ù: 'u', û: 'u', ü: 'u',
        ç: 'c', ñ: 'n',
        Á: 'a', À: 'a', Ã: 'a', Â: 'a', Ä: 'a',
        É: 'e', È: 'e', Ê: 'e', Ë: 'e',
        Í: 'i', Ì: 'i', Î: 'i', Ï: 'i',
        Ó: 'o', Ò: 'o', Õ: 'o', Ô: 'o', Ö: 'o',
        Ú: 'u', Ù: 'u', Û: 'u', Ü: 'u',
        Ç: 'c', Ñ: 'n',
      };
      return text
        .toString()
        .toLowerCase()
        .replace(/[áàãâä]/g, 'a')
        .replace(/[éèêë]/g, 'e')
        .replace(/[íìîï]/g, 'i')
        .replace(/[óòõôö]/g, 'o')
        .replace(/[úùûü]/g, 'u')
        .replace(/[ç]/g, 'c')
        .replace(/[ñ]/g, 'n')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
    },

    initDropZone: function (selector) {
      var elements = document.querySelectorAll(selector || '.dropzone-wrapper');

      elements.forEach(function (el) {
        var input = el.querySelector('input[type="file"]');
        var preview = el.querySelector('.dropzone-preview');
        var placeholder = el.querySelector('.dropzone-placeholder');

        if (!input) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function (eventName) {
          el.addEventListener(eventName, function (e) {
            e.preventDefault();
            e.stopPropagation();
          });
        });

        ['dragenter', 'dragover'].forEach(function (eventName) {
          el.addEventListener(eventName, function () {
            el.classList.add('drag-over');
          });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
          el.addEventListener(eventName, function () {
            el.classList.remove('drag-over');
          });
        });

        el.addEventListener('drop', function (e) {
          var files = e.dataTransfer.files;
          if (files.length) {
            input.files = files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });

        input.addEventListener('change', function () {
          if (this.files && this.files.length) {
            var file = this.files[0];
            if (preview) {
              var reader = new FileReader();
              reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
              };
              reader.readAsDataURL(file);
            }
            var label = el.querySelector('.dropzone-filename');
            if (label) {
              label.textContent = file.name + ' (' + CMS.formatFileSize(file.size) + ')';
            }
          }
        });

        el.addEventListener('click', function () {
          input.click();
        });
      });
    },

    formatFileSize: function (bytes) {
      if (bytes === 0) return '0 Bytes';
      var k = 1024;
      var sizes = ['Bytes', 'KB', 'MB', 'GB'];
      var i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    },

    initDataTable: function (selector) {
      var tables = document.querySelectorAll(selector || '.cms-data-table');
      if (!tables.length) return;

      tables.forEach(function (table) {
        var searchInput = table.parentElement.querySelector('.data-table-search');
        var rowsPerPage = parseInt(table.dataset.rowsPerPage, 10) || 15;
        var currentPage = 1;
        var allRows = Array.from(table.querySelectorAll('tbody tr'));

        if (!searchInput) {
          searchInput = document.createElement('input');
          searchInput.type = 'text';
          searchInput.className = 'form-control data-table-search mb-3';
          searchInput.placeholder = 'Pesquisar...';
          searchInput.style.maxWidth = '300px';
          table.parentElement.insertBefore(searchInput, table);
        }

        var paginationContainer = table.parentElement.querySelector('.data-table-pagination');
        if (!paginationContainer) {
          paginationContainer = document.createElement('nav');
          paginationContainer.className = 'data-table-pagination mt-3';
          table.parentElement.appendChild(paginationContainer);
        }

        function filterRows() {
          var query = searchInput.value.toLowerCase();
          allRows.forEach(function (row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.indexOf(query) > -1 ? '' : 'none';
          });
          currentPage = 1;
          paginate();
        }

        function paginate() {
          var visibleRows = allRows.filter(function (r) {
            return r.style.display !== 'none';
          });
          var totalPages = Math.ceil(visibleRows.length / rowsPerPage) || 1;

          visibleRows.forEach(function (row, index) {
            var page = Math.floor(index / rowsPerPage) + 1;
            row.style.display = page === currentPage ? '' : 'none';
          });

          var showing = visibleRows.length;
          var from = visibleRows.length ? (currentPage - 1) * rowsPerPage + 1 : 0;
          var to = Math.min(currentPage * rowsPerPage, visibleRows.length);

          var info = table.parentElement.querySelector('.data-table-info');
          if (!info) {
            info = document.createElement('small');
            info.className = 'data-table-info text-muted d-block mt-2';
            table.parentElement.appendChild(info);
          }
          info.textContent = 'Mostrando ' + from + ' a ' + to + ' de ' + showing + ' registros';

          renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
          var html = '<ul class="pagination pagination-sm justify-content-center mb-0">';
          html +=
            '<li class="page-item' +
            (currentPage <= 1 ? ' disabled' : '') +
            '"><a class="page-link" href="#" data-page="prev">&laquo;</a></li>';
          for (var i = 1; i <= totalPages; i++) {
            html +=
              '<li class="page-item' +
              (i === currentPage ? ' active' : '') +
              '"><a class="page-link" href="#" data-page="' +
              i +
              '">' +
              i +
              '</a></li>';
          }
          html +=
            '<li class="page-item' +
            (currentPage >= totalPages ? ' disabled' : '') +
            '"><a class="page-link" href="#" data-page="next">&raquo;</a></li>';
          html += '</ul>';
          paginationContainer.innerHTML = html;

          paginationContainer.querySelectorAll('.page-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
              e.preventDefault();
              var page = this.getAttribute('data-page');
              if (page === 'prev' && currentPage > 1) currentPage--;
              else if (page === 'next' && currentPage < totalPages) currentPage++;
              else if (page !== 'prev' && page !== 'next') currentPage = parseInt(page, 10);
              paginate();
            });
          });
        }

        searchInput.addEventListener('keyup', filterRows);

        paginate();

        var info = document.createElement('small');
        info.className = 'data-table-info text-muted';
        table.parentElement.appendChild(info);
        paginate();
      });
    },

    initSortable: function (containerSelector, url) {
      if (typeof Sortable === 'undefined') return;

      var containers = document.querySelectorAll(containerSelector || '.sortable-container');
      containers.forEach(function (container) {
        var sortableUrl = url || container.getAttribute('data-sortable-url');
        if (!sortableUrl) return;

        new Sortable(container, {
          animation: 200,
          handle: '.sortable-handle',
          ghostClass: 'sortable-ghost',
          dragClass: 'sortable-drag',
          onEnd: function () {
            var items = Array.from(container.children).map(function (child) {
              return { id: child.getAttribute('data-id') || child.dataset.itemId };
            });
            CMS.reorderItems(sortableUrl, items);
          },
        });
      });
    },

    bindStatusToggles: function () {
      var self = this;
      document.addEventListener('click', function (e) {
        var toggle = e.target.closest('[data-toggle-status]');
        if (!toggle) return;
        e.preventDefault();
        var url = toggle.getAttribute('data-url') || toggle.getAttribute('href');
        if (url) {
          self.toggleStatus(url, toggle);
        }
      });
    },

    bindDeleteButtons: function () {
      var self = this;
      document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-action="delete"], .btn-delete');
        if (!btn) return;
        e.preventDefault();
        var message =
          btn.getAttribute('data-message') || 'Tem certeza que deseja excluir este item?';
        var url = btn.getAttribute('data-url') || btn.getAttribute('href');
        var formId = btn.getAttribute('data-form');

        self.confirmDelete(message, function () {
          if (formId) {
            var form = document.getElementById(formId);
            if (form) {
              var methodInput = form.querySelector('input[name="_method"]');
              if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
              }
              var tokenInput = form.querySelector('input[name="_token"]');
              if (!tokenInput) {
                tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = self.csrfToken;
                form.appendChild(tokenInput);
              }
              form.submit();
            }
          } else if (url) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            form.innerHTML =
              '<input type="hidden" name="_token" value="' +
              self.csrfToken +
              '"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
          }
        });
      });
    },

    initToastify: function () {
      var self = this;
      var successEl = document.querySelector('meta[name="flash-success"]');
      var errorEl = document.querySelector('meta[name="flash-error"]');
      var warningEl = document.querySelector('meta[name="flash-warning"]');
      var infoEl = document.querySelector('meta[name="flash-info"]');

      if (successEl && successEl.getAttribute('content')) {
        self.showSuccess(successEl.getAttribute('content'));
      }
      if (errorEl && errorEl.getAttribute('content')) {
        self.showError(errorEl.getAttribute('content'));
      }
      if (warningEl && warningEl.getAttribute('content')) {
        if (typeof Toastify !== 'undefined') {
          Toastify({
            text: warningEl.getAttribute('content'),
            duration: 4500,
            gravity: 'top',
            position: 'right',
            style: { background: '#ffc107', color: '#212529' },
            close: true,
          }).showToast();
        }
      }
      if (infoEl && infoEl.getAttribute('content')) {
        if (typeof Toastify !== 'undefined') {
          Toastify({
            text: infoEl.getAttribute('content'),
            duration: 4500,
            gravity: 'top',
            position: 'right',
            style: { background: '#17a2b8' },
            close: true,
          }).showToast();
        }
      }
    },
  };

  if (typeof window.CMS === 'undefined') {
    window.CMS = CMS;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      CMS.init();
    });
  } else {
    CMS.init();
  }
})();
