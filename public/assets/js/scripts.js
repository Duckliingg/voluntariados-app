document.addEventListener('DOMContentLoaded', function() {
    initHeroSlider();

    // 2. CONFIRMACION PARA ACCIONES PELIGROSAS
    const deleteButtons = document.querySelectorAll('a[onclick*="confirm"], .btn-danger');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de realizar esta acción?')) {
                e.preventDefault();
            }
        });
    });

    // 3. VALIDACION DE FORMULARIOS
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('field-error');
                } else {
                    field.classList.remove('field-error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
            }
        });

        // Remover error al empezar a escribir
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('field-error');
            });
        });
    });

    // 4. AUTO-OCULTAR MENSAJES DESPUES DE 5 SEGUNDOS
    const messages = document.querySelectorAll('.mensaje-exito, .mensaje-error, .mensaje-info');
    messages.forEach(message => {
        setTimeout(() => {
            message.classList.add('fade-out');
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });

    // 5. FECHAS MINIMAS
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        const today = new Date().toISOString().split('T')[0];
        input.min = today;
    });
});

// SLIDER AUTOMATICO
function initHeroSlider() {
    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('.indicator');

    if (slides.length === 0) return;

    let currentSlide = 0;
    const slideInterval = 5000;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));

        slides[index].classList.add('active');
        indicators[index].classList.add('active');

        currentSlide = index;
    }

    function nextSlide() {
        let next = currentSlide + 1;
        if (next >= slides.length) {
            next = 0;
        }
        showSlide(next);
    }

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showSlide(index);
            resetTimer();
        });
    });

    // Slider automatico
    let slideTimer = setInterval(nextSlide, slideInterval);

    function resetTimer() {
        clearInterval(slideTimer);
        slideTimer = setInterval(nextSlide, slideInterval);
    }

    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        heroSection.addEventListener('mouseenter', () => {
            clearInterval(slideTimer);
        });

        heroSection.addEventListener('mouseleave', () => {
            slideTimer = setInterval(nextSlide, slideInterval);
        });
    }

    // Navegacion con teclado
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            let prev = currentSlide - 1;
            if (prev < 0) prev = slides.length - 1;
            showSlide(prev);
            resetTimer();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            resetTimer();
        }
    });
}

// Funcion para mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
}

// Funcion para contar caracteres en textareas (sin CSS inline)
function setupCharacterCount(textareaId, maxLength) {
    const textarea = document.getElementById(textareaId);
    const counter = document.createElement('div');
    counter.className = 'character-counter';

    textarea.parentNode.appendChild(counter);

    function updateCounter() {
        const remaining = maxLength - textarea.value.length;
        counter.textContent = ` caracteres restantes`;
        
        if (remaining < 0) {
            counter.classList.add('exceeded');
        } else {
            counter.classList.remove('exceeded');
        }
    }

    textarea.addEventListener('input', updateCounter);
    updateCounter();
}
