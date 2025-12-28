let currentFilter = 'all';

function loadContacts(filter = 'all') {
    currentFilter = filter;
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === filter) {
            btn.classList.add('active');
        }
    });
    
    const table = document.getElementById('contacts-table');
    if (table) {
        table.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">Loading contacts...</td></tr>';
    }
    
    // AJAX request
    fetch(`api/contacts.php?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateContactsTable(data.contacts);
                updateStats(data.stats);
            } else {
                showMessage(data.error || 'Failed to load contacts', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Network error loading contacts', 'error');
        });
}

function updateContactsTable(contacts) {
    const tableBody = document.getElementById('contacts-table');
    if (!tableBody) return;
    
    if (contacts.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">No contacts found</td></tr>';
        return;
    }
    
    let html = '';
    contacts.forEach(contact => {
        html += `
            <tr>
                <td>${escapeHtml(contact.title)}. ${escapeHtml(contact.firstname)} ${escapeHtml(contact.lastname)}</td>
                <td>${escapeHtml(contact.email)}</td>
                <td>${escapeHtml(contact.company)}</td>
                <td><span class="contact-type ${contact.type.toLowerCase().replace(' ', '-')}">${escapeHtml(contact.type)}</span></td>
                <td>${formatDate(contact.created_at)}</td>
                <td class="contact-actions">
                    <button class="view-btn" onclick="viewContact(${contact.id})">View</button>
                    <button class="assign-btn" onclick="assignToMe(${contact.id})">Assign to Me</button>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
}

// Update dashboard statistics
function updateStats(stats) {
    document.querySelectorAll('.stat-number').forEach(element => {
        const stat = element.dataset.stat;
        if (stats[stat] !== undefined) {
            element.textContent = stats[stat];
        }
    });
}

// View contact details with AJAX
function viewContact(contactId) {
    fetch(`api/get_contact.php?id=${contactId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `viewcontact.php?id=${contactId}`;
            } else {
                showMessage(data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error loading contact details', 'error');
        });
}

// Assign contact to current user
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
            showMessage('Contact assigned to you!', 'success');
            loadContacts(currentFilter);
        } else {
            showMessage(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error assigning contact', 'error');
    });
}

function switchContactType(contactId, currentType) {
    const newType = currentType === 'Sales Lead' ? 'Support' : 'Sales Lead';
    
    if (!confirm(`Switch contact type to ${newType}?`)) return;
    
    fetch('api/switch_type.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `contact_id=${contactId}&new_type=${newType}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(`Contact type changed to ${newType}`, 'success');
            if (window.location.href.includes('viewcontact.php')) {
                window.location.reload();
            } else {
                loadContacts(currentFilter);
            }
        } else {
            showMessage(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error switching type', 'error');
    });
}

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Initializes tge dashboard on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            loadContacts(filter);
        });
    });
    
    loadContacts('all');
    
    const addContactBtn = document.getElementById('add-contact-btn');
    if (addContactBtn) {
        addContactBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'createcontact.php';
        });
    }
});