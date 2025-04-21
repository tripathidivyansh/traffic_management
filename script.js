document.addEventListener('DOMContentLoaded', function() {
    // DOM elements
    const lastUpdatedEl = document.getElementById('lastUpdated');
    const currentTimeEl = document.getElementById('current-time');
    const dayNightToggle = document.getElementById('day-night-toggle');
    const body = document.body;
    
    const vehicleContainers = {
        north: document.getElementById('north-vehicles'),
        south: document.getElementById('south-vehicles'),
        east: document.getElementById('east-vehicles'),
        west: document.getElementById('west-vehicles')
    };
    
    const vehicleCounts = {
        north: document.querySelector('.lane.north .vehicle-count'),
        south: document.querySelector('.lane.south .vehicle-count'),
        east: document.querySelector('.lane.east .vehicle-count'),
        west: document.querySelector('.lane.west .vehicle-count')
    };
    
    const statValues = {
        north: document.getElementById('northbound-stat'),
        south: document.getElementById('southbound-stat'),
        east: document.getElementById('eastbound-stat'),
        west: document.getElementById('westbound-stat')
    };
    
    const trendIndicators = {
        north: document.getElementById('north-trend'),
        south: document.getElementById('south-trend'),
        east: document.getElementById('east-trend'),
        west: document.getElementById('west-trend')
    };
    
    const trafficLights = {
        north: document.getElementById('north-light'),
        south: document.getElementById('south-light'),
        east: document.getElementById('east-light'),
        west: document.getElementById('west-light')
    };
    
    const pedestrianLights = {
        north: document.getElementById('north-pedestrian'),
        south: document.getElementById('south-pedestrian'),
        east: document.getElementById('east-pedestrian'),
        west: document.getElementById('west-pedestrian')
    };
    
    const pedestrianFigures = {
        north: document.getElementById('north-pedestrian-figure'),
        south: document.getElementById('south-pedestrian-figure'),
        east: document.getElementById('east-pedestrian-figure'),
        west: document.getElementById('west-pedestrian-figure')
    };
    
    const emergencyAlert = document.getElementById('emergency-alert');
    const autoModeBtn = document.getElementById('auto-mode');
    const manualModeBtn = document.getElementById('manual-mode');
    const manualControls = document.getElementById('manual-controls');
    const directionBtns = document.querySelectorAll('.direction-btn');
    const cycleTimeInput = document.getElementById('cycle-time');
    const currentCycleEl = document.getElementById('current-cycle');
    const systemEfficiencyEl = document.getElementById('system-efficiency');
    const sensitivityInput = document.getElementById('sensitivity');
    const sensitivityValue = document.getElementById('sensitivity-value');
    const pedestrianPriority = document.getElementById('pedestrian-priority');
    const emergencyPriority = document.getElementById('emergency-priority');
    const peakHours = document.getElementById('peak-hours');
    const emergencyBtn = document.getElementById('emergency-btn');
    const historyRange = document.getElementById('history-range');
    const viewHistoryBtn = document.getElementById('view-history');
    
    // State variables
    let currentMode = 'auto';
    let previousTraffic = {north: 0, south: 0, east: 0, west: 0};
    let emergencyActive = false;
    let emergencyDirection = '';
    let vehicles = [];
    let pedestriansWaiting = false;
    
    // Initialize time display
    function updateTime() {
        const now = new Date();
        currentTimeEl.textContent = now.toLocaleTimeString();
    }
    
    setInterval(updateTime, 1000);
    updateTime();
    
    // Day/night mode toggle
    dayNightToggle.addEventListener('change', function() {
        body.classList.toggle('night-mode', this.checked);
    });

    document.getElementById('pedestrian-btn').addEventListener('click', function() {
        activatePedestrianCrossing();
    });
    
    // Chart setup
    const trafficChart = new ApexCharts(document.querySelector("#trafficChart"), {
        series: [{
            name: 'Vehicles',
            data: [0, 0, 0, 0]
        }],
        chart: {
            type: 'bar',
            height: '100%',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c'],
        plotOptions: {
            bar: {
                distributed: true,
                borderRadius: 4,
                horizontal: false,
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['North', 'South', 'East', 'West'],
        },
        yaxis: {
            title: {
                text: 'Number of Vehicles'
            }
        },
        tooltip: {
            enabled: true,
            y: {
                formatter: function(val) {
                    return val + " vehicles";
                }
            }
        }
    });
    
    trafficChart.render();
    
    const historyChart = new ApexCharts(document.querySelector("#historyChart"), {
        series: [
            {
                name: 'North',
                data: []
            },
            {
                name: 'South',
                data: []
            },
            {
                name: 'East',
                data: []
            },
            {
                name: 'West',
                data: []
            }
        ],
        chart: {
            type: 'line',
            height: 300,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true
            }
        },
        colors: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c'],
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeFormatter: {
                    hour: 'HH:mm'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Vehicles per minute'
            }
        },
        tooltip: {
            x: {
                format: 'HH:mm'
            }
        },
        legend: {
            position: 'top'
        }
    });
    
    historyChart.render();
    
    // Mode switching
    autoModeBtn.addEventListener('click', function() {
        currentMode = 'auto';
        autoModeBtn.classList.add('active');
        manualModeBtn.classList.remove('active');
        manualControls.style.display = 'none';
        fetchTrafficData();
    });
    
    manualModeBtn.addEventListener('click', function() {
        currentMode = 'manual';
        manualModeBtn.classList.add('active');
        autoModeBtn.classList.remove('active');
        manualControls.style.display = 'flex';
    });
    
    // Manual direction control
    directionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const direction = this.dataset.direction;
            setManualLight(direction);
        });
    });
    
    // Sensitivity slider
    sensitivityInput.addEventListener('input', function() {
        sensitivityValue.textContent = this.value;
    });
    
    // Update cycle time
    cycleTimeInput.addEventListener('change', function() {
        currentCycleEl.textContent = `${this.value} sec`;
    });
    
    // Emergency button
    emergencyBtn.addEventListener('click', function() {
        simulateEmergency();
    });
    
    // View history button
    viewHistoryBtn.addEventListener('click', function() {
        loadHistoryData(historyRange.value);
    });
    
    // Function to create a vehicle element
    function createVehicle(direction, isEmergency = false) {
        const vehicle = document.createElement('div');
        vehicle.className = 'vehicle';
        if (isEmergency) vehicle.classList.add('emergency');
        
        // Random vehicle emoji
        const vehicles = ['🚗', '🚕', '🚙', '🚌', '🚎', '🏎️', '🚓', '🚑', '🚒', '🚚'];
        const randomVehicle = vehicles[Math.floor(Math.random() * vehicles.length)];
        vehicle.textContent = isEmergency ? '🚑' : randomVehicle;
        
        // Random position in lane
        let top, left;
        const size = 24;
        
        switch(direction) {
            case 'north':
                top = Math.random() * 100;
                left = 50 - size/2;
                break;
            case 'south':
                top = 200 - size - Math.random() * 100;
                left = 50 - size/2;
                break;
            case 'east':
                top = 50 - size/2;
                left = Math.random() * 100;
                break;
            case 'west':
                top = 50 - size/2;
                left = 200 - size - Math.random() * 100;
                break;
        }
        
        vehicle.style.top = `${top}px`;
        vehicle.style.left = `${left}px`;
        
        // Add to DOM
        vehicleContainers[direction].appendChild(vehicle);
        
        // Animate movement
        setTimeout(() => {
            let animation;
            switch(direction) {
                case 'north':
                    animation = [{transform: 'translateY(0)'}, {transform: 'translateY(200px)'}];
                    break;
                case 'south':
                    animation = [{transform: 'translateY(0)'}, {transform: 'translateY(-200px)'}];
                    break;
                case 'east':
                    animation = [{transform: 'translateX(0)'}, {transform: 'translateX(200px)'}];
                    break;
                case 'west':
                    animation = [{transform: 'translateX(0)'}, {transform: 'translateX(-200px)'}];
                    break;
            }
            
            vehicle.animate(animation, {
                duration: 2000,
                iterations: 1
            });
            
            // Remove after animation
            setTimeout(() => {
                vehicle.remove();
            }, 2000);
        }, 100);
        
        return vehicle;
    }
    
    // Function to simulate emergency vehicle
    function simulateEmergency() {
        if (emergencyActive) return;
        
        const directions = ['north', 'south', 'east', 'west'];
        emergencyDirection = directions[Math.floor(Math.random() * directions.length)];
        emergencyActive = true;
        
        // Show alert
        emergencyAlert.style.display = 'block';
        
        // Create emergency vehicle
        for (let i = 0; i < 3; i++) {
            setTimeout(() => {
                createVehicle(emergencyDirection, true);
            }, i * 500);
        }
        
        // End emergency after some time
        setTimeout(() => {
            emergencyActive = false;
            emergencyAlert.style.display = 'none';
        }, 10000);
    }

    function activatePedestrianCrossing() {
        // Set all lights to RED
        fetch('traffic-control.php?mode=pedestrian')
            .then(response => response.json())
            .then(data => {
                updateLightsDisplay({north: 'red', south: 'red', east: 'red', west: 'red'});
                
                // Stop all vehicles
                Object.values(vehicleContainers).forEach(container => {
                    const vehicles = container.querySelectorAll('.vehicle');
                    vehicles.forEach(vehicle => {
                        vehicle.style.animationPlayState = 'paused';
                    });
                });
                
                // Activate pedestrian lights
                Object.values(pedestrianLights).forEach(light => {
                    light.querySelector('.walk').classList.add('active');
                    light.querySelector('.wait').classList.remove('active');
                });
    
                // Show walking animation
                Object.values(pedestrianFigures).forEach(ped => {
                    ped.classList.add('visible');
                    ped.style.animation = 'pedestrianCross 10s forwards';
                });
    
                // Reset after crossing time
                setTimeout(() => {
                    Object.values(pedestrianFigures).forEach(ped => {
                        ped.classList.remove('visible');
                        ped.style.animation = '';
                    });
                    
                    // Change lights back to wait
                    Object.values(pedestrianLights).forEach(light => {
                        light.querySelector('.walk').classList.remove('active');
                        light.querySelector('.wait').classList.add('active');
                    });
                    
                    // Resume normal traffic
                    if (currentMode === 'auto') {
                        fetchTrafficData();
                    } else {
                        // In manual mode, resume vehicle movement
                        Object.values(vehicleContainers).forEach(container => {
                            const vehicles = container.querySelectorAll('.vehicle');
                            vehicles.forEach(vehicle => {
                                vehicle.style.animationPlayState = 'running';
                            });
                        });
                    }
                }, 10000); // 10 seconds for pedestrian crossing
            });
    }
    
    // Function to simulate pedestrians
    function simulatePedestrians() {
        if (Math.random() > 0.7) { // 30% chance of pedestrians
            pedestriansWaiting = true;
            
            // Show pedestrian figures
            Object.values(pedestrianFigures).forEach(ped => {
                ped.classList.add('visible');
            });
            
            // If in auto mode, request pedestrian crossing
            if (currentMode === 'auto') {
                setTimeout(() => {
                    if (pedestriansWaiting) {
                        activatePedestrianCrossing();
                    }
                }, 3000);
            }
        }
    }

    function createVehicle(direction, isEmergency = false) {
        const lightStatus = trafficLights[direction].querySelector('.light.active').classList[1];
        
        // Only create vehicle if light is green or it's an emergency
        if (lightStatus === 'green' || isEmergency) {
            const vehicle = document.createElement('div');
            vehicle.className = 'vehicle';
            if (isEmergency) vehicle.classList.add('emergency');
            
            const vehicles = ['🚗', '🚕', '🚙', '🚌', '🚎', '🏎️', '🚓', '🚑', '🚒', '🚚'];
            const randomVehicle = vehicles[Math.floor(Math.random() * vehicles.length)];
            vehicle.textContent = isEmergency ? '🚑' : randomVehicle;
            
            let top, left;
            const size = 24;
            
            switch(direction) {
                case 'north':
                    top = Math.random() * 100;
                    left = 50 - size/2;
                    break;
                case 'south':
                    top = 200 - size - Math.random() * 100;
                    left = 50 - size/2;
                    break;
                case 'east':
                    top = 50 - size/2;
                    left = Math.random() * 100;
                    break;
                case 'west':
                    top = 50 - size/2;
                    left = 200 - size - Math.random() * 100;
                    break;
            }
            
            vehicle.style.top = `${top}px`;
            vehicle.style.left = `${left}px`;
            
            vehicleContainers[direction].appendChild(vehicle);
            
            setTimeout(() => {
                let animation;
                switch(direction) {
                    case 'north':
                        animation = [{transform: 'translateY(0)'}, {transform: 'translateY(200px)'}];
                        break;
                    case 'south':
                        animation = [{transform: 'translateY(0)'}, {transform: 'translateY(-200px)'}];
                        break;
                    case 'east':
                        animation = [{transform: 'translateX(0)'}, {transform: 'translateX(200px)'}];
                        break;
                    case 'west':
                        animation = [{transform: 'translateX(0)'}, {transform: 'translateX(-200px)'}];
                        break;
                }
                
                const anim = vehicle.animate(animation, {
                    duration: 2000,
                    iterations: 1
                });
                
                // Pause animation if light turns red
                const checkLight = setInterval(() => {
                    const currentLight = trafficLights[direction].querySelector('.light.active').classList[1];
                    if (currentLight !== 'green' && !isEmergency) {
                        anim.pause();
                    } else {
                        anim.play();
                    }
                }, 100);
                
                setTimeout(() => {
                    clearInterval(checkLight);
                    vehicle.remove();
                }, 2000);
            }, 100);
            
            return vehicle;
        }
        return null;
    }
    
    // Function to activate pedestrian crossing
    function activatePedestrianCrossing() {
        // Change pedestrian lights to walk
        Object.values(pedestrianLights).forEach(light => {
            light.querySelector('.walk').classList.add('active');
            light.querySelector('.wait').classList.remove('active');
        });
        
        // Pedestrians cross
        Object.values(pedestrianFigures).forEach(ped => {
            ped.style.animation = 'pedestrianCross 5s forwards';
        });
        
        // Reset after crossing
        setTimeout(() => {
            pedestriansWaiting = false;
            Object.values(pedestrianFigures).forEach(ped => {
                ped.classList.remove('visible');
                ped.style.animation = '';
            });
            
            // Change lights back to wait
            Object.values(pedestrianLights).forEach(light => {
                light.querySelector('.walk').classList.remove('active');
                light.querySelector('.wait').classList.add('active');
            });
        }, 5000);
    }
    
    // Function to update traffic lights display
    function updateLightsDisplay(lights) {
        // First set all to red
        Object.keys(trafficLights).forEach(direction => {
            const lightEl = trafficLights[direction];
            lightEl.querySelectorAll('.light').forEach(light => {
                light.classList.remove('active');
            });
            lightEl.querySelector('.light.red').classList.add('active');
        });
        
        // Then activate the correct lights
        Object.keys(lights).forEach(direction => {
            const status = lights[direction];
            const lightEl = trafficLights[direction];
            
            lightEl.querySelectorAll('.light').forEach(light => {
                light.classList.remove('active');
            });
            
            lightEl.querySelector(`.light.${status}`).classList.add('active');
            
            // If yellow, blink it
            if (status === 'yellow') {
                const yellowLight = lightEl.querySelector('.light.yellow');
                const interval = setInterval(() => {
                    yellowLight.classList.toggle('active');
                }, 500);
                
                // Stop blinking after 3 seconds (typical yellow light duration)
                setTimeout(() => {
                    clearInterval(interval);
                    yellowLight.classList.remove('active');
                    lightEl.querySelector('.light.red').classList.add('active');
                }, 3000);
            }
        });
    }
    
    // Function to update vehicle counts
    function updateVehicleCounts(traffic) {
        Object.keys(traffic).forEach(direction => {
            const count = traffic[direction];
            vehicleCounts[direction].textContent = count;
            statValues[direction].textContent = `${count} vehicles`;
            
            // Update trend indicator
            const trend = trendIndicators[direction];
            if (count > previousTraffic[direction]) {
                trend.textContent = '↑';
                trend.className = 'stat-trend trend-up';
            } else if (count < previousTraffic[direction]) {
                trend.textContent = '↓';
                trend.className = 'stat-trend trend-down';
            } else {
                trend.textContent = '→';
                trend.className = 'stat-trend';
            }
            
            // Highlight if count is high
            if (count > 15) {
                vehicleCounts[direction].classList.add('high');
            } else {
                vehicleCounts[direction].classList.remove('high');
            }
            
            // Create vehicles for visualization
            const diff = count - previousTraffic[direction];
            if (diff > 0) {
                for (let i = 0; i < Math.min(diff, 5); i++) {
                    setTimeout(() => {
                        createVehicle(direction);
                    }, i * 300);
                }
            }
            
            previousTraffic[direction] = count;
        });
        
        // Update chart
        trafficChart.updateSeries([{
            data: [
                traffic.north,
                traffic.south,
                traffic.east,
                traffic.west
            ]
        }]);
        
        // Calculate and update system efficiency (mock calculation)
        const total = traffic.north + traffic.south + traffic.east + traffic.west;
        const maxLane = Math.max(traffic.north, traffic.south, traffic.east, traffic.west);
        const efficiency = total > 0 ? Math.round(100 - (maxLane / total * 100 - 25)) : 100;
        systemEfficiencyEl.textContent = `${efficiency}%`;
    }
    
    // Function to fetch traffic data from server
    function fetchTrafficData() {
        const sensitivity = sensitivityInput.value;
        const params = new URLSearchParams({
            mode: currentMode,
            sensitivity: sensitivity,
            pedestrian: pedestrianPriority.checked,
            emergency: emergencyPriority.checked,
            peak: peakHours.checked
        });
        
        if (emergencyActive && emergencyPriority.checked) {
            params.set('emergency_direction', emergencyDirection);
        }
        
        fetch(`traffic-control.php?${params}`)
            .then(response => response.json())
            .then(data => {
                updateLightsDisplay(data.lights);
                updateVehicleCounts(data.traffic);
                lastUpdatedEl.textContent = `Last updated: ${new Date().toLocaleTimeString()}`;
                
                // Check for pedestrians
                if (Math.random() > 0.8) {
                    simulatePedestrians();
                }
                
                // Schedule next update based on cycle time
                const cycleTime = parseInt(cycleTimeInput.value) * 1000;
                setTimeout(fetchTrafficData, cycleTime);
            })
            .catch(error => {
                console.error('Error fetching traffic data:', error);
                setTimeout(fetchTrafficData, 5000); // Retry after 5 seconds
            });
    }
    
    // Function to set manual light direction
    function setManualLight(direction) {
        // Determine the opposite direction
        let oppositeDirection;
        switch(direction) {
            case 'north': oppositeDirection = 'south'; break;
            case 'south': oppositeDirection = 'north'; break;
            case 'east': oppositeDirection = 'west'; break;
            case 'west': oppositeDirection = 'east'; break;
        }
        
        fetch(`traffic-control.php?mode=manual&direction=${direction}&opposite=${oppositeDirection}`)
            .then(response => response.json())
            .then(data => {
                updateLightsDisplay(data.lights);
                updateVehicleCounts(data.traffic);
                lastUpdatedEl.textContent = `Last updated: ${new Date().toLocaleTimeString()}`;
                
                // Only allow vehicles in the green directions
                restrictVehicleMovement(direction, oppositeDirection);
            })
            .catch(error => {
                console.error('Error setting manual light:', error);
            });
    }
    // Function to load historical data
    function loadHistoryData(hours) {
        fetch(`traffic-history.php?hours=${hours}`)
            .then(response => response.json())
            .then(data => {
                historyChart.updateSeries([
                    { name: 'North', data: data.north },
                    { name: 'South', data: data.south },
                    { name: 'East', data: data.east },
                    { name: 'West', data: data.west }
                ]);
            })
            .catch(error => {
                console.error('Error loading history data:', error);
            });
    }
    
    // Simulate traffic changes (in a real system, this would come from sensors)
    function simulateTrafficChanges() {
        fetch('simulate-traffic.php')
            .then(response => response.json())
            .then(data => {
                updateVehicleCounts(data.traffic);
                
                // If in auto mode, the fetchTrafficData will handle light updates
                if (!autoModeBtn.classList.contains('active')) {
                    // In manual mode, just update the traffic display
                    updateLightsDisplay(data.lights);
                }
            })
            .catch(error => {
                console.error('Error simulating traffic:', error);
            });
        
        // Run every 5 seconds
        setTimeout(simulateTrafficChanges, 5000);
    }

    // Function to restrict vehicle movement to only allowed directions
function restrictVehicleMovement(allowedDir1, allowedDir2) {
    const directions = ['north', 'south', 'east', 'west'];
    
    directions.forEach(dir => {
        if (dir !== allowedDir1 && dir !== allowedDir2) {
            // Stop all vehicles in this direction
            const vehicles = vehicleContainers[dir].querySelectorAll('.vehicle');
            vehicles.forEach(vehicle => {
                vehicle.style.animationPlayState = 'paused';
            });
        } else {
            // Resume vehicles in allowed directions
            const vehicles = vehicleContainers[dir].querySelectorAll('.vehicle');
            vehicles.forEach(vehicle => {
                vehicle.style.animationPlayState = 'running';
            });
        }
    });
}



    
    // Initialize
    currentCycleEl.textContent = `${cycleTimeInput.value} sec`;
    fetchTrafficData();
    simulateTrafficChanges();
    loadHistoryData(24); // Load 24 hours of history by default
    
    // Add animation for pedestrian crossing
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pedestrianCross {
            0% { transform: translate(0, 0); opacity: 1; }
            100% { transform: translate(20px, 20px); opacity: 0; }
        }
        
        .lane.north .pedestrian, .lane.south .pedestrian {
            animation-name: pedestrianCross;
        }
        
        .lane.east .pedestrian, .lane.west .pedestrian {
            animation-name: pedestrianCross;
        }
    `;
    document.head.appendChild(style);
});