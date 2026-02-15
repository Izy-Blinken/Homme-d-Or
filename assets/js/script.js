function showGeneralToast(message, type='success') {
    const toast = document.getElementById('generalToast');
    toast.textContent = message;
    toast.className = `generalToast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000); 
}