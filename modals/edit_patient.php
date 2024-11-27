<div id="editPatientModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editPatientModal')">&times;</span>
        <h2 class="modal-title">Edit Patient</h2>
        <form action="edit_patient.php" method="POST">
            <input type="hidden" id="editPatientId" name="patient_id">
            <div class="form-group">
                <label class="form-label" for="editPatientName">Name:</label>
                <input type="text" id="editPatientName" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="editPatientAddress">Address:</label>
                <input type="text" id="editPatientAddress" name="address" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="editPatientMedicalRecords">Medical Records:</label>
                <textarea id="editPatientMedicalRecords" name="medical_records" class="form-textarea" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('editPatientModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>