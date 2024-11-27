<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healthcare Management Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="styles.css">
    <script>
        // Core UI functions
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.tab');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => tab.classList.remove('active'));
            contents.forEach(content => content.classList.remove('active'));

            document.querySelector(`#${tabId}`).classList.add('active');
            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Patient modal functions
        function openEditPatientModal(id, name, address, medicalRecords) {
            document.getElementById('editPatientId').value = id;
            document.getElementById('editPatientName').value = name;
            document.getElementById('editPatientAddress').value = address;
            document.getElementById('editPatientMedicalRecords').value = medicalRecords;
            openModal('editPatientModal');
        }

        // Caregiver modal functions
        function openEditCaregiverModal(id, name) {
            document.getElementById('editCaregiverId').value = id;
            document.getElementById('editCaregiverName').value = name;
            openModal('editCaregiverModal');
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search functionality
            document.querySelectorAll('.search-input').forEach(input => {
                input.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableId = this.dataset.table;
                    const rows = document.querySelectorAll(`#${tableId} tbody tr`);

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            });

            // Initialize caregiver time slot selection
            const caregiverSelect = document.getElementById('caregiver_id');
            if (caregiverSelect) {
                caregiverSelect.addEventListener('change', function() {
                    const caregiverId = this.value;
                    const timeSlotDropdown = document.getElementById('time_slot');

                    timeSlotDropdown.innerHTML = '<option value="" disabled selected>Select Time Slot</option>';

                    if (caregiverId) {
                        fetch(`get_time_slots.php?caregiver_id=${caregiverId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    data.timeSlots.forEach(slot => {
                                        const option = document.createElement('option');
                                        option.value = slot;
                                        option.textContent = slot;
                                        timeSlotDropdown.appendChild(option);
                                    });
                                } else {
                                    alert('Error fetching time slots');
                                }
                            });
                    }
                });
            }
        });
    </script>
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <div>
                <h1 class="header-title">Healthcare Management Dashboard</h1>
                <p class="header-subtitle">Manage patients and caregivers effectively</p>
            </div>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </header>