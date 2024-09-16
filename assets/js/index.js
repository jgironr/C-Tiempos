let timers = {};
let workspaceTypes = {};
let timerStates = {};
let timerSeconds = {};

document.querySelectorAll('.play-pause-timer').forEach(button => {
    button.onclick = function () {
        const id = this.dataset.workspaceId;
        if (timerStates[id] === 'running') {
            pauseTimer(id);
            this.innerHTML = '<i class="fas fa-play"></i>';
            timerStates[id] = 'paused';
        } else {
            const type = workspaceTypes[id] || 'stopwatch';
            const limit = parseInt(document.getElementById(`limit-${id}`).value) || 0;
            startTimer(id, type, limit);
            this.innerHTML = '<i class="fas fa-pause"></i>';
            timerStates[id] = 'running';
        }
    };
});

document.querySelectorAll('.reset-timer').forEach(button => {
    button.onclick = function () {
        const id = this.dataset.workspaceId;
        resetTimer(id);
        cleanInput(id);
        timerStates[id] = 'paused';
        timerSeconds[id] = 0;
        document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`).innerHTML =
            '<i class="fas fa-play"></i>';
    };
});

document.querySelectorAll('.toggle-workspace').forEach(button => {
    button.onclick = function () {
        const id = this.dataset.workspaceId;
        toggleWorkspace(id, this);
    };
});

document.querySelectorAll('.select-type').forEach(button => {
    button.onclick = function () {
        const id = this.dataset.workspaceId;
        const type = this.dataset.type;
        workspaceTypes[id] = type;
        document.querySelectorAll(`.select-type[data-workspace-id="${id}"]`).forEach(btn => {
            btn.classList.toggle('btn-primary', btn.dataset.type === type);
            btn.classList.toggle('btn-secondary', btn.dataset.type !== type);
        });

        const playPauseButton = document.querySelector(`.play-pause-timer[data-workspace-id="${id}"]`);
        playPauseButton.classList.remove('d-none');
        document.querySelector(`.reset-timer[data-workspace-id="${id}"]`).classList.remove('d-none');
        document.querySelector(`.input-limit[data-workspace-id="${id}"]`).classList.remove('d-none');
    };
});

function startTimer(id, type, limit) {
    if (timers[id]) clearInterval(timers[id]);

    let seconds = timerSeconds[id] || (type === 'countdown' ? limit * 60 : 0);
    if (timerStates[id] === 'running') return;

    timers[id] = setInterval(() => {
        if (type === 'countdown') {
            seconds--;
            if (seconds <= 0) {
                clearInterval(timers[id]);
                alert('Tiempo ' + id + ' finalizado!');
            }
        } else {
            seconds++;
            if (seconds >= limit * 60) {
                clearInterval(timers[id]);
                alert('Cronómetro ' + id + ' alcanzó el límite de ' + limit + ' minutos!');
            }
        }
        document.getElementById(`timer-${id}`).innerText = formatTime(seconds);
        timerSeconds[id] = seconds;
    }, 1000);
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
}

function pauseTimer(id) {
    if (timers[id]) clearInterval(timers[id]);
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
                }
                window.location.reload();
            }
        });
}

function cleanInput(id) {
    const input = document.querySelector(`#limit-${id}`);
    if (input) {
        input.value = '';
    }
}