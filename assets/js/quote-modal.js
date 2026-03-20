function openQuoteModal() {
    document.getElementById('quoteModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeQuoteModal() {
    document.getElementById('quoteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('quoteForm').reset();
    document.getElementById('quoteFormMessage').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('quoteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuoteModal();
    }
});

// Handle form submission
document.getElementById('quoteForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'submit_quote_request');
    formData.append('nonce', str_ajax.nonce);
    
    const messageDiv = document.getElementById('quoteFormMessage');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    
    try {
        const response = await fetch(str_ajax.ajax_url, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        messageDiv.classList.remove('hidden');
        
        if (data.success) {
            messageDiv.className = 'mb-4 p-4 bg-teal/10 border border-teal/20 rounded-lg text-teal font-bold';
            messageDiv.textContent = data.data.message;
            setTimeout(() => {
                closeQuoteModal();
            }, 2000);
        } else {
            messageDiv.className = 'mb-4 p-4 bg-red-100 border border-red-300 rounded-lg text-red-700 font-bold';
            messageDiv.textContent = data.data.message || 'An error occurred';
        }
    } catch (error) {
        messageDiv.classList.remove('hidden');
        messageDiv.className = 'mb-4 p-4 bg-red-100 border border-red-300 rounded-lg text-red-700 font-bold';
        messageDiv.textContent = 'Network error. Please try again.';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Quote Request';
    }
});
