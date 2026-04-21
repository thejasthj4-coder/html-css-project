<?php // exercise10 index: UI for scientific calculator (calls calc.php via fetch)
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exercise10 - PHP Scientific Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Exercise 10: Scientific Calculator (PHP)</h2>

    <div class="calc">
        <input type="number" id="x" placeholder="Operand X">
        <input type="number" id="y" placeholder="Operand Y (optional)">
        <select id="op">
            <option value="add">Add</option>
            <option value="sub">Subtract</option>
            <option value="mul">Multiply</option>
            <option value="div">Divide</option>
            <option value="pow">Power (x^y)</option>
            <option value="sqrt">Sqrt(x)</option>
            <option value="factorial">Factorial(x)</option>
            <option value="sin">sin(x) - degrees</option>
            <option value="cos">cos(x) - degrees</option>
            <option value="tan">tan(x) - degrees</option>
            <option value="asin">asin(x)</option>
            <option value="acos">acos(x)</option>
            <option value="atan">atan(x)</option>
            <option value="log10">log10(x)</option>
            <option value="ln">ln(x)</option>
            <option value="exp">exp(x)</option>
        </select>
        <button id="calcBtn">Calculate</button>
    </div>

    <div id="resultArea">Result: <span id="result">—</span></div>

    <h3>History</h3>
    <ul id="history"></ul>
</div>

<script>
async function doCalc(){
    const x = document.getElementById('x').value;
    const y = document.getElementById('y').value;
    const op = document.getElementById('op').value;
    const resEl = document.getElementById('result');
    try{
        const resp = await fetch('calc.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({x, y, op})
        });
        const data = await resp.json();
        if (data.error) {
            resEl.textContent = 'Error: ' + data.error;
        } else {
            resEl.textContent = data.result;
            const hist = document.getElementById('history');
            hist.innerHTML = '';
            (data.history || []).forEach(h => {
                const li = document.createElement('li'); li.textContent = h; hist.appendChild(li);
            });
        }
    } catch (e) { resEl.textContent = 'Request failed'; }
}

document.getElementById('calcBtn').addEventListener('click', doCalc);
</script>
</body>
</html>