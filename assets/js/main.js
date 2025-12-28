// AJAX Helper Function
function ajaxRequest(url, method = 'POST', data = null, callback = null) {
    const xhr = new XMLHttpRequest();
    const formData = new FormData();
    
    if (data && typeof data === 'object') {
        for (const key in data) {
            formData.append(key, data[key]);
        }
    }
    
    xhr.open(method, url, true);
    
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (callback) callback(response);
            } catch (e) {
                if (callback) callback(xhr.responseText);
            }
        } else {
            console.error('Request failed:', xhr.statusText);
            showMessage('Error: ' + xhr.statusText, 'error');
        }
    };
    
    xhr.onerror = function() {
        console.error('Request failed');
        showMessage('Network error occurred', 'error');
    };
    
    if (data instanceof FormData) {
        xhr.send(data);
    } else if (data) {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(new URLSearchParams(data));
    } else {
        xhr.send();
    }
}

// Shows message to user
function showMessage(text, type = 'info') {
    const existing = document.querySelector('.message');
    if (existing) existing.remove();
    
    const message = document.createElement('div');
    message.className = `message ${type}`;
    message.textContent = text;
    
    document.body.insertBefore(message, document.body.firstChild);
    
    setTimeout(() => {
        if (message.parentNode) {
            message.parentNode.removeChild(message);
        }
    }, 5000);
}

// Form submission with AJAX
function setupAjaxForm(formId, successCallback = null) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Shows the loading
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        
        ajaxRequest(this.action, this.method, formData, function(response) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            
            if (typeof response === 'object') {
                if (response.success) {
                    showMessage(response.message || 'Success!', 'success');
                    if (successCallback) successCallback(response);
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 1500);
                    }
                } else {
                    showMessage(response.error || 'Error occurred', 'error');
                }
            } else {
                showMessage(response, 'info');
            }
        });
    });
}

// Initialize all AJAX forms on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form.ajax-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: this.method,
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const message = doc.querySelector('.message');
                
                if (message) {
                    showMessage(message.textContent, message.classList.contains('error') ? 'error' : 'success');
                }
            })
            .catch(error => {
                showMessage('Error: ' + error, 'error');
            });
        });
    });
    
    // Logout AJAX
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            ajaxRequest('logout.php', 'POST', null, function(response) {
                window.location.href = 'login.php';
            });
        });
    }
});