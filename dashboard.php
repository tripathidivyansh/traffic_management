<style>
    
.vehicle-container {
    position: relative;
    overflow: hidden;
    height: 40px;
}

.vehicle {
    position: absolute;
    font-size: 24px;
    transition: transform 2s linear;
}

.vehicle.move-east {
    left: 0;
    top: 0;
    transform: translateX(120px);
}
.vehicle.move-west {
    right: 0;
    top: 0;
    transform: translateX(-120px);
}
.vehicle.move-north {
    top: 40px;
    left: 0;
    transform: translateY(-120px);
}
.vehicle.move-south {
    top: 0;
    left: 0;
    transform: translateY(120px);
}
</style>


<div class="container">
    <div class="header-controls">
        <div class="time-display">
            <span id="current-time"></span>
        </div>
        <div class="last-updated" id="lastUpdated">Last updated: Just now</div>
    </div>
    
    <div class="dashboard">
         <div class="traffic-intersection">
                <div class="road horizontal">
                    <div class="lane west">
                        <div class="vehicle-container" id="west-vehicles"></div>
                        <div class="vehicle-count">0</div>
                        <div class="traffic-light" id="west-light">
                            <div class="light red active"></div>
                            <div class="light yellow"></div>
                            <div class="light green"></div>
                        </div>
                        <div class="pedestrian-crossing">
                            <div class="pedestrian-light" id="west-pedestrian">
                                <div class="ped-light walk"></div>
                                <div class="ped-light wait active"></div>
                            </div>
                            <div class="pedestrian" id="west-pedestrian-figure">🚶</div>
                        </div>
                    </div>
                    <div class="lane east">
                        <div class="vehicle-container" id="east-vehicles"></div>
                        <div class="vehicle-count">0</div>
                        <div class="traffic-light" id="east-light">
                            <div class="light red active"></div>
                            <div class="light yellow"></div>
                            <div class="light green"></div>
                        </div>
                        <div class="pedestrian-crossing">
                            <div class="pedestrian-light" id="east-pedestrian">
                                <div class="ped-light walk"></div>
                                <div class="ped-light wait active"></div>
                            </div>
                            <div class="pedestrian" id="east-pedestrian-figure">🚶</div>
                        </div>
                    </div>
                </div>
                
                <div class="road vertical">
                    <div class="lane north">
                        <div class="vehicle-container" id="north-vehicles"></div>
                        <div class="vehicle-count">0</div>
                        <div class="traffic-light" id="north-light">
                            <div class="light red active"></div>
                            <div class="light yellow"></div>
                            <div class="light green"></div>
                        </div>
                        <div class="pedestrian-crossing">
                            <div class="pedestrian-light" id="north-pedestrian">
                                <div class="ped-light walk"></div>
                                <div class="ped-light wait active"></div>
                            </div>
                            <div class="pedestrian" id="north-pedestrian-figure">🚶</div>
                        </div>
                    </div>
                    <div class="lane south">
                        <div class="vehicle-container" id="south-vehicles"></div>
                        <div class="vehicle-count">0</div>
                        <div class="traffic-light" id="south-light">
                            <div class="light red active"></div>
                            <div class="light yellow"></div>
                            <div class="light green"></div>
                        </div>
                        <div class="pedestrian-crossing">
                            <div class="pedestrian-light" id="south-pedestrian">
                                <div class="ped-light walk"></div>
                                <div class="ped-light wait active"></div>
                            </div>
                            <div class="pedestrian" id="south-pedestrian-figure">🚶</div>
                        </div>
                    </div>
                </div>
                
                <div class="intersection-center">
                    <div class="emergency-alert" id="emergency-alert">🚨 Emergency Vehicle Detected</div>
                </div>
            </div>
        
            <div class="stats-panel">
                <div class="traffic-stats">
                    <h2>Traffic Statistics</h2>
                    <div class="stat-grid">
                        <div class="stat-item">
                            <span class="stat-label">Westbound:</span>
                            <span class="stat-value" id="westbound-stat">0 vehicles</span>
                            <div class="stat-trend" id="west-trend">→</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Eastbound:</span>
                            <span class="stat-value" id="eastbound-stat">0 vehicles</span>
                            <div class="stat-trend" id="east-trend">→</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Northbound:</span>
                            <span class="stat-value" id="northbound-stat">0 vehicles</span>
                            <div class="stat-trend" id="north-trend">→</div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Southbound:</span>
                            <span class="stat-value" id="southbound-stat">0 vehicles</span>
                            <div class="stat-trend" id="south-trend">→</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <span class="stat-label">Current Cycle:</span>
                        <span class="stat-value" id="current-cycle">30 sec</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">System Efficiency:</span>
                        <span class="stat-value" id="system-efficiency">85%</span>
                    </div>
                </div>
                
                <div class="chart-container">
                    <div id="trafficChart"></div>
                </div>
            </div>
    </div>
    
    <div class="control-panel">
            <div class="control-section">
                <h2>Control Panel</h2>
                <div class="controls">
                    <button id="pedestrian-btn">Request Pedestrian Crossing</button>
                    <button id="auto-mode" class="active">Auto Mode</button>
                    <button id="manual-mode">Manual Mode</button>
                    <div class="manual-controls" id="manual-controls" style="display: none;">
                        <button class="direction-btn" data-direction="north">North Green</button>
                        <button class="direction-btn" data-direction="south">South Green</button>
                        <button class="direction-btn" data-direction="east">East Green</button>
                        <button class="direction-btn" data-direction="west">West Green</button>
                    </div>
                    <div class="time-control">
                        <label for="cycle-time">Cycle Time (sec):</label>
                        <input type="number" id="cycle-time" min="10" max="120" value="30">
                    </div>
                    <button id="emergency-btn">Simulate Emergency</button>
                </div>
            </div>
            
            <div class="ai-settings">
                <h3>AI Settings</h3>
                <div class="setting">
                    <label for="sensitivity">Traffic Sensitivity:</label>
                    <input type="range" id="sensitivity" min="1" max="10" value="5">
                    <span id="sensitivity-value">5</span>
                </div>
                <div class="setting">
                    <label for="pedestrian-priority">Pedestrian Priority:</label>
                    <input type="checkbox" id="pedestrian-priority">
                </div>
                <div class="setting">
                    <label for="emergency-priority">Emergency Priority:</label>
                    <input type="checkbox" id="emergency-priority" checked>
                </div>
                <div class="setting">
                    <label for="peak-hours">Peak Hours Mode:</label>
                    <input type="checkbox" id="peak-hours">
                </div>
            </div>
            
            <div class="history-section">
                <h3>Historical Data</h3>
                <div class="history-controls">
                    <select id="history-range">
                        <option value="1">Last Hour</option>
                        <option value="6">Last 6 Hours</option>
                        <option value="24" selected>Last 24 Hours</option>
                        <option value="168">Last Week</option>
                    </select>
                    <button id="view-history">View</button>
                </div>
                <div id="historyChart"></div>
            </div>
        </div>
</div>


<!-- Include Chart.js from CDN for stats & history -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const cycleInput = document.getElementById("cycle-time");
    const currentCycle = document.getElementById("current-cycle");
    const systemEfficiency = document.getElementById("system-efficiency");
    const sensitivityRange = document.getElementById("sensitivity");
    const sensitivityValue = document.getElementById("sensitivity-value");
    const pedestrianBtn = document.getElementById("pedestrian-btn");
    const emergencyBtn = document.getElementById("emergency-btn");
    const autoBtn = document.getElementById("auto-mode");
    const manualBtn = document.getElementById("manual-mode");
    const manualControls = document.getElementById("manual-controls");
    const emergencyAlert = document.getElementById("emergency-alert");
    const lastUpdated = document.getElementById("lastUpdated");

    const lights = {
        north: document.getElementById("north-light"),
        south: document.getElementById("south-light"),
        east: document.getElementById("east-light"),
        west: document.getElementById("west-light")
    };

    const pedestrianLights = {
        north: document.getElementById("north-pedestrian"),
        south: document.getElementById("south-pedestrian"),
        east: document.getElementById("east-pedestrian"),
        west: document.getElementById("west-pedestrian")
    };

    let activeDirection = "north";
    let isAuto = true;
    let intervalId;

    // Time display
    setInterval(() => {
        document.getElementById("current-time").innerText = new Date().toLocaleTimeString();
    }, 1000);

    function spawnCar(direction) {
        const container = document.getElementById(`${direction}-vehicles`);
        const car = document.createElement("div");
        car.classList.add("vehicle", `move-${direction}`);
        car.innerText = "🚗";
        container.appendChild(car);

        setTimeout(() => {
            container.removeChild(car);
        }, 2000);
    }

    function setActiveLight(dir) {
        Object.keys(lights).forEach(d => {
            lights[d].querySelector(".red").classList.add("active");
            lights[d].querySelector(".green").classList.remove("active");
        });
        lights[dir].querySelector(".red").classList.remove("active");
        lights[dir].querySelector(".green").classList.add("active");

        Object.keys(pedestrianLights).forEach(d => {
            pedestrianLights[d].querySelector(".walk").classList.remove("active");
            pedestrianLights[d].querySelector(".wait").classList.add("active");
        });
        pedestrianLights[dir].querySelector(".walk").classList.add("active");
        pedestrianLights[dir].querySelector(".wait").classList.remove("active");

        spawnCar(dir);

        activeDirection = dir;
        lastUpdated.innerText = `Last updated: ${new Date().toLocaleTimeString()}`;
    }

    function autoCycle() {
        const directions = ["north", "east", "south", "west"];
        let i = 0;
        clearInterval(intervalId);
        intervalId = setInterval(() => {
            setActiveLight(directions[i]);
            i = (i + 1) % directions.length;
        }, parseInt(cycleInput.value) * 1000);
    }

    // Manual control
    document.querySelectorAll(".direction-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            clearInterval(intervalId);
            setActiveLight(btn.dataset.direction);
        });
    });

    // Mode switching
    autoBtn.addEventListener("click", () => {
        isAuto = true;
        manualControls.style.display = "none";
        autoBtn.classList.add("active");
        manualBtn.classList.remove("active");
        autoCycle();
    });

    manualBtn.addEventListener("click", () => {
        isAuto = false;
        manualControls.style.display = "block";
        manualBtn.classList.add("active");
        autoBtn.classList.remove("active");
        clearInterval(intervalId);
    });

    // Pedestrian button
    pedestrianBtn.addEventListener("click", () => {
        setActiveLight(activeDirection);
        alert("Pedestrian crossing activated.");
    });

    // Emergency button
    emergencyBtn.addEventListener("click", () => {
        emergencyAlert.style.display = "block";
        clearInterval(intervalId);
        setActiveLight("north");
        setTimeout(() => {
            emergencyAlert.style.display = "none";
            if (isAuto) autoCycle();
        }, 5000);
    });

    // Cycle time change
    cycleInput.addEventListener("change", () => {
        currentCycle.innerText = `${cycleInput.value} sec`;
        if (isAuto) autoCycle();
    });

    // Sensitivity control
    sensitivityRange.addEventListener("input", () => {
        sensitivityValue.innerText = sensitivityRange.value;
        systemEfficiency.innerText = `${80 + parseInt(sensitivityRange.value)}%`;
    });

    // Chart
    const ctx = document.createElement("canvas");
    document.getElementById("trafficChart").appendChild(ctx);
    new Chart(ctx, {
        type: "line",
        data: {
            labels: ["00:00", "06:00", "12:00", "18:00", "24:00"],
            datasets: [{
                label: "Vehicle Count",
                data: [12, 19, 30, 22, 17],
                borderColor: "rgba(75,192,192,1)",
                fill: false,
                tension: 0.3
            }]
        }
    });

    // Historical chart mock
    document.getElementById("view-history").addEventListener("click", () => {
        document.getElementById("historyChart").innerText = "Loading history data...";
        setTimeout(() => {
            document.getElementById("historyChart").innerText = "History data displayed here (mock)";
        }, 1000);
    });

    // Start in auto mode
    autoCycle();
});
</script>
