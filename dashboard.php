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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { 
            font-size: 16px !important; 
            padding: 0 25px;
        }
        h1 { 
            font-size: 32px !important; 
            font-weight: 600 !important; 
            margin: 0 !important;
            color: #2a5298 !important;
        }
        .dashboard-container {
            padding: 25px;
            max-width: 1400px;
            margin: 0 auto;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0 30px 0;
            padding: 25px 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        #add-contact-btn {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        #add-contact-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(42, 82, 152, 0.4);
        }
        .filters {
            display: flex;
            gap: 15px;
            margin: 25px 0 30px 0;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 14px 28px !important;
            background: white;
            border: 2px solid #2a5298;
            color: #2a5298;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px !important;
            transition: all 0.3s;
            min-width: 140px;
            text-align: center;
        }
        .filter-btn:hover,
        .filter-btn.active {
            background: #2a5298;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.2);
        }
        .contacts-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px !important;
        }
        th {
            background: #2a5298;
            color: white;
            padding: 20px 18px !important;
            text-align: left;
            font-weight: 600;
            font-size: 17px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 18px 18px !important;
            border-bottom: 1px solid #eee;
            font-size: 16px !important;
            vertical-align: middle;
        }
        tr:hover {
            background: #f8f9fa !important;
        }
        .contact-type {
            padding: 8px 16px !important;
            border-radius: 25px;
            font-size: 14px !important;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            min-width: 100px;
            text-align: center;
        }
        .sales-lead {
            background: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }
        .support {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
        }
        .btn-view, .btn-assign {
            padding: 10px 20px !important;
            border-radius: 6px;
            font-size: 14px !important;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            min-width: 100px;
            text-align: center;
        }
        .btn-view {
            background: #17a2b8;
            color: white;
        }
        .btn-assign {
            background: #28a745;
            color: white;
        }
        .btn-view:hover {
            background: #138496;
            transform: translateY(-2px);
        }
        .btn-assign:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .loading-message {
            text-align: center;
            padding: 50px !important;
            font-size: 18px !important;
            color: #666;
            font-weight: 500;
        }
        .error-message {
            text-align: center;
            padding: 50px !important;
            font-size: 16px !important;
            color: #dc3545;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            #add-contact-btn {
                width: 100%;
                justify-content: center;
            }
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-btn {
                width: 100%;
            }
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }
            .btn-view, .btn-assign {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>
    
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <button id="add-contact-btn" onclick="window.location.href='createcontact.php'">
                <span style="font-size: 20px; margin-right: 8px;">+</span> Add New Contact
            </button>
        </div>
        
        <!-- Filter Buttons -->
        <div class="filters">
            <button class="filter-btn active" data-filter="all" onclick="loadContacts('all')">All Contacts</button>
            <button class="filter-btn" data-filter="saleslead" onclick="loadContacts('saleslead')">Sales Leads</button>
            <button class="filter-btn" data-filter="support" onclick="loadContacts('support')">Support</button>
            <button class="filter-btn" data-filter="assigntome" onclick="loadContacts('assigntome')">Assigned to Me</button>
        </div>
        
        <!-- Contacts Table -->
        <div class="contacts-table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 20%;">Company</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 15%;">Created</th>
                        <th style="width: 25%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="contacts-table">
                    <tr>
                        <td colspan="6" class="loading-message">
                            Loading contacts...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    function loadContacts(filter = 'all') {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.filter === filter) {
                btn.classList.add('active');
            }
        });
        
        // Shows the  loading message
        document.getElementById('contacts-table').innerHTML = `
            <tr>
                <td colspan="6" class="loading-message">
                    Loading contacts...
                </td>
            </tr>
        `;
        
        // AJAX request
        fetch(`api/contacts.php?filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.contacts) {
                    updateContactsTable(data.contacts);
                } else {
                    document.getElementById('contacts-table').innerHTML = `
                        <tr>
                            <td colspan="6" class="error-message">
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
                        <td colspan="6" class="error-message">
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
                    <td colspan="6" class="loading-message">
                        No contacts found
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        contacts.forEach(contact => {
            const typeClass = contact.type === 'Sales Lead' ? 'sales-lead' : 'support';
            const createdDate = new Date(contact.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            const fullName = `${contact.title || ''} ${contact.firstname} ${contact.lastname}`.trim();
            
            html += `
                <tr>
                    <td style="font-weight: 500;">${escapeHtml(fullName)}</td>
                    <td>${escapeHtml(contact.email)}</td>
                    <td>${escapeHtml(contact.company)}</td>
                    <td>
                        <span class="contact-type ${typeClass}">
                            ${escapeHtml(contact.type)}
                        </span>
                    </td>
                    <td>${createdDate}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-view" onclick="viewContact(${contact.id})">View</button>
                            <button class="btn-assign" onclick="assignToMe(${contact.id})">Assign to Me</button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }
    
    function viewContact(contactId) {
        window.location.href = `viewcontact.php?id=${contactId}`;
    }
    
    function assignToMe(contactId) {
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
    
    document.addEventListener('DOMContentLoaded', function() {
        loadContacts('all');
        
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const filter = this.dataset.filter;
                loadContacts(filter);
            });
        });
    });
    </script>
</body>
</html>