function addNote(contactId) {
    const comment = document.getElementById('note-comment').value.trim();
    
    if (!comment) {
        showMessage('Please enter a note', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('contact_id', contactId);
    formData.append('comment', comment);
    formData.append('ajax', '1');
    
    fetch('addnote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Note added successfully!', 'success');
            document.getElementById('note-comment').value = '';
            loadNotes(contactId);
            updateContactTimestamp(contactId);
        } else {
            showMessage(data.error || 'Error adding note', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Network error adding note', 'error');
    });
}

function loadNotes(contactId) {
    fetch(`api/get_notes.php?contact_id=${contactId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotesList(data.notes);
            }
        })
        .catch(error => {
            console.error('Error loading notes:', error);
        });
}

// Update notes list
function updateNotesList(notes) {
    const notesContainer = document.getElementById('notes-list');
    if (!notesContainer) return;
    
    if (notes.length === 0) {
        notesContainer.innerHTML = '<div class="note"><p>No notes yet</p></div>';
        return;
    }
    
    let html = '';
    notes.forEach(note => {
        html += `
            <div class="note">
                <div class="note-author">${escapeHtml(note.created_by_name)}</div>
                <div class="note-date">${formatDateTime(note.created_at)}</div>
                <div class="note-content">${escapeHtml(note.comment)}</div>
            </div>
        `;
    });
    
    notesContainer.innerHTML = html;
}

// Update contact timestamp
function updateContactTimestamp(contactId) {
    const timestampElement = document.querySelector('.updated-timestamp');
    if (timestampElement) {
        const now = new Date();
        timestampElement.textContent = `Updated: ${formatDateTime(now.toISOString())}`;
    }
}

// Assign contact to current user (from view page)
function assignToMeView(contactId) {
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
            document.getElementById('assigned-to').textContent = 'You';
            document.getElementById('assign-me-btn').style.display = 'none';
        } else {
            showMessage(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error assigning contact', 'error');
    });
}

function switchTypeView(contactId, currentType) {
    const newType = currentType === 'Sales Lead' ? 'Support' : 'Sales Lead';
    
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
            document.getElementById('contact-type').textContent = newType;
            document.getElementById('switch-type-btn').textContent = 
                `Switch to ${newType === 'Sales Lead' ? 'Support' : 'Sales Lead'}`;
        } else {
            showMessage(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error switching type', 'error');
    });
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Initialize contact page
document.addEventListener('DOMContentLoaded', function() {
    const contactId = document.body.dataset.contactId || 
                     new URLSearchParams(window.location.search).get('id');
    
    if (contactId) {
        loadNotes(contactId);
        
        const addNoteForm = document.getElementById('add-note-form');
        if (addNoteForm) {
            addNoteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                addNote(contactId);
            });
        }
        
        // Set up assign to me button
        const assignBtn = document.getElementById('assign-me-btn');
        if (assignBtn) {
            assignBtn.addEventListener('click', function() {
                assignToMeView(contactId);
            });
        }
        
        // Set up switch type button
        const switchBtn = document.getElementById('switch-type-btn');
        if (switchBtn) {
            const currentType = document.getElementById('contact-type').textContent;
            switchBtn.addEventListener('click', function() {
                switchTypeView(contactId, currentType);
            });
        }
    }
});