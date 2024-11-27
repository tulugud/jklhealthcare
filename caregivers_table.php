<?php
$stmt = $conn->prepare("SELECT c.id, c.name FROM caregivers c");
$stmt->execute();
$caregivers = $stmt->get_result();

while ($row = $caregivers->fetch_assoc()) {
    echo "<tr>
        <td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>
        <td class='actions'>
            <button class='btn btn-secondary' onclick=\"openEditCaregiverModal({$row['id']}, '" . 
            htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "')\">Edit</button>
            <button class='btn btn-danger' onclick=\"if(confirm('Are you sure?')) window.location.href='delete_caregiver.php?id={$row['id']}'\">Delete</button>
        </td>
    </tr>";
}
?>