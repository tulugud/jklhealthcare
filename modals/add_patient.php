<div id="addPatientModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('addPatientModal')">&times;</span>
        <h2 class="modal-title">Add Patient</h2>
        <form action="add_patient.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="address">Address:</label>
                <input type="text" id="address" name="address" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="medical_records">Medical Records:</label>
                <textarea id="medical_records" name="medical_records" class="form-textarea" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Patient</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addPatientModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>