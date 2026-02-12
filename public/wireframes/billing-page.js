// Toggle Payment Form
const togglePaymentBtn = document.getElementById('togglePaymentForm');
const paymentForm = document.getElementById('paymentForm');

togglePaymentBtn.addEventListener('click', () => {
    paymentForm.classList.toggle('active');
    
    // Update button text
    if (paymentForm.classList.contains('active')) {
        togglePaymentBtn.innerHTML = `
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Hide Payment Form
        `;
    } else {
        togglePaymentBtn.innerHTML = `
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add New Payment
        `;
    }
});

// Tab Switching
const tabButtons = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        const targetTab = button.getAttribute('data-tab');
        
        // Remove active class from all tabs and contents
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding content
        button.classList.add('active');
        document.getElementById(targetTab).classList.add('active');
    });
});

// Search functionality (placeholder)
const searchBtn = document.querySelector('.search-btn');
const searchInput = document.querySelector('.search-input');

searchBtn.addEventListener('click', () => {
    const searchTerm = searchInput.value.trim();
    if (searchTerm) {
        console.log('Searching for:', searchTerm);
        // Add your search logic here
        alert(`Searching for: ${searchTerm}`);
    } else {
        alert('Please enter a Consumer ID or Name');
    }
});

// Enter key for search
searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        searchBtn.click();
    }
});

// Form validation and submission (placeholder)
const savePaymentBtn = document.querySelector('.form-actions .btn-primary');
const generateReceiptBtn = document.querySelector('.form-actions .btn-success');

savePaymentBtn.addEventListener('click', (e) => {
    e.preventDefault();
    
    // Get form values
    const billingMonth = document.querySelector('.form-control').value;
    const amountPaid = document.querySelectorAll('.form-control')[2].value;
    const paymentMethod = document.querySelectorAll('.form-control')[3].value;
    const orNumber = document.querySelectorAll('.form-control')[4].value;
    
    // Basic validation
    if (!amountPaid || !orNumber || paymentMethod === 'Select Method') {
        alert('Please fill in all required fields');
        return;
    }
    
    console.log('Payment saved:', { billingMonth, amountPaid, paymentMethod, orNumber });
    alert('Payment saved successfully!');
    
    // Reset form and hide it
    paymentForm.classList.remove('active');
    togglePaymentBtn.click();
});

generateReceiptBtn.addEventListener('click', (e) => {
    e.preventDefault();
    console.log('Generating official receipt...');
    alert('Official Receipt generated!');
});

// Smooth hover effects for table rows
const tableRows = document.querySelectorAll('.data-table tbody tr');
tableRows.forEach(row => {
    row.addEventListener('click', () => {
        row.style.transform = 'scale(1.01)';
        setTimeout(() => {
            row.style.transform = 'scale(1)';
        }, 200);
    });
});

// Add loading animation on search
searchBtn.addEventListener('click', function() {
    const originalContent = this.innerHTML;
    this.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="animation: spin 1s linear infinite;">
            <circle cx="12" cy="12" r="10"/>
        </svg>
        Searching...
    `;
    
    setTimeout(() => {
        this.innerHTML = originalContent;
    }, 1000);
});

// Add spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
