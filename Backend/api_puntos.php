<?php
include 'config.php';

header('Content-Type: application/json');

// Obtener puntos de cliente
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cliente_id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT puntosacomulados, puntosredimidos FROM puntosclientes WHERE id = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo json_encode($result->fetch_assoc());
}

// Actualizar puntos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("UPDATE puntosclientes 
                           SET puntosacomulados = ?, puntosredimidos = ?
                           WHERE id = ?");
    $stmt->bind_param("iii", 
        $data['puntosacumulados'],
        $data['puntosredimidos'],
        $data['id']
    );
    
    if($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>