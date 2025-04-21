<style>
    body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(90deg,rgb(0, 2, 3),rgb(0, 0, 0),rgb(4, 125, 201));
    color: #ffffff;
}

.rules-container {
    padding: 2rem;
    text-align: center;
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 2rem;
    color: #f0f0f0;
}

.rules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.rule-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1rem;
    cursor: pointer;
    transition: transform 0.3s ease, background 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.rule-card:hover {
    transform: scale(1.02);
    background: rgba(255, 255, 255, 0.1);
}

.rule-header {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
}

.rule-icon {
    font-size: 2rem;
}

.rule-points {
    list-style: none;
    padding-left: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.5s ease, opacity 0.3s ease;
    opacity: 0;
    margin-top: 1rem;
}

.rule-card.open .rule-points {
    max-height: 500px;
    opacity: 1;
}

</style>





<div class="rules-container">
    <h1>Traffic Rules and Regulations</h1>

    <div class="rules-grid">
        <div class="rule-card" onclick="toggleRule(this)">
            <div class="rule-header">
                <div class="rule-icon">🚦</div>
                <h3>Traffic Light Rules</h3>
            </div>
            <ul class="rule-points">
                <li>Red means STOP</li>
                <li>Yellow means PREPARE TO STOP</li>
                <li>Green means GO if safe</li>
                <li>Never run a red light</li>
            </ul>
        </div>

        <div class="rule-card" onclick="toggleRule(this)">
            <div class="rule-header">
                <div class="rule-icon">🚸</div>
                <h3>Pedestrian Rules</h3>
            </div>
            <ul class="rule-points">
                <li>Cross only at designated crossings</li>
                <li>Wait for pedestrian signal</li>
                <li>Look both ways before crossing</li>
                <li>Don't cross when countdown has started</li>
            </ul>
        </div>

        <div class="rule-card" onclick="toggleRule(this)">
            <div class="rule-header">
                <div class="rule-icon">🚑</div>
                <h3>Emergency Vehicles</h3>
            </div>
            <ul class="rule-points">
                <li>Yield to emergency vehicles</li>
                <li>Pull over to the side when safe</li>
                <li>Don't block intersections</li>
                <li>Follow instructions from emergency personnel</li>
            </ul>
        </div>

        <div class="rule-card" onclick="toggleRule(this)">
            <div class="rule-header">
                <div class="rule-icon">🚗</div>
                <h3>General Driving Rules</h3>
            </div>
            <ul class="rule-points">
                <li>Obey speed limits</li>
                <li>Maintain safe following distance</li>
                <li>Use turn signals properly</li>
                <li>Don't drive under influence</li>
            </ul>
        </div>
    </div>
</div>


<script>
    function toggleRule(card) {
        card.classList.toggle('open');
    }
</script>
