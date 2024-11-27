<div id="editCaregiverModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('editCaregiverModal')">&times;</span>
        <h2 class="modal-title">Edit Caregiver</h2>
        <form action="edit_caregiver.php" method="POST">
            <input type="hidden" id="editCaregiverId" name="caregiver_id">
            <div class="form-group">
                <label class="form-label" for="editCaregiverName">Name:</label>
                <input type="text" id="editCaregiverName" name="name" class="form-input" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('editCaregiverModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>