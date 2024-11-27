<?php
require_once 'header.php';
require_once 'db.php';
?>

<?php 
include 'modals/add_patient.php';
include 'modals/edit_patient.php';
include 'modals/add_caregiver.php';
include 'modals/edit_caregiver.php';
?>
<div class="container">
    <div class="tabs">
        <div class="tab active" onclick="showTab('patients')">Patients</div>
        <div class="tab" onclick="showTab('caregivers')">Caregivers</div>
        <div class="tab" onclick="showTab('assign')">Assign</div>
    </div>

    <!-- Patients Tab -->
    <div id="patients" class="tab-content active">
        <div class="content-header">
            <h2>Patients</h2>
            <button style="float:right" class="btn btn-primary" onclick="openModal('addPatientModal')">Add Patient</button>
        </div>

        <div class="search-container">
            <input type="text" class="search-input" data-table="patientsTable" placeholder="Search patients...">
        </div>

        <div class="table-container">
            <table id="patientsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Medical Records</th>
                        <th>Assigned Caregiver</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include 'patients_table.php'; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Caregivers Tab -->
    <div id="caregivers" class="tab-content">
        <div class="content-header">
            <h2>Caregivers</h2>
            <button style="float:right" class="btn btn-primary" onclick="openModal('addCaregiverModal')">Add Caregiver</button>
        </div>

        <div class="search-container">
            <input type="text" class="search-input" data-table="caregiversTable" placeholder="Search caregivers...">
        </div>

        <div class="table-container">
            <table id="caregiversTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include 'caregivers_table.php'; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assignment Tab -->
    <div id="assign" class="tab-content">
        <h2>Assign Patient to Caregiver</h2>
        <?php include 'assignment_form.php'; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>