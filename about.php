<style>
    body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(145deg, #0a0f2c, #011627);
    color: #ffffff;
}

.about-container {
    padding: 2rem;
    text-align: center;
    color: #e0e0e0;
}

.about-container h1 {
    font-size: 2.5rem;
    margin-bottom: 2rem;
    color: #ffffff;
}

.about-content {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.about-section {
    background: rgba(0, 0, 50, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1rem 2rem;
    cursor: pointer;
    transition: transform 0.3s ease, background 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 50, 0.3);
    text-align: left;
}

.about-section:hover {
    transform: scale(1.01);
    background: rgba(0, 0, 80, 0.3);
}

.about-section h2 {
    margin: 0;
    font-size: 1.8rem;
    color: #90cdf4;
}

.about-body {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.5s ease, opacity 0.4s ease;
    margin-top: 1rem;
}

.about-section.open .about-body {
    max-height: 500px;
    opacity: 1;
}

.about-body p, .about-body ul {
    margin: 0.5rem 0;
    font-size: 1rem;
    color: #cbd5e0;
}

.about-body ul {
    padding-left: 1.5rem;
}

.tech-stack {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-top: 1rem;
}

.tech-item {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 12px;
    text-align: center;
    width: 120px;
    transition: background 0.3s ease;
}

.tech-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.tech-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

</style>


<div class="about-container">
    <h1>About the AI Traffic Management System</h1>
    
    <div class="about-content">
        <div class="about-section" onclick="toggleAbout(this)">
            <h2>Our Mission</h2>
            <div class="about-body">
                <p>To create smarter, safer, and more efficient traffic systems using artificial intelligence and real-time data analysis.</p>
            </div>
        </div>
        
        <div class="about-section" onclick="toggleAbout(this)">
            <h2>How It Works</h2>
            <div class="about-body">
                <p>Our system uses:</p>
                <ul>
                    <li>Real-time traffic sensors to monitor vehicle and pedestrian flow</li>
                    <li>Machine learning algorithms to predict traffic patterns</li>
                    <li>Adaptive signal control to optimize traffic flow</li>
                    <li>Emergency vehicle detection for priority routing</li>
                </ul>
            </div>
        </div>
        
        <div class="about-section" onclick="toggleAbout(this)">
            <h2>Technology Stack</h2>
            <div class="about-body">
                <div class="tech-stack">
                    <div class="tech-item">
                        <div class="tech-icon">🤖</div>
                        <p>AI & Machine Learning</p>
                    </div>
                    <div class="tech-item">
                        <div class="tech-icon">☁️</div>
                        <p>Cloud Computing</p>
                    </div>
                    <div class="tech-item">
                        <div class="tech-icon">📊</div>
                        <p>Data Analytics</p>
                    </div>
                    <div class="tech-item">
                        <div class="tech-icon">🌐</div>
                        <p>IoT Sensors</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function toggleAbout(section) {
        section.classList.toggle('open');
    }
</script>
