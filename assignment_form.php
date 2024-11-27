<form action="assign_patient.php" method="POST" class="assignment-form">
    <div class="form-group">
        <label class="form-label" for="patient_id">Select Patient:</label>
        <select name="patient_id" id="patient_id" class="form-select" required>
            <option value="" disabled selected>Select Patient</option>
            <?php
            $stmt = $conn->prepare("SELECT id, name FROM patients WHERE id NOT IN (SELECT patient_id FROM caregiver_schedule)");
            $stmt->execute();
            $patients = $stmt->get_result();

            while ($row = $patients->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>" . 
                     htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="caregiver_id">Select Caregiver:</label>
        <select name="caregiver_id" id="caregiver_id" class="form-select" required>
            <option value="" disabled selected>Select Caregiver</option>
            <?php
            $stmt = $conn->prepare("SELECT id, name FROM caregivers");
            $stmt->execute();
            $caregivers = $stmt->get_result();

            while ($row = $caregivers->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>" . 
                     htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label" for="time_slot">Select Time Slot:</label>
        <select name="time_slot" id="time_slot" class="form-select" required>
            <option value="" disabled selected>Select Time Slot</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Assign Patient</button>
</form>