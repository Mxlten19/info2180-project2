<?php
session_start();
require_once 'db_conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .filters {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #2a5298;
            background: white;
            color: #2a5298;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        .filter-btn:hover,
        .filter-btn.active {
            background: #2a5298;
            color: white;
        }
        .contact-type {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 500;
        }
        .sales-lead {
            background: #e3f2fd;
            color: #1565c0;
        }
        .support {
            background: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <button id="add-contact-btn" class="btn-primary" onclick="window.location.href='createcontact.php'">
            + Add New Contact
        </button>
    </div>
    
    <div class="filters">
        <button class="filter-btn active" data-filter="all" onclick="loadContacts('all')">All Contacts</button>
        <button class="filter-btn" data-filter="saleslead" onclick="loadContacts('saleslead')">Sales Leads</button>
        <button class="filter-btn" data-filter="support" onclick="loadContacts('support')">Support</button>
        <button class="filter-btn" data-filter="assigntome" onclick="loadContacts('assigntome')">Assigned to Me</button>
    </div>
    
    <!-- Contacts Table -->
    <div id="contacts-table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="contacts-table">
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        Loading contacts...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
    function loadContacts(filter = 'all') {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.filter === filter) {
                btn.classList.add('active');
            }
        });
        
        // Shows the loading
        document.getElementById('contacts-table').innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px;">
                    Loading contacts...
                </td>
            </tr>
        `;
        
        // the AJAX request
        fetch(`api/contacts.php?filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.contacts) {
                    updateContactsTable(data.contacts);
                } else {
                    document.getElementById('contacts-table').innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; color: #dc3545; padding: 40px;">
                                Error loading contacts: ${data.error || 'Unknown error'}
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('contacts-table').innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; color: #dc3545; padding: 40px;">
                            Network error loading contacts
                        </td>
                    </tr>
                `;
            });
    }
    
    function updateContactsTable(contacts) {
        const tableBody = document.getElementById('contacts-table');
        
        if (contacts.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">
                        No contacts found
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        contacts.forEach(contact => {
            const typeClass = contact.type.toLowerCase().replace(' ', '-');
            const createdDate = new Date(contact.created_at).toLocaleDateString();
            
            html += `
                <tr>
                    <td>${escapeHtml(contact.title)}. ${escapeHtml(contact.firstname)} ${escapeHtml(contact.lastname)}</td>
                    <td>${escapeHtml(contact.email)}</td>
                    <td>${escapeHtml(contact.company)}</td>
                    <td><span class="contact-type ${typeClass}">${escapeHtml(contact.type)}</span></td>
                    <td>${createdDate}</td>
                    <td>
                        <button class="btn-secondary" onclick="viewContact(${contact.id})">View</button>
                        <button class="btn-success" onclick="assignToMe(${contact.id}, this)">Assign to Me</button>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }
    
    function viewContact(contactId) {
        window.location.href = `viewcontact.php?id=${contactId}`;
    }
    
    function assignToMe(contactId, button) {
        if (!confirm('Assign this contact to you?')) return;
        
        fetch('api/assign_contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `contact_id=${contactId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Contact assigned to you!');
                const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
                loadContacts(activeFilter);
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error assigning contact');
        });
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Loads the initial contacts
    document.addEventListener('DOMContentLoaded', function() {
        loadContacts('all');
    });
    </script>
</body>
</html>