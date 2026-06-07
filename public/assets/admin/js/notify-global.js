/**
 * ISSM CMS - Global Toastify Notification Module
 * Toast notifications for Laravel session flashes and programmatic use
 */

(function () {
  'use strict';

  var Notify = {
    defaults: {
      duration: 4500,
      gravity: 'top',
      position: 'right',
      close: true,
      stopOnFocus: true,
      rtl: false,
      className: 'cms-toast',
    },

    colors: {
      success: '#28a745',
      error: '#dc3545',
      warning: '#ffc107',
      info: '#17a2b8',
    },

    textColors: {
      success: '#ffffff',
      error: '#ffffff',
      warning: '#212529',
      info: '#ffffff',
    },

    showToast: function (message, type) {
      if (typeof Toastify === 'undefined') {
        console.warn('Toastify not loaded. Falling back to console.', type, message);
        if (type === 'error') {
          console.error(message);
        } else {
          console.log(message);
        }
        return;
      }

      var color = this.colors[type] || this.colors.info;
      var textColor = this.textColors[type] || this.textColors.info;
      var isError = type === 'error';

      var options = Object.assign({}, this.defaults, {
        text: message || '',
        duration: isError ? 0 : this.defaults.duration,
        style: {
          background: color,
          color: textColor,
          borderRadius: '6px',
          boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
          padding: '12px 20px',
          fontSize: '14px',
          fontWeight: '500',
        },
        onClick: function () {},
      });

      if (isError) {
        options.style.cursor = 'pointer';
        var oldClick = options.onClick;
        options.onClick = function () {
          if (typeof oldClick === 'function') oldClick();
        };
      }

      if (type === 'warning') {
        options.style.border = '1px solid #e0a800';
      }

      return Toastify(options).showToast();
    },

    success: function (message) {
      return this.showToast(message, 'success');
    },

    error: function (message) {
      return this.showToast(message, 'error');
    },

    warning: function (message) {
      return this.showToast(message, 'warning');
    },

    info: function (message) {
      return this.showToast(message, 'info');
    },

    flash: function (message, type) {
      return this.showToast(message, type);
    },

    readFlashMeta: function () {
      var self = this;
      var flashes = ['success', 'error', 'warning', 'info'];
      flashes.forEach(function (type) {
        var meta = document.querySelector('meta[name="flash-' + type + '"]');
        if (meta && meta.getAttribute('content')) {
          setTimeout(function () {
            self.showToast(meta.getAttribute('content'), type);
          }, 300);
        }
      });
    },

    setupLaravelSessionFlash: function () {
      var self = this;
      var types = [
        { key: 'success', type: 'success' },
        { key: 'error', type: 'error' },
        { key: 'warning', type: 'warning' },
        { key: 'info', type: 'info' },
        { key: 'danger', type: 'error' },
      ];

      types.forEach(function (item) {
        var meta = document.querySelector('meta[name="flash-' + item.key + '"]');
        if (meta && meta.getAttribute('content')) {
          setTimeout(function () {
            self.showToast(meta.getAttribute('content'), item.type);
          }, 300);
        }
      });
    },
  };

  if (typeof window.Notify === 'undefined') {
    window.Notify = Notify;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      Notify.setupLaravelSessionFlash();
    });
  } else {
    setTimeout(function () {
      Notify.setupLaravelSessionFlash();
    }, 300);
  }

  console.log('Notify (Toastify) global module loaded.');
})();
