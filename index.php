<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOE Monitor v3.0 - AGX Thor</title>
    <style>
        :root {
            --bg-primary: #0d1117;
            --bg-secondary: #161b22;
            --bg-tertiary: #21262d;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --accent-green: #3fb950;
            --accent-blue: #58a6ff;
            --accent-orange: #d29922;
            --accent-red: #f85149;
            --accent-purple: #a371f7;
            --accent-cyan: #39c5cf;
            --accent-pink: #db61a2;
            --border-color: #30363d;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h1 span.version {
            font-size: 0.7rem;
            background: var(--accent-cyan);
            color: var(--bg-primary);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .uptime-display {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 0.9rem;
            color: var(--accent-cyan);
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent-red);
            animation: pulse 2s infinite;
        }

        .status-dot.connected { background: var(--accent-green); }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .main-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            height: calc(100vh - 56px);
        }

        .left-panel {
            display: grid;
            grid-template-rows: auto auto 1fr;
            gap: 12px;
            padding: 12px;
            overflow: hidden;
        }

        .top-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 12px;
        }

        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
        }

        .card-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .current-state { text-align: center; }

        .state-display {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--accent-blue);
            font-family: 'JetBrains Mono', monospace;
        }

        .state-step {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 3px;
        }

        .process-id {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 8px;
            font-family: monospace;
        }

        /* Métricas Grid */
        .metrics-section {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }

        .metric-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .metric-card.highlight {
            border-color: var(--accent-blue);
            background: rgba(88, 166, 255, 0.1);
        }

        .metric-card.success {
            border-color: var(--accent-green);
            background: rgba(63, 185, 80, 0.1);
        }

        .metric-card.warning {
            border-color: var(--accent-orange);
            background: rgba(210, 153, 34, 0.1);
        }

        .metric-card.danger {
            border-color: var(--accent-red);
            background: rgba(248, 81, 73, 0.1);
        }

        .metric-value {
            font-size: 1.4rem;
            font-weight: bold;
            font-family: 'JetBrains Mono', monospace;
        }

        .metric-value.green { color: var(--accent-green); }
        .metric-value.red { color: var(--accent-red); }
        .metric-value.blue { color: var(--accent-blue); }
        .metric-value.orange { color: var(--accent-orange); }
        .metric-value.purple { color: var(--accent-purple); }
        .metric-value.cyan { color: var(--accent-cyan); }

        .metric-label {
            font-size: 0.65rem;
            color: var(--text-secondary);
            margin-top: 2px;
            text-transform: uppercase;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: var(--bg-tertiary);
            border-radius: 6px;
        }

        .stat-value {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .stat-value.success { color: var(--accent-green); }
        .stat-value.error { color: var(--accent-red); }

        .stat-label {
            font-size: 0.6rem;
            color: var(--text-secondary);
            text-transform: uppercase;
        }

        /* Video Stream */
        .video-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .video-header {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .video-header h3 {
            font-size: 0.8rem;
            color: var(--text-secondary);
            text-transform: uppercase;
        }

        .video-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            min-height: 300px;
        }

        .video-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .video-offline {
            color: var(--text-secondary);
            text-align: center;
        }

        /* Timeline */
        .timeline-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
        }

        .timeline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .timeline-step {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--bg-tertiary);
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .timeline-step.completed {
            background: var(--accent-green);
            border-color: var(--accent-green);
            color: #fff;
        }

        .timeline-step.current {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
            animation: pulse 1s infinite;
        }

        .timeline-connector {
            flex: 1;
            height: 3px;
            background: var(--border-color);
            margin: 0 4px;
        }

        .timeline-connector.completed {
            background: var(--accent-green);
        }

        /* Right Panel */
        .right-panel {
            background: var(--bg-secondary);
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-header h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--text-secondary);
        }

        .log-controls {
            display: flex;
            gap: 8px;
        }

        .log-controls button {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.7rem;
        }

        .log-controls button:hover {
            background: var(--border-color);
        }

        .log-controls button.active {
            background: var(--accent-blue);
            color: #fff;
            border-color: var(--accent-blue);
        }

        /* Terminal */
        .terminal {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .terminal-body {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 0.7rem;
            line-height: 1.4;
        }

        .log-entry {
            padding: 2px 0;
            border-bottom: 1px solid var(--bg-tertiary);
        }

        .log-entry.SUCCESS { color: var(--accent-green); }
        .log-entry.ERROR { color: var(--accent-red); }
        .log-entry.WARNING { color: var(--accent-orange); }
        .log-entry.DEBUG { color: var(--accent-purple); }

        .log-time {
            color: var(--text-secondary);
            margin-right: 8px;
        }

        /* History */
        .history-section {
            max-height: 200px;
            overflow-y: auto;
            border-top: 1px solid var(--border-color);
            padding: 8px;
        }

        .history-item {
            display: grid;
            grid-template-columns: 60px 1fr auto auto;
            gap: 8px;
            align-items: center;
            padding: 6px;
            background: var(--bg-tertiary);
            border-radius: 4px;
            margin-bottom: 4px;
            font-size: 0.75rem;
        }

        .history-item.completed { border-left: 3px solid var(--accent-green); }
        .history-item.failed { border-left: 3px solid var(--accent-red); }
        .history-item.in-progress { border-left: 3px solid var(--accent-blue); }

        .history-id {
            font-family: monospace;
            color: var(--text-secondary);
        }

        .history-step-badges {
            display: flex;
            gap: 2px;
        }

        .step-badge {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
        }

        .step-badge.done {
            background: var(--accent-green);
            border-color: var(--accent-green);
            color: #fff;
        }

        .step-badge.current {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
        }

        .step-badge.error {
            background: var(--accent-red);
            border-color: var(--accent-red);
            color: #fff;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: bold;
        }

        .status-badge.completed {
            background: rgba(63, 185, 80, 0.2);
            color: var(--accent-green);
        }

        .status-badge.failed {
            background: rgba(248, 81, 73, 0.2);
            color: var(--accent-red);
        }

        .status-badge.in-progress {
            background: rgba(88, 166, 255, 0.2);
            color: var(--accent-blue);
        }

        .history-time {
            color: var(--text-secondary);
            font-family: monospace;
        }

        /* Log Stats */
        .log-stats {
            display: flex;
            gap: 12px;
            padding: 8px 12px;
            background: var(--bg-tertiary);
            border-top: 1px solid var(--border-color);
            font-size: 0.7rem;
        }

        .log-stat {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .log-stat-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .log-stat-dot.total { background: var(--text-secondary); }
        .log-stat-dot.success { background: var(--accent-green); }
        .log-stat-dot.warning { background: var(--accent-orange); }
        .log-stat-dot.error { background: var(--accent-red); }

        /* Mini chart */
        .mini-chart {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 35px;
            padding: 4px 0;
        }

        .mini-bar {
            flex: 1;
            background: var(--accent-blue);
            border-radius: 2px 2px 0 0;
            min-width: 4px;
            opacity: 0.7;
        }

        .mini-bar:last-child {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔬 HOE Monitor <span class="version">v3.0 AGX Thor</span></h1>
        <div class="header-right">
            <div class="uptime-display" id="uptime">00:00:00</div>
            <div class="connection-status">
                <div class="status-dot" id="statusDot"></div>
                <span id="connectionText">Desconectado</span>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="left-panel">
            <!-- Top Cards -->
            <div class="top-section">
                <div class="card current-state">
                    <div class="card-title">Estado Actual</div>
                    <div class="state-display" id="currentState">S0</div>
                    <div class="state-step" id="currentStepDesc">Esperando botella</div>
                    <div class="process-id">ID: <span id="currentProcessId">--------</span></div>
                </div>
                
                <div class="card">
                    <div class="card-title">Resultados</div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value success" id="completedCount">0</div>
                            <div class="stat-label">Completados</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value error" id="failedCount">0</div>
                            <div class="stat-label">Fallidos</div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-title">Rendimiento</div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value" id="successRate" style="color: var(--accent-green)">0%</div>
                            <div class="stat-label">Éxito</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="avgTime" style="color: var(--accent-cyan)">0s</div>
                            <div class="stat-label">Promedio</div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-title">Streak</div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value" id="currentStreak" style="color: var(--accent-blue)">0</div>
                            <div class="stat-label">Actual</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="bestStreak" style="color: var(--accent-purple)">0</div>
                            <div class="stat-label">Mejor</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Row -->
            <div class="metrics-section">
                <div class="metric-card success">
                    <div class="metric-value green" id="throughput">0</div>
                    <div class="metric-label">Proc/Hora</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value cyan" id="p50">0s</div>
                    <div class="metric-label">P50</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value blue" id="p90">0s</div>
                    <div class="metric-label">P90</div>
                </div>
                <div class="metric-card warning">
                    <div class="metric-value orange" id="p99">0s</div>
                    <div class="metric-label">P99</div>
                </div>
                <div class="metric-card">
                    <div class="metric-value purple" id="fastest">--</div>
                    <div class="metric-label">Más rápido</div>
                </div>
                <div class="metric-card danger">
                    <div class="metric-value red" id="violations">0</div>
                    <div class="metric-label">Violaciones</div>
                </div>
            </div>

            <!-- Video + Timeline -->
            <div style="display: grid; grid-template-rows: 1fr auto; gap: 12px; overflow: hidden;">
                <div class="video-section">
                    <div class="video-header">
                        <h3>📹 Video Stream</h3>
                        <span id="streamStatus" style="font-size: 0.7rem; color: var(--text-secondary);">Conectando...</span>
                    </div>
                    <div class="video-container">
                        <img id="videoStream" src="/stream" alt="Video Stream" onerror="this.style.display='none'; document.getElementById('videoOffline').style.display='block';">
                        <div id="videoOffline" class="video-offline" style="display: none;">
                            <p>⚠️ Stream no disponible</p>
                            <p style="font-size: 0.8rem; margin-top: 8px;">Verificar conexión MJPEG</p>
                        </div>
                    </div>
                </div>

                <div class="timeline-section">
                    <div class="card-title">Progreso del Proceso</div>
                    <div class="timeline">
                        <div class="timeline-step" id="step1">1</div>
                        <div class="timeline-connector" id="conn1"></div>
                        <div class="timeline-step" id="step2">2</div>
                        <div class="timeline-connector" id="conn2"></div>
                        <div class="timeline-step" id="step3">3</div>
                        <div class="timeline-connector" id="conn3"></div>
                        <div class="timeline-step" id="step4">4</div>
                        <div class="timeline-connector" id="conn4"></div>
                        <div class="timeline-step" id="step5">5</div>
                        <div class="timeline-connector" id="conn5"></div>
                        <div class="timeline-step" id="step6">6</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: 0.6rem; color: var(--text-secondary);">
                        <span>Botella Z0</span>
                        <span>+Persona</span>
                        <span>Bot Z1</span>
                        <span>Pers Z2</span>
                        <span>Pers Z3</span>
                        <span>Bot→Z0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Logs -->
        <div class="right-panel">
            <div class="panel-header">
                <h3>📋 Logs en Tiempo Real</h3>
                <div class="log-controls">
                    <button id="autoScrollBtn" class="active" onclick="toggleAutoScroll()">Auto</button>
                    <button onclick="clearLogs()">Limpiar</button>
                </div>
            </div>
            
            <div class="terminal">
                <div class="terminal-body" id="terminalBody"></div>
            </div>

            <div class="log-stats">
                <div class="log-stat">
                    <div class="log-stat-dot total"></div>
                    <span id="logTotal">0</span>
                </div>
                <div class="log-stat">
                    <div class="log-stat-dot success"></div>
                    <span id="logSuccess">0</span>
                </div>
                <div class="log-stat">
                    <div class="log-stat-dot warning"></div>
                    <span id="logWarning">0</span>
                </div>
                <div class="log-stat">
                    <div class="log-stat-dot error"></div>
                    <span id="logError">0</span>
                </div>
            </div>

            <div class="history-section">
                <div class="card-title" style="margin-bottom: 8px;">Historial Reciente</div>
                <div id="historyList"></div>
            </div>
        </div>
    </div>

    <script>
        // Config
        const WS_PORT = 8765;
        const MAX_LOGS = 200;
        const MAX_HISTORY = 15;
        
        // Estado S0-S5 (NOMENCLATURA UNIFICADA)
        const STATE_TO_STEP = {
            'S0': 0, 'S1': 1, 'S2': 2, 'S3': 3, 'S4': 4, 'S5': 5, 'DONE': 6
        };
        
        const STEP_DESCRIPTIONS = {
            'S0': 'Esperando botella en zona_0',
            'S1': 'Botella detectada, esperando persona',
            'S2': 'Persona tomó botella, esperando en zona_1',
            'S3': 'Botella en zona_1, esperando persona en zona_2',
            'S4': 'Persona en zona_2, esperando en zona_3',
            'S5': 'Persona en zona_3, esperando botella regrese',
            'DONE': 'Proceso completado'
        };

        let ws = null;
        let autoScroll = true;
        let processes = new Map();
        let currentProcessId = null;
        let logCounts = { total: 0, success: 0, warning: 0, error: 0 };
        let stats = { completed: 0, failed: 0, skips: 0, timeouts: 0 };

        function connect() {
            const wsUrl = `ws://${window.location.hostname}:${WS_PORT}`;
            ws = new WebSocket(wsUrl);
            
            ws.onopen = () => {
                document.getElementById('statusDot').classList.add('connected');
                document.getElementById('connectionText').textContent = 'Conectado';
                addLog('WebSocket conectado', 'SUCCESS');
            };
            
            ws.onclose = () => {
                document.getElementById('statusDot').classList.remove('connected');
                document.getElementById('connectionText').textContent = 'Desconectado';
                setTimeout(connect, 2000);
            };
            
            ws.onerror = () => {
                addLog('Error de conexión WebSocket', 'ERROR');
            };
            
            ws.onmessage = (event) => {
                try {
                    const data = JSON.parse(event.data);
                    
                    if (data.type === 'LOG') {
                        addLog(data.message, data.level);
                    } else if (data.type === 'EVENT') {
                        handleEvent(data);
                    } else if (data.type === 'METRICS') {
                        updateMetrics(data);
                    }
                } catch (e) {
                    console.error('Error parsing message:', e);
                }
            };
        }

        function updateMetrics(data) {
            // Actualizar contadores
            document.getElementById('completedCount').textContent = data.processes_completed || 0;
            document.getElementById('failedCount').textContent = data.processes_failed || 0;
            document.getElementById('successRate').textContent = (data.success_rate || 0).toFixed(0) + '%';
            document.getElementById('avgTime').textContent = (data.avg_process_time || 0).toFixed(1) + 's';
            document.getElementById('currentStreak').textContent = data.current_streak || 0;
            document.getElementById('bestStreak').textContent = data.best_streak || 0;
            
            // Métricas
            document.getElementById('throughput').textContent = (data.throughput_per_hour || 0).toFixed(1);
            document.getElementById('p50').textContent = (data.p50 || 0).toFixed(1) + 's';
            document.getElementById('p90').textContent = (data.p90 || 0).toFixed(1) + 's';
            document.getElementById('p99').textContent = (data.p99 || 0).toFixed(1) + 's';
            document.getElementById('fastest').textContent = data.fastest_process ? data.fastest_process.toFixed(1) + 's' : '--';
            document.getElementById('violations').textContent = data.violations_total || 0;
            
            // Uptime
            if (data.uptime) {
                document.getElementById('uptime').textContent = data.uptime;
            }
            
            // Color del success rate
            const rate = data.success_rate || 0;
            const rateEl = document.getElementById('successRate');
            if (rate >= 80) rateEl.style.color = 'var(--accent-green)';
            else if (rate >= 50) rateEl.style.color = 'var(--accent-orange)';
            else rateEl.style.color = 'var(--accent-red)';
        }

        function handleEvent(data) {
            const { event_type, process_id, state, step, rule, metrics } = data;
            const shortId = process_id ? process_id.substring(0, 8) : '--------';
            
            currentProcessId = process_id;
            document.getElementById('currentProcessId').textContent = shortId;
            
            if (state) updateState(state);
            if (metrics) updateMetrics(metrics);

            const proc = getOrCreateProcess(process_id);
            
            if (state && STATE_TO_STEP[state] !== undefined) {
                const completedStep = STATE_TO_STEP[state];
                for (let i = 1; i <= completedStep; i++) {
                    if (!proc.steps.includes(i)) proc.steps.push(i);
                }
                proc.lastStep = completedStep;
                proc.currentStep = completedStep + 1;
            }
            
            switch (event_type) {
                case 'STEP_OK':
                    if (step && !proc.steps.includes(step)) proc.steps.push(step);
                    proc.lastStep = Math.max(proc.lastStep, step || 0);
                    proc.currentStep = (step || 0) + 1;
                    updateTimeline(proc.steps, step);
                    break;
                    
                case 'PROCESS_COMPLETE':
                    proc.status = 'completed';
                    proc.duration = data.duration_total;
                    proc.steps = [1, 2, 3, 4, 5, 6];
                    proc.currentStep = null;
                    stats.completed++;
                    resetTimeline();
                    break;
                    
                case 'VIOLATION':
                    proc.status = 'failed';
                    proc.errorStep = step;
                    proc.errorReason = rule;
                    proc.currentStep = null;
                    if (rule && rule.includes('SKIPPED')) stats.skips++;
                    else stats.timeouts++;
                    stats.failed++;
                    break;
                    
                case 'RESET':
                    if (data.reason !== 'complete' && proc.status === 'in-progress') {
                        proc.status = 'failed';
                        proc.errorReason = data.reason;
                        proc.currentStep = null;
                    }
                    resetTimeline();
                    break;
            }
            
            renderHistory();
        }

        function getOrCreateProcess(processId) {
            if (!processes.has(processId)) {
                processes.set(processId, {
                    id: processId,
                    shortId: processId.substring(0, 8),
                    steps: [],
                    status: 'in-progress',
                    startTime: Date.now(),
                    lastStep: 0,
                    currentStep: 1,
                    errorStep: null,
                    errorReason: null
                });
            }
            return processes.get(processId);
        }

        function updateState(state) {
            document.getElementById('currentState').textContent = state;
            document.getElementById('currentStepDesc').textContent = STEP_DESCRIPTIONS[state] || state;
            
            const stateEl = document.getElementById('currentState');
            if (state === 'S0') {
                stateEl.style.color = 'var(--text-secondary)';
            } else if (state === 'DONE') {
                stateEl.style.color = 'var(--accent-green)';
            } else {
                stateEl.style.color = 'var(--accent-blue)';
            }
        }

        function updateTimeline(completedSteps, currentStep) {
            for (let i = 1; i <= 6; i++) {
                const stepEl = document.getElementById(`step${i}`);
                const connEl = document.getElementById(`conn${i}`);
                stepEl.classList.remove('completed', 'current');
                if (connEl) connEl.classList.remove('completed');
                
                if (completedSteps.includes(i)) {
                    stepEl.classList.add('completed');
                    if (connEl) connEl.classList.add('completed');
                } else if (i === (currentStep || 0) + 1) {
                    stepEl.classList.add('current');
                }
            }
        }

        function resetTimeline() {
            for (let i = 1; i <= 6; i++) {
                document.getElementById(`step${i}`).classList.remove('completed', 'current');
                const conn = document.getElementById(`conn${i}`);
                if (conn) conn.classList.remove('completed');
            }
        }

        function renderHistory() {
            const container = document.getElementById('historyList');
            const sorted = Array.from(processes.values())
                .sort((a, b) => b.startTime - a.startTime)
                .slice(0, MAX_HISTORY);
            
            container.innerHTML = sorted.map(proc => {
                const elapsed = proc.duration || ((Date.now() - proc.startTime) / 1000);
                const timeStr = elapsed < 60 ? `${elapsed.toFixed(1)}s` : `${(elapsed/60).toFixed(1)}m`;
                
                let stepBadges = '';
                for (let i = 1; i <= 6; i++) {
                    let cls = '';
                    if (proc.steps.includes(i)) cls = 'done';
                    else if (proc.status === 'in-progress' && proc.currentStep === i) cls = 'current';
                    else if (proc.errorStep === i) cls = 'error';
                    stepBadges += `<div class="step-badge ${cls}">${i}</div>`;
                }
                
                const statusText = proc.status === 'completed' ? '✓ OK' : 
                                   proc.status === 'failed' ? '✗ FAIL' : 
                                   `● S${proc.currentStep || '?'}`;
                
                return `
                    <div class="history-item ${proc.status}">
                        <div class="history-id">${proc.shortId}</div>
                        <div class="history-step-badges">${stepBadges}</div>
                        <div><span class="status-badge ${proc.status}">${statusText}</span></div>
                        <div class="history-time">${timeStr}</div>
                    </div>
                `;
            }).join('');
        }

        function addLog(message, level = 'INFO') {
            const container = document.getElementById('terminalBody');
            const time = new Date().toLocaleTimeString('es-ES', { hour12: false });
            
            const entry = document.createElement('div');
            entry.className = `log-entry ${level}`;
            entry.innerHTML = `<span class="log-time">${time}</span><span class="log-msg">${escapeHtml(message)}</span>`;
            container.insertBefore(entry, container.firstChild);
            
            while (container.children.length > MAX_LOGS) {
                container.removeChild(container.lastChild);
            }
            
            logCounts.total++;
            if (level === 'SUCCESS') logCounts.success++;
            else if (level === 'WARNING') logCounts.warning++;
            else if (level === 'ERROR') logCounts.error++;
            
            document.getElementById('logTotal').textContent = logCounts.total;
            document.getElementById('logSuccess').textContent = logCounts.success;
            document.getElementById('logWarning').textContent = logCounts.warning;
            document.getElementById('logError').textContent = logCounts.error;
            
            if (autoScroll) container.scrollTop = 0;
        }

        function clearLogs() {
            document.getElementById('terminalBody').innerHTML = '';
            logCounts = { total: 0, success: 0, warning: 0, error: 0 };
            document.getElementById('logTotal').textContent = '0';
            document.getElementById('logSuccess').textContent = '0';
            document.getElementById('logWarning').textContent = '0';
            document.getElementById('logError').textContent = '0';
        }

        function toggleAutoScroll() {
            autoScroll = !autoScroll;
            document.getElementById('autoScrollBtn').classList.toggle('active', autoScroll);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Verificar stream de video
        function checkVideoStream() {
            const img = document.getElementById('videoStream');
            img.onerror = () => {
                img.style.display = 'none';
                document.getElementById('videoOffline').style.display = 'block';
                document.getElementById('streamStatus').textContent = 'Offline';
            };
            img.onload = () => {
                img.style.display = 'block';
                document.getElementById('videoOffline').style.display = 'none';
                document.getElementById('streamStatus').textContent = 'En vivo';
            };
        }

        // Inicializar
        connect();
        renderHistory();
        checkVideoStream();
        
        // Actualizar tiempos cada segundo
        setInterval(() => {
            if (processes.size > 0) renderHistory();
        }, 1000);
        
        // Reintentar video cada 5 segundos si está offline
        setInterval(() => {
            const img = document.getElementById('videoStream');
            if (img.style.display === 'none') {
                img.src = '/stream?' + Date.now();
            }
        }, 5000);
    </script>
</body>
</html>