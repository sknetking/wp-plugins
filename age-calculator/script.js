jQuery(document).ready(function($) {
    // Function to calculate age based on birthdate input
 // Initialize Flatpickr for birth date with a restriction to not allow future dates
 var today = new Date().toISOString().split('T')[0];
    
 // Set the max attribute of the date field to today's date
 document.getElementById("birthDate").setAttribute("max",today);

// Initialize Flatpickr for current date with today's date as default

document.getElementById('ageForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const birthDate = new Date(document.getElementById('birthDate').value);
    const currentDate = new Date(document.getElementById('currentDate').value);

    if (birthDate > currentDate) {
        alert('Birth date cannot be in the future!');
        return;
    }

    let years = currentDate.getFullYear() - birthDate.getFullYear();
    let months = currentDate.getMonth() - birthDate.getMonth();
    let days = currentDate.getDate() - birthDate.getDate();

    if (days < 0) {
        months--;
        days += new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
    }

    if (months < 0) {
        years--;
        months += 12;
    }

    document.getElementById('result').innerHTML = `You are : ${years} Year${years !== 1 ? 's' : ''}, ${months} Month${months !== 1 ? 's' : ''}, ${days} Day${days !== 1 ? 's' : ''} old.`;
});

});
