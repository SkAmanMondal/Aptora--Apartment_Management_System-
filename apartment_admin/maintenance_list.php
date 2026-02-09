<?php
    $sql = "
    SELECT f.flat_no, b.*
    FROM maintenance_bills b
    JOIN flats f ON b.flat_id=f.id
    WHERE b.apartment_id='$apartment_id'
    ORDER BY year DESC, month DESC
    ";
?>