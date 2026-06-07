/**
 * ISSM CMS - SweetAlert2 Global Configuration
 * Confirmation dialogs, toasts, and auto-binding for delete actions
 */

(function () {
  'use strict';

  var SwalGlobal = {
    defaultConfirmColor: '#3085d6',
    defaultCancelColor: '#6c757d',
    defaultDangerColor: '#d33',

    confirmDelete: function (message) {
      return Swal.fire({
        title: 'Confirmar Exclusão',
        text: message || 'Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: this.defaultDangerColor,
        cancelButtonColor: this.defaultCancelColor,
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: true,
        preConfirm: function () {
          return true;
        },
      });
    },

    confirmAction: function (title, text, icon) {
      return Swal.fire({
        title: title || 'Confirmar Ação',
        text: text || 'Tem certeza que deseja realizar esta ação?',
        icon: icon || 'question',
        showCancelButton: true,
        confirmButtonColor: this.defaultConfirmColor,
        cancelButtonColor: this.defaultCancelColor,
        confirmButtonText: 'Sim, confirmar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
      });
    },

    toastSuccess: function (message) {
      return Swal.fire({
        text: message || 'Operação realizada com sucesso!',
        icon: 'success',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4500,
        timerProgressBar: true,
        didOpen: function (toast) {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
      });
    },

    toastError: function (message) {
      return Swal.fire({
        text: message || 'Erro ao realizar operação.',
        icon: 'error',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 0,
        timerProgressBar: false,
        showCloseButton: true,
      });
    },

    toastWarning: function (message) {
      return Swal.fire({
        text: message || 'Atenção!',
        icon: 'warning',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4500,
        timerProgressBar: true,
        didOpen: function (toast) {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
      });
    },

    toastInfo: function (message) {
      return Swal.fire({
        text: message || 'Informação.',
        icon: 'info',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4500,
        timerProgressBar: true,
        didOpen: function (toast) {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
      });
    },

    confirmBulkAction: function (message) {
      return Swal.fire({
        title: 'Confirmar Ação em Lote',
        text: message || 'Tem certeza que deseja realizar esta ação em todos os itens selecionados?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: this.defaultConfirmColor,
        cancelButtonColor: this.defaultCancelColor,
        confirmButtonText: 'Sim, confirmar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
      });
    },

    showLoading: function (message) {
      return Swal.fire({
        title: message || 'Carregando...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function () {
          Swal.showLoading();
        },
      });
    },

    showSuccessDialog: function (title, text) {
      return Swal.fire({
        title: title || 'Sucesso!',
        text: text || 'Operação realizada com sucesso.',
        icon: 'success',
        confirmButtonColor: this.defaultConfirmColor,
        confirmButtonText: 'OK',
      });
    },

    showErrorDialog: function (title, text) {
      return Swal.fire({
        title: title || 'Erro!',
        text: text || 'Ocorreu um erro ao realizar a operação.',
        icon: 'error',
        confirmButtonColor: this.defaultConfirmColor,
        confirmButtonText: 'OK',
      });
    },

    showWarningDialog: function (title, text) {
      return Swal.fire({
        title: title || 'Atenção!',
        text: text || 'Verifique as informações antes de prosseguir.',
        icon: 'warning',
        confirmButtonColor: this.defaultConfirmColor,
        confirmButtonText: 'OK',
      });
    },

    autoBindDeleteForms: function () {
      var self = this;

      document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.hasAttribute('data-confirm')) {
          e.preventDefault();
          var message = form.getAttribute('data-confirm') || 'Tem certeza?';
          self.confirmDelete(message).then(function (result) {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        }
      });

      document.addEventListener('click', function (e) {
        var element = e.target.closest('[data-action="delete"], .btn-delete');
        if (!element) return;

        if (
          element.tagName === 'A' ||
          element.tagName === 'BUTTON' ||
          element.tagName === 'SPAN' ||
          element.tagName === 'I'
        ) {
          if (element.closest('form')) {
            var parentForm = element.closest('form');
            if (parentForm && !parentForm.hasAttribute('data-confirm')) {
              return;
            }
          }
        }

        if (element.tagName === 'A') {
          e.preventDefault();
        }

        var message = element.getAttribute('data-message') || 'Tem certeza que deseja excluir este item?';
        var url = element.getAttribute('data-url') || element.getAttribute('href');
        var formId = element.getAttribute('data-form');

        self.confirmDelete(message).then(function (result) {
          if (!result.isConfirmed) return;

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
                tokenInput.value =
                  document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    : '';
                form.appendChild(tokenInput);
              }
              form.submit();
            }
          } else if (url) {
            var csrfToken =
              document.querySelector('meta[name="csrf-token"]')
                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                : '';
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';
            form.innerHTML =
              '<input type="hidden" name="_token" value="' +
              csrfToken +
              '"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
          }
        });
      });
    },

    autoBindFormsWithConfirm: function () {
      var self = this;
      document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        if (btn.tagName !== 'BUTTON' && btn.tagName !== 'A' && btn.tagName !== 'INPUT') return;
        e.preventDefault();

        var message = btn.getAttribute('data-confirm') || 'Tem certeza?';
        var form = btn.closest('form');

        self.confirmDelete(message).then(function (result) {
          if (result.isConfirmed) {
            if (form) {
              form.submit();
            } else if (btn.tagName === 'A') {
              window.location.href = btn.getAttribute('href');
            }
          }
        });
      });
    },
  };

  if (typeof Swal === 'undefined') {
    console.warn('SweetAlert2 not loaded. SweetAlert global functions unavailable.');
    window.SwalGlobal = SwalGlobal;
    return;
  }

  var toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    didOpen: function (toast) {
      toast.addEventListener('mouseenter', Swal.stopTimer);
      toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
  });

  SwalGlobal.toastMixin = toast;

  window.SwalGlobal = SwalGlobal;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      SwalGlobal.autoBindDeleteForms();
      SwalGlobal.autoBindFormsWithConfirm();
    });
  } else {
    SwalGlobal.autoBindDeleteForms();
    SwalGlobal.autoBindFormsWithConfirm();
  }

  console.log('SweetAlert2 global config loaded: delete binding active.');
})();
