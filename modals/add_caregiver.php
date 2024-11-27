<div id="addCaregiverModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal('addCaregiverModal')">&times;</span>
        <h2 class="modal-title">Add Caregiver</h2>
        <form action="add_caregiver.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="caregiverName">Name:</label>
                <input type="text" id="caregiverName" name="name" class="form-input" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Caregiver</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('addCaregiverModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>