<?php
session_start();
if(isset($_FILES['leaf_image'])){
  $image_path = "uploads/" . uniqid() . "_" . $_FILES['leaf_image']['name'];
  move_uploaded_file($_FILES['leaf_image']['tmp_name'], $image_path);

  // Simulate AI; replace with actual Python API call if available.
  $result = [
    'success'=>true,
    'disease'=>'Late Blight',
    'confidence'=>'97%',
    'image_path'=>$image_path,
    'symptoms'=>'Dark spots, wilted leaves',
    'treatment'=>'Use copper-based fungicide'
  ];
  echo json_encode($result);
}
?>
