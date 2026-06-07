export function initContactForm() {
    const contactForm = document.getElementById('contact-form');
    if (!contactForm) return;

    // Carrega reCAPTCHA v3 se a chave estiver disponível
    const recaptchaKey = contactForm.dataset.recaptchaKey;
    if (recaptchaKey) {
        loadRecaptchaV3(recaptchaKey);
    }

    // Aplica máscara de telefone
    const phoneInput = contactForm.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', maskPhoneNumber);
    }

    // Manipula o envio do formulário
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('contact-submit-btn');
        const originalText = submitBtn.innerText;
        
        // Prevenir envios duplicados
        if (submitBtn.disabled) return;
        
        submitBtn.disabled = true;
        submitBtn.innerText = 'Enviando...';

        try {
            // Se reCAPTCHA v3 estiver disponível, gera o token
            if (recaptchaKey) {
                await generateRecaptchaToken(recaptchaKey);
            }

            // Pequeno delay para garantir que o token foi preenchido
            await new Promise(resolve => setTimeout(resolve, 300));

            // Envia o formulário via AJAX
            const formData = new FormData(contactForm);
            const response = await fetch(contactForm.action || '/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Sucesso
                await window.Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message,
                    confirmButtonColor: '#15803d',
                    confirmButtonText: 'OK'
                });
                contactForm.reset();
            } else {
                // Erro
                await window.Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: data.message || 'Ocorreu um erro ao enviar a mensagem. Tente novamente.',
                    confirmButtonColor: '#15803d',
                    confirmButtonText: 'OK'
                });
            }

        } catch (error) {
            console.error('Erro ao enviar formulário:', error);
            await window.Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Ocorreu um erro ao enviar a mensagem. Tente novamente mais tarde.',
                confirmButtonColor: '#15803d',
                confirmButtonText: 'OK'
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    });
}

/**
 * Carrega o script do reCAPTCHA v3
 */
function loadRecaptchaV3(siteKey) {
    if (window.grecaptcha) {
        return; // Já foi carregado
    }

    const script = document.createElement('script');
    script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

/**
 * Gera token reCAPTCHA v3 e o insere no campo hidden
 */
function generateRecaptchaToken(siteKey) {
    return new Promise((resolve, reject) => {
        window.grecaptcha.ready(function() {
            window.grecaptcha.execute(siteKey, { action: 'contact' })
                .then(function(token) {
                    const input = document.getElementById('g-recaptcha-response');
                    if (input) {
                        input.value = token;
                    }
                    resolve(token);
                })
                .catch(function(error) {
                    console.error('Erro ao gerar token reCAPTCHA:', error);
                    reject(error);
                });
        });
    });
}

/**
 * Máscara de telefone
 * Formata para: (XX) 9XXXX-XXXX (celular) ou (XX) XXXX-XXXX (fixo)
 */
function maskPhoneNumber(e) {
    let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
    
    if (value.length === 0) {
        e.target.value = '';
        return;
    }

    // Limita a 11 caracteres (máximo para Brasil: 11 dígitos)
    if (value.length > 11) {
        value = value.slice(0, 11);
    }

    let formatted = '';
    
    if (value.length <= 2) {
        // (XX
        formatted = `(${value}`;
    } else if (value.length <= 7) {
        // (XX) XXXXX ou (XX) XXXX
        formatted = `(${value.slice(0, 2)}) ${value.slice(2)}`;
    } else {
        // (XX) 9XXXX-XXXX ou (XX) XXXX-XXXX
        formatted = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
    }

    e.target.value = formatted;
}

