let timers = {};
let timerStates = {};
let timerSeconds = {};

const alarmSound = new Audio('/C-Tiempos/assets/sounds/alert.mp3');
let workspaceTypes = {};
let timerLimits = {};

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.play-pause-timer').forEach(button => {
        const id = button.dataset.workspaceId;
        const savedState = loadTimerState(id);

        document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).classList.add('d-none');
        document.querySelector(`.reset-timer[data-workspace-id="${id}"]`).classList.add('d-none');
        document.querySelector(`.input-limit[data-workspace-id="${id}"]`).classList.add('d-none');

        const savedType = localStorage.getItem(`workspaceType_${id}`);
        if (savedType) {
            workspaceTypes[id] = savedType;

            document.querySelectorAll(`.select-type[data-workspace-id="${id}"]`).forEach(btn => {
                if (btn.dataset.type === savedType) {
                    btn.classList.add('btn-primary');
                    btn.classList.remove('btn-outline-light');
                } else {
                    btn.classList.add('btn-outline-light');
                    btn.classList.remove('btn-primary');
                }
            });

            document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).classList.remove('d-none');
            document.querySelector(`.reset-timer[data-workspace-id="${id}"]`).classList.remove('d-none');
            document.querySelector(`.input-limit[data-workspace-id="${id}"]`).classList.remove('d-none');
        }

        if (savedState) {
            const elapsedTime = Math.floor((Date.now() - savedState.timestamp) / 1000);

            if (savedState.state === 'running') {
                if (savedState.type === 'countdown') {
                    const remainingTime = Math.max(savedState.timeInSeconds - elapsedTime, 0);
                    timerSeconds[id] = remainingTime;
                } else {
                    timerSeconds[id] = savedState.timeInSeconds + elapsedTime;
                }
                startTimer(id, savedState.type, savedState.limit);
                button.innerHTML = '<i class="fas fa-pause"></i>';
            } else {
                timerSeconds[id] = savedState.timeInSeconds;
                button.innerHTML = '<i class="fas fa-play"></i>';
            }

            document.getElementById(`timer-${id}`).innerText = formatTime(timerSeconds[id]);

            workspaceTypes[id] = savedState.type || 'stopwatch';
            timerLimits[id] = savedState.limit || 0;

            const limitInput = document.getElementById(`limit-${id}`);
            if (limitInput) {
                limitInput.value = savedState.limit || '';
                limitInput.classList.remove('d-none');
            }
        }
    });

    document.querySelectorAll('.select-type').forEach(button => {
        button.onclick = function () {
            const id = this.dataset.workspaceId;
            const type = this.dataset.type;
            workspaceTypes[id] = type;

            localStorage.setItem(`workspaceType_${id}`, type);

            document.querySelectorAll(`.select-type[data-workspace-id="${id}"]`).forEach(btn => {
                if (btn.dataset.type === type) {
                    btn.classList.add('btn-primary');
                    btn.classList.remove('btn-outline-light');
                } else {
                    btn.classList.add('btn-outline-light');
                    btn.classList.remove('btn-primary');
                }
            });

            const playPauseBtn = document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`);
            const resetBtn = document.querySelector(`.reset-timer[data-workspace-id="${id}"]`);
            const limitInput = document.querySelector(`.input-limit[data-workspace-id="${id}"]`);

            playPauseBtn.classList.add('d-none');
            resetBtn.classList.add('d-none');

            if (type === 'countdown') {
                limitInput.classList.remove('d-none');

                limitInput.oninput = function () {
                    const inputValue = parseInt(limitInput.value);

                    if (inputValue > 0) {
                        playPauseBtn.classList.remove('d-none');
                        resetBtn.classList.remove('d-none');
                    } else {
                        playPauseBtn.classList.add('d-none');
                        resetBtn.classList.add('d-none');
                    }
                };
            } else {
                playPauseBtn.classList.remove('d-none');
                resetBtn.classList.remove('d-none');
                limitInput.classList.remove('d-none');
            }
        };
    });



    document.querySelectorAll('.play-pause-timer').forEach(button => {
        button.onclick = function () {
            const id = this.dataset.workspaceId;
            const rate = parseFloat(document.getElementById(`rate-${id}`).value);
            if (timerStates[id] === 'running') {

                pauseTimer(id);
                this.innerHTML = '<i class="fas fa-play"></i>';
                timerStates[id] = 'paused';

                const timeInSeconds = timerSeconds[id];
                let totalCost = 0;

                if (workspaceTypes[id] === 'countdown') {
                    const timeUsedInHours = (timerLimits[id] * 60 - timeInSeconds) / 3600;
                    totalCost = timeUsedInHours * rate;
                } else {
                    const timeInHours = timeInSeconds / 3600;
                    totalCost = timeInHours * rate;
                }

                // Muestra el costo total en un modal
                Swal.fire({
                    title: 'Temporizador pausado',
                    text: `Total a pagar: Q${totalCost.toFixed(2)}`,
                    icon: 'info'
                });

                // Guardar el estado en localStorage
                saveTimerState(id, timeInSeconds, 'paused', workspaceTypes[id], timerLimits[id]);
            } else {
                const type = workspaceTypes[id] || 'stopwatch';
                const limit = parseInt(document.getElementById(`limit-${id}`).value) || 0;
                timerLimits[id] = limit;
                if (timerStates[id] === 'paused') {
                    timerSeconds[id] = 0;
                }

                startTimer(id, type, limit);
                this.innerHTML = '<i class="fas fa-pause"></i>';
                timerStates[id] = 'running';

                // Guardar el estado del cronómetro en localStorage
                saveTimerState(id, timerSeconds[id], 'running', type, limit);
            }
        };
    });

    // Función para pausar el cronómetro
    function pauseTimer(id) {
        if (timers[id]) clearInterval(timers[id]);
        saveTimerState(id, timerSeconds[id], 'paused', workspaceTypes[id], timerLimits[id]);
    }


    // Función para guardar el estado del cronómetro en localStorage
    function saveTimerState(id, timeInSeconds, state, type, limit) {
        const timerState = {
            timeInSeconds: timeInSeconds,
            state: state,
            type: type,
            limit: limit,
            timestamp: Date.now()
        };
        localStorage.setItem(`timer_${id}`, JSON.stringify(timerState));
    }

    function loadTimerState(id) {
        const savedState = localStorage.getItem(`timer_${id}`);
        if (savedState) {
            const parsedState = JSON.parse(savedState);

            // Restaurar el valor del límite de tiempo en el campo de entrada
            const limitInput = document.getElementById(`limit-${id}`);
            if (limitInput && parsedState.limit) {
                limitInput.value = parsedState.limit;
                limitInput.classList.remove('d-none');
            }

            return parsedState;
        }
        return null;
    }


    // Función para formatear el tiempo en formato HH:MM:SS
    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${pad(hours)}:${pad(minutes)}:${pad(secs)}`;
    }

    // Función para añadir ceros iniciales
    function pad(num) {
        return num.toString().padStart(2, '0');
    }




    document.querySelectorAll('.reset-timer').forEach(button => {
        button.onclick = function () {
            const id = this.dataset.workspaceId;
            const rate = parseFloat(document.getElementById(`rate-${id}`).value);

            // Calcular el tiempo transcurrido antes del reinicio
            const timeInSeconds = timerSeconds[id];
            let totalCost = 0;

            // Si es cuenta regresiva, el costo se basa en el tiempo usado del total
            if (workspaceTypes[id] === 'countdown') {
                const timeUsedInHours = (timerLimits[id] * 60 - timeInSeconds) / 3600;
                totalCost = timeUsedInHours * rate;
            } else {
                // Si es cronómetro, el costo se calcula con el tiempo transcurrido
                const timeInHours = timeInSeconds / 3600;
                totalCost = timeInHours * rate;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Total acumulado a pagar: Q${totalCost.toFixed(2)}\n\n¡No podrás revertir esto!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, reiniciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetTimer(id);
                    cleanInput(id);
                    timerStates[id] = 'paused';
                    timerSeconds[id] = 0;
                    document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).innerHTML =
                        '<i class="fas fa-play"></i>';

                    Swal.fire(
                        'Reiniciado!',
                        'El cronómetro ha sido reiniciado.',
                        'success'
                    );
                }
            });
        };
    });


    document.querySelectorAll('.toggle-workspace').forEach(button => {
        button.onclick = function () {
            const id = this.dataset.workspaceId;
            toggleWorkspace(id, this);
        };
    });


    // Función para iniciar el cronómetro/cuenta atrás
    function startTimer(id, type, limit) {
        if (timers[id]) clearInterval(timers[id]);

        let seconds = timerSeconds[id] || (type === 'countdown' ? limit * 60 : 0);
        if (timerStates[id] === 'running') return;

        if (type === 'countdown' && seconds === 0) {
            seconds = limit * 60;
        }
        timers[id] = setInterval(() => {
            if (type === 'countdown') {
                seconds--;
                if (seconds <= 0) {
                    clearInterval(timers[id]);
                    triggerAlert(id);

                    const rate = parseFloat(document.getElementById(`rate-${id}`).value);
                    const totalCost = (limit / 60) * rate;

                    Swal.fire({
                        title: 'Tiempo completado',
                        text: `El tiempo ha terminado. Total a pagar: Q${totalCost.toFixed(2)}`,
                        icon: 'success'
                    });

                    resetTimerState(id);
                    return;
                }
            } else {

                seconds++;
                if (limit > 0 && seconds >= limit * 60) {
                    clearInterval(timers[id]);
                    triggerAlert(id);
                    const rate = parseFloat(document.getElementById(`rate-${id}`).value);
                    const timeInHours = seconds / 3600;
                    const totalCost = timeInHours * rate;

                    Swal.fire({
                        title: 'Límite alcanzado',
                        text: `El cronómetro ha alcanzado el límite. Total a pagar: Q${totalCost.toFixed(2)}`,
                        icon: 'info'
                    });

                    return;
                }
            }

            document.getElementById(`timer-${id}`).innerText = formatTime(seconds);
            timerSeconds[id] = seconds;
            saveTimerState(id, seconds, 'running', type, limit);
        }, 1000);
    }


    function triggerAlert(id) {
        alarmSound.play();

        const timerElement = document.getElementById(`timer-${id}`);
        timerElement.classList.add('alert-active');
        setTimeout(() => {
            timerElement.classList.remove('alert-active');
        }, 15000);
    }

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;

        return `${pad(hours)}:${pad(minutes)}:${pad(secs)}`;
    }

    function pad(num) {
        return num.toString().padStart(2, '0');
    }

    function resetTimer(id) {
        if (timers[id]) clearInterval(timers[id]);
        document.getElementById(`timer-${id}`).innerText = '00:00:00';
        timerSeconds[id] = 0;
        timerStates[id] = 'paused';
        timerLimits[id] = 0;
        workspaceTypes[id] = '';

        saveTimerState(id, timerSeconds[id], 'paused', '', 0);

        localStorage.removeItem(`workspaceType_${id}`);
        localStorage.removeItem(`timerSeconds_${id}`);
        localStorage.removeItem(`timerState_${id}`);
        localStorage.removeItem(`timerLimit_${id}`);
        localStorage.removeItem(`timer_${id}`);
        location.reload();
        document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).innerHTML = '<i class="fas fa-play"></i>';
    }


    document.querySelectorAll('.toggle-workspace').forEach(button => {
        button.onclick = function () {
            const id = this.dataset.workspaceId;
            const url = this.dataset.url;
            toggleWorkspace(id, this, url);
        };
    });

    function toggleWorkspace(id, button, url) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `workspace_id=${id}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.active) {
                        button.classList.replace('btn-outline-success', 'btn-outline-danger');
                        button.innerText = 'Dejar de Usar';
                    } else {
                        button.classList.replace('btn-outline-danger', 'btn-outline-success');
                        button.innerText = 'Usar';
                        clearWorkspaceState(id);
                        hideWorkspace(id);
                    }
                }
            });
    }

    function hideWorkspace(id) {
        const workspaceCard = document.querySelector(`[data-workspace-id="${id}"]`).closest('.mb-4');
        if (workspaceCard) {
            workspaceCard.remove();
        }
    }

    function clearWorkspaceState(id) {
        if (timers[id]) clearInterval(timers[id]);
        document.getElementById(`timer-${id}`).innerText = '00:00:00';
        delete timers[id];
        delete timerStates[id];
        delete timerSeconds[id];
        delete workspaceTypes[id];
        delete timerLimits[id];
        localStorage.removeItem(`timer_${id}`);
        localStorage.removeItem(`workspaceType_${id}`);
        document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).classList.add('d-none');
        document.querySelector(`.reset-timer[data-workspace-id="${id}"]`).classList.add('d-none');
        document.querySelector(`.input-limit[data-workspace-id="${id}"]`).classList.add('d-none');

        document.getElementById(`limit-${id}`).value = '';
        document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).innerHTML = '<i class="fas fa-play"></i>';
    }

    function hideWorkspace(id) {
        const workspaceCard = document.querySelector(`[data-workspace-id="${id}"]`).closest('.workspace-card');
        if (workspaceCard) {
            workspaceCard.remove();
            console.log("Espacio de trabajo eliminado:", id);
        } else {
            console.error("No se encontró el espacio de trabajo con ID:", id);
        }
    }

    function showWorkspace(id) {
        const workspaceCard = document.querySelector(`[data-workspace-id="${id}"]`).closest('.workspace-card');
        if (workspaceCard) {
            workspaceCard.style.display = 'block';
        }
    }

    function cleanInput(id) {
        const input = document.querySelector(`#limit-${id}`);
        if (input) {
            input.value = '';
        }
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });


    document.querySelectorAll('.preset-interval').forEach(button => {
        button.onclick = function () {
            const duration = this.dataset.duration;
            const workspaceId = this.closest('.dropdown').querySelector('.dropdown-toggle').id.split('-')[1];
            const limitInput = document.getElementById(`limit-${workspaceId}`);

            limitInput.value = duration;
            limitInput.classList.remove('d-none');

            const event = new Event('input', { bubbles: true });
            limitInput.dispatchEvent(event);
        };
    });

    const correctPin = "0000";  // PIN correcto 
    let currentWorkspaceId = null;
    let isLocked = {};  // Objeto para controlar el estado de bloqueo de cada máquina
    let failedAttempts = {};  // Objeto para contar intentos fallidos de cada máquina
    
    // Inicializa el estado de bloqueo y contadores para una máquina
    function initializeWorkspace(workspaceId) {
        isLocked[workspaceId] = true;  // Inicialmente bloqueada
        failedAttempts[workspaceId] = 0;  // Inicializar intentos fallidos
    }
    
    // Mostrar modal para ingresar PIN cuando se intenta pausar o reiniciar
    function requestPin(workspaceId) {
        currentWorkspaceId = workspaceId;
        $('#pinModal').modal('show');
    }
    
    // Verificar el PIN ingresado
    $('#confirmPinBtn').on('click', function() {
        const enteredPin = $('#pinInput').val();
        
        // Inicializa el estado de bloqueo y los intentos fallidos si no existen
        if (!isLocked[currentWorkspaceId]) {
            isLocked[currentWorkspaceId] = true;
        }
    
        if (!failedAttempts[currentWorkspaceId]) {
            failedAttempts[currentWorkspaceId] = 0;
        }
    
        if (enteredPin === correctPin) {
            isLocked[currentWorkspaceId] = false;  // Desbloquear si el PIN es correcto
            $('#pinModal').modal('hide');
            $('#pinError').hide();
            unlockControls(currentWorkspaceId);  // Desbloquear los botones de la máquina correspondiente
            failedAttempts[currentWorkspaceId] = 0;  // Reiniciar el contador de intentos fallidos
        } else {
            failedAttempts[currentWorkspaceId]++;
            $('#pinError').show();  // Mostrar mensaje de error
            $('#pinInput').addClass('error');  
            
            // Bloquear el PIN después de 3 intentos fallidos
            if (failedAttempts[currentWorkspaceId] >= 3) {
                alert("Demasiados intentos fallidos para esta máquina. Intenta de nuevo más tarde.");
                $('#confirmPinBtn').prop('disabled', true);  // Deshabilitar el botón
                setTimeout(() => {
                    $('#confirmPinBtn').prop('disabled', false);  // Habilitar después de un tiempo
                    $('#pinInput').val('').removeClass('error');  // Limpiar y quitar error
                    failedAttempts[currentWorkspaceId] = 0;  // Reiniciar el contador
                }, 10000);  // 10 segundos de espera
            }
        }
    });
    
    // Desbloquear botones de pausa y reinicio
    function unlockControls(workspaceId) {
        $('.play-pause-timer[data-workspace-id="' + workspaceId + '"]').prop('disabled', false);
        $('.reset-timer[data-workspace-id="' + workspaceId + '"]').prop('disabled', false);
    }
    
    // Bloquear los controles al iniciar el cronómetro o cuenta atrás
    function lockControls(workspaceId) {
        $('.play-pause-timer[data-workspace-id="' + workspaceId + '"]').prop('disabled', true);
        $('.reset-timer[data-workspace-id="' + workspaceId + '"]').prop('disabled', true);
    }
    
    // Evento cuando se presiona el botón de iniciar (cuenta atrás o cronómetro)
    $('.play-pause-timer').on('click', function() {
        const workspaceId = $(this).data('workspace-id');
        
     
        if (!isLocked[workspaceId]) {
            initializeWorkspace(workspaceId);
        }
        
        lockControls(workspaceId);  // Bloquear los botones al iniciar
    });
    
    // Evento cuando se intenta pausar o reiniciar
    $('.play-pause-timer, .reset-timer').on('click', function(e) {
        const workspaceId = $(this).data('workspace-id');
        if (isLocked[workspaceId]) {
            e.preventDefault();  // Evitar la acción si está bloqueado
            requestPin(workspaceId);  // Pedir el PIN
        }
    });
    

});