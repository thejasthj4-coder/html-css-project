<?php
session_start();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { echo json_encode(['error'=>'Invalid request']); exit; }
$x = isset($input['x']) && $input['x'] !== '' ? floatval($input['x']) : null;
$y = isset($input['y']) && $input['y'] !== '' ? floatval($input['y']) : null;
$op = $input['op'] ?? '';

function factorial($n) {
    $n = intval($n);
    if ($n < 0) return null;
    $res = 1;
    for ($i=2;$i<=$n;$i++) $res *= $i;
    return $res;
}

$result = null;
$error = null;
try {
    switch ($op) {
        case 'add': $result = $x + $y; break;
        case 'sub': $result = $x - $y; break;
        case 'mul': $result = $x * $y; break;
        case 'div':
            if ($y == 0) $error = 'Division by zero'; else $result = $x / $y; break;
        case 'pow': $result = pow($x, $y); break;
        case 'sqrt': $result = sqrt($x); break;
        case 'factorial':
            if (!is_numeric($x) || $x < 0) $error = 'Invalid input for factorial'; else $result = factorial($x); break;
        case 'sin': $result = sin(deg2rad($x)); break;
        case 'cos': $result = cos(deg2rad($x)); break;
        case 'tan': $result = tan(deg2rad($x)); break;
        case 'asin': $result = asin($x); break;
        case 'acos': $result = acos($x); break;
        case 'atan': $result = atan($x); break;
        case 'log10': $result = log10($x); break;
        case 'ln': $result = log($x); break;
        case 'exp': $result = exp($x); break;
        default: $error = 'Unknown operation';
    }
} catch (Exception $e) { $error = $e->getMessage(); }

if (!isset($_SESSION['history'])) $_SESSION['history'] = [];
$entry = $error ? "{$op} => ERROR: {$error}" : "{$op}({$x}
$entry = $error ? "{$op} => ERROR: {$error}" : (is_null($y) ? "{$op}({$x}) = {$result}" : "{$op}({$x},{$y}) = {$result}");
// push to history (keep last 10)
array_unshift($_SESSION['history'], $entry);
if (count($_SESSION['history']) > 10) array_pop($_SESSION['history']);

echo json_encode(['result'=>$result, 'error'=>$error, 'history'=>$_SESSION['history']]);
exit;