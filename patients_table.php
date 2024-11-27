<?php
$stmt = $conn->prepare("SELECT p.id, p.name, p.address, p.medical_records, c.name AS caregiver 
                      FROM patients p 
                      LEFT JOIN caregivers c ON p.caregiver_id = c.id");
$stmt->execute();
$patients = $stmt->get_result();

while ($row = $patients->fetch_assoc()) {
    echo "<tr>
        <td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . htmlspecialchars($row['medical_records'], ENT_QUOTES, 'UTF-8') . "</td>
        <td>" . ($row['caregiver'] ? htmlspecialchars($row['caregiver'], ENT_QUOTES, 'UTF-8') : 'None') . "</td>
        <td class='actions'>
            <button class='btn btn-secondary' onclick=\"openEditPatientModal({$row['id']}, '" . 
            htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "', '" . 
            htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . "', '" . 
            htmlspecialchars($row['medical_records'], ENT_QUOTES, 'UTF-8') . "')\">Edit</button>
            <button class='btn btn-danger' onclick=\"if(confirm('Are you sure?')) window.location.href='delete_patient.php?id={$row['id']}'\">Delete</button>
            " . ($row['caregiver'] ? 
            "<button class='btn btn-secondary' onclick=\"if(confirm('Unassign this patient?')) window.location.href='unassign_patient.php?id={$row['id']}'\">Unassign</button>" : 
            "") . "
        </td>
    </tr>";
}
?>